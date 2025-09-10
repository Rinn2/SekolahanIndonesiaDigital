<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

         

            // Redirect berdasarkan role pengguna
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Login berhasil sebagai Admin!');
            } 
            if ($user->role === 'instruktur') {
                return redirect()->intended(route('instruktur.dashboard'))->with('success', 'Login berhasil sebagai Instruktur!');
            } 
            if ($user->role === 'peserta') {
                return redirect()->intended(route('peserta.dashboard'))->with('success', 'Login berhasil sebagai Peserta!');
            }
            
            // Jika role tidak dikenali, logout untuk keamanan
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Role pengguna tidak dikenali.']);
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan halaman form registrasi.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Memproses permintaan registrasi.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'terms' => 'required|accepted'
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.'
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'role' => 'peserta', // Default role untuk pendaftar baru
            ]);

            event(new Registered($user));

         
            
            return redirect()->route('verification.notice')->with('success', 'Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi.');
                
        } catch (\Exception) {
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat mendaftarkan akun. Silakan coba lagi.'
            ])->withInput();
        }
    }
    
    /**
     * Menampilkan halaman notifikasi verifikasi.
     */
    public function showVerificationNotice()
    {
        // Pastikan view ini ada: resources/views/auth/verify-email.blade.php
        return view('auth.verify-email');
    }

    /**
     * Memproses link verifikasi email.
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'))
                             ->with('info', 'Email Anda sudah terverifikasi.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard'))
                         ->with('success', 'Verifikasi berhasil! Selamat datang.');
    }
        
    /**
     * Mengirim ulang link verifikasi email.
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('info', 'Email Anda sudah terverifikasi.');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi baru telah dikirim ke email Anda.');
    }

    // ... sisa metode lainnya (forgot password, reset password, logout) tetap sama ...

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token, 
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
