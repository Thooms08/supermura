<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class PengunjungController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        // Ambil data pengunjung atau buat instance baru jika belum ada (firstOrCreate)
        $profil = Pengunjung::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap' => $user->name,
                'email' => $user->email
            ]
        );

        return view('pengunjung.profile', compact('user', 'profil'));
    }

    public function update(Request $request)
    {
        $user = \App\Models\User::find(Auth::id());
        $profil = Pengunjung::where('user_id', $user->id)->first();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'required|numeric',
            'alamat_lengkap' => 'required',
            'kota_kabupaten' => 'required',
            'provinsi' => 'required',
            'kode_pos' => 'required|numeric',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // 1. Logika Update Foto
        if ($request->hasFile('foto_profile')) {
            $path = public_path('asset/profile-pengunjung');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            // Hapus foto lama jika ada
            if ($profil->foto_profile && File::exists(public_path($profil->foto_profile))) {
                File::delete(public_path($profil->foto_profile));
            }

            $file = $request->file('foto_profile');
            $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move($path, $fileName);
            $profil->foto_profile = 'asset/profile-pengunjung/' . $fileName;
        }

        // 2. Update Data Profil
        $profil->update([
            'nama_lengkap' => $request->nama_lengkap,
            'no_whatsapp' => $request->no_whatsapp,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kota_kabupaten' => $request->kota_kabupaten,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
        ]);

        // 3. Update User (Nama & Password)
        $user->name = $request->nama_lengkap;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Email hanya diupdate jika bukan login via Google
        if (!$user->google_id) {
            $request->validate(['email' => 'required|email|unique:users,email,' . $user->id]);
            $user->email = $request->email;
            $profil->email = $request->email;
            $profil->save();
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
    public function daftarAlamat()
{
    $alamat = Pengunjung::where('user_id', Auth::id())->get();
    return view('pengunjung.daftar-alamat', compact('alamat'));
}

public function tambahAlamat()
{
    return view('pengunjung.tambah-alamat');
}

public function storeAlamat(Request $request)
{
    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'no_whatsapp' => 'required|numeric',
        'alamat_lengkap' => 'required',
        'kota_kabupaten' => 'required',
        'provinsi' => 'required',
        'kode_pos' => 'required|numeric',
    ]);

    Pengunjung::create([
        'user_id' => Auth::id(),
        'nama_lengkap' => $request->nama_lengkap,
        'no_whatsapp' => $request->no_whatsapp,
        'alamat_lengkap' => $request->alamat_lengkap,
        'kota_kabupaten' => $request->kota_kabupaten,
        'provinsi' => $request->provinsi,
        'kode_pos' => $request->kode_pos,
        'email' => Auth::user()->email,
    ]);

    return redirect()->route('pengunjung.alamat.index')->with('success_alert', 'ALAMAT BERHASIL DISIMPAN');
}

public function pilihAlamat($id)
{
    // Simpan ID alamat terpilih ke session agar bisa dipakai di checkout
    session(['selected_alamat_id' => $id]);
    return redirect()->route('checkout');
}
public function editAlamat($id)
{
    // Pastikan alamat yang diedit adalah milik user yang sedang login
    $alamat = Pengunjung::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    return view('pengunjung.edit-alamat', compact('alamat'));
}

public function updateAlamat(Request $request, $id)
{
    $alamat = Pengunjung::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'no_whatsapp' => 'required|numeric',
        'alamat_lengkap' => 'required',
        'kota_kabupaten' => 'required',
        'provinsi' => 'required',
        'kode_pos' => 'required|numeric',
    ]);

    $alamat->update([
        'nama_lengkap' => $request->nama_lengkap,
        'no_whatsapp' => $request->no_whatsapp,
        'alamat_lengkap' => $request->alamat_lengkap,
        'kota_kabupaten' => $request->kota_kabupaten,
        'provinsi' => $request->provinsi,
        'kode_pos' => $request->kode_pos,
    ]);

    return redirect()->route('pengunjung.alamat.index')->with('success_alert', 'ALAMAT BERHASIL DIPERBARUI');
}

public function destroyAlamat($id)
{
    $alamat = Pengunjung::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
    $alamat->delete();

    // Jika alamat yang dihapus sedang dipilih di session, hapus session tersebut
    if (session('selected_alamat_id') == $id) {
        session()->forget('selected_alamat_id');
    }

    return back()->with('success_alert', 'ALAMAT BERHASIL DIHAPUS');
}
}