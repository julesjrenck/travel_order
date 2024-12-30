<?php

namespace App\Services;

use App\Models\TravelOrder;
use App\Notifications\TravelOrderStatusNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TravelOrderService
{
    /**
     * Verifica se o pedido de viagem pode ser cancelado.
     *
     * @param TravelOrder $travelOrder
     * @return bool
     */
    public function canCancel(TravelOrder $travelOrder)
    {
        if (
            $travelOrder->status === 'solicitado' || 
            ($travelOrder->status === 'aprovado' && $travelOrder->start_date > now())
        ) {
            return true;
        }
       
        if ($travelOrder->status === 'aprovado' && $travelOrder->start_date <= now()) {
            throw ValidationException::withMessages([
                'status' => 'Pedidos aprovados não podem ser cancelados após a data de início.'
            ]);
        }

        if ($travelOrder->status === 'cancelado') {
            throw ValidationException::withMessages([
                'status' => 'Pedido já está cancelado.'
            ]);
        }
    
    }

    /**
     * Envia a notificação sobre o status do pedido.
     *
     * @param TravelOrder $travelOrder
     * @param string $status
     * @return void
     */
    public function notifyUser(TravelOrder $travelOrder, string $status)
    {
        $travelOrder->user->notify(new TravelOrderStatusNotification($status, $travelOrder));
    }
}
