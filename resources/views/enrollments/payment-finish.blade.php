@extends('layouts.app')

@section('title', 'Hasil Pembayaran - ' . $enrollment->program->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-2 rounded-full mr-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Hasil Pembayaran</h1>
            </div>
            <p class="text-gray-600">Berikut adalah status pembayaran dan pendaftaran Anda.</p>
        </div>

        <!-- Payment Status -->
<!-- Perbaikan logika status pembayaran -->
@if($enrollment->payment_status === 'paid')
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-green-100 p-2 rounded-full mr-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-green-800">Pembayaran Berhasil!</h2>
                <p class="text-green-700">
                    @if($enrollment->status === 'diterima')
                        Pendaftaran Anda telah dikonfirmasi dan diterima.
                    @elseif($enrollment->status === 'pending')
                        Pembayaran berhasil, sedang menunggu konfirmasi pendaftaran.
                    @else
                        Pembayaran berhasil, status pendaftaran: {{ $enrollment->status }}
                    @endif
                </p>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg">
            <h3 class="font-semibold text-gray-800 mb-2">Detail Pembayaran</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Order ID:</span>
                    <span class="font-mono text-gray-800">{{ $enrollment->order_id }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Tanggal Pembayaran:</span>
                    <span class="text-gray-800">{{ $enrollment->payment_date ? $enrollment->payment_date->format('d/m/Y H:i') : 'Baru saja' }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Jumlah:</span>
                    <span class="text-gray-800">Rp {{ number_format($enrollment->program->price, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Status Pembayaran:</span>
                    <span class="text-green-600 font-medium">Lunas</span>
                </div>
                <div>
                    <span class="text-gray-600">Status Pendaftaran:</span>
                    <span class="font-medium 
                        @if($enrollment->status === 'diterima') text-green-600
                        @elseif($enrollment->status === 'pending') text-yellow-600
                        @else text-red-600 @endif">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

@elseif($enrollment->payment_status === 'pending')
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-yellow-100 p-2 rounded-full mr-3">
                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-yellow-800">Pembayaran Pending</h2>
                <p class="text-yellow-700">Pembayaran sedang diproses. Silakan tunggu konfirmasi.</p>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg">
            <h3 class="font-semibold text-gray-800 mb-2">Langkah Selanjutnya</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• Selesaikan pembayaran jika belum selesai</li>
                <li>• Pembayaran akan diverifikasi secara otomatis</li>
                <li>• Anda akan menerima notifikasi setelah pembayaran dikonfirmasi</li>
            </ul>
        </div>
    </div>

@elseif($enrollment->payment_status === 'failed')
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-red-100 p-2 rounded-full mr-3">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-red-800">Pembayaran Gagal</h2>
                <p class="text-red-700">Terjadi kesalahan saat memproses pembayaran.</p>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg">
            <h3 class="font-semibold text-gray-800 mb-2">Apa yang harus dilakukan?</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• Coba lakukan pembayaran ulang</li>
                <li>• Pastikan saldo atau limit kartu mencukupi</li>
                <li>• Hubungi customer service jika masalah berlanjut</li>
            </ul>
        </div>
    </div>

@else
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <div class="bg-gray-100 p-2 rounded-full mr-3">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Status Tidak Diketahui</h2>
                <p class="text-gray-700">Status pembayaran: {{ $enrollment->payment_status ?? 'Tidak diketahui' }}</p>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg">
            <h3 class="font-semibold text-gray-800 mb-2">Hubungi Customer Service</h3>
            <p class="text-sm text-gray-600">
                Silakan hubungi customer service untuk informasi lebih lanjut mengenai status pembayaran Anda.
            </p>
        </div>
    </div>
@endif

        <!-- Program Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Detail Program</h2>
            
            <div class="flex items-start space-x-4">
                @if($enrollment->program->image)
                    <img src="{{ Storage::url($enrollment->program->image) }}" 
                         alt="{{ $enrollment->program->title }}"
                         class="w-16 h-16 object-cover rounded-lg">
                @else
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                @endif
                
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">{{ $enrollment->program->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $enrollment->program->description }}</p>
                    
                    @if($enrollment->schedule)
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="font-medium">Jadwal:</span> {{ $enrollment->schedule->name }}
                        </div>
                    @endif
                    
                    <div class="mt-2 text-sm text-gray-600">
                        <span class="font-medium">Harga:</span> 
                        <span class="text-green-600 font-semibold">Rp {{ number_format($enrollment->program->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('enrollments.status', $enrollment->id) }}" 
                   class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors text-center font-medium">
                    Lihat Status Lengkap
                </a>
                
                @if($enrollment->payment_status === 'failed' || $enrollment->payment_status === 'pending')
                    <a href="{{ route('enrollments.payment', $enrollment->id) }}" 
                       class="flex-1 bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition-colors text-center font-medium">
                        Coba Bayar Lagi
                    </a>
                @endif
                
                <a href="{{ route('peserta.dashboard') }}" 
                   class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 transition-colors text-center font-medium">
                    Semua Pendaftaran
                </a>
            </div>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <h3 class="font-medium text-gray-800 mb-2">Butuh Bantuan?</h3>
            <p class="text-sm text-gray-600 mb-2">
                Jika Anda mengalami masalah dengan pembayaran atau memiliki pertanyaan, 
                silakan hubungi customer service kami.
            </p>
            <div class="flex flex-wrap gap-4 text-sm">
                <a href="mailto:sekolahanid@gmail.com" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    sekolahanid@gmail.com
                </a>
                <a href="tel:+6282119001500" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    +62 821-1900-1500
                </a>
            </div>
        </div>
    </div>
</div>

@if($enrollment->payment_status === 'pending')
<script>
// Auto refresh page every 30 seconds untuk check status pembayaran
setTimeout(function() {
    window.location.reload();
}, 30000);
</script>
@endif
@endsection