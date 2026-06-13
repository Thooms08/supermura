<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminTokoController extends Controller
{
    public function index()
    {
        $tokos = Toko::latest()->get();
        return view('admin.profile-toko', compact('tokos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko'   => 'required|string|max:255',
            'email'       => 'required|email',
            'no_whatsapp' => 'required', // numeric dihapus jika ingin mendukung format +62
            'foto_toko'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alamat'      => 'nullable|string',
            'deskripsi'   => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_toko')) {
            $image = $request->file('foto_toko');
            $name = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('profile-toko', $image, $name);
            $data['foto_toko'] = $name;
        }

        Toko::create($data);
        return redirect()->route('profile-toko.index')->with('success', 'Toko berhasil ditambahkan!');
    }

    public function update(Request $request, Toko $profile_toko)
    {
        $request->validate([
            'nama_toko'   => 'required|string|max:255',
            'email'       => 'required|email',
            'no_whatsapp' => 'required',
            'foto_toko'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'alamat'      => 'nullable|string',
            'deskripsi'   => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_toko')) {
            // Hapus foto lama jika ada
            if ($profile_toko->foto_toko) {
                Storage::disk('public')->delete('profile-toko/' . $profile_toko->foto_toko);
            }

            $image = $request->file('foto_toko');
            $name = time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('profile-toko', $image, $name);
            $data['foto_toko'] = $name;
        } else {
            $data['foto_toko'] = $profile_toko->foto_toko;
        }

        $profile_toko->update($data);
        return redirect()->route('profile-toko.index')->with('success', 'Toko berhasil diperbarui!');
    }

    public function destroy(Toko $profile_toko)
    {
        if ($profile_toko->foto_toko) {
            Storage::disk('public')->delete('profile-toko/' . $profile_toko->foto_toko);
        }

        $profile_toko->delete();
        return back()->with('success', 'Toko berhasil dihapus!');
    }
}

