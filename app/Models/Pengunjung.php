<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengunjung extends Model
{
    protected $table = 'pengunjung';

    protected $fillable = [
        'user_id', 'foto_profile', 'nama_lengkap', 'email', 
        'no_whatsapp', 'alamat_lengkap', 'kota_kabupaten', 'provinsi', 'kode_pos'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}