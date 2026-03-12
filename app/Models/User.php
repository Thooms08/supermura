<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'google_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Tambahkan di dalam class User
public function pengunjung()
{
    return $this->hasOne(Pengunjung::class, 'user_id');
}
public function affiliator() {
    return $this->hasOne(Affiliator::class, 'user_id');
}
}
