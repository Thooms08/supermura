<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class AdminOrderSuccessController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['pengunjung', 'items.produk.fotos', 'shippingMethod'])
            ->where('status', 'success');

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

        $orders = $query->latest()->get();

        // Jika request datang dari AJAX, kembalikan hanya bagian tabel saja
        if ($request->ajax()) {
            return view('admin.partials.order-success-table', compact('orders'))->render();
        }

        return view('admin.order-success', compact('orders'));
    }
}

