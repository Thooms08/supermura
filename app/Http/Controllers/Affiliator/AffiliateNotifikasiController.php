<?php

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;

use App\Models\Affiliator;
use App\Models\PengajuanKomisi;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateNotifikasiController extends Controller
{
    public function index()
    {
        $affiliator = Auth::user()->affiliator;
        
        if (!$affiliator) {
            abort(404);
        }

        // 1. Ambil Notifikasi Pengajuan Komisi
        $pengajuan = PengajuanKomisi::where('affiliator_id', $affiliator->id)
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->tipe_notif = 'komisi';
                return $item;
            });

        // 2. Ambil Notifikasi Rekrut Affiliator Baru
        $rekrut = Affiliator::where('kode_referral', $affiliator->id_unik)
            ->latest()
            ->get()
            ->map(function ($item) {
                // Ambil nilai komisi rekrut dari setting saat ini
                $nominalKomisi = Setting::where('key', 'komisi_rekrut')->first()->value ?? 0;
                $item->tipe_notif = 'rekrut';
                $item->nominal_bonus = $nominalKomisi;
                return $item;
            });

        // 3. Gabungkan dan Urutkan berdasarkan created_at terbaru
        $notifications = $pengajuan->concat($rekrut)->sortByDesc('created_at');

        return view('affiliate.notifikasi', compact('notifications', 'affiliator'));
    }

    /**
     * AJAX Method untuk menghitung jumlah total notifikasi
     */
    public function getCount()
    {
        $affiliator = Auth::user()->affiliator;
        
        if (!$affiliator) {
            return response()->json(['total' => 0]);
        }

        $countPengajuan = PengajuanKomisi::where('affiliator_id', $affiliator->id)->count();
        $countRekrut = Affiliator::where('kode_referral', $affiliator->id_unik)->count();

        return response()->json([
            'total' => $countPengajuan + $countRekrut
        ]);
    }
}

