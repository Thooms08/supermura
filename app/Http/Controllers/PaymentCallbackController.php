<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan; // Memanggil model Pesanan
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handleXenditCallback(Request $request)
    {
        // 1. Ambil token dari file .env/config dan header request
        $xenditXCallbackToken = config('services.xendit.callback_token');
        $reqHeaders = $request->header('x-callback-token');

        // 2. Verifikasi Keamanan (Cegah request palsu)
        if ($reqHeaders !== $xenditXCallbackToken) {
            Log::warning('Xendit Webhook - Token Tidak Valid');
            return response()->json(['message' => 'Invalid Callback Token'], 403);
        }

        // 3. Ambil semua data yang dikirim Xendit
        $data = $request->all();
        Log::info('Xendit Webhook Diterima: ', $data);

        // 4. Pastikan payload memiliki external_id (nomor pesanan kita) dan status
        if (isset($data['external_id']) && isset($data['status'])) {
            $externalId = $data['external_id'];
            $status = $data['status'];

            // Cari pesanan di database berdasarkan nomor_pesanan
            $pesanan = Pesanan::where('nomor_pesanan', $externalId)->first();

            if ($pesanan) {
                // 5. Update status pesanan di database
                if ($status === 'PAID' || $status === 'SETTLED') {
                    $pesanan->update([
                        'status' => 'success', // Atau sesuaikan dengan enum status di database kamu (misal: 'dibayar')
                    ]);
                    Log::info("Pesanan {$externalId} berhasil dibayar.");
                    
                } elseif ($status === 'EXPIRED') {
                    $pesanan->update([
                        'status' => 'failed', // Atau 'batal'/'expired'
                    ]);
                    Log::info("Pesanan {$externalId} kadaluwarsa karena tidak dibayar.");
                }
            } else {
                Log::error("Pesanan dengan ID {$externalId} tidak ditemukan di database.");
            }
        }

        // 6. Selalu kembalikan status 200 OK ke Xendit
        // Jika tidak, sistem Xendit akan mengira website kamu mati dan terus mencoba mengirim ulang data (retrying)
        return response()->json(['message' => 'Webhook berhasil diproses'], 200);
    }
}