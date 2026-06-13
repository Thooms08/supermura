<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $request)
    {
        $request->validate([
            'email'                => 'required|email',
            'password'             => 'required',
            'cf-turnstile-response' => ['required', new Turnstile],
        ], [
            'cf-turnstile-response.required' => 'Verifikasi keamanan wajib diselesaikan.',
        ]);

        $credentials = $request->only('email', 'password');

        return match (Auth::attempt($credentials)) {
            true  => $this->processLogin($request),
            false => back()->withErrors(['email' => 'Email atau password salah.']),
        };
    }

    private function processLogin($request)
    {
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|string|email|unique:users',
            'password'             => 'required|min:8|confirmed',
            'cf-turnstile-response' => ['required', new Turnstile],
        ], [
            'cf-turnstile-response.required' => 'Verifikasi keamanan wajib diselesaikan.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pengunjung',
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}