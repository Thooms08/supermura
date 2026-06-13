<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\{Produk, KomisiAffiliator};
use Illuminate\Http\Request;

class AdminKomisiController extends Controller {
    // Menampilkan halaman utama pengaturan komisi
    public function index() {
    // Eager load fotos, variants, dan komisis
    $produks = Produk::with(['variants', 'fotos', 'komisis'])->get();
    
    // Ambil hanya produk yang sudah punya komisi untuk tabel bawah
    $komisiAktif = Produk::has('komisis')->with(['variants', 'fotos', 'komisis'])->get();

    return view('admin.atur-komisi', compact('produks', 'komisiAktif'));
}

// Tambahkan method destroy untuk menghapus komisi
public function destroy($id) {
    \App\Models\KomisiAffiliator::where('produk_id', $id)->delete();
    return back()->with('success', 'Seluruh komisi pada produk tersebut berhasil dihapus.');
}

    // Menyimpan atau memperbarui data komisi
    public function store(Request $request) {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'komisi'    => 'required|array',
            'komisi.*'  => 'required|numeric|min:0',
        ]);

        $produkId = $request->produk_id;

        foreach ($request->komisi as $key => $nominal) {
            // Jika key adalah 'single', berarti produk satuan
            // Jika angka, berarti itu ID Variant
            $variantId = ($key === 'single') ? null : $key;

            KomisiAffiliator::updateOrCreate(
                ['produk_id' => $produkId, 'produk_variant_id' => $variantId],
                ['nominal_komisi' => $nominal]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan komisi berhasil disimpan!');
    }
}

