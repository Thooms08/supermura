<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan (Hanya status PENDING)
     */
    public function index()
    {
        // Filter: Hanya mengambil pesanan yang berstatus 'pending'
        $orders = Pesanan::with(['pengunjung', 'items'])
            ->where('status', 'pending') 
            ->latest()
            ->get();

        return view('admin.order', compact('orders'));
    }

    /**
     * Mengubah status pesanan (Process / Fail)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:process,fail'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        
        // Proteksi: Hanya ubah jika status saat ini masih pending
        if ($pesanan->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan pending yang dapat diubah statusnya.');
        }

        $pesanan->update([
            'status' => $request->status
        ]);

        // Karena setelah update statusnya bukan 'pending' lagi, 
        // pesanan ini otomatis akan hilang dari halaman index saat redirect.
        $message = $request->status == 'process' 
            ? 'Pesanan #' . $pesanan->nomor_pesanan . ' berhasil dipindah ke bagian PROSES!' 
            : 'Pesanan #' . $pesanan->nomor_pesanan . ' telah dibatalkan (FAIL).';

        return back()->with('success', $message);
    }

    public function getPendingCount()
{
    // Menghitung jumlah pesanan dengan status pending
    $count = Pesanan::where('status', 'pending')->count();

    return response()->json([
        'count' => $count
    ]);
}
}