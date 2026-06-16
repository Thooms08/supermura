<?php

namespace App\Http\Controllers\pengunjung;

use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\Produk;
use App\Models\ProdukVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['produk.fotos', 'variant'])
            ->get();

        return view('pengunjung.keranjang', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id'  => 'required|exists:produks,id',
            'qty'        => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:produk_variants,id',
        ]);

        $produk    = Produk::findOrFail($request->produk_id);
        $variantId = $request->variant_id;

        if ((int) session('referrer_product_id') === (int) $produk->id && session()->has('referrer_variant_id')) {
            $variantId = (int) session('referrer_variant_id');
        }

        // Validasi stok
        if ($variantId) {
            $variant = ProdukVariant::where('id', $variantId)
                ->where('produk_id', $produk->id)
                ->firstOrFail();
            $stokTersedia = $variant->stok;
        } else {
            $stokTersedia = $produk->totalStok();
        }

        // Hitung qty yang sudah ada di keranjang
        $existing = Cart::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->where('variant_id', $variantId)
            ->first();

        $qtyDiKeranjang = $existing ? $existing->qty : 0;
        $qtyBaru        = $qtyDiKeranjang + $request->qty;

        if ($stokTersedia <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok produk ini habis.',
            ], 422);
        }

        if ($qtyBaru > $stokTersedia) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak mencukupi. Tersisa {$stokTersedia}, di keranjang sudah {$qtyDiKeranjang}.",
            ], 422);
        }

        if ($existing) {
            $existing->increment('qty', $request->qty);
        } else {
            Cart::create([
                'user_id'    => Auth::id(),
                'produk_id'  => $request->produk_id,
                'variant_id' => $variantId,
                'qty'        => $request->qty,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
        ]);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())
            ->with('variant', 'produk')
            ->firstOrFail();

        $action = $request->action;

        if ($action === 'increase') {
            // Cek stok sebelum nambah
            $stok = $cart->variant ? $cart->variant->stok : $cart->produk->totalStok();
            if ($cart->qty >= $stok) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok maksimal {$stok}.",
                    'new_qty' => $cart->qty,
                ], 422);
            }
            $cart->increment('qty');
        } elseif ($action === 'decrease') {
            if ($cart->qty <= 1) {
                // Qty sudah 1 — hapus item
                $cart->delete();
                return response()->json([
                    'success'  => true,
                    'deleted'  => true,
                    'new_qty'  => 0,
                ]);
            }
            $cart->decrement('qty');
        }

        $cart->refresh();

        // Hitung harga satuan yang berlaku
        $harga = $cart->variant
            ? ($cart->variant->harga_variant ?? $cart->produk->harga)
            : $cart->produk->harga;

        return response()->json([
            'success'  => true,
            'deleted'  => false,
            'new_qty'  => $cart->qty,
            'subtotal' => $cart->qty * $harga,
        ]);
    }

    public function destroy($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function processToCheckout(Request $request)
    {
        $selectedIds = $request->input('selected_items', []);

        if (empty($selectedIds)) {
            return back()->with('error', 'Pilih minimal satu produk untuk di-checkout.');
        }

        // Verifikasi item memang milik user yang login
        $validIds = Cart::where('user_id', Auth::id())
            ->whereIn('id', $selectedIds)
            ->pluck('id')
            ->toArray();

        if (empty($validIds)) {
            return back()->with('error', 'Item tidak valid.');
        }

        session(['checkout_items' => $validIds]);

        return redirect()->route('checkout');
    }
}

