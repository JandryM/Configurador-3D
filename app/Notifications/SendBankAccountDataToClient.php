<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\BankAccount;
use App\Models\User;

class SendBankAccountDataToClient extends Notification
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
        return (new MailMessage)
            ->subject('Datos Bancarios para tu Orden en Quality')
            ->greeting('Hola ' . $this->client->name . ',')
            ->line('Tu orden ha sido registrada exitosamente. Para realizar el pago, utiliza los siguientes datos bancarios:')
            ->line('Banco: ' . ($this->bankAccount->bank_name ?? 'No configurado'))
            ->line('Tipo de cuenta: ' . ($this->bankAccount->account_type ?? 'No configurado'))
            ->line('Número de cuenta: ' . ($this->bankAccount->account_number ?? 'No configurado'))
            ->line('Titular: ' . ($this->bankAccount->holder_name ?? 'No configurado'))
            ->line('Identificación: ' . ($this->bankAccount->identification ?? 'No configurado'))
            ->line('Teléfono: ' . ($this->bankAccount->phone ?? 'No configurado'))
            ->line('Si tienes dudas, puedes responder a este correo o contactar al propietario.');
    }
}
