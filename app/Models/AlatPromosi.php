<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlatPromosi extends Model {
    protected $table = 'alat_promosi';
    protected $fillable = ['poster', 'caption'];
}