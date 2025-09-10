@extends('layouts.app')

@section('title', 'Buat Akun')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="flex flex-col lg:flex-row">
                <!-- Left Section - Info -->
                <div class="lg:w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 p-12 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <!-- Header -->
                        <div class="mb-8">
                            <div class="flex items-center mb-6">
                                <img src="{{ asset('images/Sekolahan-v2.png') }}" alt="Logo SekolahID" class="h-10 w-10 mr-3">
                                <span class="text-xl font-bold">SekolahID</span>
                            </div>
                            <h1 class="text-4xl font-bold mb-4 leading-tight">
                                Bergabunglah dengan<br>
                                LPK Kami
                            </h1>
                            <p class="text-blue-100 text-lg">
                                Mulai perjalanan belajar Anda bersama kami dan kembangkan keterampilan profesional Anda
                            </p>
                        </div>

                        <!-- Contact Info -->
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                    <i data-lucide="mail" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-200">Email</p>
                                    <p class="font-medium">info@sekolahid.com</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                    <i data-lucide="phone" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-200">Telepon</p>
                                    <p class="font-medium">+62 123 456 7890</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                    <i data-lucide="map-pin" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-200">Alamat</p>
                                    <p class="font-medium">Bandung, Jawa Barat</p>
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="mt-12">
                            <h3 class="text-xl font-semibold mb-6">Mengapa Memilih Kami?</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 mr-3 text-green-300"></i>
                                    <span>Program pelatihan berkualitas tinggi</span>
                                </div>
                                <div class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 mr-3 text-green-300"></i>
                                    <span>Instruktur berpengalaman dan bersertifikat</span>
                                </div>
                                <div class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 mr-3 text-green-300"></i>
                                    <span>Sertifikat yang diakui industri</span>
                                </div>
                                <div class="flex items-center">
                                    <i data-lucide="check-circle" class="h-5 w-5 mr-3 text-green-300"></i>
                                    <span>Bantuan penempatan kerja</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500 rounded-full opacity-20 -translate-y-20 translate-x-20"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-400 rounded-full opacity-20 translate-y-16 -translate-x-16"></div>
                </div>

                <!-- Right Section - Form -->
                <div class="lg:w-1/2 p-12">
                    <div class="max-w-md mx-auto">
                        <!-- Form Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
                            <p class="text-gray-600">Isi informasi di bawah untuk membuat akun</p>
                        </div>

                        <!-- Registration Form -->
                        <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-6">
                            @csrf
                            
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                                       placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                                       placeholder="nama@email.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="+62 123 456 7890">
                            </div>

                         
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" required
                                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('password') border-red-500 @enderror"
                                           placeholder="Minimal 8 karakter">
                                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i data-lucide="eye" class="h-5 w-5 text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                <div id="passwordStrength" class="mt-1 text-xs"></div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" required
                                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="Ulangi password">
                                    <button type="button" id="togglePasswordConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i data-lucide="eye" class="h-5 w-5 text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="flex items-start">
                                <input type="checkbox" name="terms" id="terms" required
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                                <label for="terms" class="ml-3 text-sm text-gray-700">
                                    Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-800 underline">Syarat dan Ketentuan</a> 
                                    serta <a href="#" class="text-blue-600 hover:text-blue-800 underline">Kebijakan Privasi</a>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="submitBtn"
                                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all font-medium flex items-center justify-center">
                                <span id="submitText">Buat Akun</span>
                                <span id="submitLoading" class="hidden">
                                    <i data-lucide="loader-2" class="h-5 w-5 animate-spin mr-2"></i>
                                    Memproses...
                                </span>
                            </button>
                        </form>

                        <!-- Login Link -->
                        <div class="mt-6 text-center">
                            <p class="text-gray-600">
                                Sudah punya akun? 
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 underline font-medium">
                                    Masuk di sini
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Password visibility toggle
    function togglePasswordVisibility(toggleId, inputId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        const icon = toggle.querySelector('i');
        
        toggle.addEventListener('click', function() {
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        });
    }

    togglePasswordVisibility('togglePassword', 'password');
    togglePasswordVisibility('togglePasswordConfirm', 'password_confirmation');

    // Form submission with loading state
    document.getElementById('registerForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitLoading = document.getElementById('submitLoading');
        
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
        
        lucide.createIcons();
    });

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const strengthIndicator = document.getElementById('passwordStrength');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let feedback = [];
        
        if (password.length >= 8) strength++;
        else if (password.length > 0) feedback.push('minimal 8 karakter');
        
        if (/[a-z]/.test(password)) strength++;
        else if (password.length > 0) feedback.push('huruf kecil');
        
        if (/[A-Z]/.test(password)) strength++;
        else if (password.length > 0) feedback.push('huruf besar');
        
        if (/[0-9]/.test(password)) strength++;
        else if (password.length > 0) feedback.push('angka');
        
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        let strengthText = '';
        let strengthClass = '';
        
        if (password.length === 0) {
            strengthIndicator.innerHTML = '';
            return;
        }
        
        if (strength < 2) {
            strengthText = 'Lemah';
            strengthClass = 'text-red-500';
        } else if (strength < 4) {
            strengthText = 'Sedang';
            strengthClass = 'text-yellow-500';
        } else {
            strengthText = 'Kuat';
            strengthClass = 'text-green-500';
        }
        
        strengthIndicator.innerHTML = `<span class="${strengthClass}">Kekuatan: ${strengthText}</span>`;
        if (feedback.length > 0) {
            strengthIndicator.innerHTML += ` <span class="text-gray-500">(Perlu: ${feedback.join(', ')})</span>`;
        }
    });
    
    // Password confirmation validation
    passwordConfirmInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (confirmPassword.length > 0) {
            if (password === confirmPassword) {
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            } else {
                this.classList.remove('border-green-500');
                this.classList.add('border-red-500');
            }
        } else {
            this.classList.remove('border-red-500', 'border-green-500');
        }
    });

    // Initialize Lucide icons
    lucide.createIcons();
</script>
@endpush
@endsection