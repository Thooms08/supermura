<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\KomisiAffiliator;
use App\Models\KomisiAffiliatorRecord;
use App\Models\OrderRefund; 

class Pesanan extends Model 
{
    protected $table = 'pesanan';
    
    protected $fillable = [
        'pengunjung_id', 
        'affiliator_id',
        'shipping_id',
        'nomor_pesanan', 
        'total_harga', 
        'metode_pengiriman', 
        'metode_pembayaran', 
        'no_resi',
        'sent_at',
        'snap_token', 
        'status',
        'cancelled_before_payment',
        'seen_at',
    ];

    protected $casts = [
        'cancelled_before_payment' => 'boolean',
        'seen_at'                  => 'datetime',
        'sent_at'                  => 'datetime',
    ];

    // --- RELASI ---

    /**
     * Relasi ke data Refund (Satu pesanan biasanya memiliki satu pengajuan refund)
     */
    public function refund()
    {
        return $this->hasOne(OrderRefund::class, 'pesanan_id');
    }

    public function pengunjung()
    {
        return $this->belongsTo(Pengunjung::class, 'pengunjung_id');
    }

    public function items()
    {
        return $this->hasMany(PesananItem::class, 'pesanan_id');
    }

    public function affiliator()
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id');
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_id');
    }

    public function komisiRecords()
    {
        return $this->hasMany(KomisiAffiliatorRecord::class, 'pesanan_id');
    }

    // --- LOGIKA KOMISI ---

    /**
     * Fungsi untuk menghasilkan komisi bagi affiliator jika pesanan sukses
     */
    public function generateCommission() 
    {
        // 1. Validasi Dasar
        if ($this->status !== 'success' || !$this->affiliator_id) {
            return false;
        }

        // 2. ANTI-DUPLIKASI: Cek apakah pesanan ini sudah pernah dibuatkan komisinya
        $exists = KomisiAffiliatorRecord::where('pesanan_id', $this->id)->exists();
        if ($exists) {
            return false; 
        }

        // 3. Gunakan Database Transaction agar data item & record konsisten
        return DB::transaction(function () {
            foreach ($this->items as $item) {
                // Cari setting komisi
                // Prioritaskan yang ada produk_variant_id-nya, jika tidak ada ambil yang NULL
                $komisiSetting = KomisiAffiliator::where('produk_id', $item->produk_id)
                    ->where(function($q) use ($item) {
                        $q->where('produk_variant_id', $item->produk_variant_id)
                          ->orWhereNull('produk_variant_id');
                    })
                    ->orderBy('produk_variant_id', 'desc') 
                    ->first();

                if ($komisiSetting) {
                    KomisiAffiliatorRecord::create([
                        'affiliator_id'     => $this->affiliator_id,
                        'pesanan_id'        => $this->id,
                        'produk_id'         => $item->produk_id,
                        'produk_variant_id' => $item->produk_variant_id,
                        'nominal_komisi'    => $komisiSetting->nominal_komisi,
                        'status'            => 'berhasil'
                    ]);
                }
            }
            return true;
        });
    }
}