<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\PengajuanKomisi;
use Illuminate\Http\Request;

class AdminPengajuanKomisiController extends Controller
{
    /**
     * Menampilkan daftar pengajuan komisi
     */
    public function index()
    {
        $pengajuan = PengajuanKomisi::with('affiliator')
                    ->latest()
                    ->paginate(15);

        return view('admin.pengajuan-komisi', compact('pengajuan'));
    }

    /**
     * Update status pengajuan (Success atau Fail)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:success,fail'
        ]);

        $data = PengajuanKomisi::findOrFail($id);
        $data->status = $request->status;
        $data->save();

        $pesan = $request->status == 'success' ? 'Pengajuan berhasil dikonfirmasi.' : 'Pengajuan telah ditolak.';
        
        return redirect()->back()->with('success', $pesan);
    }
}

