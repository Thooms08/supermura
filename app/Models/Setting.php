<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti standar plural Laravel (settings)
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'no_rek',
        'qris',
    ];
}