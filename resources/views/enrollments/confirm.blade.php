@extends('layouts.app')

@section('title', 'Konfirmasi Pendaftaran - ' . $program->title)

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
                <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Pendaftaran</h1>
            </div>
            <p class="text-gray-600">Pastikan informasi program yang akan Anda daftarkan sudah benar.</p>
        </div>

        <!-- Program Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Detail Program</h2>
            
            @if($program->image)
                <img src="{{ asset('storage/' . $program->image) }}" alt="{{ $program->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
            @endif
            
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Nama Program</label>
                    <p class="text-lg font-semibold text-gray-800">{{ $program->title }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Deskripsi</label>
                    <p class="text-gray-700">{{ $program->description }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Durasi</label>
                        <p class="text-gray-800">{{ $program->duration }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Harga</label>
                        <p class="text-xl font-bold {{ $program->price > 0 ? 'text-green-600' : 'text-blue-600' }}">
                            {{ $program->price > 0 ? 'Rp ' . number_format($program->price, 0, ',', '.') : 'GRATIS' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Form Pendaftaran</h2>
            
            <form action="{{ route('enrollments.store', $program) }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Schedule Selection -->
                @if($schedules->count() > 0)
                    <div>
                        <label for="schedule_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Jadwal <span class="text-red-500">*</span>
                        </label>
                        <select name="schedule_id" id="schedule_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">-- Pilih Jadwal --</option>
                            @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                    {{ $schedule->name }} 
                                    @if($schedule->start_date && $schedule->end_date)
                                        - {{ Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }} s/d {{ Carbon\Carbon::parse($schedule->end_date)->format('d M Y') }}
                                    @elseif($schedule->start_date)
                                        - Mulai {{ Carbon\Carbon::parse($schedule->start_date)->format('d M Y') }}
                                    @else
                                        - Jadwal akan ditentukan
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('schedule_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
             

                <!-- Terms & Conditions -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                        <label for="terms" class="ml-2 text-sm text-gray-700">
                            Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat dan ketentuan</a> yang berlaku dan bertanggung jawab atas informasi yang saya berikan.
                        </label>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex space-x-4 pt-4">
                    <a href="{{ route('programs.show', $program) }}" class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition duration-200 text-center">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 font-semibold">
                        {{ $program->price > 0 ? 'Lanjut ke Pembayaran' : 'Daftar Sekarang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-select first schedule if only one available
    document.addEventListener('DOMContentLoaded', function() {
        const scheduleSelect = document.getElementById('schedule_id');
        if (scheduleSelect && scheduleSelect.options.length === 2) {
            scheduleSelect.selectedIndex = 1;
        }
    });
</script>
@endsection