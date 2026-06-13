<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\AlatPromosi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
            Storage::disk('public')->putFileAs('alat-promosi', $file, $filename);
            $data->poster = $filename;
        }

        // Simpan Caption jika ada
        if ($request->filled('caption')) {
            $data->caption = $request->caption;
        }

        $data->save();

        return back()->with('success', 'Alat promosi berhasil ditambahkan!');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'poster'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'caption' => 'nullable|string',
        ]);

        $data = AlatPromosi::findOrFail($id);

        // Ganti poster jika ada file baru
        if ($request->hasFile('poster')) {
            // Hapus file lama
            if ($data->poster) {
                Storage::disk('public')->delete('alat-promosi/' . $data->poster);
            }
            $file     = $request->file('poster');
            $filename = time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->putFileAs('alat-promosi', $file, $filename);
            $data->poster = $filename;
        }

        // Hapus poster jika admin centang "hapus poster"
        if ($request->boolean('remove_poster') && $data->poster) {
            Storage::disk('public')->delete('alat-promosi/' . $data->poster);
            $data->poster = null;
        }

        $data->caption = $request->filled('caption') ? $request->caption : null;
        $data->save();

        return back()->with('success', 'Alat promosi berhasil diperbarui!');
    }

    public function destroy($id) {
        $data = AlatPromosi::findOrFail($id);

        if ($data->poster) {
            Storage::disk('public')->delete('alat-promosi/' . $data->poster);
        }

        $data->delete();
        return back()->with('success', 'Alat promosi berhasil dihapus.');
    }
}

