<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi pemilik toko.']);
        }

        return $next($request);
    }
}
