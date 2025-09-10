@extends('layouts.app')

@section('title', 'Status Pendaftaran - ' . $enrollment->program->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Status Pendaftaran</h1>
                        <p class="text-gray-600">{{ $enrollment->program->title }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($enrollment->status === 'diterima') bg-green-100 text-green-800
                        @elseif($enrollment->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($enrollment->status === 'ditolak') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-6 text-gray-800">Progress Pendaftaran</h2>
            
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                
                <!-- Step 1: Pendaftaran -->
                <div class="relative flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-500 rounded-full border-4 border-white shadow-md z-10">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-800">Pendaftaran Berhasil</h3>
                        <p class="text-sm text-gray-600">{{ $enrollment->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-sm text-gray-500">Pendaftaran program berhasil diajukan</p>
                    </div>
                </div>
                
                <!-- Step 2: Pembayaran -->
                <div class="relative flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full border-4 border-white shadow-md z-10
                        @if($enrollment->payment_status === 'paid' || $enrollment->payment_status === 'free') bg-green-500
                        @elseif($enrollment->payment_status === 'pending') bg-yellow-500
                        @elseif($enrollment->payment_status === 'failed') bg-red-500
                        @else bg-gray-300
                        @endif">
                        @if($enrollment->payment_status === 'paid' || $enrollment->payment_status === 'free')
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($enrollment->payment_status === 'pending')
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($enrollment->payment_status === 'failed')
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @else
                            <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-800">
                            @if($enrollment->payment_status === 'paid') Pembayaran Berhasil
                            @elseif($enrollment->payment_status === 'free') Program Gratis
                            @elseif($enrollment->payment_status === 'pending') Menunggu Pembayaran
                            @elseif($enrollment->payment_status === 'failed') Pembayaran Gagal
                            @else Pembayaran Belum Dimulai
                            @endif
                        </h3>
                        @if($enrollment->payment_date)
                            <p class="text-sm text-gray-600">{{ $enrollment->payment_date->format('d M Y, H:i') }}</p>
                        @endif
                        <p class="text-sm text-gray-500">
                            @if($enrollment->payment_status === 'paid') Pembayaran telah dikonfirmasi
                            @elseif($enrollment->payment_status === 'free') Program gratis, tidak perlu pembayaran
                            @elseif($enrollment->payment_status === 'pending') Silakan selesaikan pembayaran
                            @elseif($enrollment->payment_status === 'failed') Pembayaran gagal, silakan coba lagi
                            @else Pembayaran belum dimulai
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Step 3: Konfirmasi -->
                <div class="relative flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full border-4 border-white shadow-md z-10
                        @if($enrollment->status === 'diterima') bg-green-500
                        @elseif($enrollment->status === 'ditolak') bg-red-500
                        @elseif($enrollment->status === 'pending') bg-yellow-500
                        @else bg-gray-300
                        @endif">
                        @if($enrollment->status === 'diterima')
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($enrollment->status === 'ditolak')
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @elseif($enrollment->status === 'pending')
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-800">
                            @if($enrollment->status === 'diterima') Pendaftaran Diterima
                            @elseif($enrollment->status === 'ditolak') Pendaftaran Ditolak
                            @elseif($enrollment->status === 'pending') Menunggu Konfirmasi
                            @else Belum Dikonfirmasi
                            @endif
                        </h3>
                        @if($enrollment->updated_at != $enrollment->created_at)
                            <p class="text-sm text-gray-600">{{ $enrollment->updated_at->format('d M Y, H:i') }}</p>
                        @endif
                        <p class="text-sm text-gray-500">
                            @if($enrollment->status === 'diterima') Selamat! Pendaftaran Anda telah diterima
                            @elseif($enrollment->status === 'ditolak') Maaf, pendaftaran Anda ditolak
                            @elseif($enrollment->status === 'pending') Menunggu review dari admin
                            @else Status akan diperbarui setelah pembayaran
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Step 4: Selesai -->
                <div class="relative flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full border-4 border-white shadow-md z-10
                        @if($enrollment->status === 'diterima' && $enrollment->completion_date) bg-green-500
                        @else bg-gray-300
                        @endif">
                        @if($enrollment->status === 'diterima' && $enrollment->completion_date)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @else
                            <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-800">
                            @if($enrollment->completion_date) Program Selesai
                            @else Program Belum Selesai
                            @endif
                        </h3>
                        @if($enrollment->completion_date)
                            <p class="text-sm text-gray-600">{{ $enrollment->completion_date->format('d M Y, H:i') }}</p>
                            <p class="text-sm text-gray-500">Program telah selesai diikuti</p>
                        @else
                            <p class="text-sm text-gray-500">Program akan dimulai setelah pendaftaran diterima</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Program Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Program Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Detail Program</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nama Program</label>
                        <p class="text-gray-800">{{ $enrollment->program->title }}</p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="text-gray-800">{{ $enrollment->program->description }}</p>
                    </div>
                    
                    @if($enrollment->schedule)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Jadwal</label>
                            <p class="text-gray-800">{{ $enrollment->schedule->name }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Harga</label>
                        <p class="text-gray-800">
                            @if($enrollment->program->price == 0)
                                <span class="text-green-600 font-semibold">Gratis</span>
                            @else
                                <span class="text-gray-800">Rp {{ number_format($enrollment->program->price, 0, ',', '.') }}</span>
                            @endif
                        </p>
                    </div>
                    
                    @if($enrollment->notes)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Catatan</label>
                            <p class="text-gray-800">{{ $enrollment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Informasi Pembayaran</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status Pembayaran</label>
                        <p class="text-gray-800">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($enrollment->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($enrollment->payment_status === 'free') bg-blue-100 text-blue-800
                                @elseif($enrollment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($enrollment->payment_status === 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($enrollment->payment_status === 'paid') Lunas
                                @elseif($enrollment->payment_status === 'free') Gratis
                                @elseif($enrollment->payment_status === 'pending') Pending
                                @elseif($enrollment->payment_status === 'failed') Gagal
                                @else Belum Bayar
                                @endif
                            </span>
                        </p>
                    </div>
                    
                    @if($enrollment->order_id)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Order ID</label>
                            <p class="font-mono text-gray-800">{{ $enrollment->order_id }}</p>
                        </div>
                    @endif
                    
                    @if($enrollment->payment_date)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Pembayaran</label>
                            <p class="text-gray-800">{{ $enrollment->payment_date->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Jumlah</label>
                        <p class="text-gray-800">
                            @if($enrollment->program->price == 0)
                                <span class="text-green-600 font-semibold">Gratis</span>
                            @else
                                <span class="text-gray-800">Rp {{ number_format($enrollment->program->price, 0, ',', '.') }}</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tanggal Pendaftaran</label>
                        <p class="text-gray-800">{{ $enrollment->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Tindakan</h2>
            
            <div class="flex flex-wrap gap-4">
                @if($enrollment->payment_status === 'pending' || $enrollment->payment_status === 'failed')
                    <a href="{{ route('enrollments.payment', $enrollment->id) }}" 
                       class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors">
                        @if($enrollment->payment_status === 'pending') Lanjutkan Pembayaran @else Bayar Ulang @endif
                    </a>
                @endif
                
                <a href="{{ route('peserta.dashboard') }}" 
                   class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Lihat Semua Pendaftaran
                </a>
                
                <a href="{{ route('programs.show', $enrollment->program->id) }}" 
                   class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition-colors">
                    Lihat Program
                </a>
            </div>
        </div>
    </div>
</div>
@endsection