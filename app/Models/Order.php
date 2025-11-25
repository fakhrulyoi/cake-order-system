<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_whatsapp',
        'pickup_datetime',
        'notes',
        'total_amount',
        'payment_status',
        'order_status'
    ];

    protected $casts = [
        'pickup_datetime' => 'datetime'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'ORD' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}
