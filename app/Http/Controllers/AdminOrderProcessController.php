<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderProcessController extends Controller
{
    public function index()
    {
        // Hanya mengambil pesanan dengan status 'process'
        $orders = Pesanan::with(['pengunjung', 'items', 'shippingMethod'])
            ->where('status', 'process')
            ->latest()
            ->get();

        $shippingMethods = ShippingMethod::where('is_aktif', true)->get();

        return view('admin.order-process', compact('orders', 'shippingMethods'));
    }

    public function markAsFail($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => 'fail']);

        return back()->with('success', 'Pesanan #' . $pesanan->nomor_pesanan . ' ditandai sebagai GAGAL.');
    }

    public function markAsSuccess(Request $request, $id)
    {
        $request->validate([
            'shipping_id' => 'required|exists:shipping_methods,id',
            'no_resi' => 'required|string|max:50',
        ], [
            'no_resi.required' => 'Nomor resi wajib diisi untuk konfirmasi sukses.'
        ]);

        // Gunakan Transaction agar jika komisi gagal, status order tidak berubah
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request, $id) {
        $pesanan = Pesanan::findOrFail($id);
        
        // 1. Update status
        $pesanan->update([
            'status' => 'success',
            'shipping_id' => $request->shipping_id,
            'no_resi' => $request->no_resi
        ]);

            // 2. Pemicu otomatis pembagian komisi
            $pesanan->generateCommission();

            return back()->with('success', 'Pesanan #' . $pesanan->nomor_pesanan . ' berhasil diselesaikan dan komisi telah dicatat!');
        });
    }
}