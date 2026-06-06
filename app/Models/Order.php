<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'order_id_string',
        'total_price',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'status',
        'shipping_address',
        'items'
    ];
}