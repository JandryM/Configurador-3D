<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Código para restablecer tu contraseña')
            ->line('Has solicitado recuperar tu contraseña.')
            ->line('Tu código de recuperación es:')
            ->line('<strong style="font-size:2rem;">' . $this->code . '</strong>')
            ->line('Ingresa este código en la página para continuar con el cambio de contraseña.')
            ->line('Si no solicitaste este código, puedes ignorar este mensaje.');
    }
}
