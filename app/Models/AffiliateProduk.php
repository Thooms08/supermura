<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateProduk extends Model {
    protected $fillable = ['affiliator_id', 'produk_id'];
    public function produk() { return $this->belongsTo(Produk::class); }
}