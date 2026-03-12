<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affiliator extends Model
{
    protected $fillable = [
        'user_id', 
        'id_unik', 
        'kode_referral', 
        'nama', 
        'foto_profile', 
        'no_whatsapp', 
        'domisili', 
        'metode_pembayaran', 
        'bukti_transfer', 
        'nominal_tunai', 
        'komisi_rekrut', 
        'status'
    ];

    /**
     * Relasi ke model User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mutator: Pastikan id_unik selalu huruf besar (Uppercase)
     */
    public function setIdUnikAttribute($value)
    {
        $this->attributes['id_unik'] = strtoupper($value);
    }

    /**
     * Mutator: Pastikan kode_referral selalu huruf besar (Uppercase)
     */
            // Ganti setKodedReferralkAttribute menjadi:
        public function setKodeReferralAttribute($value)
        {
            $this->attributes['kode_referral'] = strtoupper($value);
        }

    /**
     * Casting tipe data agar komisi dibaca sebagai angka (bukan string)
     */
    protected $casts = [
        'komisi_rekrut' => 'decimal:2',
        'nominal_tunai' => 'decimal:2',
    ];
}