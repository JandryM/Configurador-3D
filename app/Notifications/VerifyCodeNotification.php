<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyCodeNotification extends Notification implements ShouldQueue
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
            ->subject('Código de verificación de correo')
            ->line('Tu código de verificación es:')
            ->html('<strong style="font-size:2rem;">' . $this->code . '</strong>')
            ->line('Ingresa este código en la página para verificar tu correo electrónico.')
            ->line('Si no solicitaste este código, puedes ignorar este mensaje.');
    }
}
