<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string',
            'foto.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil nama dari profil pengunjung
        $profil = Pengunjung::where('user_id', Auth::id())->first();
        $nama_pengulas = $profil ? $profil->nama_lengkap : Auth::user()->name;

        $fotoPaths = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if (count($fotoPaths) < 5) {
                    $name = time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    Storage::disk('public')->putFileAs('ulasan', $file, $name);
                    $fotoPaths[] = $name;
                }
            }
        }

        Ulasan::create([
            'user_id'   => Auth::id(), // PENTING: Harus disimpan agar bisa di-edit/hapus nanti
            'produk_id' => $request->produk_id,
            'nama'      => $nama_pengulas,
            'rating'    => $request->rating,
            'komentar'  => $request->komentar,
            'foto'      => $fotoPaths,
        ]);

        return response()->json(['message' => 'Ulasan berhasil dikirim!']);
    }

    public function update(Request $request, $id)
    {
        // Menggunakan Auth::id() untuk menghindari error "Undefined method id"
        $ulasan = Ulasan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string',
        ]);

        $ulasan->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return response()->json(['message' => 'Ulasan berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        // Menggunakan Auth::id() lebih aman dan preventif terhadap error helper
        $ulasan = Ulasan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        // Hapus file foto dari server jika ada
        if ($ulasan->foto) {
            foreach ($ulasan->foto as $path) {
                Storage::disk('public')->delete('ulasan/' . $path);
            }
        }

        $ulasan->delete();
        return response()->json(['message' => 'Ulasan berhasil dihapus!']);
    }
}