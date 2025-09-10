@extends('layouts.app')

@section('title', 'Program Pelatihan')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-gradient-to-r from-blue-600 to-green-600 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Program Pelatihan</h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Pilih program pelatihan yang sesuai dengan kebutuhan dan tingkat kemampuan Anda
                </p>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-8 bg-white border-b">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Search Form -->
                <form method="GET" class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari program..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i data-lucide="search" class="absolute left-3 top-2.5 h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="hidden" name="level" value="{{ request('level') }}">
                </form>

                <!-- Level Filter -->
                <div class="flex gap-2">
                    <a href="{{ route('programs.index') }}" 
                       class="px-4 py-2 rounded-lg {{ !request('level') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                        Semua
                    </a>
                    <!-- Fixed: Hardcoded levels to ensure all levels are shown -->
                    @php
                        $availableLevels = ['Pemula', 'Menengah', 'Lanjutan'];
                    @endphp
                    @foreach($availableLevels as $level)
                    <a href="{{ route('programs.index', ['level' => $level, 'search' => request('search')]) }}" 
                       class="px-4 py-2 rounded-lg {{ request('level') === $level ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                        {{ $level }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Grid -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            @if($programs && $programs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($programs as $program)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <div class="p-6">
                            <!-- Program Header -->
                            <div class="flex items-center justify-between mb-4">
                                @php
                                    $levelClasses = [
                                        'Pemula' => 'bg-green-100 text-green-800',
                                        'Menengah' => 'bg-blue-100 text-blue-800',
                                        'Lanjutan' => 'bg-red-100 text-red-800'
                                    ];
                                    $levelClass = $levelClasses[$program->level ?? 'default'] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $levelClass }}">
                                    {{ $program->level ?? 'Tidak Ditentukan' }}
                                </span>
                                <span class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="clock" class="h-4 w-4 mr-1"></i>
                                    {{ $program->duration ?? 'Tidak Ditentukan' }}
                                </span>
                            </div>

                            <!-- Program Title - Fixed potential issues -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                                {{ $program->title ?? $program->name ?? 'Judul Program Tidak Tersedia' }}
                            </h3>

                            <!-- Program Description -->
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $program->description ?? 'Deskripsi tidak tersedia' }}
                            </p>

                            <!-- Program Details -->
                            <div class="space-y-2 mb-6">
                                @if(!empty($program->instructor))
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-lucide="user" class="h-4 w-4 mr-2"></i>
                                    <span>{{ $program->instructor }}</span>
                                </div>
                                @endif
                                
                                @if(!empty($program->price))
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-lucide="tag" class="h-4 w-4 mr-2"></i>
                                    <span class="font-semibold text-blue-600">
                                        {{ $program->formatted_price ?? 'Rp ' . number_format($program->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                @endif

                                @if(!empty($program->max_participants))
                                <div class="flex items-center text-sm text-gray-600">
                                    <i data-lucide="users" class="h-4 w-4 mr-2"></i>
                                    <span>Maks. {{ $program->max_participants }} peserta</span>
                                </div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('programs.show', $program->id) }}" 
                               class="inline-block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Lihat Detail Program
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $programs->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <i data-lucide="search-x" class="h-16 w-16 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Program Tidak Ditemukan</h3>
                    <p class="text-gray-600 mb-6">Tidak ada program yang sesuai dengan pencarian Anda.</p>
                    <a href="{{ route('programs.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Lihat Semua Program
                    </a>
                </div>
            @endif
        </div>
    </section>
</div>

@push('scripts')
<script>
    // Auto submit search form on input
    document.querySelector('input[name="search"]').addEventListener('input', function() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
</script>
@endpush
@endsection