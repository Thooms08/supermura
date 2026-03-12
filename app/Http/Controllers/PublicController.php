<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PublicController extends Controller
{
    public function index()
    {
        $produks = Produk::with(['fotos', 'toko', 'variants'])->latest()->get();
        return view('index', compact('produks'));
    }

    public function show(Request $request, Produk $produk)
    {
        // 1. Tangkap kode referral dari URL (?ref=xxx)
        if ($request->has('ref')) {
            session(['referrer_id' => $request->query('ref')]);
        }

        $produk->load(['fotos', 'variants', 'ulasans.user']);
        return view('page-product', compact('produk'));
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id', 
            'qty' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:produk_variants,id'
        ]);

        $produk = Produk::find($request->produk_id);
        $harga = $produk->harga;

        if ($request->variant_id) {
            $variant = ProdukVariant::find($request->variant_id);
            if ($variant && $variant->harga_variant) {
                $harga = $variant->harga_variant;
            }
        }

        // 2. Kunci data affiliator ke dalam session checkout
        Session::put('checkout_item', [
            'produk_id'  => $produk->id,
            'qty'        => $request->qty,
            'harga'      => $harga,
            'variant_id' => $request->variant_id,
            'ref'        => session('referrer_id'), // Dikunci di sini
        ]);

        return redirect()->route('checkout');
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $produks = Produk::with(['fotos', 'kategori', 'toko', 'variants'])
            ->where(function($q) use ($query) {
                $q->where('nama_produk', 'LIKE', "%{$query}%")
                  ->orWhere('harga', 'LIKE', "%{$query}%")
                  ->orWhereHas('variants', function($sq) use ($query) {
                      $sq->where('size', 'LIKE', "%{$query}%")
                        ->orWhere('model', 'LIKE', "%{$query}%");
                  });
            })->get();

        return $request->ajax() 
            ? view('partials.product-grid', compact('produks'))->render() 
            : view('index', compact('produks'));
    }
}