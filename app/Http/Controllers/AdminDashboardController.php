<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        // Validasi data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update data
        $user->name = $request->name;
        $user->email = $request->email;

        // Hanya update password jika field diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Method 'save' sekarang pasti terbaca karena $user adalah instance Model
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}