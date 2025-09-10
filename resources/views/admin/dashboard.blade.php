@extends('layouts.app')
@section('title', 'Dashboard Admin')
@if(request()->is('admin*'))
@vite('resources/js/admin.js')
@vite('resources/js/gallery.js')
@endif
@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <i data-lucide="shield-check" class="h-6 w-6 sm:h-8 sm:w-8 text-blue-600"></i>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                        <p class="text-xs sm:text-sm text-gray-600 hidden sm:block">Selamat datang, {{ Auth::user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-lucide="users" class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400"></i>
                        </div>
                        <div class="ml-3 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                                <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $totalUsers }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-lucide="book-open" class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400"></i>
                        </div>
                        <div class="ml-3 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Program</dt>
                                <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $totalPrograms }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-lucide="user-check" class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400"></i>
                        </div>
                        <div class="ml-3 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Pendaftar Aktif</dt>
                                <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $activeEnrollments }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-lucide="calendar" class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400"></i>
                        </div>
                        <div class="ml-3 sm:ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Jadwal</dt>
                                <dd class="text-base sm:text-lg font-medium text-gray-900">{{ $totalSchedules }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg mb-6 sm:mb-8">
            <div class="px-4 py-4 sm:px-6 sm:py-5">
                <h3 class="text-base sm:text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i data-lucide="activity" class="h-4 w-4 sm:h-5 sm:w-5 inline mr-2"></i>
                    Aktivitas Terbaru
                </h3>
                <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                                <i data-lucide="{{ $activity['icon'] }}" class="h-3 w-3 sm:h-4 sm:w-4 text-{{ $activity['color'] }}-600"></i>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm text-gray-900">{{ $activity['message'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="sm:hidden mb-4">
            <label for="mobile-tab-select" class="sr-only">Select a tab</label>
            <select id="mobile-tab-select" class="block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                <option value="users">Pengguna</option>
                <option value="programs">Program</option>
                <option value="schedules">Jadwal</option>
                <option value="enrollments">Pendaftaran</option>
                <option value="gallery">Galeri</option>
                <option value="certificates">Sertifikat</option>
                <option value="gallery-category">Kategori Galeri</option>

            </select>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-4 sm:space-x-8 px-4 sm:px-6 overflow-x-auto hidden sm:flex" aria-label="Tabs">
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="users">
                        <i data-lucide="users" class="h-4 w-4 inline mr-1"></i>
                        <span class="hidden sm:inline">Pengguna</span>
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="programs">
                        <i data-lucide="book-open" class="h-4 w-4 inline mr-1"></i>
                        <span class="hidden sm:inline">Program</span>
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="schedules">
                        <i data-lucide="calendar" class="h-4 w-4 inline mr-1"></i>
                        <span class="hidden sm:inline">Jadwal</span>
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="enrollments">
                        <i data-lucide="user-check" class="h-4 w-4 inline mr-1"></i>
                        <span class="hidden sm:inline">Pendaftaran</span>
                    </button>
                    <button class="tab-button border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="gallery">
                        <i data-lucide="image" class="h-4 w-4 inline mr-1"></i>
                        <span class="hidden sm:inline">Galeri</span>
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="gallery-category">
                        <i data-lucide="folder" class="h-4 w-4 inline mr-1"></i>
                        <span class="hidden sm:inline">Kategori Galeri</span>
                    </button>
                    <button class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="certificates">
                        <i data-lucide="award" class="h-4 w-4 inline mr-1"></i>
                        <span class="hidden sm:inline">Sertifikat</span>
                    </button>
                </nav>
            </div>
               <!-- Users Tab -->
                <div class="p-4 sm:p-6">
                    <div id="users-tab" class="tab-content hidden">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Manajemen Pengguna</h3>
                            <button id="add-user-btn" class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                <i data-lucide="plus" class="h-4 w-4 inline mr-1"></i>
                                Tambah Pengguna
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">NIK</th>
                                        <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Email</th>
                                        <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Pendaftaran</th>
                                        <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($users as $user)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 text-xs sm:text-sm font-medium">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-2 sm:ml-3">
                                                    <div class="text-xs sm:text-sm font-medium text-gray-900">{{ Str::limit($user->name, 15) }}</div>
                                                    <div class="text-xs text-gray-500 sm:hidden">{{ Str::limit($user->email, 20) }}</div>
                                                    <div class="text-xs text-gray-500 md:hidden">
                                                        NIK: {{ !empty($user->NIK) ? $user->NIK : 'Tidak ada' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden md:table-cell">
                                            <span class="{{ !empty($user->nik) ? 'text-gray-900' : 'text-gray-400 italic' }}">
                                                {{ !empty($user->nik) ? $user->nik : 'Tidak ada' }}
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden sm:table-cell">{{ $user->email }}</td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($user->role == 'admin') bg-red-100 text-red-800
                                            @elseif($user->role == 'instruktur') bg-green-100 text-green-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden lg:table-cell">
                                            {{ $user->enrollments->count() }} program
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-2 sm:mr-3 edit-user-btn" data-id="{{ $user->id }}">
                                                <i data-lucide="edit" class="h-3 w-3 sm:h-4 sm:w-4"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900 delete-user-btn" data-id="{{ $user->id }}">
                                                <i data-lucide="trash-2" class="h-3 w-3 sm:h-4 sm:w-4"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                            Tidak ada pengguna yang ditemukan.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    </div>
                    <div id="user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-bold text-gray-900 mb-4" id="user-modal-title">Tambah Pengguna</h3>
                        <form id="user-form" method="POST" action="/admin/users/update">
                            @csrf
                            <input type="hidden" id="user-id" name="user_id" value="">

                            <div class="mb-4">
                                <label for="user-name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                <input type="text" id="user-name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>

                            <div class="mb-4">
                                <label for="user-email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" id="user-email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="mb-4">
                                <label for="user-nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                <input type="text" id="user-nik" name="nik" maxlength="16" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada NIK</p>
                            </div>
                            <div class="mb-4">
                                <label for="user-password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <input type="password" id="user-password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                            </div>

                            <div class="mb-4">
                                <label for="user-role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <select id="user-role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="instruktur">Instruktur</option>
                                    <option value="peserta">Peserta</option>
                                </select>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" id="close-user-modal" class="px-4 py-2 text-gray-500 hover:text-gray-700 focus:outline-none">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                </div>

                <!-- Programs Tab -->
            <div id="programs-tab" class="tab-content hidden">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Manajemen Program</h3>
                            <button id="add-program-btn" class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                <i data-lucide="plus" class="h-4 w-4 inline mr-1"></i>
                                Tambah Program
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Program</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pertemuan</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Durasi</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Harga</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Peserta</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($programs as $program)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900">{{ Str::limit($program->name, 20) }}</div>
                                            <div class="text-xs text-gray-500">{{ Str::limit($program->description, 30) }}</div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($program->level == 'Pemula') bg-green-100 text-green-800
                                        @elseif($program->level == 'Menengah') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                                {{ $program->level }}
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">{{ $program->total_meetings }} pertemuan</td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden lg:table-cell">{{ $program->duration_months }} bulan</td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden md:table-cell">
                                            @if($program->price > 0)
                                            Rp {{ number_format($program->price, 0, ',', '.') }}
                                            @else
                                            Gratis
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden lg:table-cell">
                                            {{ $program->enrollments->count() }}/{{ $program->max_participants }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-2 sm:mr-3 edit-program-btn" data-id="{{ $program->id }}">
                                                <i data-lucide="edit" class="h-3 w-3 sm:h-4 sm:w-4"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900 delete-program-btn" data-id="{{ $program->id }}">
                                                <i data-lucide="trash-2" class="h-3 w-3 sm:h-4 sm:w-4"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- Program  Modal -->
   <div id="program-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-bold text-gray-900 mb-4" id="program-modal-title">Tambah Program</h3>
                        <form id="program-form">
                            <input type="hidden" id="program-id">
                            <!-- Name Field -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Program</label>
                                <input type="text" id="program-name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <!-- Description Field -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                <textarea id="program-description" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" rows="3"></textarea>
                            </div>
                            <!-- CORRECTED: Total Meetings Field -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Total Pertemuan</label>
                                <input type="number" id="program-total-meetings" name="total_meetings" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" min="1" required>
                            </div>
                            <!-- CORRECTED: Duration Field -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (bulan)</label>
                                <input type="number" id="program-duration" name="duration_months" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" min="1" required>
                            </div>
                            <!-- Level Field -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                                <select id="program-level" name="level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    <option value="">Pilih Level</option>
                                    <option value="Pemula">Pemula</option>
                                    <option value="Menengah">Menengah</option>
                                    <option value="Lanjutan">Lanjutan</option>
                                </select>
                            </div>
                            <!-- Max Participants Field -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Peserta</label>
                                <input type="number" id="program-max-participants" name="max_participants" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" min="1" required>
                            </div>
                            <!-- Price Field -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                                <input type="number" id="program-price" name="price" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" min="0" step="1000">
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika gratis</p>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" id="close-program-modal" class="px-4 py-2 text-gray-500 hover:text-gray-700">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Schedule Tab -->
            <div id="schedules-tab" class="tab-content hidden">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Manajemen Jadwal</h3>
                            <button id="add-schedule-btn" class="bg-blue-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                <i data-lucide="plus" class="h-4 w-4 inline mr-1"></i>
                                Tambah Jadwal
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Program</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Instruktur</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Lokasi</th>
                                        <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($schedules as $schedule)
                                    <tr>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900">{{ Str::limit($schedule->title, 20) }}</div>
                                            <div class="text-xs text-gray-500 md:hidden">
                                                {{ $schedule->program ? Str::limit($schedule->program->name, 15) : 'Program tidak tersedia' }}
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden md:table-cell">
                                            {{ $schedule->program->name ?? 'Program tidak tersedia' }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden lg:table-cell">
                                            {{ $schedule->instructor->name ?? 'Belum ditentukan' }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                            <div>{{ \Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 hidden lg:table-cell">{{ $schedule->location ?? 'Online' }}</td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-2 sm:mr-3 edit-schedule-btn" data-id="{{ $schedule->id }}">
                                                <i data-lucide="edit" class="h-3 w-3 sm:h-4 sm:w-4"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900 delete-schedule-btn" data-id="{{ $schedule->id }}">
                                                <i data-lucide="trash-2" class="h-3 w-3 sm:h-4 sm:w-4"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                     <div id="schedule-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-bold text-gray-900 mb-4" id="schedule-modal-title">Tambah Jadwal</h3>
                        <form id="schedule-form">
                            <input type="hidden" id="schedule-id">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                                <input type="text" id="schedule-title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Program</label>
                                <select id="schedule-program" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                    <option value="">Pilih Program</option>
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Instruktur</label>
                                <select id="schedule-instructor" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="">Pilih Instruktur</option>
                                    @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                                <input type="date" id="schedule-start-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                                <input type="date" id="schedule-end-date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                                <input type="text" id="schedule-location" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Kosongkan jika online">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Peserta</label>
                                <input type="number" id="schedule-max-participants" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" min="1">
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" id="close-schedule-modal" class="px-4 py-2 text-gray-500 hover:text-gray-700">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="gallery-tab" class="tab-content hidden">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold text-gray-800">Manajemen Galeri</h2>
                                <button onclick="openModal('addGalleryModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Galeri Baru
                                </button>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                                <div class="flex-1">
                                    <input type="text" id="gallerySearch" placeholder="Cari galeri..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <select id="galleryCategoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Kategori</option>
                                    {{-- CORRECTED: Use the correct variable $gallery_categories --}}
                                    @foreach($gallery_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($galleries as $gallery)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="w-16 h-16 object-cover rounded-lg">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $gallery->title }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($gallery->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
        {{ $gallery->category->name ?? 'Tanpa Kategori' }}
                                                </span>
                                            </td>
                                           <td class="px-6 py-4 whitespace-nowrap">
                                                    <button onclick="toggleGalleryStatus({{ $gallery->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $gallery->is_active ? 'bg-green-600' : 'bg-gray-300' }}" title="{{ $gallery->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $gallery->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                                    </button>
                                                    <span class="ml-2 text-xs text-gray-500">{{ $gallery->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                                </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button onclick="editGallery({{ $gallery->id }})" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-colors" title="Edit">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </button>
                                                    <button onclick="deleteGallery('{{ $gallery->id }}')" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-gray-500"> Tidak ada galeri yang tersedia </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="gallery-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-bold text-gray-900 mb-4" id="gallery-modal-title">Tambah Gambar Galeri</h3>
                        <form id="gallery-form" action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="gallery-id-1">
                            <div class="mb-4">
                                <label for="gallery-title-1" class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                                {{-- FIX 1: Tambahkan name="title" --}}
                                <input type="text" id="gallery-title-1" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="mb-4">
                                <label for="gallery-description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                                {{-- FIX 2: Tambahkan name="description" --}}
                                <textarea id="gallery-description" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="gallery-image-2" class="block text-sm font-medium text-gray-700">Upload Gambar</label>
                                <input type="file" id="gallery-image-2" name="image" accept="image/*" class="mt-1 block w-full border border-gray-300 rounded-md">
                                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, maksimal 5MB</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                                {{-- FIX 3: Ganti name="gallery_category_id" menjadi "category_id" --}}
                                <select id="gallery-category" name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($gallery_categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" id="close-gallery-modal" class="px-4 py-2 text-gray-500 hover:text-gray-700">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




                    
            <div id="certificates-tab" class="tab-content hidden p-4 md:p-6 bg-white rounded-lg shadow-md">

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        Manajemen Sertifikat
                    </h3>
                    <button id="add-certificate-btn" class="inline-flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors text-sm font-medium mt-3 sm:mt-0">
                        <i data-lucide="plus" class="h-4 w-4 mr-2"></i>
                        Tambah Sertifikat
                    </button>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nomor Sertifikat
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Peserta
                                </th>
                                <th scope="col" class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Program
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Terbit
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>

                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($certificates as $cert)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $cert->certificate_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $cert->user->name }}
                                </td>
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $cert->program->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($cert->issue_date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 text-xs font-semibold leading-5 rounded-full {{ $cert->status == 'Kompeten' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $cert->status }}
                                    </span>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                    Belum ada sertifikat yang diterbitkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 sm:p-6">
 
                    

                    
                    <div id="enrollments-tab" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Manajemen Pendaftaran</h3>
                            <div class="flex space-x-2">
                                <select id="enrollment-filter" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="ditolak">Ditolak</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="dropout">Dropout</option>
                                </select>
                                <button id="refresh-enrollments" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
                                    <i data-lucide="refresh-cw" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($enrollments as $enrollment)
                                    <tr class="enrollment-row" data-status="{{ $enrollment->status }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">

                                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                    <span class="text-gray-600 text-sm font-medium">
                                                        {{ optional($enrollment->user)->name ? substr($enrollment->user->name, 0, 1) : '?' }}
                                                    </span>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ optional($enrollment->user)->name ?? 'User tidak ditemukan' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ optional($enrollment->user)->email ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ optional($enrollment->program)->name ?? 'Program tidak ditemukan' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                @if(optional($enrollment->program)->price > 0)
                                                Rp {{ number_format($enrollment->program->price, 0, ',', '.') }}
                                                @else
                                                Gratis
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($enrollment->status == 'diterima') bg-green-100 text-green-800
                                        @elseif($enrollment->status == 'ditolak') bg-red-100 text-red-800
                                        @elseif($enrollment->status == 'lulus') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                                @switch($enrollment->status)
                                                @case('pending') Menunggu Pembayaran @break
                                                @case('diterima') Diterima @break
                                                @case('ditolak') Ditolak @break
                                                @case('lulus') Lulus @break
                                                @case('dropout') Dropout @break
                                                @case('tidak_lulus') Tidak Lulus @break
                                                @default {{ ucfirst($enrollment->status) }}
                                                @endswitch
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $enrollment->enrollment_date ? \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-3 edit-enrollment-btn" data-id="{{ $enrollment->id }}">
                                                <i data-lucide="edit" class="h-4 w-4"></i>
                                            </button>
                                            @if($enrollment->status == 'pending' && optional($enrollment->program)->price > 0)
                                            <button class="text-green-600 hover:text-green-900 mr-3 force-approve-btn" data-id="{{ $enrollment->id }}">
                                                <i data-lucide="check-circle" class="h-4 w-4"></i>
                                            </button>
                                            @endif
                                            <button class="text-red-600 hover:text-red-900 delete-enrollment-btn" data-id="{{ $enrollment->id }}">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>

                    
            <div id="certificate-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900" id="certificate-modal-title">Terbitkan Sertifikat Program</h3>
                            <button type="button" id="close-certificate-modal" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="h-6 w-6"></i>
                            </button>
                        </div>
                        <form id="certificate-form">
                            @csrf
                            <div id="certificate-page-1">
                                <p class="text-sm text-gray-600 mb-4">Langkah 1 dari 2: Pilih Program dan Detail Sertifikat</p>
                                <div class="space-y-4">
                                    <div>
                                        <label for="certificate-program" class="block text-sm font-medium text-gray-700 mb-1">Program Pelatihan</label>
                                        <select id="certificate-program" name="program_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="">-- Pilih Program --</option>
                                            @foreach($programs as $program)
                                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Sertifikat akan diterbitkan untuk semua peserta yang 'lulus' dari program ini.</p>
                                    </div>
                                    <div>
                                        <label for="certificate-issue-date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Terbit</label>
                                        <input type="date" id="certificate-issue-date" name="issue_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label for="certificate-status" class="block text-sm font-medium text-gray-700 mb-1">Status Kelulusan</label>
                                        <select id="certificate-status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                            <option value="Kompeten">Kompeten</option>
                                            <option value="Belum Kompeten">Belum Kompeten</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-end mt-6">
                                    <button type="button" id="next-to-page-2" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Selanjutnya</button>
                                </div>
                            </div>

                            <div id="certificate-page-2" class="hidden">
                                <p class="text-sm text-gray-600 mb-4">Langkah 2 dari 2: Pilih Unit Kompetensi</p>

                                {{-- Form untuk menambah unit kompetensi --}}
                                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Tambah Unit Kompetensi</h4>
                                    <div class="space-y-3">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label for="category-select" class="block text-xs text-gray-600 mb-1">Kategori Kompetensi</label>
                                                <select id="category-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                                                    <option value="">-- Pilih Kategori --</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category }}">{{ $category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label for="unit-select" class="block text-xs text-gray-600 mb-1">Unit Kompetensi</label>
                                                <select id="unit-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" disabled>
                                                    <option value="">-- Pilih kategori terlebih dahulu --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="button" id="add-selected-unit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                                                <i data-lucide="plus" class="h-4 w-4 inline mr-1"></i>Tambah Unit
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Filter berdasarkan kategori yang sudah dipilih --}}
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Filter berdasarkan Kategori:</h4>
                                    <div id="category-filter" class="flex flex-wrap gap-2 mb-3">
                                    </div>
                                </div>

                                {{-- Daftar unit kompetensi yang dipilih --}}
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-sm font-medium text-gray-700">Unit Kompetensi yang Dipilih:</h4>
                                        <span id="selected-units-count" class="text-xs text-gray-500">0 unit dipilih</span>
                                    </div>
                                    <div id="cert-competency-units-list" class="space-y-2 max-h-80 overflow-y-auto pr-2 border rounded-md p-3 bg-gray-50">
                                        <p class="text-gray-500 text-center text-sm" id="empty-units-message">Belum ada unit yang dipilih.</p>
                                    </div>
                                </div>

                                <div class="flex justify-between mt-6">
                                    <button type="button" id="back-to-page-1" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">Kembali</button>
                                    <button type="submit" id="submit-certificate" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>Terbitkan Sertifikat</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
         
           

            <div id="enrollment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-bold text-gray-900 mb-4" id="enrollment-modal-title">Edit Status Pendaftaran</h3>
                        <form id="enrollment-form">
                            <input type="hidden" id="enrollment-id">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="enrollment-status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="ditolak">Ditolak</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="dropout">Dropout</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="enrollment-notes" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Catatan opsional"></textarea>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" id="close-enrollment-modal" class="px-4 py-2 text-gray-500 hover:text-gray-700">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

{{-- Hapus div duplikat dan gunakan versi yang lebih lengkap ini --}}
<div id="gallery-category-tab" class="tab-content hidden p-4 sm:p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Kategori Galeri</h2>
        {{-- FIX: Ganti openModal() menjadi openCategoryModal() agar sesuai dengan JS --}}
        <button onclick="openCategoryModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <i data-lucide="plus" class="h-5 w-5 mr-2"></i>
            Tambah Kategori Baru
        </button>
    </div>
    
    <div class="mb-6">
        <input type="text" id="categorySearch" placeholder="Cari kategori..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($gallery_categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ Str::limit($category->description, 50) }}</div>
                        </td>
                       
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="toggleCategoryStatus({{ $category->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {{ $category->is_active ? 'bg-green-600' : 'bg-gray-300' }}" title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $category->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                            <span class="ml-2 text-xs text-gray-500">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="editCategory({{ $category->id }})" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-colors" title="Edit">
                                    <i data-lucide="edit" class="h-5 w-5"></i>
                                </button>
                                {{-- FIX: Perbaiki pemanggilan fungsi onclick --}}
                                <button onclick="deleteCategory({{ $category->id }})" class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="h-5 w-5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada kategori yang tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<div id="gallery-category-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4" id="gallery-category-modal-title">Tambah Kategori Galeri</h3>
            <form id="gallery-category-form">
                @csrf
                <input type="hidden" id="gallery-category-id" name="gallery_category_id" value="">
                
                <div class="mb-4">
                    <label for="gallery-category-name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" id="gallery-category-name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label for="gallery-category-description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="gallery-category-description" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Deskripsi kategori (opsional)"></textarea>
                </div>
                
                <div class="mb-4">
                    <label for="gallery-category-status" class="flex items-center">
                        <input type="checkbox" id="gallery-category-status" name="is_active" value="1" checked class="mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Status Aktif</span>
                    </label>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" id="close-gallery-category-modal" class="px-4 py-2 text-gray-500 hover:text-gray-700 focus:outline-none">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
// Gallery Category Management Functions
document.addEventListener('DOMContentLoaded', function() {
    // Function to open category modal (for add/edit)
    window.openCategoryModal = function(id = null) {
        const modal = document.getElementById('gallery-category-modal');
        const form = document.getElementById('gallery-category-form');
        const title = document.getElementById('gallery-category-modal-title');
        const idInput = document.getElementById('gallery-category-id');
        const nameInput = document.getElementById('gallery-category-name');
        const descriptionInput = document.getElementById('gallery-category-description');
        const statusInput = document.getElementById('gallery-category-status');

        // Reset form
        form.reset();
        idInput.value = '';
        statusInput.checked = true;

        if (id) {
            // Edit mode
            title.textContent = 'Edit Kategori Galeri';
            
            // Fetch category data (you'll need to implement this endpoint)
            fetch(`/admin/gallery-categories/${id}/edit`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const category = data.data;
                    idInput.value = category.id;
                    nameInput.value = category.name;
                    descriptionInput.value = category.description || '';
                    statusInput.checked = category.is_active;
                }
            })
            .catch(error => {
                console.error('Error fetching category:', error);
                alert('Gagal mengambil data kategori');
            });
        } else {
            // Add mode
            title.textContent = 'Tambah Kategori Galeri';
        }

        modal.classList.remove('hidden');
    };

    // Handle form submission
    const categoryForm = document.getElementById('gallery-category-form');
    if (categoryForm) {
        categoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const id = document.getElementById('gallery-category-id').value;
            
            // Convert FormData to JSON
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            
            // Handle checkbox
            data.is_active = document.getElementById('gallery-category-status').checked ? 1 : 0;
            
            const url = id ? `/admin/gallery-categories/${id}` : '/admin/gallery-categories';
            const method = id ? 'PUT' : 'POST';
            
            if (id) {
                data._method = 'PUT';
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById('gallery-category-modal').classList.add('hidden');
                    location.reload(); // Reload to show updated data
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menyimpan kategori');
            });
        });
    }

    // Close modal handlers
    document.getElementById('close-gallery-category-modal').addEventListener('click', function() {
        document.getElementById('gallery-category-modal').classList.add('hidden');
    });

    // Click outside to close
    document.getElementById('gallery-category-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Search functionality
    const categorySearch = document.getElementById('categorySearch');
    if (categorySearch) {
        categorySearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#gallery-category-tab tbody tr');
            
            tableRows.forEach(row => {
                if (row.querySelector('td[colspan]')) return; // Skip empty state row
                
                const name = row.cells[0].textContent.toLowerCase();
                const description = row.cells[1].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || description.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});

// Global functions for inline onclick handlers
window.editCategory = function(id) {
    openCategoryModal(id);
};

window.deleteCategory = function(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus kategori ini? Galeri dalam kategori ini mungkin akan terpengaruh.')) {
        return;
    }
    
    fetch(`/admin/gallery-categories/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal menghapus kategori');
    });
};

window.toggleCategoryStatus = function(id) {
    fetch(`/admin/gallery-categories/${id}/toggle-status`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI without full reload
            const button = document.querySelector(`button[onclick="toggleCategoryStatus(${id})"]`);
            const statusText = button.nextElementSibling;
            
            if (data.data.is_active) {
                button.classList.remove('bg-gray-300');
                button.classList.add('bg-green-600');
                button.querySelector('span').classList.remove('translate-x-1');
                button.querySelector('span').classList.add('translate-x-6');
                statusText.textContent = 'Aktif';
            } else {
                button.classList.remove('bg-green-600');
                button.classList.add('bg-gray-300');
                button.querySelector('span').classList.remove('translate-x-6');
                button.querySelector('span').classList.add('translate-x-1');
                statusText.textContent = 'Nonaktif';
            }
            
            if (data.data.affected_galleries > 0) {
                alert(data.message);
            }
        } else {
            alert(data.message || 'Gagal mengubah status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengubah status kategori');
    });
};
</script>
            



            <style>
                .tab-button.active {
                    border-color: #3b82f6;
                    color: #3b82f6;
                }

                .tab-button {
                    border-color: transparent;
                    color: #6b7280;
                    transition: all 0.2s;
                }

                .tab-button:hover {
                    color: #374151;
                }

                .tab-content {
                    display: none;
                }

                .tab-content.active {
                    display: block;
                }

                .modal {
                    backdrop-filter: blur(4px);
                }

                .table-hover tbody tr:hover {
                    background-color: #f9fafb;
                }

                /* Loading spinner */
                .loading {
                    opacity: 0.6;
                    pointer-events: none;
                }

                .loading::after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 20px;
                    height: 20px;
                    border: 2px solid #f3f4f6;
                    border-top: 2px solid #3b82f6;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% {
                        transform: translate(-50%, -50%) rotate(0deg);
                    }

                    100% {
                        transform: translate(-50%, -50%) rotate(360deg);
                    }
                }
            </style>
            @endsection