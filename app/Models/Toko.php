<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto_toko',
        'nama_toko',
        'deskripsi',
        'alamat',
        'email',
        'no_whatsapp',
    ];
}