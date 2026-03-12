<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Produk;
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
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:produk_variants,id'
        ]);

        // Cek jika item yang sama sudah ada di keranjang
        $existing = Cart::where('user_id', Auth::id())
            ->where('produk_id', $request->produk_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existing) {
            $existing->increment('qty', $request->qty);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'produk_id' => $request->produk_id,
                'variant_id' => $request->variant_id,
                'qty' => $request->qty
            ]);
        }

        return redirect()->route('keranjang.index')->with('success', 'Berhasil ditambah ke keranjang');
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($request->action == 'increase') {
            $cart->increment('qty');
        } elseif ($request->action == 'decrease' && $cart->qty > 1) {
            $cart->decrement('qty');
        }

        return response()->json(['success' => true, 'new_qty' => $cart->qty]);
    }

    public function destroy($id)
    {
        Cart::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Item dihapus dari keranjang');
    }

    public function processToCheckout(Request $request)
    {
        $selectedIds = $request->input('selected_items', []);
        
        if (empty($selectedIds)) {
            return back()->with('error', 'Pilih minimal satu produk');
        }

        // Simpan ID yang dipilih ke session untuk dibaca di halaman checkout
        session(['checkout_items' => $selectedIds]);

        return redirect()->route('checkout');
    }
}