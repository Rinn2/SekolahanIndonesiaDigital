@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 flex items-center justify-center py-12">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <img src="{{ asset('images/Sekolahan-v2.png') }}" alt="Logo SekolahID" class="h-8 w-8">
                    <span class="text-2xl font-bold text-gray-900">SIPEL</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Lupa Password</h2>
                <p class="text-gray-600 mt-2">Masukkan email Anda untuk menerima link reset password.</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200">
                    <div class="flex">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-400 mr-3 mt-0.5"></i>
                        <div class="text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
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

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200 font-medium">
                    Kirim Link Reset Password
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
@endsection