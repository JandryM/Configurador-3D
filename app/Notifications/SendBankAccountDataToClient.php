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

    protected $order;
    protected $client;
    protected $owner;
    protected $bankAccount;

    public function __construct($order, User $client)
    {
        $this->order = $order;
        $this->client = $client;
        $this->owner = User::where('role', 'owner')->first();
        $this->bankAccount = BankAccount::where('user_id', $this->owner->id)->first();
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $orderNumber = is_array($this->order) ? $this->order['number'] : $this->order->number;
        $orderAmount = is_array($this->order) ? $this->order['amount'] : $this->order->amount;
        
        return (new MailMessage)
            ->subject('¡Tu Orden #' . $orderNumber . ' ha sido Aprobada! - Quality Services')
            ->greeting('¡Hola ' . $this->client->name . '!')
            ->line('Nos complace informarte que tu orden **#' . $orderNumber . '** ha sido aprobada y está lista para procesar.')
            ->line('**Monto total a pagar:** $' . number_format($orderAmount, 2))
            ->line('---')
            ->line('### 📋 Instrucciones de Pago')
            ->line('Por favor, realiza la transferencia o depósito bancario utilizando los siguientes datos:')
            ->line('')
            ->line('**🏦 Banco:** ' . ($this->bankAccount->bank_name ?? 'No configurado'))
            ->line('**💳 Tipo de cuenta:** ' . ucfirst($this->bankAccount->account_type ?? 'No configurado'))
            ->line('**🔢 Número de cuenta:** ' . ($this->bankAccount->account_number ?? 'No configurado'))
            ->line('**👤 Titular:** ' . ($this->bankAccount->holder_name ?? 'No configurado'))
            ->line('**📇 Cédula/RUC:** ' . ($this->bankAccount->identification ?? 'No configurado'))
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
            ->line('• Contactarnos al: ' . ($this->bankAccount->phone ?? $this->owner->phone ?? 'No disponible'))
            ->line('• Visitar nuestra plataforma para ver el estado de tu orden')
            ->line('')
            ->salutation('Saludos cordiales,  
**Equipo de Quality Services**  
*Tu satisfacción es nuestra prioridad*');
    }
}
