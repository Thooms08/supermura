<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomisiAffiliator extends Model {
    protected $fillable = ['produk_id', 'produk_variant_id', 'nominal_komisi'];

    public function produk() {
        return $this->belongsTo(Produk::class);
    }

    public function variant() {
        return $this->belongsTo(ProdukVariant::class, 'produk_variant_id');
    }
}