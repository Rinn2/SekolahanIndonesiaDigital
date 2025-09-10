@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
    <!-- Hero Section -->
    <section class="py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                Sistem Informasi
                <span class="bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent block">
                    Pelatihan LPK
                </span>
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Platform terintegrasi untuk manajemen pelatihan kerja yang efisien. 
                Tingkatkan kualitas SDM melalui pelatihan terstruktur dan pelaporan yang akurat.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <!-- Tombol untuk user yang sudah login -->
                    <a href="{{ route('programs.index') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Lihat Program Pelatihan
                    </a>
                    @if(auth()->user()->role === 'student')
                        <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('programs.index') }}" class="inline-block px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Lihat Program
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Mulai Belajar Sekarang
                    </a>
                    <a href="{{ route('programs.index') }}" class="inline-block px-8 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Lihat Program
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white/50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ $stats['graduates'] }}+</div>
                    <div class="text-gray-600">Peserta Lulus</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ $stats['programs'] }}+</div>
                    <div class="text-gray-600">Program Pelatihan</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">{{ $stats['instructors'] }}+</div>
                    <div class="text-gray-600">Instruktur Ahli</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-orange-600 mb-2">{{ $stats['satisfaction'] }}%</div>
                    <div class="text-gray-600">Tingkat Kepuasan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Perkembangan Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Perjalanan & Pencapaian Kami</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Melihat kembali perjalanan panjang LPK dalam mengembangkan SDM berkualitas
                </p>
            </div>
            
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-blue-200 h-full"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-12">
                    <!-- 2024 Item -->
                    <div class="relative flex items-center">
                        <div class="flex-1 pr-8 text-right">
                            <!-- Empty space for right alignment -->
                        </div>
                        <div class="relative z-10 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                            <i data-lucide="monitor" class="h-6 w-6 text-white"></i>
                        </div>
                        <div class="flex-1 pl-8">
                            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
                                <div class="text-2xl font-bold text-blue-600 mb-2">2024</div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Percetakan dan Penerbit Modul Digital</h3>
                                <p class="text-gray-600">Sebagai percetakan dan penerbit modul pembelajaran digital dengan kurikulum yang up-to-date.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2023 Item -->
                    <div class="relative flex items-center">
                        <div class="flex-1 pr-8 text-right">
                            <div class="bg-white rounded-lg shadow-md p-6 border-r-4 border-green-600">
                                <div class="text-2xl font-bold text-green-600 mb-2">2023</div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Lembaga Kursus dan Pelatihan</h3>
                                <p class="text-gray-600">
                                    Lembaga Pelatihan Kerja = LKP Sekolahan Indonesia Digital<br>
                                    Divisi Konten Kreator dan Divisi Pelatihan Fiber Optik
                                </p>
                            </div>
                        </div>
                        <div class="relative z-10 w-12 h-12 bg-green-600 rounded-full flex items-center justify-center">
                            <i data-lucide="graduation-cap" class="h-6 w-6 text-white"></i>
                        </div>
                        <div class="flex-1 pl-8">
                            <!-- Empty space for left alignment -->
                        </div>
                    </div>

                    <!-- 2022 Item -->
                    <div class="relative flex items-center">
                        <div class="flex-1 pr-8 text-right">
                            <!-- Empty space for right alignment -->
                        </div>
                        <div class="relative z-10 w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center">
                            <i data-lucide="wifi" class="h-6 w-6 text-white"></i>
                        </div>
                        <div class="flex-1 pl-8">
                            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-600">
                                <div class="text-2xl font-bold text-purple-600 mb-2">2022</div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Memperkuat Jaringan Fiber Optik</h3>
                                <p class="text-gray-600">Membangun jaringan Fiber Optik di Jawa Barat serta memiliki tim teknik FO dan OSP.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2021 Item -->
                    <div class="relative flex items-center">
                        <div class="flex-1 pr-8 text-right">
                            <div class="bg-white rounded-lg shadow-md p-6 border-r-4 border-orange-600">
                                <div class="text-2xl font-bold text-orange-600 mb-2">2021</div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Internet Service Provider</h3>
                                <p class="text-gray-600">Memulai SIDNET dengan kualitas internet yang cepat dan menjangkau banyak wilayah.</p>
                            </div>
                        </div>
                        <div class="relative z-10 w-12 h-12 bg-orange-600 rounded-full flex items-center justify-center">
                            <i data-lucide="globe" class="h-6 w-6 text-white"></i>
                        </div>
                        <div class="flex-1 pl-8">
                            <!-- Empty space for left alignment -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Tentang LPK Kami</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Lembaga Pelatihan Kerja terdepan yang telah berpengalaman lebih dari 10 tahun 
                        dalam mengembangkan sumber daya manusia berkualitas melalui program pelatihan 
                        yang terstruktur dan bersertifikat.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="check-circle" class="h-5 w-5 text-green-500"></i>
                            <span class="text-gray-700">Instruktur berpengalaman dan bersertifikat</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i data-lucide="check-circle" class="h-5 w-5 text-green-500"></i>
                            <span class="text-gray-700">Kurikulum yang selalu update dengan industri</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i data-lucide="check-circle" class="h-5 w-5 text-green-500"></i>
                            <span class="text-gray-700">Fasilitas lengkap dan teknologi terkini</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i data-lucide="check-circle" class="h-5 w-5 text-green-500"></i>
                            <span class="text-gray-700">Sertifikat resmi dan diakui industri</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white p-6 rounded-lg shadow-md text-center border-2 border-blue-100 hover:border-blue-200 transition-colors">
                        <i data-lucide="target" class="h-8 w-8 text-blue-600 mx-auto mb-4"></i>
                        <h3 class="font-semibold text-gray-900 mb-2">Visi</h3>
                        <p class="text-sm text-gray-600">Menjadi LPK terdepan dalam mencetak SDM berkualitas</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md text-center border-2 border-green-100 hover:border-green-200 transition-colors">
                        <i data-lucide="book-open" class="h-8 w-8 text-green-600 mx-auto mb-4"></i>
                        <h3 class="font-semibold text-gray-900 mb-2">Misi</h3>
                        <p class="text-sm text-gray-600">Memberikan pelatihan berkualitas dan terjangkau</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="program" class="py-20 bg-gradient-to-r from-blue-50 to-green-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Program Pelatihan Unggulan</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Berbagai program pelatihan yang dirancang untuk meningkatkan kompetensi 
                dan daya saing di dunia kerja
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($programs as $program)
            <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-md group hover:shadow-xl transition-all duration-300 border-0">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $program->level === 'Pemula' ? 'bg-green-100 text-green-800' : 
                               ($program->level === 'Menengah' ? 'bg-blue-100 text-blue-800' : 
       ($program->level === 'Lanjutan' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ $program->level }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $program->duration }}</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                        {{ $program->title }}
                    </h3>
                    <p class="text-gray-600 mb-4">{{ $program->description }}</p>
                    <a href="{{ route('programs.show', $program->id) }}" 
                       class="inline-block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors group-hover:bg-blue-600">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('programs.index') }}" class="inline-block px-8 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-50 transition-colors shadow-md">
                Lihat Semua Program
            </a>
        </div>
    </div>
</section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Mengapa Memilih Kami?</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Keunggulan yang membuat LPK kami menjadi pilihan terbaik untuk pengembangan karir Anda
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                        <i data-lucide="award" class="h-8 w-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Sertifikat Resmi</h3>
                    <p class="text-gray-600">Dapatkan sertifikat yang diakui industri dan pemerintah untuk meningkatkan kredibilitas profesional Anda.</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                        <i data-lucide="users" class="h-8 w-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Kelas Kecil</h3>
                    <p class="text-gray-600">Pembelajaran dengan kelas kecil memastikan setiap peserta mendapat perhatian optimal dari instruktur.</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                        <i data-lucide="clock" class="h-8 w-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Jadwal Fleksibel</h3>
                    <p class="text-gray-600">Berbagai pilihan jadwal pelatihan yang dapat disesuaikan dengan aktivitas dan kesibukan Anda.</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-200 transition-colors">
                        <i data-lucide="headphones" class="h-8 w-8 text-orange-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Dukungan 24/7</h3>
                    <p class="text-gray-600">Tim support yang siap membantu Anda kapan saja selama proses pembelajaran dan setelahnya.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-green-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Apa Kata Alumni Kami</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Dengarkan pengalaman mereka yang telah merasakan manfaat pelatihan di LPK kami
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-600">
                    <div class="mb-4">
                        <div class="flex text-yellow-400 mb-2">
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                        </div>
                        <p class="text-gray-600 italic">"Pelatihan digital marketing di sini sangat lengkap dan praktis. Sekarang saya berhasil menjalankan bisnis online sendiri!"</p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">A</div>
                        <div class="ml-3">
                            <div class="font-semibold text-gray-900">Andi Pratama</div>
                            <div class="text-sm text-gray-600">Alumni Pelatihan Fiber Optik</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-600">
                    <div class="mb-4">
                        <div class="flex text-yellow-400 mb-2">
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                        </div>
                        <p class="text-gray-600 italic">"Instruktur yang kompeten dan fasilitas yang memadai. Saya langsung dapat pekerjaan setelah lulus!"</p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold">S</div>
                        <div class="ml-3">
                            <div class="font-semibold text-gray-900">Sari Dewi</div>
                            <div class="text-sm text-gray-600">Alumni Pelatihan Fiber Optik</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-600">
                    <div class="mb-4">
                        <div class="flex text-yellow-400 mb-2">
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                        </div>
                        <p class="text-gray-600 italic">"Program pelatihan yang sangat terstruktur dan up-to-date dengan perkembangan teknologi terbaru."</p>
                    </div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-semibold">B</div>
                        <div class="ml-3">
                            <div class="font-semibold text-gray-900">Budi Santoso</div>
                            <div class="text-sm text-gray-600">Alumni Pelatihan Fiber Optik</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-green-600">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-white mb-4">Siap Memulai Perjalanan Belajar Anda?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan alumni yang telah sukses berkarir setelah mengikuti pelatihan di LPK kami
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('programs.index') }}" class="inline-block px-8 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                        Pilih Program
                    </a>
                    @if(auth()->user()->role === 'student')
                        <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-600 transition-colors font-semibold">
                            Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('programs.index') }}" class="inline-block px-8 py-3 border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-600 transition-colors font-semibold">
                            Konsultasi Gratis
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('programs.index') }}" class="inline-block px-8 py-3 border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-600 transition-colors font-semibold">
                        Konsultasi Gratis
                    </a>
                @endauth
            </div>
        </div>
    </section>



<script>
    // Initialize Lucide icons
    lucide.createIcons();
</script>
@endsection