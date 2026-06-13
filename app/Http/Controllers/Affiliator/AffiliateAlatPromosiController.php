<?php

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;

use App\Models\AlatPromosi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AffiliateAlatPromosiController extends Controller
{
    /**
     * Menampilkan daftar alat promosi untuk affiliator
     */
    public function index()
    {
        $promosi = AlatPromosi::latest()->get();
        return view('affiliate.alat-promosi', compact('promosi'));
    }

    /**
     * Fungsi untuk mendownload poster
     */
    public function download($filename)
    {
        $path = public_path('asset/alat-promosi/' . $filename);

        if (File::exists($path)) {
            return response()->download($path);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }
}

