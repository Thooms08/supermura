<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class AdminOrderSendController extends Controller
{
    /**
     * Halaman daftar pesanan berstatus 'send' (sedang dikirim).
     */
    public function index()
    {
        $orders = Pesanan::with(['pengunjung', 'items', 'shippingMethod'])
            ->where('status', 'send')
            ->latest()
            ->get();

        return view('admin.order-send', compact('orders'));
    }

    /**
     * Tandai paket sudah tiba di tangan pengunjung → status menjadi 'success'.
     * Juga trigger generate komisi affiliator jika ada.
     */
    public function markAsArrived(Request $request, $id)
    {
        $pesanan = Pesanan::with('items')->findOrFail($id);

        if ($pesanan->status !== 'send') {
            return back()->with('error', 'Hanya pesanan berstatus "Dikirim" yang dapat dikonfirmasi tiba.');
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($pesanan) {
            $pesanan->update(['status' => 'success']);
            $pesanan->generateCommission();

            return back()->with('success', 'Pesanan #' . $pesanan->nomor_pesanan . ' dikonfirmasi TIBA dan komisi telah dicatat!');
        });
    }

    /**
     * Endpoint AJAX untuk menghitung jumlah pesanan berstatus 'send'.
     */
    public function getSendCount()
    {
        return response()->json([
            'count' => Pesanan::where('status', 'send')->count(),
        ]);
    }
}
