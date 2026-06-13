<?php

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\KomisiAffiliatorRecord;
use App\Models\PengajuanKomisi;
use App\Models\Affiliator;
use Illuminate\Support\Facades\Auth;

class AffiliateKomisiController extends Controller
{
    /**
     * Helper untuk menghitung saldo yang benar-benar bisa ditarik.
     * Rumus: (Total Komisi Penjualan Berhasil + Komisi Rekrut) - (Total Penarikan Pending/Sukses)
     */
    private function getAvailableBalance($affiliatorId)
    {
        $affiliator = Affiliator::find($affiliatorId);
        
        // 1. Ambil saldo bonus rekrut langsung dari tabel affiliators
        $komisiRekrut = $affiliator->komisi_rekrut ?? 0;

        // 2. Hitung total komisi dari penjualan yang statusnya 'berhasil'
        $totalKomisiPenjualan = KomisiAffiliatorRecord::where('affiliator_id', $affiliatorId)
            ->where('status', 'berhasil')
            ->sum('nominal_komisi');

        // 3. Hitung total penarikan yang sudah diajukan (Pending & Success)
        $totalKeluar = PengajuanKomisi::where('affiliator_id', $affiliatorId)
            ->whereIn('status', ['pending', 'success'])
            ->sum('nominal');

        return ($totalKomisiPenjualan + $komisiRekrut) - $totalKeluar;
    }

    /**
     * Menampilkan halaman ringkasan dan riwayat komisi.
     */
   public function index()
{
    $affiliator = Auth::user()->affiliator;
    $affiliatorId = $affiliator->id;

    // 1. Data Komisi Penjualan Berhasil
    $totalKomisiPenjualan = KomisiAffiliatorRecord::where('affiliator_id', $affiliatorId)
                            ->where('status', 'berhasil')
                            ->sum('nominal_komisi');

    // 2. Data Komisi Rekrut (AMBIL DARI KOLOM DATABASE)
    $totalKomisiRekrut = $affiliator->komisi_rekrut ?? 0;

    // 3. Aktivitas Penarikan (WD)
    $wd_pending = PengajuanKomisi::where('affiliator_id', $affiliatorId)->where('status', 'pending')->sum('nominal');
    $wd_success = PengajuanKomisi::where('affiliator_id', $affiliatorId)->where('status', 'success')->sum('nominal');
    $wd_fail    = PengajuanKomisi::where('affiliator_id', $affiliatorId)->where('status', 'fail')->sum('nominal');

    $stats = [
        // Saldo tersedia = (Komisi Penjualan + Komisi Rekrut) - (WD Pending + WD Sukses)
        'saldo_tersedia'     => ($totalKomisiPenjualan + $totalKomisiRekrut) - ($wd_pending + $wd_success),
        'penarikan_berhasil' => $wd_success,
        'penarikan_pending'  => $wd_pending,
        'penarikan_gagal'    => $wd_fail,
        'komisi_rekrut'      => $totalKomisiRekrut, // Menampilkan total bonus rekrut
        'komisi_penjualan'   => $totalKomisiPenjualan
    ];

    $history = KomisiAffiliatorRecord::with(['produk', 'variant'])
                ->where('affiliator_id', $affiliatorId)
                ->latest() 
                ->paginate(10); 

    return view('affiliate.komisi', compact('stats', 'history'));
}

    /**
     * Menampilkan form pengajuan komisi.
     */
    public function createPengajuan()
    {
        $affiliatorId = Auth::user()->affiliator->id;
        $saldo = $this->getAvailableBalance($affiliatorId);

        return view('affiliate.pengajuan-komisi', compact('saldo'));
    }

    /**
     * Memproses data pengajuan penarikan dana.
     */
    public function storePengajuan(Request $request)
    {
        $affiliatorId = Auth::user()->affiliator->id;
        $saldo = $this->getAvailableBalance($affiliatorId);

        $request->validate([
            'nominal' => [
                'required', 
                'numeric', 
                'min:50000', 
                "max:$saldo"
            ],
            'nomor_pembayaran' => 'required|string|max:255',
        ], [
            'nominal.required' => 'Nominal penarikan harus diisi.',
            'nominal.min'      => 'Minimal penarikan adalah Rp 50.000.',
            'nominal.max'      => 'Saldo Anda tidak mencukupi untuk melakukan penarikan ini.',
            'nomor_pembayaran.required' => 'Nomor Rekening atau detail E-Wallet wajib diisi.'
        ]);

        PengajuanKomisi::create([
            'affiliator_id'    => $affiliatorId,
            'nominal'          => $request->nominal,
            'nomor_pembayaran' => $request->nomor_pembayaran,
            'status'           => 'pending',
        ]);

        return redirect()->route('affiliate.komisi.konfirmasi');
    }

    public function konfirmasi()
    {
        return view('affiliate.konfirmasi-komisi');
    }
}

