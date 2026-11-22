<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Cek apakah user memiliki role yang diizinkan.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Cek apakah akun aktif
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi pemilik toko.']);
        }

        // Cek apakah role user ada di daftar yang diizinkan
        if (!in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
