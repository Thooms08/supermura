<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Mengambil user atau membuat baru jika belum ada
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'role' => 'pengunjung',
                    'password' => null,
                ]
            );

            // Validasi: Hanya 'pengunjung' yang boleh login via Google
            // Menggunakan match untuk menggantikan if-else
            return match ($user->role) {
                'pengunjung' => $this->loginAndRedirect($user),
                default => redirect('/login')->with('error', 'Login Google hanya untuk pengunjung.'),
            };
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login menggunakan Google.');
        }
    }

    private function loginAndRedirect($user)
    {
        Auth::login($user);
        return redirect()->route('dashboard');
    }
}