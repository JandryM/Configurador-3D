<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class OrderCompleted extends Notification implements ShouldQueue
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
            ->subject('🎉 ¡Tu Pedido Está Listo! - Orden #' . $orderNumber . ' - Quality Services')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('¡Tenemos una noticia emocionante! Tu orden **#' . $orderNumber . '** ha sido completada exitosamente.')
            ->line('')
            ->line('🎉 **¡Tu Producto Está Terminado!**')
            ->line('Nos complace informarte que hemos finalizado la fabricación de tu pedido con los más altos estándares de calidad.')
            ->line('')
            ->line('---')
            ->line('### 📋 Detalles de tu Orden')
            ->line('')
            ->line('**📄 Número de orden:** #' . $orderNumber);
        
        if ($orderAmount > 0) {
            $mail->line('**💰 Monto:** $' . number_format($orderAmount, 2));
        }
        
        $mail->line('**✓ Estado:** Completada y lista para entrega')
            ->line('')
            ->line('---')
            ->line('### ✨ Control de Calidad Aprobado')
            ->line('')
            ->line('Tu producto ha pasado por nuestro riguroso proceso de inspección:')
            ->line('✓ **Verificación de materiales:** Materiales de primera calidad confirmados')
            ->line('✓ **Acabado perfecto:** Revisión completa de terminaciones')
            ->line('✓ **Pruebas funcionales:** Funcionamiento óptimo verificado')
            ->line('✓ **Empaque protegido:** Listo para un transporte seguro')
            ->line('')
            ->line('---')
            ->line('### 📦 Próximos Pasos')
            ->line('')
            ->line('1. **Coordinación de entrega:** Nos pondremos en contacto contigo para coordinar la entrega')
            ->line('2. **Inspección final:** Podrás revisar tu producto al momento de recibirlo')
            ->line('3. **Garantía incluida:** Tu producto cuenta con nuestra garantía de calidad')
            ->line('')
            ->line('💡 **Importante:** Nuestro equipo se comunicará contigo pronto para coordinar los detalles de la entrega.')
            ->line('')
            ->line('---');
        
        // Información de contacto si existe
        if ($this->owner && $this->owner->phone) {
            $mail->line('### 📞 Contáctanos')
                ->line('Para coordinar la entrega o cualquier consulta:')
                ->line('• Teléfono: ' . $this->owner->phone)
                ->line('• Plataforma: Revisa los detalles en tu cuenta')
                ->line('')
                ->line('---')
                ->line('');
        }
        
        $mail->line('🌟 **¡Gracias por tu Confianza!**')
            ->line('Ha sido un placer trabajar en tu proyecto. Esperamos que disfrutes de tu nuevo producto tanto como nosotros disfrutamos creándolo.')
            ->line('')
            ->line('📝 **Tu opinión es importante:** Nos encantaría conocer tu experiencia con nosotros.')
            ->line('')
            ->salutation('Con aprecio,  
**Equipo de Quality Services**  
*Creando productos excepcionales desde [año]*');
        
        return $mail;
    }
}
