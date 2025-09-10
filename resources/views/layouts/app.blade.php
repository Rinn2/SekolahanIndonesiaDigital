<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'SIPEL') }} - @yield('title', 'Sistem Informasi Pelatihan LPK')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Sekolahan-v2.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    @stack('styles')

    @php
        use Illuminate\Support\Str;
    @endphp
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden">
        <div class="flex items-center justify-between p-4 border-b">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/Sekolahan-v2.png') }}" alt="Logo SekolahID" class="h-8 w-8">
                <span class="text-xl font-bold text-gray-900">SekolahanID</span>
            </div>
            <button id="closeSidebar" class="text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="h-6 w-6"></i>
            </button>
        </div>
        
        <nav class="p-4">
            @unless(Str::startsWith(request()->path(), 'dashboard') || Str::startsWith(request()->path(), 'admin'))
                <ul class="space-y-4 mb-6">
                    <li><a href="{{ route('home.about') }}#tentang" class="block text-gray-700 hover:text-blue-600 transition-colors py-2">Tentang Kami</a></li>
                    <li><a href="{{ route('programs.index') }}" class="block text-gray-700 hover:text-blue-600 transition-colors py-2">Program</a></li>
                    <li><a href="{{ route('galeri') }}#galeri" class="block text-gray-700 hover:text-blue-600 transition-colors py-2">Galeri</a></li>
                    <li><a href="{{ route('contact') }}" class="block text-gray-700 hover:text-blue-600 transition-colors py-2">Kontak</a></li>
                </ul>
            @endunless
            
            <div class="border-t pt-4">
                @guest
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" class="block w-full px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors text-center">Login</a>
                        <a href="{{ route('register') }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-center">Daftar</a>
                    </div>
                @else
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2 px-4 py-2 text-gray-700">
                            <i data-lucide="user" class="h-4 w-4"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <a href="/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Dashboard</a>
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">Logout</button>
                        </form>
                    </div>
                @endguest
            </div>
        </nav>
    </div>

    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md border-b sticky top-0 z-30">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/Sekolahan-v2.png') }}" alt="Logo SekolahID" class="h-8 w-8">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900">SekolahID</a>
                </div>

                <!-- Desktop Navigation -->
                @unless(Str::startsWith(request()->path(), 'dashboard') || Str::startsWith(request()->path(), 'admin'))
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home.about') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Tentang Kami</a>
                        <a href="{{ route('programs.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Program</a>
                        <a href="{{ route('galeri') }}#galeri" class="text-gray-700 hover:text-blue-600 transition-colors">Galeri</a>
                        <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 transition-colors">Kontak</a>
                    </div>
                @endunless

                <!-- Desktop User Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Daftar</a>
                    @else
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                                <span>{{ Auth::user()->name }}</span>
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </button>
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                <a href="/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Burger Button -->
                <button id="mobileMenuButton" class="md:hidden text-gray-700 hover:text-blue-600 transition-colors">
                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div id="flashMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div id="flashMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @unless(Str::startsWith(request()->path(), 'dashboard') || Str::startsWith(request()->path(), 'admin'))
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="{{ asset('images/Sekolahan-v2.png') }}" alt="Logo SekolahID" class="h-8 w-8">
                        <span class="text-xl font-bold">SekolahanID</span>
                    </div>
                    <p class="text-gray-400">
                        Sistem Informasi Pelatihan untuk Lembaga Pelatihan Kerja
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Menu</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}#tentang" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('programs.index') }}" class="hover:text-white transition-colors">Program</a></li>
                        <li><a href="{{ route('galeri') }}#galeri" class="hover:text-white transition-colors">Galeri</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Program</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Fiber Optik</li>
                        <li>Web Development</li>
                        <li>Digital Marketing</li>
                        <li>Web Developer</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">

                        <li><a href="https://maps.app.goo.gl/vj78gHfBPqvtwu4m7" target="_blank" class="hover:text-white transition-colors">Jl. Kerkof No.35A, Cibeber, Kec. Cimahi Sel., Kota Cimahi, Jawa Barat 40532i</a></li>
                        <li><a href="tel:+6282119001500" class="hover:text-white transition-colors">+62 821-1900-1500</a></li>
                        <li><a href="mailto:sekolahanid@gmail.com" class="hover:text-white transition-colors">sekolahanid@gmail.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} SekolahanID - Sistem Informasi Pelatihan LPK. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @endunless

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Desktop user menu toggle
        document.getElementById('userMenuButton')?.addEventListener('click', function() {
            document.getElementById('userMenu').classList.toggle('hidden');
        });

        // Mobile sidebar functionality
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const closeSidebar = document.getElementById('closeSidebar');

        function openSidebar() {
            mobileSidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebarFunc() {
            mobileSidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        mobileMenuButton?.addEventListener('click', openSidebar);
        closeSidebar?.addEventListener('click', closeSidebarFunc);
        sidebarOverlay?.addEventListener('click', closeSidebarFunc);

        // Close sidebar when clicking on a link
        document.querySelectorAll('#mobileSidebar a').forEach(link => {
            link.addEventListener('click', closeSidebarFunc);
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('userMenu');
            const userMenuButton = document.getElementById('userMenuButton');
            
            if (userMenu && !userMenu.contains(event.target) && !userMenuButton?.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });

        // Hide flash messages after 5 seconds
        setTimeout(() => {
            const flashMessage = document.getElementById('flashMessage');
            if (flashMessage) {
                flashMessage.style.display = 'none';
            }
        }, 5000);

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                closeSidebarFunc();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>