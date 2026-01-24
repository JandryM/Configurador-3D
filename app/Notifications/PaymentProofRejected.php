<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\BankAccount;
use App\Models\User;

class PaymentProofRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $owner;
    protected $bankAccount;

    public function __construct($order)
    {
        $this->order = $order;
        $this->owner = User::where('role', 'owner')->first();
        $this->bankAccount = $this->owner ? BankAccount::where('user_id', $this->owner->id)->first() : null;
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
            ->subject('Comprobante de Pago No Válido - Orden #' . $orderNumber . ' - Quality Services')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Te contactamos respecto a tu orden **#' . $orderNumber . '**.')
            ->line('')
            ->line('⚠️ **Comprobante de Pago No Válido**')
            ->line('Lamentablemente, el comprobante de pago que enviaste no pudo ser validado. Esto puede deberse a:')
            ->line('• La imagen no es legible')
            ->line('• El comprobante no corresponde al monto de tu orden')
            ->line('• Los datos bancarios no coinciden')
            ->line('• El formato del archivo no es correcto')
            ->line('')
            ->line('---')
            ->line('### 📋 ¿Qué debes hacer?')
            ->line('')
            ->line('1. **Verifica tu pago:** Asegúrate de haber realizado la transferencia por el monto correcto')
            ->line('2. **Obtén un nuevo comprobante:** Si es necesario, solicita uno nuevo a tu banco')
            ->line('3. **Sube el comprobante nuevamente:** Accede a nuestra plataforma y carga el comprobante correcto')
            ->line('')
            ->line('**💰 Monto a pagar:** $' . number_format($orderAmount, 2))
            ->line('')
            ->line('---');
        
        // Solo incluir datos bancarios si existen
        if ($this->bankAccount) {
            $mail->line('### 🏦 Datos Bancarios para Verificar')
                ->line('Asegúrate de que tu pago se realizó a esta cuenta:')
                ->line('')
                ->line('**Banco:** ' . ($this->bankAccount->bank_name ?? 'No configurado'))
                ->line('**Tipo de cuenta:** ' . ucfirst($this->bankAccount->account_type ?? 'No configurado'))
                ->line('**Número de cuenta:** ' . ($this->bankAccount->account_number ?? 'No configurado'))
                ->line('**Titular:** ' . ($this->bankAccount->holder_name ?? 'No configurado'))
                ->line('**Cédula/RUC:** ' . ($this->bankAccount->identification ?? 'No configurado'))
                ->line('')
                ->line('---');
        }
        
        $mail->line('### 📸 Consejos para el Comprobante')
            ->line('• Asegúrate de que la imagen sea clara y legible')
            ->line('• Incluye todos los datos de la transferencia')
            ->line('• Verifica que el monto sea visible')
            ->line('• Usa formato JPG, PNG o PDF')
            ->line('')
            ->line('💬 **¿Necesitas ayuda?**')
            ->line('Si tienes dudas o problemas, contáctanos:');
        
        // Solo incluir teléfono si existe
        if ($this->bankAccount && $this->bankAccount->phone) {
            $mail->line('• Teléfono: ' . $this->bankAccount->phone);
        } elseif ($this->owner && $this->owner->phone) {
            $mail->line('• Teléfono: ' . $this->owner->phone);
        }
        
        $mail->line('• Ingresa a nuestra plataforma para más información')
            ->line('')
            ->line('⏰ **Importante:** Una vez que subas el comprobante correcto, lo verificaremos y procederemos con tu orden lo antes posible.')
            ->line('')
            ->salutation('Saludos cordiales,  
**Equipo de Quality Services**  
*Estamos aquí para ayudarte*');
        
        return $mail;
    }
}
