<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderRefund extends Model
{
    protected $table = 'order_refunds';

    // WAJIB: Daftarkan kolom agar bisa diisi menggunakan fungsi ::create()
    protected $fillable = [
        'pesanan_id', 
        'nomor_pengembalian', 
        'alasan', 
        'status'
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}