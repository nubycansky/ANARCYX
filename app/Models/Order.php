<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_address',
        'items' // <-- Di sini kehebatan MongoDB (Embedding) bekerja!
    ];
}