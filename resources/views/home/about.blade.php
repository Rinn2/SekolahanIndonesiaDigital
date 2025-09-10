@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="bg-gray-50 font-sans">
    <div class="container mx-auto px-6 py-16 md:py-24">
        
        <div class="grid md:grid-cols-2 gap-12 items-center">
            
           <img src="{{ asset('images/lpk.png') }}" 
     alt="Tim Kami" 
     class="rounded-xl shadow-lg w-full h-auto object-cover"
     onerror="this.onerror=null;this.src='https://placehold.co/600x450/e2e8f0/334155?text=Gagal+Memuat+Gambar';">

            {{-- Kolom Kanan: Konten Teks --}}
            <div class="wow fadeIn" data-wow-delay="0.4s">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                    Mengenal Kami Lebih Dekat
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Kami adalah tim profesional yang berdedikasi untuk menciptakan solusi digital inovatif yang memberikan dampak nyata dan nilai tambah bagi klien dan pengguna kami.
                </p>

                {{-- Misi & Visi dengan spacing yang baik --}}
                <div class="space-y-6 border-l-4 border-blue-500 pl-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Misi Kami</h2>
                        <p class="text-gray-600 leading-relaxed">
                            Memberikan layanan dan produk berkualitas tinggi yang mudah diakses, intuitif, dan mampu menyelesaikan masalah kompleks dengan cara yang sederhana dan efisien.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-2">Visi Kami</h2>
                        <p class="text-gray-600 leading-relaxed">
                            Menjadi mitra teknologi terdepan yang menginspirasi pertumbuhan dan kesuksesan melalui inovasi tanpa henti dan komitmen terhadap keunggulan.
                        </p>
                    </div>
                </div>

                <div class="mt-10">
                    <a href="{{ route('contact') }}" class="inline-block bg-blue-600 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:bg-blue-700 transition-all duration-300 transform hover:-translate-y-1">
                        Hubungi Kami
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
