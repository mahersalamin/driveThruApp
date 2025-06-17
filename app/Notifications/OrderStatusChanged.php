<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'تم تحديث حالة الطلب',
            'body' => 'طلب رقم #' . $this->order->id . ' أصبح الآن: ' . $this->order->status,
            'order_id' => $this->order->id,
            'status' => $this->order->status,
        ];
    }
}

