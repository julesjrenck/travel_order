<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TravelOrderStatusNotification extends Notification
{
    use Queueable;

    private $status;
    private $travelOrder;

    /**
     * Create a new notification instance.
     */
    public function __construct($status, $travelOrder)
    {
        $this->status = $status;
        $this->travelOrder = $travelOrder;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Notificação via e-mail e salvando no banco
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        
        return (new MailMessage)
                    ->subject('Status do Pedido de Viagem Atualizado')
                    ->greeting("Olá, {$notifiable->name}!")
                    ->line("O status do seu pedido de viagem foi atualizado para: $this->status.")
                    ->line("Destino: {$this->travelOrder->destination}")
                    ->line('Data de início: ' . $this->travelOrder->start_date->format('d/m/Y'))
                    ->line('Data de término: ' . $this->travelOrder->end_date->format('d/m/Y'))
                    ->line('Se precisar de mais informações, entre em contato com o suporte.')
                    ->salutation('Atenciosamente, Equipe Travel Order');;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->travelOrder->id,
            'status' => $this->status,
            'destination' => $this->travelOrder->destination,
        ];
    }
}
