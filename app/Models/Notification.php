<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Notification extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'notifications';

    protected $fillable = [
        'type',       // 'order', 'user', 'system'
        'message',    // Teks pesan notifikasi
        'is_read',    // true / false
        'created_at'  // Waktu dibuat (Carbon / DateTime)
    ];
}