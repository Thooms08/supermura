<?php
namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;

use App\Models\{Produk, AffiliateProduk, Affiliator};
use App\Models\ProdukVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateProdukAfiliasiController extends Controller {
    public function index() {
        $affiliator = Auth::user()->affiliator;
        
        // Eager loading untuk performa (Clean Code)
        $produks = Produk::with(['fotos', 'komisis', 'variants'])->get();
        $selectedProducts = AffiliateProduk::where('affiliator_id', $affiliator->id)
                            ->with(['produk.komisis', 'variant'])->get();

        return view('affiliate.produk-affiliasi', compact('produks', 'selectedProducts', 'affiliator'));
    }

    public function store(Request $request) {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'variant_id' => 'nullable|exists:produk_variants,id',
        ]);

        $affiliatorId = Auth::user()->affiliator->id;
        $produk = Produk::with('variants')->findOrFail($request->produk_id);
        $variantId = $request->variant_id;

        if ($produk->variants->isNotEmpty()) {
            if (!$variantId) {
                return back()->with('error', 'Pilih varian produk yang ingin dipromosikan.');
            }

            $variant = ProdukVariant::where('id', $variantId)
                ->where('produk_id', $produk->id)
                ->first();

            if (!$variant) {
                return back()->with('error', 'Varian yang dipilih tidak valid untuk produk ini.');
            }
        } else {
            $variantId = null;
        }

        // Validasi: Tidak boleh pilih kombinasi produk + varian yang sama 2x
        $exists = AffiliateProduk::where('affiliator_id', $affiliatorId)
                                 ->where('produk_id', $request->produk_id)
                                 ->where('produk_variant_id', $variantId)
                                 ->exists();
        
        if ($exists) return back()->with('error', 'Produk atau varian ini sudah ada di daftar promosi Anda.');

        AffiliateProduk::create([
            'affiliator_id' => $affiliatorId,
            'produk_id' => $request->produk_id,
            'produk_variant_id' => $variantId,
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan ke daftar promosi!');
    }

    public function destroy($id) {
        AffiliateProduk::where('id', $id)
            ->where('affiliator_id', Auth::user()->affiliator->id)->delete();
        return back()->with('success', 'Produk dihapus dari daftar promosi.');
    }
}
