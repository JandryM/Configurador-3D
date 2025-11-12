<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'owner') {
            abort(403, 'Solo el propietario puede acceder a esta sección.');
        }
        return $next($request);
    }
}
