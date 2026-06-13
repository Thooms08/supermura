<?php

namespace App\Http\Controllers\pengunjung;

use App\Http\Controllers\Controller;

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

        $query = Pesanan::with(['items.produk.fotos', 'items.variant', 'shippingMethod', 'pengunjung', 'refund'])
            ->whereHas('pengunjung', fn($q) => $q->where('user_id', $userId));

        if ($status === 'cancelled_before_payment') {
            // Tab baru: dibatalkan sebelum bayar
            $query->where('cancelled_before_payment', true);

        } elseif ($status === 'canceled') {
            // Batal disetujui admin (sudah bayar, lalu refund disetujui)
            $query->where('cancelled_before_payment', false)
                  ->whereHas('refund', fn($q) => $q->where('status', 'success'));

        } elseif ($status === 'refund_pending') {
            $query->whereHas('refund', fn($q) => $q->where('status', 'pending'))
                  ->where('cancelled_before_payment', false);

        } elseif ($status === 'refund_fail') {
            $query->whereHas('refund', fn($q) => $q->where('status', 'fail'));

        } elseif ($status === 'fail') {
            // Gagal/expired: fail tapi BUKAN cancelled_before_payment
            $query->where('status', 'fail')->where('cancelled_before_payment', false);

        } elseif ($status === 'send') {
            $query->where('status', 'send');

        } elseif ($status) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->get();

        // ── Tandai pesanan yang belum dilihat sebagai "sudah dilihat" ──
        // Semua pesanan yang sedang ditampilkan di tab ini di-mark seen_at = now
        // Khusus tab yang butuh notifikasi (pending, fail, refund_fail)
        $notifStatuses = ['pending', 'fail', 'refund_fail'];
        if (in_array($status, $notifStatuses) || $status === null) {
            Pesanan::whereHas('pengunjung', fn($q) => $q->where('user_id', $userId))
                ->where(function ($q) {
                    $q->where('status', 'pending')
                      ->orWhere(function ($q2) {
                          $q2->where('status', 'fail')->where('cancelled_before_payment', false);
                      })
                      ->orWhereHas('refund', fn($q3) => $q3->where('status', 'fail'));
                })
                ->whereNull('seen_at')
                ->update(['seen_at' => now()]);
        }

        return view('pengunjung.pesanan', compact('orders'));
    }

    public function batalkan(Request $request, $id)
    {
        $userId  = Auth::id();
        $pesanan = Pesanan::whereHas('pengunjung', fn($q) => $q->where('user_id', $userId))
            ->where('id', $id)
            ->firstOrFail();

        if (!in_array($pesanan->status, ['pending', 'process'])) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        if ($pesanan->refund) {
            return back()->with('error', 'Permintaan pembatalan sudah dikirim sebelumnya.');
        }

        // ── Pesanan PENDING (belum bayar) → batalkan langsung tanpa review admin ──
        if ($pesanan->status === 'pending') {
            // Tidak perlu validasi alasan — langsung set cancelled
            $pesanan->update([
                'status'                   => 'fail',
                'cancelled_before_payment' => true,
                'seen_at'                  => now(), // langsung dianggap sudah dilihat
            ]);

            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        // ── Pesanan PROCESS (sudah bayar) → ajukan refund, tunggu admin ──
        $request->validate([
            'nomor_pengembalian' => 'required|string',
            'alasan'             => 'required|string|min:10',
        ]);

        $refund = new OrderRefund();
        $refund->pesanan_id         = $pesanan->id;
        $refund->nomor_pengembalian = $request->nomor_pengembalian;
        $refund->alasan             = $request->alasan;
        $refund->status             = 'pending';
        $refund->save();

        return back()->with('success', 'Permintaan pembatalan berhasil dikirim! Admin akan memverifikasi dan menghubungi Anda.');
    }

    public function konfirmasiTerima($id)
    {
        $userId  = Auth::id();
        $pesanan = Pesanan::whereHas('pengunjung', fn($q) => $q->where('user_id', $userId))
            ->where('id', $id)
            ->firstOrFail();

        if ($pesanan->status !== 'send') {
            return back()->with('error', 'Pesanan ini tidak dapat dikonfirmasi penerimaan.');
        }

        // Pastikan sudah 3 hari sejak dikirim
        if (!$pesanan->sent_at || $pesanan->sent_at->diffInDays(now()) < 3) {
            return back()->with('error', 'Konfirmasi penerimaan baru bisa dilakukan setelah 3 hari sejak paket dikirim.');
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($pesanan) {
            $pesanan->update(['status' => 'success']);
            $pesanan->generateCommission();

            return back()->with('success', 'Terima kasih! Pesanan #' . $pesanan->nomor_pesanan . ' telah dikonfirmasi diterima.');
        });
    }
}
