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
            ->subject('🔐 Código para restablecer tu contraseña - Quality Services')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
            ->line('')
            ->line('---')
            ->line('### 🔑 Tu Código de Recuperación')
            ->line('')
            ->line('Ingresa el siguiente código en la página para continuar:')
            ->line('')
            ->line('**' . $this->code . '**')
            ->line('')
            ->line('⏱️ **Este código expira en 15 minutos.**')
            ->line('')
            ->line('---')
            ->line('### ⚠️ ¿No solicitaste este cambio?')
            ->line('')
            ->line('Si no fuiste tú quien solicitó este código, **ignora este mensaje**. Tu contraseña no será modificada.')
            ->line('Si crees que alguien intentó acceder a tu cuenta, te recomendamos:')
            ->line('• Verificar que tu correo electrónico esté seguro')
            ->line('• Cambiar tu contraseña actual a la brevedad')
            ->line('')
            ->line('---')
            ->salutation('Saludos,  
**Equipo de Quality Services**  
*Comprometidos con tu seguridad*');
    }
}
