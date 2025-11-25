<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cake extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'size',
        'image',
        'status',
        'category'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
