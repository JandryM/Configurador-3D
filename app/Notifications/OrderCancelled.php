<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class OrderCancelled extends Notification implements ShouldQueue
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
        $orderAmount = is_array($this->order) ? ($this->order['amount'] ?? 0) : ($this->order->amount ?? 0);
        
        $mail = (new MailMessage)
            ->subject('❌ Orden Cancelada - Orden #' . $orderNumber . ' - Quality Services')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Te contactamos para informarte sobre tu orden **#' . $orderNumber . '**.')
            ->line('')
            ->line('❌ **Orden Cancelada**')
            ->line('Lamentamos informarte que tu orden ha sido cancelada.')
            ->line('')
            ->line('---')
            ->line('### 📋 Detalles de la Orden Cancelada')
            ->line('')
            ->line('**📄 Número de orden:** #' . $orderNumber);
        
        if ($orderAmount > 0) {
            $mail->line('**💰 Monto:** $' . number_format($orderAmount, 2));
        }
        
        $mail->line('**✓ Estado:** Cancelada')
            ->line('')
            ->line('---')
            ->line('### 🔄 Motivos Comunes de Cancelación')
            ->line('')
            ->line('Una orden puede ser cancelada por diferentes razones:')
            ->line('• Solicitud del cliente')
            ->line('• Imposibilidad de cumplir con las especificaciones requeridas')
            ->line('• Problemas con el pago o documentación')
            ->line('• Falta de disponibilidad de materiales especiales')
            ->line('• Cambios en los requerimientos del proyecto')
            ->line('')
            ->line('---')
            ->line('### 💰 Información sobre Pagos')
            ->line('')
            ->line('Si realizaste algún pago relacionado con esta orden:')
            ->line('• Nos pondremos en contacto contigo para gestionar el reembolso')
            ->line('• El proceso de devolución se realizará mediante el mismo método de pago')
            ->line('• Recibirás confirmación del reembolso en los próximos días hábiles')
            ->line('')
            ->line('---')
            ->line('### 🤝 ¿Qué Sigue?')
            ->line('')
            ->line('• **Nueva cotización:** Si deseas realizar un nuevo pedido, con gusto te ayudaremos')
            ->line('• **Modificaciones:** Si quieres hacer ajustes a tu solicitud original, contáctanos')
            ->line('• **Asesoría:** Estamos disponibles para resolver cualquier duda o inquietud')
            ->line('')
            ->line('💡 **Nuestro compromiso:** Aunque esta orden no pudo completarse, valoramos tu interés y estamos comprometidos a brindarte el mejor servicio.')
            ->line('')
            ->line('---');
        
        // Información de contacto si existe
        if ($this->owner && $this->owner->phone) {
            $mail->line('### 📞 Contáctanos')
                ->line('Para cualquier consulta sobre esta cancelación o para iniciar un nuevo pedido:')
                ->line('• Teléfono: ' . $this->owner->phone)
                ->line('• Plataforma: Accede a tu cuenta para más información')
                ->line('')
                ->line('---')
                ->line('');
        }
        
        $mail->line('🙏 **Agradecemos tu Comprensión**')
            ->line('Lamentamos cualquier inconveniente que esto pueda haber causado. Esperamos poder servirte en el futuro.')
            ->line('')
            ->salutation('Saludos,  
**Equipo de Quality Services**  
*Comprometidos con tu satisfacción*');
        
        return $mail;
    }
}
