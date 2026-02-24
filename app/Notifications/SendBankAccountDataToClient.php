<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\BankAccount;
use App\Models\User;

class SendBankAccountDataToClient extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $orderNumber;
    protected float $orderAmount;

    public function __construct(string $orderNumber, float $orderAmount)
    {
        $this->orderNumber = $orderNumber;
        $this->orderAmount = $orderAmount;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $owner = User::where('role', 'owner')->first();
        $bankAccount = $owner ? BankAccount::where('user_id', $owner->id)->first() : null;
        
        return (new MailMessage)
            ->subject('¡Tu Orden #' . $this->orderNumber . ' ha sido Aprobada! - Quality Services')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Nos complace informarte que tu orden **#' . $this->orderNumber . '** ha sido aprobada y está lista para procesar.')
            ->line('**Monto total a pagar:** $' . number_format($this->orderAmount, 2))
            ->line('---')
            ->line('### 📋 Instrucciones de Pago')
            ->line('Por favor, realiza la transferencia o depósito bancario utilizando los siguientes datos:')
            ->line('')
            ->line('**🏦 Banco:** ' . ($bankAccount->bank_name ?? 'No configurado'))
            ->line('**💳 Tipo de cuenta:** ' . ucfirst($bankAccount->account_type ?? 'No configurado'))
            ->line('**🔢 Número de cuenta:** ' . ($bankAccount->account_number ?? 'No configurado'))
            ->line('**👤 Titular:** ' . ($bankAccount->holder_name ?? 'No configurado'))
            ->line('**📇 Cédula/RUC:** ' . ($bankAccount->identification ?? 'No configurado'))
            ->line('')
            ->line('---')
            ->line('### 📸 Pasos a seguir:')
            ->line('1. Realiza la transferencia por el monto indicado')
            ->line('2. Guarda el comprobante de pago')
            ->line('3. Desde nuestra plataforma, en la seccion de "Mis proformas" adjunta el comprobante en la orden correspondiente')
            ->line('4. Una vez verificado tu pago, iniciaremos la producción de inmediato')
            ->line('')
            ->line('⏰ **Importante:** Tu orden será procesada una vez confirmemos el pago.')
            ->line('')
            ->line('💬 **¿Necesitas ayuda?**')
            ->line('Si tienes alguna duda o consulta, puedes:')
            ->line('• Contactarnos al: ' . ($bankAccount->phone ?? $owner->phone ?? 'No disponible'))
            ->line('• Visitar nuestra plataforma para ver el estado de tu orden')
            ->line('')
            ->salutation('Saludos cordiales,  
**Equipo de Quality Services**  
*Tu satisfacción es nuestra prioridad*');
    }
}
