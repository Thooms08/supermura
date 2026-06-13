<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default filter hari ini
        $start = $request->get('start', date('Y-m-d'));
        $end = $request->get('end', date('Y-m-d'));

        // Query Dasar
        $query = Pesanan::whereBetween('created_at', [
            $start . ' 00:00:00', 
            $end . ' 23:59:59'
        ]);

        // Summary
        $omzet = (clone $query)->where('status', 'success')->sum('total_harga');
        $totalPenjualan = (clone $query)->where('status', 'success')->count();

        // Data Chart (Group by Date)
        $chartData = Pesanan::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN status = "success" THEN total_harga ELSE 0 END) as sales'),
                DB::raw('COUNT(CASE WHEN status = "refunded" THEN 1 END) as refunds')
            )
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return view('admin.laporan', compact('omzet', 'totalPenjualan', 'start', 'end', 'chartData'));
    }

    public function penjualan(Request $request)
    {
        $start = $request->get('start', date('Y-m-d'));
        $end = $request->get('end', date('Y-m-d'));
        $limit = $request->has('all') ? 1000 : 30;

        $data = Pesanan::with(['pengunjung', 'items.produk', 'shippingMethod'])
            ->where('status', 'success')
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->latest()
            ->limit($limit)
            ->get();

        return view('admin.laporan-penjualan', compact('data', 'start', 'end'));
    }

    public function refund(Request $request)
    {
        $start = $request->get('start', date('Y-m-d'));
        $end = $request->get('end', date('Y-m-d'));
        $limit = $request->has('all') ? 1000 : 30;

        $data = Pesanan::with(['pengunjung', 'items.produk', 'shippingMethod', 'refund'])
            ->where('status', 'refunded')
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->latest()
            ->limit($limit)
            ->get();

        return view('admin.laporan-refund', compact('data', 'start', 'end'));
    }
}

