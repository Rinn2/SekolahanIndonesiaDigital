@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Profil Saya</h1>
            <p class="text-gray-600">Kelola informasi profil dan pengaturan akun Anda</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            @if($user->hasDocument('pasfoto'))
                                <img src="{{ $user->getDocumentUrl('pasfoto') }}" alt="Pasfoto" class="w-24 h-24 rounded-full object-cover">
                            @else
                                <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-1">{{ $user->name }}</h2>
                        <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-500">Bergabung sejak</p>
                            <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Dokumen</h3>
                    <div class="space-y-4">

                        
                        <div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Pasfoto</span>
                                @if($user->hasDocument('pasfoto'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Lengkap
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        Belum
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">KTP</span>
                                @if($user->hasDocument('ktp'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Lengkap
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        Belum
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">Ijazah</span>
                                @if($user->hasDocument('ijazah_terakhir'))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Lengkap
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        Belum
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex">
                            <button class="tab-button active py-4 px-6 text-sm font-medium text-blue-600 border-b-2 border-blue-600 focus:outline-none" data-tab="profile-tab">
                                Profil
                            </button>
                            <button class="tab-button py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent focus:outline-none" data-tab="documents-tab">
                                Dokumen
                            </button>
                        </nav>
                    </div>

                    <div id="profile-tab" class="tab-content active p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Profil</h3>
                        {{-- Form Edit Profil (tidak ada perubahan di sini) --}}
                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name', $user->name) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                    required>
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                                <input type="text"
                                    name="nik"
                                    id="nik"
                                    value="{{ old('nik', $user->nik) }}"
                                    placeholder="Nomor Induk Kependudukan (16 digit)"
                                    maxlength="16"
                                    pattern="[0-9]{16}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nik') border-red-500 @enderror">
                                @error('nik')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Masukkan 16 digit angka NIK</p>
                            </div>

                            <div>
                                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                <select name="pekerjaan"
                                    id="pekerjaan"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pekerjaan') border-red-500 @enderror">
                                    <option value="">-- Pilih Pekerjaan --</option>
                                    @php
                                    $pekerjaanList = [
                                    'Belum/Tidak Bekerja','Mengurus Rumah Tangga','Pelajar/Mahasiswa','Pensiunan','Pegawai Negeri Sipil','Industri','Kontruksi','Transportasi','Karyawan Swasta','Karyawan BUMN','Karyawan BUMD','Karyawan Honorer','Dosen','Guru','Arsitek','Akuntan','Pialang','Wiraswasta','Lainnya',
                                    ];
                                    @endphp
                                    @foreach($pekerjaanList as $pekerjaan)
                                    <option value="{{ $pekerjaan }}" {{ old('pekerjaan', $user->pekerjaan) == $pekerjaan ? 'selected' : '' }}>
                                        {{ $pekerjaan }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('pekerjaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="programstudi" class="block text-sm font-medium text-gray-700 mb-2">Program Studi</label>
                                <input type="text"
                                    name="programstudi"
                                    id="programstudi"
                                    value="{{ old('programstudi', $user->programstudi) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('programstudi') border-red-500 @enderror">
                                @error('programstudi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                    required>
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="tel"
                                    name="phone"
                                    id="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="08xxxxxxxxxx"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date"
                                    name="birth_date"
                                    id="birth_date"
                                    value="{{ old('birth_date', $user->birth_date ? (\Illuminate\Support\Carbon::parse($user->birth_date))->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('birth_date') border-red-500 @enderror">
                                @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                                <select name="gender"
                                    id="gender"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gender') border-red-500 @enderror">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('gender', $user->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', $user->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="education" class="block text-sm font-medium text-gray-700 mb-2">Pendidikan</label>
                                <select name="education"
                                    id="education"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('education') border-red-500 @enderror">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="SD" {{ old('education', $user->education) === 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('education', $user->education) === 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA/SMK" {{ old('education', $user->education) === 'SMA/SMK' ? 'selected' : '' }}>SMA</option>
                                    <option value="D3" {{ old('education', $user->education) === 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('education', $user->education) === 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('education', $user->education) === 'S2' ? 'selected' : '' }}>S2</option>
                                </select>
                                @error('education')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <textarea name="address"
                                    id="address"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-6 border-t border-gray-200">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Ubah Password</h4>
                                <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                        <input type="password"
                                            name="password"
                                            id="password"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                                        @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                        <input type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-6">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="documents-tab" class="tab-content hidden p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Upload Dokumen</h3>
                        <p class="text-sm text-gray-600 mb-6">Upload dokumen yang diperlukan untuk melengkapi profil Anda.</p>

                        <div class="space-y-8">
                            <div class="border rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">Pasfoto</h4>
                                        <p class="text-sm text-gray-600">Upload foto formal dengan latar belakang putih</p>
                                    </div>
                                    @if($user->hasDocument('pasfoto'))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Sudah Upload
                                        </span>
                                    @endif
                                </div>

                                @if($user->hasDocument('pasfoto'))
                                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $user->getDocumentUrl('pasfoto') }}" alt="Pasfoto" class="w-16 h-16 rounded-lg object-cover">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Pasfoto.jpg</p>
                                                    <p class="text-xs text-gray-500">Diupload: {{ $user->documents->pasfoto_uploaded_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('profile.document.view', ['document_type' => 'pasfoto']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</a>
                                                <a href="{{ route('profile.document.download', ['document_type' => 'pasfoto']) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Download</a>
                                                <form action="{{ route('profile.document.delete') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pasfoto?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="document_type" value="pasfoto">
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('profile.document.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                                    @csrf
                                    <input type="hidden" name="document_type" value="pasfoto">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-4">
                                            <label for="pasfoto" class="cursor-pointer">
                                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                                    {{ $user->hasDocument('pasfoto') ? 'Upload Pasfoto Baru' : 'Upload Pasfoto' }}
                                                </span>
                                                <span class="mt-2 block text-sm text-gray-500">atau drag and drop</span>
                                                <span class="mt-1 block text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</span>
                                            </label>
                                            <input id="pasfoto" name="document_file" type="file" accept="image/*" class="sr-only" onchange="handleFileSelect(this, 'pasfoto')">
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors disabled:opacity-50" disabled>
                                            Upload Pasfoto
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="border rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">KTP</h4>
                                        <p class="text-sm text-gray-600">Upload scan atau foto KTP yang jelas dan terbaca</p>
                                    </div>
                                    @if($user->hasDocument('ktp'))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Sudah Upload
                                        </span>
                                    @endif
                                </div>

                                @if($user->hasDocument('ktp'))
                                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 12V6H4v10h12z" clip-rule="evenodd" /></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">KTP.pdf</p>
                                                    <p class="text-xs text-gray-500">Diupload: {{ $user->documents->ktp_uploaded_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('profile.document.view', ['document_type' => 'ktp']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</a>
                                                <a href="{{ route('profile.document.download', ['document_type' => 'ktp']) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Download</a>
                                                <form action="{{ route('profile.document.delete') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus KTP?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="document_type" value="ktp">
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('profile.document.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                                    @csrf
                                    <input type="hidden" name="document_type" value="ktp">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-4">
                                            <label for="ktp" class="cursor-pointer">
                                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                                    {{ $user->hasDocument('ktp') ? 'Upload KTP Baru' : 'Upload KTP' }}
                                                </span>
                                                <span class="mt-2 block text-sm text-gray-500">atau drag and drop</span>
                                                <span class="mt-1 block text-xs text-gray-500">PNG, JPG, JPEG, PDF hingga 2MB</span>
                                            </label>
                                            <input id="ktp" name="document_file" type="file" accept="image/*,.pdf" class="sr-only" onchange="handleFileSelect(this, 'ktp')">
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors disabled:opacity-50" disabled>
                                            Upload KTP
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="border rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">Ijazah Terakhir</h4>
                                        <p class="text-sm text-gray-600">Upload scan atau foto ijazah pendidikan terakhir</p>
                                    </div>
                                    @if($user->hasDocument('ijazah_terakhir'))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Sudah Upload
                                        </span>
                                    @endif
                                </div>

                                @if($user->hasDocument('ijazah_terakhir'))
                                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 12V6H4v10h12z" clip-rule="evenodd" /></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Ijazah.pdf</p>
                                                    <p class="text-xs text-gray-500">Diupload: {{ $user->documents->ijazah_uploaded_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('profile.document.view', ['document_type' => 'ijazah_terakhir']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat</a>
                                                <a href="{{ route('profile.document.download', ['document_type' => 'ijazah_terakhir']) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Download</a>
                                                <form action="{{ route('profile.document.delete') }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus ijazah?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="document_type" value="ijazah_terakhir">
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('profile.document.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                                    @csrf
                                    <input type="hidden" name="document_type" value="ijazah_terakhir">
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="mt-4">
                                            <label for="ijazah" class="cursor-pointer">
                                                <span class="mt-2 block text-sm font-medium text-gray-900">
                                                    {{ $user->hasDocument('ijazah_terakhir') ? 'Upload Ijazah Baru' : 'Upload Ijazah' }}
                                                </span>
                                                <span class="mt-2 block text-sm text-gray-500">atau drag and drop</span>
                                                <span class="mt-1 block text-xs text-gray-500">PNG, JPG, JPEG, PDF hingga 2MB</span>
                                            </label>
                                            <input id="ijazah" name="document_file" type="file" accept="image/*,.pdf" class="sr-only" onchange="handleFileSelect(this, 'ijazah')">
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors disabled:opacity-50" disabled>
                                            Upload Ijazah
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alert messages after 5 seconds
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 5000);
        });

        // Tab functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');

                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active', 'text-blue-600', 'border-blue-600'));
                tabButtons.forEach(btn => btn.classList.add('text-gray-500', 'border-transparent'));
                tabContents.forEach(content => content.classList.add('hidden'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to clicked button
                button.classList.add('active', 'text-blue-600', 'border-blue-600');
                button.classList.remove('text-gray-500', 'border-transparent');

                // Show target content
                const targetContent = document.getElementById(targetTab);
                targetContent.classList.remove('hidden');
                targetContent.classList.add('active');
            });
        });

        // Profile form validation
        const profileForm = document.querySelector('#profile-tab form');
        if (profileForm) {
            const submitButton = profileForm.querySelector('button[type="submit"]');

            profileForm.addEventListener('submit', function(e) {
                submitButton.disabled = true;
                submitButton.textContent = 'Menyimpan...';

                // Re-enable button after 3 seconds in case of errors
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Simpan Perubahan';
                }, 3000);
            });
        }

        // Upload form handling
        const uploadForms = document.querySelectorAll('.upload-form');
        uploadForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Mengupload...';
                
                setTimeout(() => {
                    submitButton.disabled = false;
                    const documentType = form.querySelector('input[name="document_type"]').value;
                    submitButton.textContent = `Upload ${documentType === 'pasfoto' ? 'Pasfoto' : documentType === 'ktp' ? 'KTP' : 'Ijazah'}`;
                }, 5000);
            });
        });
    });

    // File selection handler
    function handleFileSelect(input, documentType) {
        const file = input.files[0];
        const form = input.closest('form');
        const submitButton = form.querySelector('button[type="submit"]');
        
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file tidak boleh lebih dari 2MB');
                input.value = '';
                submitButton.disabled = true;
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, atau PDF');
                input.value = '';
                submitButton.disabled = true;
                return;
            }

            // Enable submit button
            submitButton.disabled = false;
            
            // Update drop zone text
            const dropZone = form.querySelector('.border-dashed');
            const label = dropZone.querySelector('label span');
            label.textContent = `File dipilih: ${file.name}`;
            dropZone.classList.add('border-blue-400', 'bg-blue-50');
        } else {
            submitButton.disabled = true;
        }
    }

    // Drag and drop functionality
    document.querySelectorAll('.border-dashed').forEach(dropZone => {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        dropZone.addEventListener('drop', handleDrop, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        e.currentTarget.classList.add('border-blue-400', 'bg-blue-50');
    }

    function unhighlight(e) {
        e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        const fileInput = e.currentTarget.querySelector('input[type="file"]');
        
        if (files.length > 0) {
            fileInput.files = files;
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    }
</script>
@endsection