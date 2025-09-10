@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-600 via-blue-200 to-green-600 py-20">
        <div class="container mx-auto px-4">
            <div class="text-center text-white">
                <h1 class="text-5xl font-bold mb-6">Hubungi Kami</h1>
                <p class="text-xl opacity-90 max-w-2xl mx-auto">
                    Siap membantu perjalanan pelatihan Anda. Kami selalu terbuka untuk diskusi dan pertanyaan.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="bg-white rounded-xl shadow-lg text-center p-8 border-2 border-transparent hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-2">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="map-pin" class="h-8 w-8 text-blue-600"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-4">Alamat Kantor</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Jl. Kerkof No.35A, Cibeber<br>
                        Kec. Cimahi Selatan<br>
                        Kota Cimahi, Jawa Barat 40532
                    </p>
                    <a href="https://maps.google.com/?q=Jl.+Kerkof+No.35A+Cibeber+Cimahi" target="_blank" 
                       class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-medium">
                        Lihat di Google Maps →
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-lg text-center p-8 border-2 border-transparent hover:border-green-200 transition-all duration-300 transform hover:-translate-y-2">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="phone" class="h-8 w-8 text-green-600"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-4">Telepon</h3>
                    <p class="text-gray-600 mb-2">Hubungi kami langsung:</p>
                    <a href="tel:082119001500" class="text-2xl font-bold text-green-600 hover:text-green-800 block mb-2">
                        0821-1900-1500
                    </a>
                    <p class="text-sm text-gray-500">Senin - Jumat: 08:00 - 17:00</p>
                </div>

                <div class="bg-white rounded-xl shadow-lg text-center p-8 border-2 border-transparent hover:border-purple-200 transition-all duration-300 transform hover:-translate-y-2">
                    <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="mail" class="h-8 w-8 text-purple-600"></i>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-4">Email</h3>
                    <p class="text-gray-600 mb-2">Kirim pesan ke:</p>
                    <a href="mailto:sekolahanid@gmail.com" class="text-xl font-bold text-blue-600 hover:text-blue-800 block mb-2">
                        sekolahanid@gmail.com
                    </a>
                    <p class="text-sm text-gray-500">Respon dalam 24 jam</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Kirim Pesan</h2>
                    <p class="text-xl text-gray-600">
                        Ada pertanyaan tentang program pelatihan? Silakan hubungi kami!
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Form -->
                    <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-200">
                        <form id="contact-form" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                    <input type="text" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                    <input type="email" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                                    <input type="tel" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Subjek</label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                        <option value="">Pilih Subjek</option>
                                        <option value="info-program">Informasi Program</option>
                                        <option value="pendaftaran">Pendaftaran</option>
                                        <option value="konsultasi">Konsultasi</option>
                                        <option value="kerjasama">Kerjasama</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pesan *</label>
                                <textarea required rows="5" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none"
                                          placeholder="Tuliskan pesan atau pertanyaan Anda..."></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-400 text-white font-bold py-4 px-6 rounded-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                                <i data-lucide="send" class="inline h-5 w-5 mr-2"></i>
                                Kirim Pesan
                            </button>
                        </form>
                    </div>

                    <!-- Additional Info -->
                    <div class="space-y-8">
                        <!-- FAQ Quick Access -->
                        <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl p-8 border border-blue-100">
                            <h3 class="font-bold text-xl text-gray-900 mb-4 flex items-center">
                                <i data-lucide="help-circle" class="h-6 w-6 text-blue-600 mr-2"></i>
                                Pertanyaan Umum
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <h4 class="font-semibold text-gray-900 mb-2">Bagaimana cara mendaftar?</h4>
                                    <p class="text-gray-600 text-sm">Anda bisa mendaftar melalui website atau datang langsung ke kantor kami.</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <h4 class="font-semibold text-gray-900 mb-2">Apakah ada program online?</h4>
                                    <p class="text-gray-600 text-sm">Ya, kami menyediakan program pelatihan online dan offline.</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <h4 class="font-semibold text-gray-900 mb-2">Bagaimana sistem pembayaran?</h4>
                                    <p class="text-gray-600 text-sm">Kami menerima pembayaran tunai, transfer bank, dan cicilan.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-xl p-8 border border-green-100">
                            <h3 class="font-bold text-xl text-gray-900 mb-4 flex items-center">
                                <i data-lucide="users" class="h-6 w-6 text-green-600 mr-2"></i>
                                Ikuti Kami
                            </h3>
                            <p class="text-gray-600 mb-6">Dapatkan update terbaru program pelatihan</p>
                            <div class="flex space-x-4">
                                <a href="mailto:jajangzainudin2015@gmail.com" class="bg-blue-400 hover:bg-blue-500 text-white p-3 rounded-full transition-colors">
                                    <i data-lucide="mail" class="h-5 w-5"></i>
                                </a>
                                <a href="https://www.instagram.com/foritastasolusindo?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="bg-pink-600 hover:bg-pink-700 text-white p-3 rounded-full transition-colors" target="_blank">
                                    <i data-lucide="instagram" class="h-5 w-5"></i>
                                </a>
                                <a href="https://wa.me/6281234567890" class="bg-green-600 hover:bg-green-700 text-white p-3 rounded-full transition-colors">
                                    <i data-lucide="phone" class="h-5 w-5"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-8 border border-purple-100">
                            <h3 class="font-bold text-xl text-gray-900 mb-4 flex items-center">
                                <i data-lucide="clock" class="h-6 w-6 text-purple-600 mr-2"></i>
                                Jam Operasional
                            </h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Senin - Jumat</span>
                                    <span class="font-semibold text-gray-900">08:00 - 17:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Sabtu</span>
                                    <span class="font-semibold text-gray-900">08:00 - 15:00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Minggu</span>
                                    <span class="font-semibold text-red-500">Tutup</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Lokasi Kami</h2>
                <p class="text-xl text-gray-600">Mudah dijangkau dengan transportasi umum</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="aspect-video bg-gray-200 relative">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.0569!2d107.5370!3d-6.8854!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e4500000000%3A0x0!2sJl.+Kerkof+No.35A%2C+Cibeber%2C+Kec.+Cimahi+Sel.%2C+Kota+Cimahi%2C+Jawa+Barat+40532!5e0!3m2!1sen!2sid!4v1234567890"
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-full">
                    </iframe>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                        <div class="text-center">
                            <i data-lucide="car" class="h-8 w-8 text-blue-600 mx-auto mb-2"></i>
                            <h4 class="font-semibold text-gray-900 mb-2">Parkir</h4>
                            <p class="text-gray-600 text-sm">Tersedia area parkir yang luas</p>
                        </div>
                        <div class="text-center">
                            <i data-lucide="coffee" class="h-8 w-8 text-purple-600 mx-auto mb-2"></i>
                            <h4 class="font-semibold text-gray-900 mb-2">Fasilitas</h4>
                            <p class="text-gray-600 text-sm">Ruang tunggu yang nyaman</p>
                        </div>
                        <div class="text-center">
                            <i data-lucide="wifi" class="h-8 w-8 text-green-600 mx-auto mb-2"></i>
                            <h4 class="font-semibold text-gray-900 mb-2">WiFi</h4>
                            <p class="text-gray-600 text-sm">Dapatkan akses WiFi gratis di seluruh area</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-3xl mx-auto text-white">
                <h2 class="text-4xl font-bold mb-6">Siap Memulai Perjalanan Belajar?</h2>
                <p class="text-xl mb-8 opacity-90">
                    Jangan ragu untuk menghubungi kami. Tim kami siap membantu Anda menemukan program pelatihan yang tepat.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="tel:082119001500" 
                       class="bg-white text-blue-600 hover:bg-gray-100 font-bold py-4 px-8 rounded-lg transition-colors inline-flex items-center justify-center">
                        <i data-lucide="phone" class="h-5 w-5 mr-2"></i>
                        Telepon Sekarang
                    </a>
                    <a href="mailto:sekolahanid@gmail.com" 
                       class="border-2 border-white text-white hover:bg-white hover:text-blue-600 font-bold py-4 px-8 rounded-lg transition-colors inline-flex items-center justify-center">
                        <i data-lucide="mail" class="h-5 w-5 mr-2"></i>
                        Kirim Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Contact form submission
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show success message (replace with actual form submission logic)
            alert('Terima kasih! Pesan Anda telah terkirim. Kami akan merespon dalam 24 jam.');
            
            // Reset form
            this.reset();
        });

        // Smooth scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
@endsection