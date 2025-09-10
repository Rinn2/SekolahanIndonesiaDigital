@extends('layouts.dashboard')
@section('title', 'Dashboard Peserta')

{{-- Menambahkan script khusus untuk halaman peserta jika diperlukan --}}
@if(request()->is('peserta*'))
@vite('resources/js/peserta.js') {{-- Buat file ini jika perlu custom JS --}}
@endif

@section('content')

<div class="min-h-screen bg-gray-50">
    {{-- HEADER --}}
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-4 space-y-3 sm:space-y-0">
                <div class="flex items-center space-x-3">
                    <i data-lucide="user" class="h-6 w-6 sm:h-8 sm:w-8 text-blue-600 flex-shrink-0"></i>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Dashboard Peserta</h1>
                        <p class="text-xs sm:text-sm text-gray-600">Selamat datang, {{ Auth::user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">
        {{-- KARTU STATISTIK --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0">
                    <div class="p-2 sm:p-3 rounded-full bg-blue-100 self-start">
                        <i data-lucide="book-marked" class="h-4 w-4 sm:h-6 sm:w-6 text-blue-600"></i>
                    </div>
                    <div class="sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Program Diikuti</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $totalPrograms }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0">
                    <div class="p-2 sm:p-3 rounded-full bg-green-100 self-start">
                        <i data-lucide="play-circle" class="h-4 w-4 sm:h-6 sm:w-6 text-green-600"></i>
                    </div>
                    <div class="sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Program Aktif</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $activePrograms }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0">
                    <div class="p-2 sm:p-3 rounded-full bg-yellow-100 self-start">
                        <i data-lucide="award" class="h-4 w-4 sm:h-6 sm:w-6 text-yellow-600"></i>
                    </div>
                    <div class="sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Sertifikat Diraih</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $completedPrograms }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0">
                    <div class="p-2 sm:p-3 rounded-full bg-purple-100 self-start">
                        <i data-lucide="trending-up" class="h-4 w-4 sm:h-6 sm:w-6 text-purple-600"></i>
                    </div>
                    <div class="sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Progres Rata-Rata</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $averageProgress }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-4 sm:space-x-8 px-3 sm:px-6 overflow-x-auto" aria-label="Tabs">
                    <button class="tab-button active whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-xs sm:text-sm" data-tab="my-programs">
                        <i data-lucide="book-open" class="h-3 w-3 sm:h-4 sm:w-4 inline mr-1 sm:mr-2"></i>
                        Program Saya
                    </button>
                    <button class="tab-button whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-xs sm:text-sm" data-tab="certificates">
                        <i data-lucide="award" class="h-3 w-3 sm:h-4 sm:w-4 inline mr-1 sm:mr-2"></i>
                        Sertifikat
                    </button>
                </nav>
            </div>

            {{-- TAB 1: DAFTAR PROGRAM SAYA --}}
            <div id="my-programs-tab" class="tab-content">
                <div class="p-3 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Daftar Program yang Diikuti</h3>
                    
                    {{-- Mobile Card View --}}
                    <div class="block sm:hidden space-y-4">
                        @forelse($enrollments as $enrollment)
                        <div class="bg-gray-50 border rounded-lg p-4">
                            <div class="space-y-3">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $enrollment->program->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $enrollment->schedule?->title ?? 'Jadwal tidak tersedia' }}</p>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Instruktur:</span>
                                    <span class="font-medium">{{ $enrollment->schedule?->instructor?->name ?? 'N/A' }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Jadwal:</span>
                                    <span class="text-right">
                                        @if($enrollment->schedule)
                                        {{ \Carbon\Carbon::parse($enrollment->schedule->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($enrollment->schedule->end_date)->format('d M Y') }}
                                        @else
                                        -
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Status:</span>
                                    <span class="px-2 py-1 text-xs leading-4 font-semibold rounded-full 
                                        @if($enrollment->status === 'diterima' || $enrollment->status === 'lulus') bg-green-100 text-green-800
                                        @elseif($enrollment->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($enrollment->status === 'ditolak' || $enrollment->status === 'tidak_lulus') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ Str::ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                                    </span>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between items-center text-sm mb-1">
                                        <span class="text-gray-600">Progres:</span>
                                        <span class="font-medium">
                                            @php
                                            $totalMeetings = $enrollment->program->total_meetings ?? 8;
                                            $completedMeetings = $enrollment->grades->count();
                                            $progressPercentage = $totalMeetings > 0 ? round(($completedMeetings / $totalMeetings) * 100) : 0;
                                            @endphp
                                            {{ $progressPercentage }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="pt-2 border-t">
                                    <a href="#" class="block text-center bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <i data-lucide="info" class="h-8 w-8 mx-auto text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Anda belum terdaftar di program manapun.</p>
                        </div>
                        @endforelse
                    </div>
                    
                    {{-- Desktop Table View --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                    <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Instruktur</th>
                                    <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                    <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Progres</th>
                                    <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($enrollments as $enrollment)
                                <tr>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-center font-medium text-gray-900">{{ $enrollment->program->name }}</div>
                                        <div class="text-sm text-center text-gray-500">{{ $enrollment->schedule?->title ?? 'Jadwal tidak tersedia' }}</div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                        {{ $enrollment->schedule?->instructor?->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                        @if($enrollment->schedule)
                                        {{ \Carbon\Carbon::parse($enrollment->schedule->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($enrollment->schedule->end_date)->format('d M Y') }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($enrollment->status === 'diterima' || $enrollment->status === 'lulus') bg-green-100 text-green-800
                                            @elseif($enrollment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($enrollment->status === 'ditolak' || $enrollment->status === 'tidak_lulus') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ Str::ucfirst(str_replace('_', ' ', $enrollment->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @php
                                        $totalMeetings = $enrollment->program->total_meetings ?? 8;
                                        $completedMeetings = $enrollment->grades->count();
                                        $progressPercentage = $totalMeetings > 0 ? round(($completedMeetings / $totalMeetings) * 100) : 0;
                                        @endphp
                                        <div class="flex items-center justify-center">
                                            <div class="w-16 lg:w-24 bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                            <span class="ml-2 text-xs font-medium">{{ $progressPercentage }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        <a href="#" class="text-blue-600 hover:text-blue-900">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 lg:px-6 py-4 text-center text-sm text-gray-500">
                                        Anda belum terdaftar di program manapun.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TAB 2: SERTIFIKAT --}}
            <div id="certificates-tab" class="tab-content hidden">
                <div class="p-3 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Sertifikat Saya</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @forelse($certificates as $certificate)
                        <div class="bg-gray-50 border rounded-lg p-4 sm:p-5 text-center flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div>
                                <div class="p-3 sm:p-4 rounded-full bg-yellow-100 inline-block mb-3 sm:mb-4">
                                    <i data-lucide="award" class="h-6 w-6 sm:h-8 sm:w-8 text-yellow-600"></i>
                                </div>
                                <h4 class="text-sm sm:text-md font-semibold text-gray-900">{{ $certificate->program->name }}</h4>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">Diterbitkan: {{ \Carbon\Carbon::parse($certificate->issue_date)->isoFormat('D MMMM YYYY') }}</p>
                                <p class="text-xs text-gray-400 mt-2">No: {{ $certificate->certificate_number }}</p>
                            </div>
                            <div class="mt-4 sm:mt-5 flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-2">
                               
                                <a href="{{ route('certificate.download', $certificate->id) }}" class="text-xs sm:text-sm font-medium text-white bg-green-600 hover:bg-green-700 px-3 sm:px-4 py-2 rounded-md inline-flex items-center justify-center">
                                    <i data-lucide="download" class="h-3 w-3 sm:h-4 sm:w-4 mr-1 sm:mr-2"></i>Unduh
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-8 sm:py-12 bg-gray-50 rounded-lg">
                            <i data-lucide="info" class="h-8 w-8 sm:h-12 sm:w-12 mx-auto text-gray-300 mb-2 sm:mb-3"></i>
                            <h4 class="text-sm sm:text-md font-semibold text-gray-700">Belum Ada Sertifikat</h4>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Sertifikat akan muncul di sini setelah Anda menyelesaikan program.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tab-button {
    @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
}

.tab-button.active {
    @apply border-blue-500 text-blue-600;
}

.tab-content {
    display: none;
}

.tab-content:not(.hidden) {
    display: block;
}

/* Mobile table scroll indicator */
@media (max-width: 640px) {
    .overflow-x-auto::-webkit-scrollbar {
        height: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Hide all tab contents
            tabContents.forEach(content => content.classList.add('hidden'));
            
            // Add active class to clicked button
            button.classList.add('active');
            
            // Show target tab content
            document.getElementById(targetTab + '-tab').classList.remove('hidden');
        });
    });
});
</script>

@endsection