<?php

namespace App\Http\Controllers;

use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class AdminShippingController extends Controller
{
    public function index()
    {
        $methods = ShippingMethod::latest()->get();
        return view('admin.metode-pengiriman', compact('methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:255|unique:shipping_methods,nama_metode',
            'is_aktif'    => 'required|boolean',
        ]);

        ShippingMethod::create($request->all());

        return back()->with('success', 'Metode pengiriman berhasil ditambahkan!');
    }

    public function update(Request $request, ShippingMethod $shipping_method)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:255|unique:shipping_methods,nama_metode,' . $shipping_method->id,
            'is_aktif'    => 'required|boolean',
        ]);

        $shipping_method->update($request->all());

        return back()->with('success', 'Metode pengiriman berhasil diperbarui!');
    }

    public function destroy(ShippingMethod $shipping_method)
    {
        $shipping_method->delete();

        return back()->with('success', 'Metode pengiriman berhasil dihapus!');
    }
}