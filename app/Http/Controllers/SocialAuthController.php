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
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
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

            // IMPORTANTE: Verificar si falta información de perfil
            // y forzar la redirección independientemente de si es usuario nuevo
            $needsProfileCompletion = empty($user->address) || empty($user->phone) || empty($user->province) || empty($user->city);

            // Iniciar sesión
            Auth::login($user, true);

            // Siempre redirigir a la página principal
            return redirect()->intended('/');

        } catch (\Exception $e) {
            Log::error('Error en autenticación Google: '.$e->getMessage());

            return redirect('/login')->withErrors(['error' => 'Error al autenticar con Google: '.$e->getMessage()]);
        }
    }
}
