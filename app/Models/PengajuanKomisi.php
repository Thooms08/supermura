<?php
// Models/PengajuanKomisi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanKomisi extends Model {
    protected $table = 'pengajuan_komisi';
    protected $fillable = ['affiliator_id', 'nominal', 'nomor_pembayaran', 'status'];

    // Relasi ke model Affiliator
    public function affiliator() {
        return $this->belongsTo(Affiliator::class, 'affiliator_id');
    }
}