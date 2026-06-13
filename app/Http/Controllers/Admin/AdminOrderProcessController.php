<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Pesanan;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderProcessController extends Controller
{
    public function index()
    {
        $orders = Pesanan::with(['pengunjung', 'items', 'shippingMethod'])
            ->where('status', 'process')
            ->latest()
            ->get();

        $shippingMethods = ShippingMethod::where('is_aktif', true)->get();

        return view('admin.order-process', compact('orders', 'shippingMethods'));
    }

    public function getProcessCount()
    {
        return response()->json([
            'count' => Pesanan::where('status', 'process')->count(),
        ]);
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
            'no_resi'     => 'required|string|max:50',
        ], [
            'no_resi.required' => 'Nomor resi wajib diisi sebelum pesanan dikirim.',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        // Ubah status menjadi 'send' (dikirim), BUKAN langsung 'success'
        // Status 'success' hanya diberikan setelah admin konfirmasi paket tiba
        $pesanan->update([
            'status'      => 'send',
            'shipping_id' => $request->shipping_id,
            'no_resi'     => $request->no_resi,
            'sent_at'     => now(),
        ]);

        return back()->with('success', 'Pesanan #' . $pesanan->nomor_pesanan . ' berhasil ditandai DIKIRIM. Konfirmasi "Paket Tiba" setelah pengunjung menerima paket.');
    }
}

