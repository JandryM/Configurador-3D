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
            ->subject('📧 Código de verificación de correo - Quality Services')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Gracias por registrarte en **Quality Services**. Para completar tu registro, necesitamos verificar tu correo electrónico.')
            ->line('')
            ->line('---')
            ->line('### ✉️ Tu Código de Verificación')
            ->line('')
            ->line('Ingresa el siguiente código en la página para verificar tu cuenta:')
            ->line('')
            ->line('**' . $this->code . '**')
            ->line('')
            ->line('⏱️ **Este código expira en 15 minutos.**')
            ->line('')
            ->line('---')
            ->line('### ⚠️ ¿No creaste esta cuenta?')
            ->line('')
            ->line('Si no fuiste tú quien se registró, **ignora este mensaje**. No se realizará ningún cambio en tu cuenta.')
            ->line('')
            ->line('---')
            ->salutation('Saludos,  
**Equipo de Quality Services**  
*Comprometidos con tu seguridad*');
    }
}
