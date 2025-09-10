@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 flex items-center justify-center py-12">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <img src="{{ asset('images/Sekolahan-v2.png') }}" alt="Logo SekolahID" class="h-8 w-8">
                    <span class="text-2xl font-bold text-gray-900">SIPEL</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Reset Password</h2>
                <p class="text-gray-600 mt-2">Masukkan password baru untuk akun Anda.</p>
            </div>

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <!-- Hidden Token -->
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ $email ?? old('email') }}"
                               required 
                               autocomplete="email"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="Masukkan email Anda">
                        <i data-lucide="mail" class="absolute left-3 top-3.5 h-5 w-5 text-gray-400"></i>
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                               placeholder="Masukkan password baru">
                        <i data-lucide="lock" class="absolute left-3 top-3.5 h-5 w-5 text-gray-400"></i>
                        <button type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" id="passwordToggleIcon" class="h-5 w-5"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Konfirmasi password baru">
                        <i data-lucide="lock" class="absolute left-3 top-3.5 h-5 w-5 text-gray-400"></i>
                        <button type="button" 
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" id="confirmPasswordToggleIcon" class="h-5 w-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                    Reset Password
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium inline-flex items-center">
                    <i data-lucide="arrow-left" class="h-4 w-4 mr-2"></i>
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const toggleIcon = document.getElementById(fieldId + 'ToggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.setAttribute('data-lucide', 'eye-off');
        } else {
            passwordInput.type = 'password';
            toggleIcon.setAttribute('data-lucide', 'eye');
        }
        
        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>
@endsection