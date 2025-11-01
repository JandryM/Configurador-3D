<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Si es una solicitud AJAX o API, no redirigir
        if ($request->expectsJson()) {
            return null;
        }

        // Siempre redirigir a la página principal para mostrar modal de login
        return route('home');
    }

    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        // Si es una solicitud AJAX, devolver respuesta JSON con información específica
        if ($request->expectsJson()) {
            throw new AuthenticationException(
                'Unauthenticated.', $guards, $this->redirectTo($request)
            );
        }

        // Para solicitudes web, redirigir con parámetro para mostrar modal
        $redirectUrl = $this->redirectTo($request);
        
        if ($redirectUrl) {
            // Añadir información adicional sobre la ruta que se intentaba acceder
            $intendedUrl = $request->fullUrl();
            $currentRoute = $request->route()?->getName();
            
            // Generar mensaje contextual según la ruta
            $message = $this->getContextualMessage($currentRoute, $request->path());
            
            return redirect()->guest($redirectUrl)
                ->with('show_login_modal', true)
                ->with('login_message', $message)
                ->with('intended_url', $intendedUrl);
        }

        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }

    /**
     * Get a contextual message based on the route the user was trying to access
     */
    private function getContextualMessage(?string $routeName, string $path): string
    {
        // Mensajes específicos para diferentes secciones
        if ($routeName && str_starts_with($routeName, 'admin.')) {
            return 'Necesitas iniciar sesión para acceder al panel de administración';
        }

        if ($routeName === 'dashboard') {
            return 'Inicia sesión para ver tu panel de usuario';
        }

        if ($routeName && str_starts_with($routeName, 'settings.')) {
            return 'Debes estar logueado para acceder a la configuración';
        }

        if (str_contains($path, 'configurador')) {
            return 'Inicia sesión para guardar tu configuración personalizada';
        }

        // Mensaje por defecto
        return 'Debes iniciar sesión para acceder a esta página';
    }
}