<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $fillable = ['user_id', 'produk_id', 'nama', 'rating', 'komentar', 'foto'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    // Cast foto otomatis menjadi array saat dipanggil
    protected $casts = [
        'foto' => 'array',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}