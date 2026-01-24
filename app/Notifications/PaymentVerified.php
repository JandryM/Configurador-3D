<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class PaymentVerified extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $owner;

    public function __construct($order)
    {
        $this->order = $order;
        $this->owner = User::where('role', 'owner')->first();
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $orderNumber = is_array($this->order) ? $this->order['number'] : $this->order->number;
        $orderAmount = is_array($this->order) ? $this->order['amount'] : $this->order->amount;
        
        $mail = (new MailMessage)
            ->subject('✅ Pago Verificado - Orden #' . $orderNumber . ' - Quality Services')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tenemos excelentes noticias sobre tu orden **#' . $orderNumber . '**.')
            ->line('')
            ->line('✅ **¡Tu Pago ha sido Verificado!**')
            ->line('Hemos confirmado exitosamente el pago de tu orden. Tu comprobante fue validado correctamente.')
            ->line('')
            ->line('---')
            ->line('### 📋 Detalles de la Transacción')
            ->line('')
            ->line('**💰 Monto verificado:** $' . number_format($orderAmount, 2))
            ->line('**📄 Número de orden:** #' . $orderNumber)
            ->line('**✓ Estado:** Pago confirmado')
            ->line('')
            ->line('---')
            ->line('### 🔧 ¿Qué Sigue Ahora?')
            ->line('')
            ->line('1. **Inicio de Producción:** Tu producto entrará en cola de fabricación')
            ->line('2. **Seguimiento en Tiempo Real:** Podrás ver el estado de tu orden en la plataforma')
            ->line('3. **Fecha de Entrega:** Podras ver la fecha estimada de finalización en la plataforma')
            ->line('')
            ->line('💡 **Importante:** Nuestro equipo de producción comenzará a trabajar en tu pedido lo antes posible.')
            ->line('')
            ->line('---')
            ->line('### 👀 Seguimiento de tu Orden')
            ->line('')
            ->line('Puedes revisar el estado actualizado de tu orden en cualquier momento ingresando a nuestra plataforma.')
            ->line('')
            ->line('---');
        
        // Información de contacto si existe
        if ($this->owner && $this->owner->phone) {
            $mail->line('### 📞 ¿Tienes Preguntas?')
                ->line('Estamos aquí para ayudarte:')
                ->line('• Teléfono: ' . $this->owner->phone)
                ->line('• Plataforma: Ingresa a tu cuenta para más detalles')
                ->line('');
        }
        
        $mail->line('🎉 **¡Gracias por confiar en nosotros!**')
            ->line('Nos comprometemos a brindarte un producto de la más alta calidad.')
            ->line('')
            ->salutation('Saludos cordiales,  
**Equipo de Quality Services**  
*Creando productos de excelencia para ti*');
        
        return $mail;
    }
}
