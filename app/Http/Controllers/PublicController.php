<?php

namespace App\Http\Controllers;

use App\Models\{Affiliator, Produk};
use App\Models\ProdukVariant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PublicController extends Controller
{
    public function index()
    {
        $produks = Produk::with(['fotos', 'toko', 'variants', 'komisis', 'ulasans'])->latest()->get();
        $komisiRekrut = (float) (Setting::where('key', 'komisi_rekrut')->value('value') ?? 0);
        return view('index', compact('produks', 'komisiRekrut'));
    }

    public function show(Request $request, Produk $produk)
    {
        // 1. Tangkap kode referral dari URL (?ref=xxx)
        if ($request->has('ref')) {
            session(['referrer_id' => $request->query('ref')]);
        }

        $lockedVariant = null;
        if ($request->filled('ref') && $request->filled('variant_id')) {
            $affiliator = Affiliator::where('id_unik', $request->query('ref'))->first();
            $variant = ProdukVariant::where('id', $request->query('variant_id'))
                ->where('produk_id', $produk->id)
                ->first();

            if ($affiliator && $variant) {
                session([
                    'referrer_id' => $affiliator->id_unik,
                    'referrer_product_id' => $produk->id,
                    'referrer_variant_id' => $variant->id,
                ]);
                $lockedVariant = $variant;
            }
        }

        $produk->load(['fotos', 'variants', 'ulasans.user']);
        return view('page-product', compact('produk', 'lockedVariant'));
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
        $variantId = $request->variant_id;

        if ((int) session('referrer_product_id') === (int) $produk->id && session()->has('referrer_variant_id')) {
            $lockedVariant = ProdukVariant::where('id', session('referrer_variant_id'))
                ->where('produk_id', $produk->id)
                ->first();

            if (!$lockedVariant) {
                return back()->with('error', 'Varian promosi tidak valid untuk produk ini.');
            }

            $variantId = $lockedVariant->id;
        }

        if ($variantId) {
            $variant = ProdukVariant::where('id', $variantId)
                ->where('produk_id', $produk->id)
                ->first();

            if (!$variant) {
                return back()->with('error', 'Varian yang dipilih tidak valid untuk produk ini.');
            }

            if ($variant && $variant->harga_variant) {
                $harga = $variant->harga_variant;
            }
        }

        // 2. Kunci data affiliator ke dalam session checkout
        Session::put('checkout_item', [
            'produk_id'  => $produk->id,
            'qty'        => $request->qty,
            'harga'      => $harga,
            'variant_id' => $variantId,
            'ref'        => session('referrer_id'), // Dikunci di sini
            'ref_variant_id' => session('referrer_variant_id'),
        ]);

        return redirect()->route('checkout');
    }

    public function sitemap()
    {
        // Ambil semua produk yang aktif dengan slug dan waktu update terakhir
        $produks = Produk::select('slug', 'updated_at')->latest('updated_at')->get();

        $content = view('sitemap', compact('produks'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $produks = Produk::with(['fotos', 'kategori', 'toko', 'variants', 'komisis', 'ulasans'])
            ->where(function($q) use ($query) {
                $q->where('nama_produk', 'LIKE', "%{$query}%")
                  ->orWhere('harga', 'LIKE', "%{$query}%")
                  ->orWhereHas('variants', function($sq) use ($query) {
                      $sq->where('size', 'LIKE', "%{$query}%")
                        ->orWhere('model', 'LIKE', "%{$query}%");
                  });
            })->get();

        $komisiRekrut = (float) (Setting::where('key', 'komisi_rekrut')->value('value') ?? 0);

        return $request->ajax() 
            ? view('partials.product-grid-list', compact('produks', 'komisiRekrut'))->render() 
            : view('index', compact('produks', 'komisiRekrut'));
    }
}
