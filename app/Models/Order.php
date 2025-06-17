<?php

namespace App\Models;

use App\Notifications\OrderStatusChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'mobile',
        'note',
        'total_price',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    protected static function booted()
    {
        static::updated(function ($order) {
            if ($order->isDirty('status')) {
                $order->customer?->notify(new OrderStatusChanged($order));
            }
        });
    }

}
