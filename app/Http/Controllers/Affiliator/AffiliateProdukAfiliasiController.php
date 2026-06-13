<?php
namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;

use App\Models\{Produk, AffiliateProduk, Affiliator};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateProdukAfiliasiController extends Controller {
    public function index() {
        $affiliator = Auth::user()->affiliator;
        
        // Eager loading untuk performa (Clean Code)
        $produks = Produk::with(['fotos', 'komisis', 'variants'])->get();
        $selectedProducts = AffiliateProduk::where('affiliator_id', $affiliator->id)
                            ->with('produk.komisis')->get();

        return view('affiliate.produk-affiliasi', compact('produks', 'selectedProducts', 'affiliator'));
    }

    public function store(Request $request) {
        $request->validate(['produk_id' => 'required|exists:produks,id']);
        $affiliatorId = Auth::user()->affiliator->id;

        // Validasi: Tidak boleh pilih produk yang sama 2x
        $exists = AffiliateProduk::where('affiliator_id', $affiliatorId)
                                 ->where('produk_id', $request->produk_id)->exists();
        
        if ($exists) return back()->with('error', 'Produk ini sudah ada di daftar promosi Anda.');

        AffiliateProduk::create([
            'affiliator_id' => $affiliatorId,
            'produk_id' => $request->produk_id
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan ke daftar promosi!');
    }

    public function destroy($id) {
        AffiliateProduk::where('id', $id)
            ->where('affiliator_id', Auth::user()->affiliator->id)->delete();
        return back()->with('success', 'Produk dihapus dari daftar promosi.');
    }
}
