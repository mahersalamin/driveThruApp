<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewOrderPlaced extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database']; // or ['mail', 'database'] if email is needed
    }

    public function toDatabase($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'total_price' => $this->order->total_price,
            'status' => $this->order->status ?? 'pending',
            'placed_at' => $this->order->created_at,
        ];
    }
}
