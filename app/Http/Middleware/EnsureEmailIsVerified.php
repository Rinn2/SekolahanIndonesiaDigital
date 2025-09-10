<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user belum login, redirect ke login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Periksa apakah user memiliki method hasVerifiedEmail
        if (!method_exists($user, 'hasVerifiedEmail')) {
            // Jika tidak ada method verifikasi, izinkan akses
            return $next($request);
        }

        // Jika email belum diverifikasi
        if (!$user->hasVerifiedEmail()) {
            // Logout user dan redirect ke halaman verifikasi
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('verification.notice')
                ->with('user_email', $user->email)
                ->with('warning', 'Akun Anda belum aktif. Silakan verifikasi email Anda terlebih dahulu untuk menggunakan fitur ini.');
        }

        return $next($request);
    }
}