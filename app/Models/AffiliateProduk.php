<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateProduk extends Model {
    protected $fillable = ['affiliator_id', 'produk_id', 'produk_variant_id'];

    public function produk() { return $this->belongsTo(Produk::class); }

    public function variant() { return $this->belongsTo(ProdukVariant::class, 'produk_variant_id'); }
}