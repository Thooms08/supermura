<?php

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AffiliateProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $affiliator = $user->affiliator;
        return view('affiliate.profile', compact('user', 'affiliator'));
    }

    public function updateProfile(Request $request)
    {
        $affiliator = Auth::user()->affiliator;

        $request->validate([
            'nama' => 'required|string|max:255',
            'domisili' => 'required|string|max:255',
            'no_whatsapp' => 'required|numeric|digits_between:10,15',
            'foto_profile' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
            'domisili' => $request->domisili,
            'no_whatsapp' => $request->no_whatsapp,
        ];

        if ($request->hasFile('foto_profile')) {
            // Hapus foto lama jika ada
            if ($affiliator->foto_profile) {
                Storage::disk('public')->delete('profile-affiliator/' . $affiliator->foto_profile);
            }

            // Upload foto baru
            $file = $request->file('foto_profile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->putFileAs('profile-affiliator', $file, $fileName);
            $data['foto_profile'] = $fileName;
        }

        $affiliator->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required|string|min:6|confirmed',
    ], [
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'password.min' => 'Password minimal harus 6 karakter.'
    ]);

    // Ambil ID user yang sedang login
    $userId = Auth::id(); 
    
    // Cari user secara eksplisit dari model User agar method update() tersedia
    $user = \App\Models\User::find($userId);

    $user->update([
        'password' => Hash::make($request->password)
    ]);

    return back()->with('success_password', 'Password berhasil diubah!');
}
}

