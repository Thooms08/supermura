<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukFoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'produk_id',
        'path_foto'
    ];

    /**
     * Relasi balik ke Produk
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}