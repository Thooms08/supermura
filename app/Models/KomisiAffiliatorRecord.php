<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomisiAffiliatorRecord extends Model {
    protected $table = 'komisi_affiliators_record';
    protected $fillable = ['affiliator_id', 'pesanan_id', 'produk_id', 'produk_variant_id', 'nominal_komisi', 'status'];
    
    public function produk() { return $this->belongsTo(Produk::class); }
    public function variant() { return $this->belongsTo(ProdukVariant::class, 'produk_variant_id'); }
}