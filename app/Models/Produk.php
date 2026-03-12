<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Produk extends Model {
    protected $table = 'produks';
    protected $fillable = ['toko_id','kategori_id', 'nama_produk', 'harga', 'deskripsi'];

    // Relasi ke Komisi Affiliator (Tambahkan ini)
    public function komisis(): HasMany { 
        return $this->hasMany(KomisiAffiliator::class, 'produk_id'); 
    }

    public function kategori(): BelongsTo { return $this->belongsTo(Kategori::class); }
    public function fotos(): HasMany { return $this->hasMany(ProdukFoto::class); }
    public function variants(): HasMany { return $this->hasMany(ProdukVariant::class); }
    public function toko() {return $this->belongsTo(Toko::class);}
    public function ulasans() {return $this->hasMany(Ulasan::class)->latest();}
    
    public function totalStok() {
        return $this->variants()->sum('stok');
    }
}