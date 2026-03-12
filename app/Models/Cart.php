<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
    protected $fillable = ['user_id', 'produk_id', 'variant_id', 'qty'];

    public function produk() { return $this->belongsTo(Produk::class); }
    public function variant() { return $this->belongsTo(ProdukVariant::class, 'variant_id'); }
}