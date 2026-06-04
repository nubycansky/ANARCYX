<?php

namespace App\Models;

// Menggunakan Authenticatable khusus driver MongoDB Laravel
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'username',
        'phone_number',
        'password',
        'role',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Otomatis melakukan enkripsi password saat pengisian data baru ke Atlas
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}