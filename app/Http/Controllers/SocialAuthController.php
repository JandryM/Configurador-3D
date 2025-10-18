<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        // Configuración temporal para desarrollo - deshabilitar verificación SSL
        if (app()->environment('local')) {
            config(['services.google.guzzle' => [
                'verify' => false
            ]]);
        }
        
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Configuración temporal para desarrollo - deshabilitar verificación SSL
            if (app()->environment('local')) {
                config(['services.google.guzzle' => [
                    'verify' => false
                ]]);
            }
            
            $googleUser = Socialite::driver('google')->user();

            // Buscar usuario existente o crear uno nuevo
            $user = User::where('email', $googleUser->email)->first();

            if (! $user) {
                // Crear nuevo usuario
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'oauth_provider' => 'google',
                ]);

                // Establecer email_verified_at directamente para evitar problemas de fillable
                $user->email_verified_at = now();
                $user->save();

            } elseif (empty($user->oauth_provider)) {
                // Usuario existe pero no tiene oauth_provider
                $user->oauth_provider = 'google';
                $user->email_verified_at = now(); // Verificar email también
                $user->save();
            }

            // IMPORTANTE: Verificar si el usuario está suspendido ANTES de hacer login
            if ($user->isSuspended()) {
                $message = 'Tu cuenta ha sido suspendida y no puedes iniciar sesión.';
                
                if ($user->suspended_until) {
                    $message = 'Tu cuenta está suspendida hasta el ' . $user->suspended_until->format('d/m/Y H:i') . '.';
                } else {
                    $message = 'Tu cuenta ha sido suspendida indefinidamente.';
                }

                if ($user->suspension_reason) {
                    $message .= ' Motivo: ' . $user->suspension_reason;
                }

                $message .= ' Contacta al administrador para más información.';

                return redirect('/login')->withErrors([
                    'email' => $message
                ]);
            }

            // IMPORTANTE: Verificar si falta información de perfil
            // y forzar la redirección independientemente de si es usuario nuevo
            $needsProfileCompletion = empty($user->address) || empty($user->phone) || empty($user->province) || empty($user->city);

            // Iniciar sesión solo si no está suspendido
            Auth::login($user, true);

            // Actualizar último login
            $user->updateLastLogin();

            // Siempre redirigir a la página principal
            return redirect()->intended('/');

        } catch (\Exception $e) {
            Log::error('Error en autenticación Google: '.$e->getMessage());

            return redirect('/login')->withErrors(['error' => 'Error al autenticar con Google: '.$e->getMessage()]);
        }
    }
}
