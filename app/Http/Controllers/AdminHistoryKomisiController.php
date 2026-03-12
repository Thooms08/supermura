<?php

namespace App\Http\Controllers;

use App\Models\PengajuanKomisi;
use Illuminate\Http\Request;

class AdminHistoryKomisiController extends Controller
{
    /**
     * Menampilkan riwayat pengajuan komisi yang sudah diproses (Success/Fail)
     */
    public function index()
    {
        $history = PengajuanKomisi::with('affiliator')
                    ->whereIn('status', ['success', 'fail']) // Hanya data final
                    ->latest()
                    ->paginate(15);

        return view('admin.history-komisi', compact('history'));
    }
}