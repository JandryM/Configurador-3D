<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserNotSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Excluir rutas específicas del middleware de suspensión
        $excludedRoutes = [
            'logout',
            'login',
            'register',
            'password.request',
            'password.reset',
            'auth.google',
        ];

        $currentRoute = $request->route();
        if ($currentRoute && in_array($currentRoute->getName(), $excludedRoutes)) {
            return $next($request);
        }

        // También excluir rutas por path
        $excludedPaths = [
            'logout',
            'login',
            'register',
            'forgot-password',
            'reset-password/*',
            'auth/google',
            'auth/google/callback',
        ];

        foreach ($excludedPaths as $path) {
            if ($request->is($path)) {
                return $next($request);
            }
        }

        $user = Auth::user();

        // Si no hay usuario autenticado, continuar (será manejado por otros middlewares)
        if (!$user) {
            return $next($request);
        }

        // Verificar si el usuario está suspendido usando el método del modelo
        if ($user->isSuspended()) {
            // Preparar mensaje de suspensión
            $message = 'Tu cuenta ha sido suspendida.';
            
            if ($user->suspended_until) {
                $message .= ' La suspensión expira el ' . $user->suspended_until->format('d/m/Y H:i');
            } else {
                $message .= ' Contacta al administrador para más información.';
            }

            if ($user->suspension_reason) {
                $message .= ' Razón: ' . $user->suspension_reason;
            }

            // Si es una petición AJAX o espera JSON
            if ($request->expectsJson()) {
                // Para peticiones AJAX, solo devolver información sin cerrar sesión automáticamente
                return response()->json([
                    'message' => $message,
                    'suspended' => true,
                    'redirect' => route('login')
                ], 403);
            }

            // Para peticiones web, mostrar la página de suspensión
            return response()->view('suspension', [
                'user' => $user,
                'message' => $message
            ], 403);
        }

        return $next($request);
    }
}
