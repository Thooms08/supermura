<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananItem extends Model 
{
    protected $table = 'pesanan_items';
    protected $fillable = [
        'pesanan_id', 
        'produk_id', 
        'produk_variant_id',
        'nama_produk', 
        'qty', 
        'harga', 
        'subtotal'
    ];

    /**
     * Relasi ke Pesanan (Parent)
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Relasi ke Produk (PENTING: Agar bisa ambil foto produk)
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function variant(): BelongsTo
{
    return $this->belongsTo(ProdukVariant::class, 'produk_variant_id');
}
}