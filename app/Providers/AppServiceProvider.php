<?php

namespace App\Providers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar middleware para admin y seller
        $router = $this->app['router'];
        $router->aliasMiddleware('admin.seller', function ($request, $next) {
            $user = $request->user();
            if (! $user || ! in_array($user->role, ['admin', 'owner', 'seller'])) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Forbidden'], 403);
                }
                return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta sección.');
            }
            return $next($request);
        });
        // Registrar listener de forma única para envío de notificación cuando se cambie contraseña
        Event::listen(PasswordReset::class, function (PasswordReset $event) {
            // Agregar un pequeño delay para evitar duplicaciones
            if (! cache()->has('password_changed_'.$event->user->id)) {
                cache()->put('password_changed_'.$event->user->id, true, 10); // 10 segundos
                $event->user->notify(new \App\Notifications\PasswordChangedNotification);
            }
        });

        // Registrar listener para actualizar last_login_at cuando un usuario inicie sesión
        Event::listen(\Illuminate\Auth\Events\Login::class, \App\Listeners\UpdateLastLoginAt::class);

        // Registrar listener para verificar suspensión después del login
        Event::listen(\Illuminate\Auth\Events\Login::class, \App\Listeners\CheckUserSuspensionAfterLogin::class);

        // Verificar que exista al menos un administrador en producción
        $this->ensureAdminExists();

        // Registrar middleware EnsureUserIsAdminOrOwner como alias 'admin.owner'
        $router = $this->app['router'];
        $router->aliasMiddleware('admin.owner', \App\Http\Middleware\EnsureUserIsAdminOrOwner::class);
    }

    /**
     * Ensure at least one admin user exists in the system
     */
    private function ensureAdminExists(): void
    {
        // Solo ejecutar en producción y si las tablas ya existen
        if (! app()->environment('production')) {
            return;
        }

        try {
            // Verificar si existe la tabla users y tiene datos
            if (! \Schema::hasTable('users')) {
                return;
            }

            $adminExists = \App\Models\User::where('role', \App\Models\User::ROLE_ADMIN)->exists();

            if (! $adminExists) {
                \Log::warning('No admin users found in production. Please create one using: php artisan admin:create');

                // Opcional: crear un admin de emergencia si están configuradas las variables de entorno
                if (env('EMERGENCY_ADMIN_EMAIL') && env('EMERGENCY_ADMIN_PASSWORD')) {
                    $this->createEmergencyAdmin();
                }
            }
        } catch (\Exception $e) {
            // Silenciosamente ignorar errores durante el boot (ej: migraciones pendientes)
            \Log::info('Admin check skipped: '.$e->getMessage());
        }
    }

    /**
     * Create emergency admin from environment variables
     */
    private function createEmergencyAdmin(): void
    {
        try {
            \App\Models\User::firstOrCreate(
                ['email' => env('EMERGENCY_ADMIN_EMAIL')],
                [
                    'name' => env('EMERGENCY_ADMIN_NAME', 'Emergency Admin'),
                    'email' => env('EMERGENCY_ADMIN_EMAIL'),
                    'password' => \Hash::make(env('EMERGENCY_ADMIN_PASSWORD')),
                    'phone' => env('EMERGENCY_ADMIN_PHONE', '+593000000000'),
                    'address' => env('EMERGENCY_ADMIN_ADDRESS', 'Emergency Address'),
                    'province' => env('EMERGENCY_ADMIN_PROVINCE', 'Guayas'),
                    'city' => env('EMERGENCY_ADMIN_CITY', 'Guayaquil'),
                    'role' => \App\Models\User::ROLE_ADMIN,
                    'email_verified_at' => now(),
                ]
            );

            \Log::info('Emergency admin created from environment variables');
        } catch (\Exception $e) {
            \Log::error('Failed to create emergency admin: '.$e->getMessage());
        }
    }
}
