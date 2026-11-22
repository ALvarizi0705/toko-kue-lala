<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login bisa menggunakan email ATAU nomor WhatsApp + kata sandi.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email atau nomor WhatsApp wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Tentukan apakah login menggunakan email atau nomor WA
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([$field => $login, 'password' => $password, 'is_active' => true], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => 'Kredensial tidak cocok atau akun tidak aktif.',
            ]);
        }

        $request->session()->regenerate();

        // Redirect berdasarkan role
        $role = Auth::user()->role;
        if ($role === 'pelanggan') {
            return redirect()->intended(route('home'));
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
