@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4">
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/Sekolahan-v2.png') }}" alt="Logo SekolahID" class="h-12 w-12">
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Email</h1>
            <p class="text-gray-600">
                @if(session('just_registered'))
                    Terima kasih telah mendaftar! Silakan verifikasi email Anda untuk mengaktifkan akun.
                @else
                    Silakan verifikasi email Anda untuk melanjutkan.
                @endif
            </p>
        </div>

        <!-- Verification Card -->
        <div class="bg-white rounded-lg shadow-xl p-8">
            <!-- Email Icon -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i data-lucide="mail" class="h-8 w-8 text-blue-600"></i>
                </div>
            </div>

            <!-- Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600 mt-0.5 mr-3 flex-shrink-0"></i>
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-lucide="alert-triangle" class="h-5 w-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0"></i>
                        <p class="text-sm text-yellow-800">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            @if(session('user_email'))
                <div class="text-center mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-center mb-2">
                            <i data-lucide="mail" class="h-5 w-5 text-blue-600 mr-2"></i>
                            <span class="text-sm text-blue-800 font-medium">Email verifikasi telah dikirim ke:</span>
                        </div>
                        <p class="text-blue-900 font-semibold">{{ session('user_email') }}</p>
                    </div>
                </div>
            @endif

            <!-- Important Notice -->
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start">
                    <i data-lucide="alert-circle" class="h-5 w-5 text-red-600 mt-0.5 mr-3 flex-shrink-0"></i>
                    <div class="text-sm text-red-800">
                        <p class="font-semibold mb-1">Akun Belum Aktif</p>
                        <p>Akun Anda belum dapat digunakan hingga email diverifikasi. Silakan periksa email Anda dan klik link verifikasi.</p>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Langkah Selanjutnya:</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                    <li>Buka aplikasi email Anda (Gmail, Yahoo, Outlook, dll.)</li>
                    <li>Cari email dari "{{ config('app.name', 'SekolahID') }}" dengan subjek verifikasi</li>
                    <li>Klik link verifikasi dalam email tersebut</li>
                    <li>Setelah verifikasi berhasil, Anda dapat login ke akun Anda</li>
                </ol>
            </div>

            <!-- Resend Verification Form -->
            <div class="border-t pt-6">
                <p class="text-sm text-gray-600 mb-4 text-center">
                    Belum menerima email verifikasi?
                </p>
                
                <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                    @csrf
                    
                    @if(!Auth::check())
                        <!-- Form untuk user yang belum login -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email yang didaftarkan:
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ session('user_email') ?? old('email') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan email Anda">
                        </div>
                        
                        @error('email')
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-800">{{ $message }}</p>
                            </div>
                        @enderror
                    @else
                        <!-- Hidden input untuk user yang sudah login -->
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                    @endif
                    
                    <button type="submit" id="resendBtn"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium flex items-center justify-center disabled:bg-blue-400 disabled:cursor-not-allowed">
                        <span id="resendText">Kirim Ulang Email Verifikasi</span>
                        <span id="resendLoading" class="hidden">
                            <i data-lucide="loader-2" class="h-5 w-5 animate-spin mr-2"></i>
                            Mengirim...
                        </span>
                    </button>
                </form>
            </div>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Sudah verifikasi email? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">
                        Login di sini
                    </a>
                </p>
            </div>

            <!-- Register Link (untuk yang belum punya akun) -->
            @if(!session('just_registered') && !Auth::check())
                <div class="mt-4 text-center">
                    <p class="text-gray-600 text-sm">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            @endif
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-start">
                <i data-lucide="help-circle" class="h-5 w-5 text-gray-500 mt-0.5 mr-3 flex-shrink-0"></i>
                <div class="text-sm text-gray-700">
                    <p class="font-medium mb-1">Masalah dengan verifikasi?</p>
                    <ul class="list-disc ml-4 space-y-1">
                        <li>Periksa folder spam/junk email Anda</li>
                        <li>Pastikan email yang didaftarkan benar</li>
                        <li>Tunggu hingga 10 menit untuk email tiba</li>
                        <li>Coba kirim ulang email verifikasi</li>
                        <li>Hubungi support jika masih bermasalah</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Form submission with loading state
    document.getElementById('resendForm').addEventListener('submit', function(e) {
        const resendBtn = document.getElementById('resendBtn');
        const resendText = document.getElementById('resendText');
        const resendLoading = document.getElementById('resendLoading');
        
        // Validasi email untuk user yang belum login
        @if(!Auth::check())
        const emailInput = document.getElementById('email');
        if (!emailInput.value.trim()) {
            e.preventDefault();
            alert('Silakan masukkan email Anda.');
            return;
        }
        
        // Validasi format email sederhana
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value.trim())) {
            e.preventDefault();
            alert('Format email tidak valid.');
            return;
        }
        @endif
        
        resendBtn.disabled = true;
        resendText.classList.add('hidden');
        resendLoading.classList.remove('hidden');
        
        lucide.createIcons();
        
        // Re-enable button after 30 seconds to prevent spam
        setTimeout(function() {
            resendBtn.disabled = false;
            resendText.classList.remove('hidden');
            resendLoading.classList.add('hidden');
            lucide.createIcons();
        }, 30000);
    });

    // Initialize Lucide icons
    lucide.createIcons();
    
    // Auto-focus email input if exists
    @if(!Auth::check())
    const emailInput = document.getElementById('email');
    if (emailInput && !emailInput.value.trim()) {
        emailInput.focus();
    }
    @endif
</script>
@endpush
@endsection