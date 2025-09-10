@extends('layouts.app')

@section('title', 'Status Pendaftaran - ' . $enrollment->program->title)

@push('styles')
<style>
    .status-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .status-header {
        padding: 2rem;
        text-align: center;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .status-diterima {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .status-ditolak {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .status-lulus {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }
    
    .status-dropout {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .progress-bar {
        height: 8px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
        overflow: hidden;
        margin-top: 1rem;
    }
    
    .progress-fill {
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        transition: width 0.3s ease;
    }
    
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -0.5rem;
        top: 0.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #d1d5db;
        border: 3px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .timeline-item.completed::before {
        background: #10b981;
    }
    
    .timeline-item.current::before {
        background: #f59e0b;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <nav class="bg-white border-b py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
                <a href="{{ route('enrollments.index') }}" class="hover:text-blue-600">Pendaftaran Saya</a>
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
                <span class="text-gray-900">Status Pendaftaran</span>
            </div>
        </div>
    </nav>

    <!-- Status Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Status Card -->
                <div class="status-card mb-8">
                    <div class="status-header status-{{ $enrollment->status }}">
                        <div class="mb-4">
                            @if($enrollment->status === 'pending')
                                <i data-lucide="clock" class="h-16 w-16 mx-auto mb-4"></i>
                                <h1 class="text-2xl font-bold mb-2">Menunggu Konfirmasi</h1>
                                <p class="text-blue-100">Pendaftaran Anda sedang diproses</p>
                            @elseif($enrollment->status === 'diterima')
                                <i data-lucide="check-circle" class="h-16 w-16 mx-auto mb-4"></i>
                                <h1 class="text-2xl font-bold mb-2">Pendaftaran Diterima</h1>
                                <p class="text-green-100">Selamat! Anda telah terdaftar dalam program ini</p>
                            @elseif($enrollment->status === 'ditolak')
                                <i data-lucide="x-circle" class="h-16 w-16 mx-auto mb-4"></i>
                                <h1 class="text-2xl font-bold mb-2">Pendaftaran Ditolak</h1>
                                <p class="text-red-100">Maaf, pendaftaran Anda tidak dapat diproses</p>
                            @elseif($enrollment->status === 'lulus')
                                <i data-lucide="award" class="h-16 w-16 mx-auto mb-4"></i>
                                <h1 class="text-2xl font-bold mb-2">Selamat! Anda Lulus</h1>
                                <p class="text-purple-100">Anda telah berhasil menyelesaikan program</p>
                            @elseif($enrollment->status === 'dropout')
                                <i data-lucide="user-x" class="h-16 w-16 mx-auto mb-4"></i>
                                <h1 class="text-2xl font-bold mb-2">Program Dihentikan</h1>
                                <p class="text-gray-100">Partisipasi Anda dalam program telah berakhir</p>
                            @endif
                        </div>
                        
                        <div class="status-badge bg-white/20">
                            {{ ucfirst($enrollment->status) }}
                        </div>
                        
                        @if($enrollment->status === 'diterima' || $enrollment->status === 'lulus')
                        <div class="progress-bar">
                            style="width: {{ $enrollment->status === 'lulus' ? '100%' : '60%' }}"
                        </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <!-- Program Details -->
                        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Program</h2>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Program</span>
                                    <span class="font-medium text-gray-900">{{ $enrollment->program->title }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Level</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $enrollment->program->level === 'Pemula' ? 'bg-green-100 text-green-800' : 
                                           ($enrollment->program->level === 'Menengah' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $enrollment->program->level }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Durasi</span>
                                    <span class="font-medium text-gray-900">{{ $enrollment->program->duration }}</span>
                                </div>
                                @if($enrollment->program->instructor)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Instruktur</span>
                                    <span class="font-medium text-gray-900">{{ $enrollment->program->instructor }}</span>
                                </div>
                                @endif
                                @if($enrollment->schedule)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Jadwal</span>
                                    <span class="font-medium text-gray-900">{{ $enrollment->schedule->name }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tanggal Daftar</span>
                                    <span class="font-medium text-gray-900">{{ $enrollment->enrollment_date->format('d M Y H:i') }}</span>
                                </div>
                                @if($enrollment->completion_date)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tanggal Selesai</span>
                                    <span class="font-medium text-gray-900">{{ $enrollment->completion_date->format('d M Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6">Timeline Pendaftaran</h2>
                            <div class="timeline">
                                <div class="timeline-item completed">
                                    <div class="timeline-content">
                                        <h3 class="font-medium text-gray-900">Pendaftaran Diajukan</h3>
                                        <p class="text-sm text-gray-600">{{ $enrollment->enrollment_date->format('d M Y H:i') }}</p>
                                        <p class="text-sm text-gray-500 mt-1">Anda telah mengajukan pendaftaran untuk program ini</p>
                                    </div>
                                </div>
                                
                                @if($enrollment->program->price > 0)
                                <div class="timeline-item {{ in_array($enrollment->status, ['diterima', 'lulus']) ? 'completed' : ($enrollment->status === 'pending' ? 'current' : '') }}">
                                    <div class="timeline-content">
                                        <h3 class="font-medium text-gray-900">Pembayaran</h3>
                                        @if($enrollment->status === 'diterima' || $enrollment->status === 'lulus')
                                            <p class="text-sm text-gray-600">Pembayaran berhasil</p>
                                            <p class="text-sm text-gray-500 mt-1">Pembayaran sebesar {{ $enrollment->program->formatted_price }} telah diterima</p>
                                        @elseif($enrollment->status === 'pending')
                                            <p class="text-sm text-gray-600">Menunggu pembayaran</p>
                                            <p class="text-sm text-gray-500 mt-1">Silakan selesaikan pembayaran untuk melanjutkan</p>
                                        @else
                                            <p class="text-sm text-gray-600">Pembayaran tidak berhasil</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <div class="timeline-item {{ in_array($enrollment->status, ['diterima', 'lulus']) ? 'completed' : ($enrollment->status === 'ditolak' ? 'current' : '') }}">
                                    <div class="timeline-content">
                                        <h3 class="font-medium text-gray-900">Verifikasi Admin</h3>
                                        @if($enrollment->status === 'diterima' || $enrollment->status === 'lulus')
                                            <p class="text-sm text-gray-600">Pendaftaran disetujui</p>
                                            <p class="text-sm text-gray-500 mt-1">Admin telah memverifikasi dan menyetujui pendaftaran Anda</p>
                                        @elseif($enrollment->status === 'ditolak')
                                            <p class="text-sm text-gray-600">Pendaftaran ditolak</p>
                                            <p class="text-sm text-gray-500 mt-1">Pendaftaran tidak dapat diproses</p>
                                        @else
                                            <p class="text-sm text-gray-600">Menunggu verifikasi</p>
                                            <p class="text-sm text-gray-500 mt-1">Admin sedang memverifikasi pendaftaran Anda</p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($enrollment->status === 'diterima')
                                <div class="timeline-item current">
                                    <div class="timeline-content">
                                        <h3 class="font-medium text-gray-900">Mengikuti Program</h3>
                                        <p class="text-sm text-gray-600">Sedang berlangsung</p>
                                        <p class="text-sm text-gray-500 mt-1">Anda dapat mengikuti program sesuai jadwal yang telah ditentukan</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($enrollment->status === 'lulus')
                                <div class="timeline-item completed">
                                    <div class="timeline-content">
                                        <h3 class="font-medium text-gray-900">Program Selesai</h3>
                                        <p class="text-sm text-gray-600">{{ $enrollment->completion_date->format('d M Y H:i') }}</p>
                                        <p class="text-sm text-gray-500 mt-1">Selamat! Anda telah berhasil menyelesaikan program</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Actions -->
                        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tindakan</h3>
                            <div class="space-y-3">
                                @if($enrollment->status === 'pending' && $enrollment->program->price > 0)
                                <a href="{{ route('enrollments.payment', $enrollment->id) }}" 
                                   class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Bayar Sekarang
                                </a>
                                @endif
                                
                                @if($enrollment->status === 'diterima')
                                <a href="{{ route('programs.show', $enrollment->program->id) }}" 
                                   class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    Lihat Program
                                </a>
                                @endif
                                
                                @if($enrollment->status === 'lulus')
                                <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    Download Sertifikat
                                </button>
                                @endif
                                
                                <a href="{{ route('enrollments.index') }}" 
                                   class="block w-full text-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                    Kembali ke Daftar
                                </a>
                            </div>
                        </div>

                        <!-- Support -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Butuh Bantuan?</h3>
                            <div class="space-y-3">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-lucide="phone" class="h-4 w-4 mr-3"></i>
                                    <span>(021) 1234-5678</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-lucide="mail" class="h-4 w-4 mr-3"></i>
                                    <span>support@lpk-kami.id</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-lucide="message-circle" class="h-4 w-4 mr-3"></i>
                                    <span>WhatsApp: +62 812-3456-7890</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection