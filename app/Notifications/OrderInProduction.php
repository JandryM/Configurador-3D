<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class OrderInProduction extends Notification implements ShouldQueue
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
        $estimatedFinishDate = is_array($this->order) ? ($this->order['estimated_finish_at'] ?? null) : ($this->order->estimated_finish_at ?? null);
        
        $mail = (new MailMessage)
            ->subject('🔧 Producción Iniciada - Orden #' . $orderNumber . ' - Quality Services')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('¡Tenemos excelentes noticias! Tu orden **#' . $orderNumber . '** ha entrado en producción.')
            ->line('')
            ->line('🔧 **¡Ya Estamos Trabajando en tu Producto!**')
            ->line('Nuestro equipo de producción ha iniciado la fabricación de tu pedido con los más altos estándares de calidad.')
            ->line('')
            ->line('---')
            ->line('### 📋 Detalles de Producción')
            ->line('')
            ->line('**📄 Número de orden:** #' . $orderNumber);
        
        if ($orderAmount > 0) {
            $mail->line('**💰 Monto:** $' . number_format($orderAmount, 2));
        }
        
        if ($estimatedFinishDate) {
            $formattedDate = \Carbon\Carbon::parse($estimatedFinishDate)->format('d/m/Y');
            $daysRemaining = abs(ceil(now()->diffInDays(\Carbon\Carbon::parse($estimatedFinishDate), false)));
            
            $mail->line('**📅 Fecha estimada de finalización:** ' . $formattedDate)
                ->line('**⏱️ Tiempo estimado:** ' . $daysRemaining . ' día(s) aproximadamente');
        }
        
        $mail->line('**✓ Estado actual:** En producción')
            ->line('')
            ->line('---')
            ->line('### 🎯 ¿Qué Está Pasando Ahora?')
            ->line('')
            ->line('✓ **Materiales seleccionados:** Hemos reservado los mejores materiales para tu producto')
            ->line('✓ **Proceso de fabricación:** Nuestros especialistas están trabajando en cada detalle')
            ->line('✓ **Control de calidad:** Supervisamos cada paso del proceso')
            ->line('✓ **Seguimiento continuo:** Puedes ver el estado en tiempo real en nuestra plataforma')
            ->line('')
            ->line('💡 **Compromiso de calidad:** Nos aseguramos de que cada producto cumpla con los más altos estándares antes de ser entregado.')
            ->line('')
            ->line('---')
            ->line('### 👀 Mantente Informado')
            ->line('')
            ->line('Puedes revisar el progreso de tu orden en cualquier momento ingresando a nuestra plataforma. Te notificaremos cuando tu producto esté listo.')
            ->line('');
        
        // Información de contacto si existe
        if ($this->owner && $this->owner->phone) {
            $mail->line('---')
                ->line('### 📞 ¿Tienes Preguntas?')
                ->line('Estamos disponibles para ayudarte:')
                ->line('• Teléfono: ' . $this->owner->phone)
                ->line('• Plataforma: Consulta el estado de tu orden en tu cuenta')
                ->line('');
        }
        
        $mail->line('🚀 **¡Tu producto está en buenas manos!**')
            ->line('Trabajamos con dedicación para entregarte un resultado excepcional.')
            ->line('')
            ->salutation('Saludos cordiales,  
**Equipo de Producción - Quality Services**  
*Fabricando excelencia para ti*');
        
        return $mail;
    }
}
