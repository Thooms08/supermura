<?php

namespace App\Http\Controllers;

use App\Models\OrderRefund;
use Illuminate\Http\Request;

class AdminRefundController extends Controller
{
    /**
     * Menampilkan daftar pengajuan refund yang berstatus PENDING
     */
    public function index()
    {
        // Mengambil data refund beserta relasi pesanan, pengunjung, dan item produk
        $refunds = OrderRefund::with([
            'pesanan.pengunjung', 
            'pesanan.items.produk.fotos'
        ])
        ->where('status', 'pending')
        ->latest()
        ->get();

        return view('admin.refund-pending', compact('refunds'));
    }

    /**
     * Memproses konfirmasi atau penolakan refund
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:success,fail'
        ]);

        $refund = OrderRefund::findOrFail($id);

        // Aksi hanya mengubah status di tabel order_refund sesuai instruksi
        $refund->update([
            'status' => $request->status
        ]);

        $message = $request->status == 'success' 
            ? 'Pengembalian dana berhasil DIKONFIRMASI.' 
            : 'Pengembalian dana telah DITOLAK.';

        return back()->with('success', $message);
    }
    public function getPendingCount()
{
    // Hitung jumlah data refund dengan status pending
    $count = \App\Models\OrderRefund::where('status', 'pending')->count();

    return response()->json([
        'count' => $count
    ]);
}
public function successIndex()
{
    // Mengambil data awal status success
    $refunds = \App\Models\OrderRefund::with(['pesanan.pengunjung', 'pesanan.items.produk.fotos'])
                ->where('status', 'success')
                ->latest()
                ->get();

    return view('admin.refund-success', compact('refunds'));
}

public function successSearch(Request $request)
{
    $keyword = $request->get('keyword');

    $refunds = \App\Models\OrderRefund::with(['pesanan.pengunjung', 'pesanan.items.produk.fotos'])
        ->where('status', 'success')
        ->where(function($query) use ($keyword) {
            $query->whereHas('pesanan.pengunjung', function($q) use ($keyword) {
                $q->where('nama_lengkap', 'LIKE', "%$keyword%");
            })
            ->orWhereHas('pesanan.items.produk', function($q) use ($keyword) {
                $q->where('nama_produk', 'LIKE', "%$keyword%");
            })
            ->orWhere('alasan', 'LIKE', "%$keyword%");
        })
        ->latest()
        ->get();

    // Mengembalikan view partial (hanya isi list-nya saja)
    return view('admin.partials.refund-list', compact('refunds'))->render();
}
public function failIndex()
{
    $refunds = \App\Models\OrderRefund::with(['pesanan.pengunjung', 'pesanan.items.produk.fotos'])
                ->where('status', 'fail')
                ->latest()
                ->get();

    return view('admin.refund-fail', compact('refunds'));
}

public function failSearch(Request $request)
{
    $keyword = $request->get('keyword');

    $refunds = \App\Models\OrderRefund::with(['pesanan.pengunjung', 'pesanan.items.produk.fotos'])
        ->where('status', 'fail')
        ->where(function($query) use ($keyword) {
            $query->whereHas('pesanan.pengunjung', function($q) use ($keyword) {
                $q->where('nama_lengkap', 'LIKE', "%$keyword%");
            })
            ->orWhereHas('pesanan.items.produk', function($q) use ($keyword) {
                $q->where('nama_produk', 'LIKE', "%$keyword%");
            })
            ->orWhere('alasan', 'LIKE', "%$keyword%");
        })
        ->latest()
        ->get();

    // Mengembalikan partial view khusus list agar AJAX bisa merender ulang
    return view('admin.partials.refund-fail-list', compact('refunds'))->render();
}
}