<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

    // Verificar que el usuario esté autenticado y tenga acceso de admin u owner
    if (! $user || ! $user->hasAdminAccess()) {
            // Si es una petición AJAX o espera JSON, devolver error 403
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            // Para peticiones web, redirigir al dashboard con mensaje
            return redirect()
                ->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
