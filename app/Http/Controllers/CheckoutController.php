<?php

namespace App\Http\Controllers;

use App\Models\{Pesanan, PesananItem, Produk, ShippingMethod, Pengunjung, Affiliator};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class CheckoutController extends Controller
{
    public function __construct() {
        // Inisiasi API Key Xendit
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();
    
        // Logika mengambil alamat yang dipilih atau alamat pertama
        $selectedId = session('selected_alamat_id');
        if ($selectedId) {
            $profil = Pengunjung::where('user_id', $user->id)->where('id', $selectedId)->first();
        } else {
            $profil = Pengunjung::where('user_id', $user->id)->first();
        }
        
        $checkoutData = session('checkout_item');
        if (!$checkoutData) return redirect('/')->with('error', 'Tidak ada produk untuk di-checkout');

        $produk = Produk::with('fotos')->find($checkoutData['produk_id']);
        $shippingMethods = ShippingMethod::where('is_aktif', true)->get();

        return view('checkout', compact('user', 'profil', 'produk', 'checkoutData', 'shippingMethods'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required',
            'total_harga' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $checkoutData = session('checkout_item');
            
            if (!$checkoutData) {
                return response()->json(['message' => 'Sesi checkout kadaluwarsa'], 400);
            }

            $alamatId = session('selected_alamat_id');
            $pengunjung = $alamatId 
                ? Pengunjung::find($alamatId) 
                : Pengunjung::where('user_id', $user->id)->first();
            
            if (!$pengunjung) {
                return response()->json(['message' => 'Profil pengunjung tidak ditemukan'], 404);
            }

            // --- LOGIKA REFERRAL ---
            $affiliatorId = null;
            $refCode = $checkoutData['ref'] ?? session('referrer_id');

            if ($refCode) {
                $aff = Affiliator::where('id_unik', $refCode)->first();
                $affiliatorId = $aff ? $aff->id : null;
            }

            $nomorPesanan = 'SPR-' . time() . '-'.$user->id;

            // 1. Simpan Header Pesanan
            $pesanan = Pesanan::create([
                'pengunjung_id' => $pengunjung->id,
                'affiliator_id' => $affiliatorId, 
                'shipping_id'   => $request->shipping_method_id ?? null, 
                'nomor_pesanan' => $nomorPesanan,
                'total_harga'   => $request->total_harga,
                'metode_pengiriman' => $request->shipping_method,
                'status' => 'pending'
            ]);

            // 2. Simpan Item Pesanan
            $produk = Produk::find($checkoutData['produk_id']);
            PesananItem::create([
                'pesanan_id' => $pesanan->id,
                'produk_id' => $produk->id,
                'produk_variant_id' => $checkoutData['variant_id'] ?? null,
                'nama_produk' => $produk->nama_produk,
                'qty' => $checkoutData['qty'],
                'harga' => $checkoutData['harga'],
                'subtotal' => $checkoutData['qty'] * $checkoutData['harga']
            ]);

            // 3. Konfigurasi Xendit Invoice
            $apiInstance = new InvoiceApi();
            $create_invoice_request = new CreateInvoiceRequest([
                'external_id' => $nomorPesanan,
                'description' => 'Pesanan Flavory.id - ' . $nomorPesanan,
                'amount' => (int)$request->total_harga,
                'payer_email' => $user->email,
                'customer' => [
                    'given_names' => $user->name,
                    'email' => $user->email,
                    'mobile_number' => $pengunjung->no_whatsapp ?? '',
                ],
                'success_redirect_url' => route('checkout.success') . '?order_id=' . $nomorPesanan,
                'failure_redirect_url' => route('checkout'),
                'currency' => 'IDR'
            ]);

            try {
                $result = $apiInstance->createInvoice($create_invoice_request);
                
                // Gunakan kolom snap_token yang sudah ada di database kamu untuk menyimpan ID Invoice Xendit sementara
                $pesanan->update(['snap_token' => $result['id']]); 
                session(['last_order_id' => $nomorPesanan]); 
                
                // Kembalikan link invoice ke frontend
                return response()->json(['invoice_url' => $result['invoice_url']]);
            } catch (\Exception $e) {
                Log::error('Xendit Error: ' . $e->getMessage());
                return response()->json(['message' => 'Gagal terhubung ke penyedia pembayaran'], 500);
            }
        });
    }

    public function success(Request $request)
    {
        $orderId = $request->order_id ?? session('last_order_id'); 

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
        }

        $pesanan = Pesanan::with(['items', 'affiliator'])->where('nomor_pesanan', $orderId)->first();

        if (!$pesanan) {
            return "Pesanan dengan ID $orderId tidak ditemukan di database.";
        }

        session()->forget(['checkout_item', 'last_order_id']);

        return view('checkout.success', compact('pesanan'));
    }
}