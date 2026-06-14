<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Affiliator;
use App\Models\PengajuanKomisi;
use App\Models\Ulasan;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // === STATISTIK PESANAN ===
        $totalPesanan      = Pesanan::count();
        $pesananPending    = Pesanan::where('status', 'pending')->count();
        $pesananProses     = Pesanan::where('status', 'process')->count();
        $pesananSukses     = Pesanan::where('status', 'success')->count();

        // === PENDAPATAN ===
        $pendapatanTotal   = Pesanan::where('status', 'success')->sum('total_harga');
        $pendapatanBulanIni = Pesanan::where('status', 'success')
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->sum('total_harga');

        // === PRODUK & AFFILIATOR ===
        $totalProduk       = Produk::count();
        $totalAffiliator   = Affiliator::count();
        $affiliatorAktif   = Affiliator::where('status', 'aktif')->count();

        // === PENGAJUAN KOMISI ===
        $pengajuanPending  = PengajuanKomisi::where('status', 'pending')->count();

        // === PESANAN TERBARU (5 terakhir) ===
        $pesananTerbaru    = Pesanan::with('pengunjung')
                                ->latest()
                                ->take(5)
                                ->get();

        // === GRAFIK PENJUALAN 6 BULAN TERAKHIR ===
        $grafikData = collect(range(5, 0))->map(function ($i) {
            $date  = now()->subMonths($i);
            $total = Pesanan::where('status', 'success')
                        ->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->sum('total_harga');
            return [
                'bulan' => $date->translatedFormat('M'),
                'total' => (int) $total,
            ];
        });

        return view('admin.index', compact(
            'totalPesanan', 'pesananPending', 'pesananProses', 'pesananSukses',
            'pendapatanTotal', 'pendapatanBulanIni',
            'totalProduk', 'totalAffiliator', 'affiliatorAktif',
            'pengajuanPending', 'pesananTerbaru', 'grafikData'
        ));
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::id();
        $user   = User::findOrFail($userId);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}

