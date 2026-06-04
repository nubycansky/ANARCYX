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
        'order_id_string',
        'total_price',
        'status',
        'shipping_address',
        'items'
    ];
}