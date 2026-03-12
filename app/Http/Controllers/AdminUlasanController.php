<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminUlasanController extends Controller
{
    public function index()
    {
        // Mengambil ulasan terbaru beserta data produk yang diulas
        $ulasans = Ulasan::with('produk')->latest()->get();
        return view('admin.ulasan', compact('ulasans'));
    }

    public function destroy($id)
    {
        $ulasan = Ulasan::findOrFail($id);

        // Hapus file foto dari folder public/asset/ulasan jika ada
        if ($ulasan->foto && is_array($ulasan->foto)) {
            foreach ($ulasan->foto as $path) {
                $fullPath = public_path($path);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }
        }

        $ulasan->delete();

        return back()->with('success', 'Ulasan berhasil dihapus secara permanen.');
    }
}