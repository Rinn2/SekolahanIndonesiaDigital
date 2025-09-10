@extends('layouts.dashboard')
@section('title', 'Dashboard Instruktur')

@if(request()->is('instruktur*'))
    @vite('resources/js/instruktur.js')
@endif

@section('content')

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-4 space-y-3 sm:space-y-0">
                <div class="flex items-center space-x-3">
                    <i data-lucide="graduation-cap" class="h-6 w-6 sm:h-8 sm:w-8 text-green-600"></i>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Dashboard Instruktur</h1>
                        <p class="text-xs sm:text-sm text-gray-600">Selamat datang, {{ Auth::user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow-sm p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-full bg-blue-100 flex-shrink-0">
                        <i data-lucide="calendar" class="h-4 w-4 sm:h-6 sm:w-6 text-blue-600"></i>
                    </div>
                    <div class="ml-2 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Jadwal</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $totalMySchedules }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-full bg-green-100 flex-shrink-0">
                        <i data-lucide="play-circle" class="h-4 w-4 sm:h-6 sm:w-6 text-green-600"></i>
                    </div>
                    <div class="ml-2 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Jadwal Aktif</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $activeSchedules }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-full bg-yellow-100 flex-shrink-0">
                        <i data-lucide="users" class="h-4 w-4 sm:h-6 sm:w-6 text-yellow-600"></i>
                    </div>
                    <div class="ml-2 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Siswa</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $totalStudents }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-full bg-purple-100 flex-shrink-0">
                        <i data-lucide="check-circle" class="h-4 w-4 sm:h-6 sm:w-6 text-purple-600"></i>
                    </div>
                    <div class="ml-2 sm:ml-4 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Jadwal Selesai</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $completedSchedules }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-6 sm:mb-8">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h2>
            <div class="space-y-3 sm:space-y-4">
                @foreach($recentActivities as $activity)
                <div class="flex items-center space-x-3">
                    <div class="p-2 rounded-full bg-{{ $activity['color'] }}-100 flex-shrink-0">
                        <i data-lucide="{{ $activity['icon'] }}" class="h-3 w-3 sm:h-4 sm:w-4 text-{{ $activity['color'] }}-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-gray-900">{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Main Content Tabs -->
        <div class="bg-white rounded-lg shadow-sm">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 px-4 sm:px-6">
                <nav class="flex space-x-4 sm:space-x-8 overflow-x-auto" aria-label="Tabs">
                    <button class="tab-button active whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-xs sm:text-sm" data-tab="schedules">
                        <i data-lucide="calendar" class="h-3 w-3 sm:h-4 sm:w-4 inline mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">Jadwal Saya</span>
                        <span class="sm:hidden">Jadwal</span>
                    </button>
                    <button class="tab-button whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-xs sm:text-sm" data-tab="students">
                        <i data-lucide="users" class="h-3 w-3 sm:h-4 sm:w-4 inline mr-1 sm:mr-2"></i>
                        <span>Siswa</span>
                    </button>
                    <button class="tab-button whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-xs sm:text-sm" data-tab="programs">
                        <i data-lucide="book-open" class="h-3 w-3 sm:h-4 sm:w-4 inline mr-1 sm:mr-2"></i>
                        <span>Program</span>
                    </button>
                    <button class="tab-button whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-xs sm:text-sm" data-tab="grades">
                        <i data-lucide="award" class="h-3 w-3 sm:h-4 sm:w-4 inline mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">Input Nilai</span>
                        <span class="sm:hidden">Nilai</span>
                    </button>
                </nav>
            </div>

            <!-- Schedules Tab -->
            <div id="schedules-tab" class="tab-content hidden">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-3 sm:space-y-0">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Jadwal Mengajar</h3>
                        
                    </div>
                    
                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($mySchedules as $schedule)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $schedule->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $schedule->program->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $schedule->location ?? 'Online' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $schedule->enrollments->where('status', 'diterima')->count() }}/{{ $schedule->max_participants ?? 'Unlimited' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $now = now();
                                            $startDate = \Carbon\Carbon::parse($schedule->start_date);
                                            $endDate = \Carbon\Carbon::parse($schedule->end_date);
                                            
                                            if ($now < $startDate) {
                                                $status = 'upcoming';
                                                $statusText = 'Akan Datang';
                                                $statusColor = 'blue';
                                            } elseif ($now >= $startDate && $now <= $endDate) {
                                                $status = 'active';
                                                $statusText = 'Aktif';
                                                $statusColor = 'green';
                                            } else {
                                                $status = 'completed';
                                                $statusText = 'Selesai';
                                                $statusColor = 'gray';
                                            }
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900 view-schedule-btn" data-id="{{ $schedule->id }}">
                                                <i data-lucide="eye" class="h-4 w-4"></i>
                                            </button>
                            
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="lg:hidden space-y-4">
                        @foreach($mySchedules as $schedule)
                        <div class="bg-white border rounded-lg p-4">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-medium text-gray-900 text-sm">{{ $schedule->title }}</h4>
                                @php
                                    $now = now();
                                    $startDate = \Carbon\Carbon::parse($schedule->start_date);
                                    $endDate = \Carbon\Carbon::parse($schedule->end_date);
                                    
                                    if ($now < $startDate) {
                                        $statusText = 'Akan Datang';
                                        $statusColor = 'blue';
                                    } elseif ($now >= $startDate && $now <= $endDate) {
                                        $statusText = 'Aktif';
                                        $statusColor = 'green';
                                    } else {
                                        $statusText = 'Selesai';
                                        $statusColor = 'gray';
                                    }
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                    {{ $statusText }}
                                </span>
                            </div>
                            <div class="space-y-2 text-sm text-gray-600 mb-3">
                                <p><span class="font-medium">Program:</span> {{ $schedule->program->name }}</p>
                                <p><span class="font-medium">Periode:</span> {{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}</p>
                                <p><span class="font-medium">Lokasi:</span> {{ $schedule->location ?? 'Online' }}</p>
                                <p><span class="font-medium">Peserta:</span> {{ $schedule->enrollments->where('status', 'diterima')->count() }}/{{ $schedule->max_participants ?? 'Unlimited' }}</p>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button class="text-blue-600 hover:text-blue-900 view-schedule-btn" data-id="{{ $schedule->id }}">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </button>
                               
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Students Tab -->
            <div id="students-tab" class="tab-content" style="display: none;">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:items-center mb-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Manajemen Siswa</h3>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <select id="filter-schedule" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full sm:w-auto">
                                <option value="">Semua Jadwal</option>
                                @foreach($mySchedules as $schedule)
                                <option value="{{ $schedule->id }}">{{ $schedule->title }}</option>
                                @endforeach
                            </select>
                            <select id="filter-status" class="border border-gray-300 rounded-md px-3 py-2 text-sm w-full sm:w-auto">
                                <option value="">Semua Status</option>
                                <option value="pending">Menunggu</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="lulus">Lulus</option>
                                <option value="tidak_lulus">Tidak Lulus</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Desktop Table -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($enrollments as $enrollment)
                                <tr class="enrollment-row" data-schedule-id="{{ $enrollment->schedule_id }}" data-status="{{ $enrollment->status }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                <span class="text-white text-xs font-medium">{{ substr($enrollment->user->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-3 min-w-0">
                                                <div class="text-sm font-medium text-gray-900 truncate">{{ $enrollment->user->name }}</div>
                                                <div class="text-sm text-gray-500 truncate">{{ $enrollment->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $enrollment->program->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $enrollment->schedule->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($enrollment->status === 'diterima') bg-green-100 text-green-800
                                            @elseif($enrollment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($enrollment->status === 'ditolak') bg-red-100 text-red-800
                                            @elseif($enrollment->status === 'lulus') bg-blue-100 text-blue-800
                                            @elseif($enrollment->status === 'tidak_lulus') bg-gray-100 text-gray-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $enrollment->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($enrollment->final_grade)
                                            <span class="font-medium {{ $enrollment->final_grade >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $enrollment->final_grade }}
                                                @if($enrollment->final_grade >= 90) (A)
                                                @elseif($enrollment->final_grade >= 80) (B)
                                                @elseif($enrollment->final_grade >= 75) (C)
                                                @elseif($enrollment->final_grade >= 60) (D)
                                                @else (E)
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @php
                                            $totalMeetings = $enrollment->program->total_meetings ?? 8;
                                            $completedMeetings = $enrollment->grades->count();
                                            $progressPercentage = $totalMeetings > 0 ? round(($completedMeetings / $totalMeetings) * 100) : 0;
                                        @endphp
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: '{{ $progressPercentage }}%';"></div>
                                            </div>
                                            <span class="ml-2 text-xs">{{ $completedMeetings }}/{{ $totalMeetings }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $enrollment->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900 view-progress-btn" data-id="{{ $enrollment->id }}" title="Lihat Progress">
                                            <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="lg:hidden space-y-4">
                        @foreach($enrollments as $enrollment)
                        <div class="enrollment-row bg-white border rounded-lg p-4" data-schedule-id="{{ $enrollment->schedule_id }}" data-status="{{ $enrollment->status }}">
                            <div class="flex items-center mb-3">
                                <div class="h-10 w-10 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-sm font-medium">{{ substr($enrollment->user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ $enrollment->user->name }}</h4>
                                    <p class="text-xs text-gray-500 truncate">{{ $enrollment->user->email }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($enrollment->status === 'diterima') bg-green-100 text-green-800
                                    @elseif($enrollment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($enrollment->status === 'ditolak') bg-red-100 text-red-800
                                    @elseif($enrollment->status === 'lulus') bg-blue-100 text-blue-800
                                    @elseif($enrollment->status === 'tidak_lulus') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $enrollment->status_label }}
                                </span>
                            </div>
                            
                            <div class="space-y-2 text-sm text-gray-600 mb-3">
                                <p><span class="font-medium">Program:</span> {{ $enrollment->program->name }}</p>
                                <p><span class="font-medium">Jadwal:</span> {{ $enrollment->schedule->title }}</p>
                                <p><span class="font-medium">Tanggal Daftar:</span> {{ $enrollment->created_at->format('d M Y') }}</p>
                                @if($enrollment->final_grade)
                                    <p><span class="font-medium">Nilai Akhir:</span> 
                                        <span class="font-medium {{ $enrollment->final_grade >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $enrollment->final_grade }}
                                            @if($enrollment->final_grade >= 90) (A)
                                            @elseif($enrollment->final_grade >= 80) (B)
                                            @elseif($enrollment->final_grade >= 75) (C)
                                            @elseif($enrollment->final_grade >= 60) (D)
                                            @else (E)
                                            @endif
                                        </span>
                                    </p>
                                @endif
                            </div>

                            @php
                                $totalMeetings = $enrollment->program->total_meetings ?? 8;
                                $completedMeetings = $enrollment->grades->count();
                                $progressPercentage = $totalMeetings > 0 ? round(($completedMeetings / $totalMeetings) * 100) : 0;
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 flex-1">
                                    <span class="text-sm text-gray-600">Progress:</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-24">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $completedMeetings }}/{{ $totalMeetings }}</span>
                                </div>
                                <button class="text-blue-600 hover:text-blue-900 view-progress-btn ml-4" data-id="{{ $enrollment->id }}" title="Lihat Progress">
                                    <i data-lucide="bar-chart-3" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Programs Tab -->
            <div id="programs-tab" class="tab-content" style="display: none;">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Program yang Diampu</h3>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($programs as $program)
                        <div class="bg-white border rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-center mb-4">
                                <div class="p-2 sm:p-3 rounded-full bg-green-100 flex-shrink-0">
                                    <i data-lucide="book-open" class="h-5 w-5 sm:h-6 sm:w-6 text-green-600"></i>
                                </div>
                                <div class="ml-3 sm:ml-4 min-w-0">
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-900 truncate">{{ $program->name }}</h4>
                                    <p class="text-xs sm:text-sm text-gray-500">{{ $program->level }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ Str::limit($program->description, 100) }}</p>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <i data-lucide="users" class="h-3 w-3 sm:h-4 sm:w-4 text-gray-500 mr-1"></i>
                                        <span class="text-xs sm:text-sm text-gray-600">{{ $program->enrollments_count }} siswa</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-lucide="calendar" class="h-3 w-3 sm:h-4 sm:w-4 text-gray-500 mr-1"></i>
                                        <span class="text-xs sm:text-sm text-gray-600">{{ $program->schedules->count() }} jadwal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Grades Tab -->
            <div id="grades-tab" class="tab-content" style="display: none;">
                <div class="p-4 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Input Nilai Siswa</h3>
                    </div>
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
                        
                        <!-- Form Input Nilai -->
                        <div class="bg-white border rounded-lg p-4 sm:p-6 order-2 xl:order-1">
                            <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">Formulir Input Nilai</h4>
                            <form id="grade-form" action="{{ route('instruktur.grades.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="enrollment-select" class="block text-sm font-medium text-gray-700 mb-1">Pilih Siswa</label>
                                    <select id="enrollment-select" name="enrollment_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                                        <option value="">-- Pilih Siswa --</option>
                                        @foreach($enrollments->where('status', 'diterima') as $enrollment)
                                            <option value="{{ $enrollment->id }}">
                                                {{ $enrollment->user->name }} - {{ $enrollment->program->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="meeting-number" class="block text-sm font-medium text-gray-700 mb-1">Pertemuan Ke-</label>
                                    <input type="number" id="meeting-number" name="meeting_number" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" min="1">
                                </div>
                                <div>
                                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Nilai (0-100)</label>
                                    <input type="number" id="grade" name="grade" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" min="0" max="100">
                                </div>
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                    <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm resize-none"></textarea>
                                </div>
                                <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-sm">
                                    <i data-lucide="save" class="h-4 w-4 inline mr-2"></i>
                                    Simpan Nilai
                                </button>
                            </form>
                        </div>

                        <!-- Daftar Nilai -->
                        <div class="bg-white border rounded-lg p-4 sm:p-6 order-1 xl:order-2">
                            <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-4">
                                Daftar Nilai <span id="student-name-for-grades" class="text-blue-600"></span>
                            </h4>
                            <div id="grades-list" class="overflow-x-auto">
                                <p class="text-sm text-gray-500">Pilih siswa untuk melihat nilai yang sudah ada.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection