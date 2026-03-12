<?php

namespace App\Http\Controllers;

use App\Models\{Produk, Kategori, ProdukFoto, ProdukVariant, Toko};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, File};

class AdminProdukController extends Controller {
    
    public function index(Request $request) {
        $selectedTokoId = $request->query('toko_id');
        $tokos = Toko::all();
        $kategoris = Kategori::all();
        
        $produks = Produk::with(['kategori', 'fotos', 'variants', 'toko'])
            ->when($selectedTokoId, function($query) use ($selectedTokoId) {
                return $query->where('toko_id', $selectedTokoId);
            })
            ->latest()->get();

        return view('admin.produk', compact('produks', 'kategoris', 'tokos', 'selectedTokoId'));
    }

    public function store(Request $request) {
        $request->validate([
            'toko_id'     => 'required|exists:tokos,id',
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'jenis'       => 'required|string',
            'fotos'       => 'required|array|max:50',
            'fotos.*'     => 'image|mimes:jpeg,png,jpg|max:2048',
            'variants'    => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $kategori = Kategori::firstOrCreate(['nama_jenis' => $request->jenis]);

            $produk = Produk::create([
                'toko_id'     => $request->toko_id,
                'kategori_id' => $kategori->id,
                'nama_produk' => $request->nama_produk,
                'harga'       => $request->harga,
                'deskripsi'   => $request->deskripsi,
            ]);

            foreach ($request->variants as $v) {
                $produk->variants()->create([
                    'size'          => $v['size'],
                    'model'         => $v['model'],
                    'stok'          => $v['stok'],
                    'harga_variant' => $v['harga_variant'] ?? null,
                ]);
            }

            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('asset/produk'), $filename);
                    $produk->fotos()->create(['path_foto' => $filename]);
                }
            }
        });

        return redirect()->route('produk.index', ['toko_id' => $request->toko_id])->with('success', 'Produk berhasil disimpan!');
    }

    public function update(Request $request, Produk $produk) {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'variants'    => 'required|array',
            'deleted_photos' => 'nullable|array' // Tambahan untuk menangkap ID foto yang dihapus
        ]);

        DB::transaction(function () use ($request, $produk) {
            $produk->update([
                'nama_produk' => $request->nama_produk,
                'harga'       => $request->harga,
                'deskripsi'   => $request->deskripsi,
            ]);

            // --- LOGIKA HAPUS FOTO PERMANEN (Hanya dijalankan saat tombol Simpan diklik) ---
            if ($request->has('deleted_photos')) {
                foreach ($request->deleted_photos as $fotoId) {
                    $foto = ProdukFoto::find($fotoId);
                    if ($foto) {
                        $path = public_path('asset/produk/' . $foto->path_foto);
                        if (File::exists($path)) File::delete($path);
                        $foto->delete();
                    }
                }
            }

            // Logika Varian yang Cerdas
            $keptVariantIds = [];
            foreach ($request->variants as $v) {
                if (isset($v['id']) && !empty($v['id'])) {
                    $variant = ProdukVariant::find($v['id']);
                    if ($variant) {
                        $variant->update([
                            'size'          => $v['size'],
                            'model'         => $v['model'],
                            'stok'          => $v['stok'],
                            'harga_variant' => $v['harga_variant'] ?? null,
                        ]);
                        $keptVariantIds[] = $variant->id;
                    }
                } else {
                    $newVariant = $produk->variants()->create([
                        'size'          => $v['size'],
                        'model'         => $v['model'],
                        'stok'          => $v['stok'],
                        'harga_variant' => $v['harga_variant'] ?? null,
                    ]);
                    $keptVariantIds[] = $newVariant->id;
                }
            }

            try {
                $produk->variants()->whereNotIn('id', $keptVariantIds)->delete();
            } catch (\Exception $e) {}

            // Handle Foto Baru
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('asset/produk'), $filename);
                    $produk->fotos()->create(['path_foto' => $filename]);
                }
            }
        });

        return back()->with('success', 'Data produk berhasil diperbarui!');
    }

    public function destroy(Produk $produk) {
        foreach ($produk->fotos as $foto) {
            $path = public_path('asset/produk/' . $foto->path_foto);
            if (File::exists($path)) File::delete($path);
        }
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }

    public function destroyFoto(ProdukFoto $foto) {
        if (File::exists(public_path('asset/produk/' . $foto->path_foto))) {
            File::delete(public_path('asset/produk/' . $foto->path_foto));
        }
        $foto->delete();
        return back()->with('success', 'Foto berhasil dihapus!');
    }
}