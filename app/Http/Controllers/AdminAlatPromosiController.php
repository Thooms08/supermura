<?php

namespace App\Http\Controllers;

use App\Models\AlatPromosi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminAlatPromosiController extends Controller {
    
    public function index() {
        $promosi = AlatPromosi::latest()->get();
        return view('admin.alat-promosi', compact('promosi'));
    }

    public function store(Request $request) {
        // Validasi dasar
        $request->validate([
            'poster'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'caption' => 'nullable|string',
        ]);

        // Validasi Logika: Minimal salah satu harus diisi
        if (!$request->hasFile('poster') && !$request->filled('caption')) {
            return back()->with('error', 'Gagal! Harap isi minimal salah satu (Poster atau Caption).');
        }

        $data = new AlatPromosi();
        
        // Simpan Poster jika ada
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('asset/alat-promosi'), $filename);
            $data->poster = $filename;
        }

        // Simpan Caption jika ada
        if ($request->filled('caption')) {
            $data->caption = $request->caption;
        }

        $data->save();

        return back()->with('success', 'Alat promosi berhasil ditambahkan!');
    }

    public function destroy($id) {
        $data = AlatPromosi::findOrFail($id);

        // Hapus file fisik jika ada
        if ($data->poster) {
            $path = public_path('asset/alat-promosi/' . $data->poster);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $data->delete();

        return back()->with('success', 'Alat promosi berhasil dihapus.');
    }
}