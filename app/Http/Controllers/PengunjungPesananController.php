<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\OrderRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengunjungPesananController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $status = $request->query('status');

        // Memastikan shippingMethod dan pengunjung terload untuk alamat dan resi
        $query = Pesanan::with(['items.produk.fotos', 'shippingMethod', 'pengunjung', 'refund'])
            ->whereHas('pengunjung', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });

        if ($status == 'canceled') {
            $query->whereHas('refund', function($q) {
                $q->where('status', 'success');
            });
        } elseif ($status == 'refund_pending') {
            $query->whereHas('refund', function($q) {
                $q->where('status', 'pending');
            });
        } elseif ($status == 'refund_fail') {
            $query->whereHas('refund', function($q) {
                $q->where('status', 'fail');
            });
        } elseif ($status) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->get();
        return view('pengunjung.pesanan', compact('orders'));
    }

    public function batalkan(Request $request, $id)
    {
        $request->validate([
            'nomor_pengembalian' => 'required|string',
            'alasan' => 'required|string|min:10',
        ]);

        $pesanan = Pesanan::where('id', $id)
            ->whereIn('status', ['pending', 'process']) 
            ->firstOrFail();

        if ($pesanan->refund) {
            return back()->with('error', 'Permintaan pembatalan sudah dikirim sebelumnya.');
        }

        $refund = new OrderRefund();
        $refund->pesanan_id = $pesanan->id;
        $refund->nomor_pengembalian = $request->nomor_pengembalian;
        $refund->alasan = $request->alasan;
        $refund->status = 'pending';
        
        if($refund->save()) {
            return back()->with('success', 'Permintaan pembatalan berhasil dikirim!');
        } else {
            return back()->with('error', 'Gagal menyimpan ke database.');
        }
    }
}