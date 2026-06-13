<?php

namespace App\Http\Controllers;

use App\Models\{Cart, Pesanan, PesananItem, Produk, ShippingMethod, Pengunjung, Affiliator};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Http, Log};

class CheckoutController extends Controller
{
    /**
     * Tentukan dari mana checkout berasal:
     * - 'cart'    → dari keranjang  (session 'checkout_items' = array cart ID)
     * - 'buy_now' → tombol Beli Sekarang (session 'checkout_item' = satu produk)
     */
    private function getCheckoutSource(): string
    {
        if (session()->has('checkout_items')) return 'cart';
        if (session()->has('checkout_item'))  return 'buy_now';
        return 'none';
    }

    public function index(Request $request)
    {
        $user   = Auth::user();
        $source = $this->getCheckoutSource();

        if ($source === 'none') {
            return redirect('/')->with('error', 'Tidak ada produk untuk di-checkout.');
        }

        $selectedId = session('selected_alamat_id');
        $profil = $selectedId
            ? Pengunjung::where('user_id', $user->id)->where('id', $selectedId)->first()
            : Pengunjung::where('user_id', $user->id)->first();

        $shippingMethods = ShippingMethod::where('is_aktif', true)->get();

        if ($source === 'cart') {
            $cartIds   = session('checkout_items');
            $cartItems = Cart::with(['produk.fotos', 'variant'])
                ->where('user_id', $user->id)
                ->whereIn('id', $cartIds)
                ->get();

            if ($cartItems->isEmpty()) {
                session()->forget('checkout_items');
                return redirect('/')->with('error', 'Item keranjang tidak valid.');
            }

            $totalHarga = $cartItems->sum(function ($item) {
                $harga = $item->variant
                    ? ($item->variant->harga_variant ?? $item->produk->harga)
                    : $item->produk->harga;
                return $harga * $item->qty;
            });

            return view('checkout', [
                'user'            => $user,
                'profil'          => $profil,
                'shippingMethods' => $shippingMethods,
                'source'          => 'cart',
                'cartItems'       => $cartItems,
                'totalHarga'      => $totalHarga,
                'checkoutData'    => null,
                'produk'          => null,
            ]);
        }

        // Buy Now
        $checkoutData = session('checkout_item');
        $produk       = Produk::with('fotos')->find($checkoutData['produk_id']);

        return view('checkout', [
            'user'            => $user,
            'profil'          => $profil,
            'shippingMethods' => $shippingMethods,
            'source'          => 'buy_now',
            'cartItems'       => collect(),
            'totalHarga'      => 0,
            'checkoutData'    => $checkoutData,
            'produk'          => $produk,
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_method'    => 'required',
            'total_harga'        => 'required|numeric',
            'shipping_method_id' => 'nullable|exists:shipping_methods,id',
        ]);

        $source = $request->input('source', 'buy_now');

        return DB::transaction(function () use ($request, $source) {
            $user = Auth::user();

            $selectedId = session('selected_alamat_id');
            $pengunjung = $selectedId
                ? Pengunjung::find($selectedId)
                : Pengunjung::where('user_id', $user->id)->first();

            if (!$pengunjung) {
                return response()->json([
                    'message' => 'Profil pengunjung tidak ditemukan. Lengkapi profil terlebih dahulu.',
                ], 404);
            }

            // Referral
            $affiliatorId = null;
            $refCode      = session('referrer_id');
            if ($refCode) {
                $aff          = Affiliator::where('id_unik', $refCode)->first();
                $affiliatorId = $aff?->id;
            }

            $nomorPesanan = 'SPR-' . time() . '-' . $user->id;

            $pesanan = Pesanan::create([
                'pengunjung_id'     => $pengunjung->id,
                'affiliator_id'     => $affiliatorId,
                'shipping_id'       => $request->shipping_method_id,
                'nomor_pesanan'     => $nomorPesanan,
                'total_harga'       => $request->total_harga,
                'metode_pengiriman' => $request->shipping_method,
                'metode_pembayaran' => 'mayar',
                'status'            => 'pending',
            ]);

            $invoiceItems = [];

            if ($source === 'cart') {
                $cartIds   = session('checkout_items', []);
                $cartItems = Cart::with(['produk', 'variant'])
                    ->where('user_id', $user->id)
                    ->whereIn('id', $cartIds)
                    ->get();

                foreach ($cartItems as $item) {
                    $harga = $item->variant
                        ? ($item->variant->harga_variant ?? $item->produk->harga)
                        : $item->produk->harga;

                    PesananItem::create([
                        'pesanan_id'        => $pesanan->id,
                        'produk_id'         => $item->produk_id,
                        'produk_variant_id' => $item->variant_id,
                        'nama_produk'       => $item->produk->nama_produk,
                        'qty'               => $item->qty,
                        'harga'             => $harga,
                        'subtotal'          => $item->qty * $harga,
                    ]);

                    $invoiceItems[] = [
                        'quantity'    => (int) $item->qty,
                        'rate'        => (int) $harga,
                        'description' => $item->produk->nama_produk,
                    ];
                }

                Cart::where('user_id', $user->id)->whereIn('id', $cartIds)->delete();
                session()->forget('checkout_items');

            } else {
                $checkoutData = session('checkout_item');
                if (!$checkoutData) {
                    return response()->json(['message' => 'Sesi checkout kadaluwarsa.'], 400);
                }

                $produk = Produk::find($checkoutData['produk_id']);

                PesananItem::create([
                    'pesanan_id'        => $pesanan->id,
                    'produk_id'         => $produk->id,
                    'produk_variant_id' => $checkoutData['variant_id'] ?? null,
                    'nama_produk'       => $produk->nama_produk,
                    'qty'               => $checkoutData['qty'],
                    'harga'             => $checkoutData['harga'],
                    'subtotal'          => $checkoutData['qty'] * $checkoutData['harga'],
                ]);

                $invoiceItems[] = [
                    'quantity'    => (int) $checkoutData['qty'],
                    'rate'        => (int) $checkoutData['harga'],
                    'description' => $produk->nama_produk,
                ];

                session()->forget('checkout_item');
            }

            // Buat invoice Mayar
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.mayar.api_key'),
                    'Content-Type'  => 'application/json',
                ])->post(config('services.mayar.base_url') . '/invoice/create', [
                    'name'        => $user->name,
                    'email'       => $user->email,
                    // Mayar minimal 10 karakter untuk mobile, isi default jika kosong/pendek
                    'mobile'      => $this->normalizeMobile($pengunjung->no_whatsapp ?? ''),
                    'description' => 'Pesanan SUPERMURA.ID - ' . $nomorPesanan,
                    'items'       => $invoiceItems,
                    // extraData HARUS berupa Object (array), bukan JSON string
                    'extraData'   => ['order_id' => $nomorPesanan],
                    'redirectUrl' => route('checkout.success') . '?order_id=' . $nomorPesanan,
                ]);

                if (!$response->successful()) {
                    Log::error('Mayar API Error', [
                        'status'   => $response->status(),
                        'body'     => $response->body(),
                        'order_id' => $nomorPesanan,
                    ]);
                    return response()->json([
                        'message' => 'Gagal terhubung ke penyedia pembayaran. Silakan coba lagi.',
                    ], 500);
                }

                $paymentLink = $response->json('data.link');

                if (empty($paymentLink)) {
                    Log::error('Mayar: data.link kosong', ['response' => $response->json()]);
                    return response()->json(['message' => 'Respons pembayaran tidak valid.'], 500);
                }

                $pesanan->update(['snap_token' => $paymentLink]);
                session(['last_order_id' => $nomorPesanan]);

                return response()->json(['invoice_url' => $paymentLink]);

            } catch (\Exception $e) {
                Log::error('Mayar Exception: ' . $e->getMessage());
                return response()->json(['message' => 'Terjadi kesalahan internal.'], 500);
            }
        });
    }

    public function success(Request $request)
    {
        $orderId = $request->order_id ?? session('last_order_id');

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
        }

        $pesanan = Pesanan::with(['items', 'affiliator'])
            ->where('nomor_pesanan', $orderId)
            ->first();

        if (!$pesanan) {
            return redirect()->route('home')->with('error', "Pesanan {$orderId} tidak ditemukan.");
        }

        session()->forget(['checkout_item', 'checkout_items', 'last_order_id']);

        return view('checkout.success', compact('pesanan'));
    }

    /**
     * Pastikan nomor mobile minimal 10 karakter (requirement Mayar).
     * Format: hilangkan karakter non-digit, prefix 62 jika diawali 0.
     */
    private function normalizeMobile(string $mobile): string
    {
        // Hapus semua non-digit
        $digits = preg_replace('/\D/', '', $mobile);

        // Ganti awalan 0 → 62
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        // Jika masih kosong atau terlalu pendek, pakai placeholder valid
        if (strlen($digits) < 10) {
            $digits = '6200000000000';
        }

        return $digits;
    }
}
