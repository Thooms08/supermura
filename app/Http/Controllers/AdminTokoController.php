<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
            $destinationPath = public_path('asset/profile-toko');
            
            // Pastikan folder ada
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            
            $image->move($destinationPath, $name);
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
            if ($profile_toko->foto_toko && File::exists(public_path('asset/profile-toko/' . $profile_toko->foto_toko))) {
                File::delete(public_path('asset/profile-toko/' . $profile_toko->foto_toko));
            }

            $image = $request->file('foto_toko');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('asset/profile-toko'), $name);
            $data['foto_toko'] = $name;
        } else {
            // Jika tidak upload foto baru, tetap gunakan foto lama
            $data['foto_toko'] = $profile_toko->foto_toko;
        }

        $profile_toko->update($data);
        return redirect()->route('profile-toko.index')->with('success', 'Toko berhasil diperbarui!');
    }

    public function destroy(Toko $profile_toko)
    {
        if ($profile_toko->foto_toko && File::exists(public_path('asset/profile-toko/' . $profile_toko->foto_toko))) {
            File::delete(public_path('asset/profile-toko/' . $profile_toko->foto_toko));
        }

        $profile_toko->delete();
        return back()->with('success', 'Toko berhasil dihapus!');
    }
}