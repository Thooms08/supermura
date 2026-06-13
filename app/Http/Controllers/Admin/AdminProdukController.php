<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\{Produk, Kategori, ProdukFoto, ProdukVariant, Toko};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, File, Storage};

class AdminProdukController extends Controller {
    
    public function index(Request $request) {
        $selectedTokoUuid = $request->query('toko_uuid');
        $tokos = Toko::all();
        $kategoris = Kategori::all();

        // Resolve toko_id dari uuid untuk query produk
        $selectedTokoId = null;
        if ($selectedTokoUuid) {
            $toko = Toko::where('uuid', $selectedTokoUuid)->first();
            $selectedTokoId = $toko?->id;
        }

        $produks = Produk::with(['kategori', 'fotos', 'variants', 'toko'])
            ->when($selectedTokoId, function($query) use ($selectedTokoId) {
                return $query->where('toko_id', $selectedTokoId);
            })
            ->latest()->get();

        return view('admin.produk', compact('produks', 'kategoris', 'tokos', 'selectedTokoUuid'));
    }

    public function store(Request $request) {
        $request->validate([
            'toko_uuid'   => 'required|exists:tokos,uuid',
            'nama_produk' => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'jenis'       => 'required|string',
            'fotos'       => 'required|array|max:50',
            'fotos.*'     => 'image|mimes:jpeg,png,jpg|max:2048',
            'variants'    => 'required|array',
        ]);

        $toko = Toko::where('uuid', $request->toko_uuid)->firstOrFail();

        DB::transaction(function () use ($request, $toko) {
            $kategori = Kategori::firstOrCreate(['nama_jenis' => $request->jenis]);

            $produk = Produk::create([
                'toko_id'     => $toko->id,
                'kategori_id' => $kategori->id,
                'nama_produk' => $request->nama_produk,
                'harga'       => $request->harga,
                'deskripsi'   => $request->deskripsi,
            ]);

            // Generate slug setelah id tersedia (id dibutuhkan untuk hash unik)
            $produk->slug = Produk::generateSlug($produk->nama_produk, $produk->id);
            $produk->save();

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
                    Storage::disk('public')->putFileAs('produk', $file, $filename);
                    $produk->fotos()->create(['path_foto' => $filename]);
                }
            }
        });

        return redirect()->route('produk.index', ['toko_uuid' => $request->toko_uuid])->with('success', 'Produk berhasil disimpan!');
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
                        Storage::disk('public')->delete('produk/' . $foto->path_foto);
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
                    Storage::disk('public')->putFileAs('produk', $file, $filename);
                    $produk->fotos()->create(['path_foto' => $filename]);
                }
            }
        });

        return back()->with('success', 'Data produk berhasil diperbarui!');
    }

    public function destroy(Produk $produk) {
        foreach ($produk->fotos as $foto) {
            Storage::disk('public')->delete('produk/' . $foto->path_foto);
        }
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }

    public function destroyFoto(ProdukFoto $foto) {
        Storage::disk('public')->delete('produk/' . $foto->path_foto);
        $foto->delete();
        return back()->with('success', 'Foto berhasil dihapus!');
    }
}
