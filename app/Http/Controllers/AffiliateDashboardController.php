<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting; // Tambahkan ini agar kode lebih bersih

class AffiliateDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data affiliator dari user yang login
        $affiliator = Auth::user()->affiliator;

        if (!$affiliator) {
            abort(404, 'Data affiliator tidak ditemukan.');
        }

        // 2. Ambil nilai komisi_rekrut dari tabel settings
        // Pindahkan baris ini ke ATAS sebelum perintah return
        $komisi_rekrut = Setting::where('key', 'komisi_rekrut')->first()->value ?? 0;

        // 3. Kirim kedua variabel ke view dalam satu perintah return
        return view('affiliate.index', compact('affiliator', 'komisi_rekrut'));
    }
}