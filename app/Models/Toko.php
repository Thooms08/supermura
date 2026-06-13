<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'foto_toko',
        'nama_toko',
        'deskripsi',
        'alamat',
        'email',
        'no_whatsapp',
    ];

    protected static function booted(): void
    {
        static::creating(function (Toko $toko) {
            if (empty($toko->uuid)) {
                $toko->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Cari toko berdasarkan UUID.
     */
    public static function findByUuid(string $uuid): ?self
    {
        return static::where('uuid', $uuid)->first();
    }
}