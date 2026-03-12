<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class AdminOrderFailController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['pengunjung', 'items.produk.fotos', 'shippingMethod'])
            ->where('status', 'fail');

        // Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_pesanan', 'like', "%$search%")
                  ->orWhereHas('pengunjung', function($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%$search%");
                  });
            });
        }

        $orders = $query->latest()->paginate(10);

        // Jika request AJAX, kembalikan partial table
        if ($request->ajax()) {
            return view('admin.partials.order-fail-table', compact('orders'))->render();
        }

        return view('admin.order-fail', compact('orders'));
    }
}