<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Str;

class Produk extends Model {
    protected $table = 'produks';
    protected $fillable = ['toko_id', 'kategori_id', 'nama_produk', 'slug', 'harga', 'deskripsi'];

    /**
     * Route model binding pakai slug bukan id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Auto-generate slug saat create & update nama_produk.
     */
    protected static function booted(): void
    {
        static::creating(function (Produk $produk) {
            if (empty($produk->slug)) {
                $produk->slug = static::generateSlug($produk->nama_produk, $produk->id ?? 0);
            }
        });

        // Saat update, regenerate slug jika nama berubah dan slug belum di-set manual
        static::updating(function (Produk $produk) {
            if ($produk->isDirty('nama_produk') && !$produk->isDirty('slug')) {
                $produk->slug = static::generateSlug($produk->nama_produk, $produk->id);
            }
        });
    }

    /**
     * Format: {slug-nama}-{8-char-hash}
     * Contoh: kaos-polos-premium-a1b2c3d4
     * Hash dari id+nama supaya unik tapi tidak expose data internal.
     */
    public static function generateSlug(string $nama, int $id = 0): string
    {
        $base   = Str::slug($nama);
        $suffix = substr(md5($id . $nama . config('app.key', 'secret')), 0, 8);
        return $base . '-' . $suffix;
    }

    // Relasi ke Komisi Affiliator
    public function komisis(): HasMany {
        return $this->hasMany(KomisiAffiliator::class, 'produk_id');
    }

    public function kategori(): BelongsTo { return $this->belongsTo(Kategori::class); }
    public function fotos(): HasMany { return $this->hasMany(ProdukFoto::class); }
    public function variants(): HasMany { return $this->hasMany(ProdukVariant::class); }
    public function toko(): BelongsTo { return $this->belongsTo(Toko::class); }
    public function ulasans(): HasMany { return $this->hasMany(Ulasan::class)->latest(); }

    public function totalStok(): int
    {
        return (int) $this->variants()->sum('stok');
    }

    /**
     * Rata-rata rating dari ulasan yang sudah ada.
     * Return float (misal 4.2), atau null jika belum ada ulasan.
     */
    public function averageRating(): ?float
    {
        $count = $this->ulasans()->count();
        if ($count === 0) return null;
        return round($this->ulasans()->avg('rating'), 1);
    }

    /**
     * Total qty produk yang berhasil terjual.
     * Dihitung dari pesanan berstatus 'success' atau 'send' (sudah dibayar/dikirim).
     */
    public function totalTerjual(): int
    {
        return (int) \App\Models\PesananItem::whereHas('pesanan', function ($q) {
                $q->whereIn('status', ['success', 'send']);
            })
            ->where('produk_id', $this->id)
            ->sum('qty');
    }
}