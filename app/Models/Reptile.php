<?php

namespace App\Models;

// 1. Ganti bawaan Laravel pakai model khusus MongoDB:
use MongoDB\Laravel\Eloquent\Model; 

class Reptile extends Model
{
    // 2. Pastikan dia pakai koneksi MongoDB
    protected $connection = 'mongodb';
    
    // 3. Nama koleksinya di database Compass kamu
    protected $collection = 'reptiles';

    // 4. Kolom apa aja yang boleh diisi nanti (Mass Assignment)
    protected $fillable = [
        'name', 
        'category', 
        'attributes', // Nampung detail kayak umur, berat, morph (bentuk JSON)
        'price', 
        'stock', 
        'image'
    ];
}