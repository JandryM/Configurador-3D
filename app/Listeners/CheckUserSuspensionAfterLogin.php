<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckUserSuspensionAfterLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Verificar si el usuario está suspendido
        if ($user->isSuspended()) {
            // Cerrar la sesión inmediatamente
            Auth::logout();
            
            // Invalidar la sesión
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            // Preparar mensaje de suspensión
            $message = 'Tu cuenta ha sido suspendida y no puedes iniciar sesión.';
            
            if ($user->suspended_until) {
                $message .= ' La suspensión expira el ' . $user->suspended_until->format('d/m/Y H:i');
            } else {
                $message .= ' Contacta al administrador para más información.';
            }

            if ($user->suspension_reason) {
                $message .= ' Razón: ' . $user->suspension_reason;
            }

            // Guardar información de suspensión en la sesión para mostrar en la vista
            Session::flash('suspension_error', true);
            Session::flash('suspension_message', $message);
            Session::flash('suspension_reason', $user->suspension_reason);
            Session::flash('suspended_until', $user->suspended_until);
            Session::flash('suspended_user_name', $user->name);
        }
    }
}
