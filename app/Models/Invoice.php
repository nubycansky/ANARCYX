<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Invoice extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'invoices';

    protected $fillable = [
        'order_id',       // Menghubungkan ke ID transaksi di koleksi orders
        'payment_method',  // Misal: 'Transfer BCA', 'Mandiri', dll
        'transfer_proof',  // Nama file foto bukti transfer yang diupload pembeli
        'amount_paid',     // Jumlah uang yang ditransfer
        'payment_status',  // Status: 'pending', 'valid', 'invalid'
        'paid_at'          // Waktu pembayaran
    ];
}