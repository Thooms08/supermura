<?php

namespace App\Http\Controllers;

use App\Models\{Pesanan, KomisiAffiliatorRecord};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    /**
     * Endpoint webhook Mayar.id
     * POST /mayar/callback
     *
     * Header yang dikirim Mayar:
     *   X-Mayar-Token: <webhook_token>
     *
     * Payload JSON Mayar (contoh):
     * {
     *   "status": "paid",          // atau "success"
     *   "extraData": "{\"order_id\":\"SPR-xxx\"}"
     * }
     */
    public function handleMayarCallback(Request $request)
    {
        // ── 1. Verifikasi keamanan via header X-Mayar-Token ──
        //    Event 'testing' dari dashboard Mayar tidak menyertakan token,
        //    jadi kita skip validasi untuk event tersebut.
        $event         = $request->input('event', '');
        $isTesting     = strtolower($event) === 'testing';

        if (!$isTesting) {
            $incomingToken = $request->header('X-Mayar-Token');
            $expectedToken = config('services.mayar.webhook_token');

            if (empty($incomingToken) || $incomingToken !== $expectedToken) {
                Log::warning('Mayar Webhook — Token tidak valid', [
                    'ip'             => $request->ip(),
                    'received_token' => $incomingToken,
                ]);
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }

        // ── 2. Ambil payload ──
        // Mayar membungkus data di dalam key "data": { ... }
        $payload = $request->all();
        Log::info('Mayar Webhook diterima', $payload);

        $data = $payload['data'] ?? $payload; // fallback ke root jika tidak ada "data"

        // Jika ini hanya event pengujian dari dashboard, langsung return 200
        if ($isTesting) {
            Log::info('Mayar Webhook — Event testing diterima, skip proses pesanan.');
            return response()->json(['message' => 'Webhook test berhasil diterima'], 200);
        }

        // Status bisa ada di root atau di dalam "data"
        $status   = strtolower($data['status'] ?? $payload['status'] ?? '');

        // extraData sekarang berupa array/object (bukan JSON string)
        // tapi tetap handle fallback jika masih string untuk backward compat
        $extraRaw  = $data['extraData'] ?? $payload['extraData'] ?? null;
        $extraData = is_string($extraRaw) ? json_decode($extraRaw, true) : (array) $extraRaw;
        $orderId   = $extraData['order_id'] ?? null;

        if (!$orderId) {
            Log::error('Mayar Webhook — order_id tidak ditemukan di extraData', $payload);
            return response()->json(['message' => 'order_id missing'], 400);
        }

        // ── 3. Cari pesanan ──
        $pesanan = Pesanan::where('nomor_pesanan', $orderId)->first();

        if (!$pesanan) {
            Log::error("Mayar Webhook — Pesanan {$orderId} tidak ditemukan");
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // ── 4. Update status berdasarkan status Mayar ──
        if (in_array($status, ['paid', 'success', 'settlement', 'succeeded'])) {

            // Hindari proses ganda jika webhook dikirim ulang
            if ($pesanan->status === 'success') {
                return response()->json(['message' => 'Already processed'], 200);
            }

            $pesanan->update(['status' => 'success']);
            Log::info("Mayar Webhook — Pesanan {$orderId} berhasil dibayar.");

            // Catat komisi affiliator jika pesanan berasal dari referral
            $this->recordKomisiAffiliator($pesanan);

        } elseif (in_array($status, ['expired', 'failed', 'cancelled', 'failure'])) {

            $pesanan->update(['status' => 'fail']);
            Log::info("Mayar Webhook — Pesanan {$orderId} gagal/kadaluwarsa (status: {$status}).");
        }

        // ── 5. Selalu kembalikan 200 agar Mayar tidak retry ──
        return response()->json(['message' => 'Webhook berhasil diproses'], 200);
    }

    /**
     * Catat komisi ke tabel komisi_affiliators_record
     * jika pesanan ini berasal dari referral affiliator.
     */
    private function recordKomisiAffiliator(Pesanan $pesanan): void
    {
        if (!$pesanan->affiliator_id) return;

        $pesanan->load('items.produk');

        foreach ($pesanan->items as $item) {
            // Ambil nominal komisi dari pengaturan admin (tabel komisi_affiliators)
            $komisi = \App\Models\KomisiAffiliator::where('produk_id', $item->produk_id)
                ->where(function ($q) use ($item) {
                    $q->where('produk_variant_id', $item->produk_variant_id)
                      ->orWhereNull('produk_variant_id');
                })
                ->orderByRaw('produk_variant_id IS NULL ASC') // prioritaskan komisi per-varian
                ->first();

            if (!$komisi || $komisi->nominal_komisi <= 0) continue;

            // Hindari duplikasi record
            $alreadyRecorded = KomisiAffiliatorRecord::where('affiliator_id', $pesanan->affiliator_id)
                ->where('pesanan_id', $pesanan->id)
                ->where('produk_id', $item->produk_id)
                ->exists();

            if ($alreadyRecorded) continue;

            KomisiAffiliatorRecord::create([
                'affiliator_id'    => $pesanan->affiliator_id,
                'pesanan_id'       => $pesanan->id,
                'produk_id'        => $item->produk_id,
                'produk_variant_id'=> $item->produk_variant_id,
                'nominal_komisi'   => $komisi->nominal_komisi,
                'status'           => 'berhasil',
            ]);

            Log::info("Komisi dicatat: affiliator_id={$pesanan->affiliator_id}, pesanan={$pesanan->nomor_pesanan}, produk_id={$item->produk_id}, nominal={$komisi->nominal_komisi}");
        }
    }
}
