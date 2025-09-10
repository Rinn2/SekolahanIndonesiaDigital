@extends('layouts.app')

@section('title', $program->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <nav class="bg-white border-b py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a>
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
                <a href="{{ route('programs.index') }}" class="hover:text-blue-600">Program</a>
                <i data-lucide="chevron-right" class="h-4 w-4"></i>
                <span class="text-gray-900">{{ $program->title }}</span>
            </div>
        </div>
    </nav>

    <!-- Program Detail -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <!-- Program Header -->
                        <div class="mb-8">
                            <div class="flex items-center gap-4 mb-4">
                                <span class="px-3 py-1 text-sm font-medium rounded-full 
                                    {{ $program->level === 'Pemula' ? 'bg-green-100 text-green-800' : 
                                       ($program->level === 'Menengah' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $program->level }}
                                </span>
                                <span class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="clock" class="h-4 w-4 mr-1"></i>
                                    {{ $program->duration }}
                                </span>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $program->title }}</h1>
                            <p class="text-lg text-gray-600">{{ $program->description }}</p>
                        </div>

                        <!-- Program Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            @if($program->instructor)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                <i data-lucide="user" class="h-6 w-6 text-blue-600 mr-3"></i>
                                <div>
                                    <div class="text-sm text-gray-600">Instruktur</div>
                                    <div class="font-semibold text-gray-900">{{ $program->instructor }}</div>
                                </div>
                            </div>
                            @endif

                            @if($program->max_participants)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                <i data-lucide="users" class="h-6 w-6 text-green-600 mr-3"></i>
                                <div>
                                    <div class="text-sm text-gray-600">Kapasitas</div>
                                    <div class="font-semibold text-gray-900">{{ $program->max_participants }} peserta</div>
                                </div>
                            </div>
                            @endif

                            @if($program->start_date)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                <i data-lucide="calendar" class="h-6 w-6 text-purple-600 mr-3"></i>
                                <div>
                                    <div class="text-sm text-gray-600">Mulai</div>
                                    <div class="font-semibold text-gray-900">{{ $program->start_date->format('d M Y') }}</div>
                                </div>
                            </div>
                            @endif

                            @if($program->end_date)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                <i data-lucide="calendar-x" class="h-6 w-6 text-red-600 mr-3"></i>
                                <div>
                                    <div class="text-sm text-gray-600">Berakhir</div>
                                    <div class="font-semibold text-gray-900">{{ $program->end_date->format('d M Y') }}</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Program Content -->
                        <div class="prose max-w-none">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Tentang Program</h3>
                            <div class="text-gray-600 mb-6">
                                <p>Program {{ $program->title }} dirancang untuk memberikan pemahaman mendalam dan keterampilan praktis yang dibutuhkan di industri modern...</p>
                            </div>

                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Yang Akan Anda Pelajari</h3>
                            <ul class="space-y-2 text-gray-600 mb-6">
                                <li class="flex items-start">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span>Fundamental dan konsep dasar yang kuat</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span>Praktik langsung dengan tools dan teknologi terkini</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span>Studi kasus dan project real-world</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span>Best practices dan industry standards</span>
                                </li>
                            </ul>

                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Persyaratan</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li class="flex items-start">
                                    <i data-lucide="dot" class="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span>Minimal pendidikan SMA/SMK atau sederajat</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="dot" class="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span>Memiliki laptop/komputer untuk praktik</span>
                                </li>
                                <li class="flex items-start">
                                    <i data-lucide="dot" class="h-5 w-5 text-gray-400 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <span>Motivasi tinggi untuk belajar dan berkembang</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Enrollment Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        @if($program->price)
                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold text-blue-600">{{ $program->formatted_price }}</div>
                            <div class="text-sm text-gray-500">per peserta</div>
                        </div>
                        @endif

                        @guest
                        <div class="space-y-3">
                            <a href="{{ route('enrollments.confirm', $program) }}"
                               class="block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Daftar Program
                            </a>
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                Login untuk Mendaftar
                            </a>
                            <!-- Share Link Button -->
                            <button onclick="toggleShareModal()" 
                                    class="w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i data-lucide="share-2" class="h-4 w-4 inline mr-2"></i>
                                Bagikan Program
                            </button>
                        </div>
                        @else
                        <div class="space-y-3">
                            <a href="{{ route('enrollments.confirm', $program) }}" 
                               class="block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Daftar Program
                            </a>
                            <button onclick="toggleShareModal()" 
                                    class="w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i data-lucide="share-2" class="h-4 w-4 inline mr-2"></i>
                                Bagikan Program
                            </button>
                        </div>
                        @endguest

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center text-sm text-gray-600">
                                <i data-lucide="shield-check" class="h-4 w-4 mr-2"></i>
                                <span>Sertifikat resmi disediakan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Butuh Bantuan?</h3>
                        <div class="space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <i data-lucide="phone" class="h-4 w-4 mr-3"></i>
                                <span>+62 821-1900-1500</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i data-lucide="mail" class="h-4 w-4 mr-3"></i>
                                <span>info@lpk-kami.id</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i data-lucide="message-circle" class="h-4 w-4 mr-3"></i>
                                <span>WhatsApp: +62 821-1900-1500</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Programs -->
    @if($relatedPrograms->count() > 0)
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Program Serupa</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPrograms as $related)
                <div class="bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $related->level === 'Pemula' ? 'bg-green-100 text-green-800' : 
                               ($related->level === 'Menengah' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                            {{ $related->level }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $related->duration }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $related->title }}</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($related->description, 100) }}</p>
                    <a href="{{ route('programs.show', $related) }}" 
                       class="inline-block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        Lihat Detail
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Bagikan Program</h3>
                <button onclick="toggleShareModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>
            
            <!-- Share URL -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Link Program:</label>
                <div class="flex items-center space-x-2">
                    <input type="text" 
                           id="shareUrl" 
                           value="{{ request()->url() }}" 
                           readonly 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
                    <button onclick="copyToClipboard()" 
                            class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <i data-lucide="copy" class="h-4 w-4"></i>
                    </button>
                </div>
                <p id="copyMessage" class="text-sm text-green-600 mt-1 hidden">Link berhasil disalin!</p>
            </div>
            
            <!-- Social Media Share Options -->
            <div class="space-y-3">
                <h4 class="text-sm font-medium text-gray-700">Bagikan ke:</h4>
                
                <!-- WhatsApp -->
                <a href="#" 
                   onclick="shareToWhatsApp()" 
                   class="flex items-center w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                        <i data-lucide="message-circle" class="h-4 w-4 text-white"></i>
                    </div>
                    <span class="text-gray-700">WhatsApp</span>
                </a>
                
                <!-- Facebook -->
                <a href="#" 
                   onclick="shareToFacebook()" 
                   class="flex items-center w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                        <span class="text-white text-sm font-bold">f</span>
                    </div>
                    <span class="text-gray-700">Facebook</span>
                </a>
                
                <!-- Twitter/X -->
                <a href="#" 
                   onclick="shareToTwitter()" 
                   class="flex items-center w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-black rounded-full flex items-center justify-center mr-3">
                        <span class="text-white text-sm font-bold">X</span>
                    </div>
                    <span class="text-gray-700">Twitter / X</span>
                </a>
                
                <!-- LinkedIn -->
                <a href="#" 
                   onclick="shareToLinkedIn()" 
                   class="flex items-center w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center mr-3">
                        <span class="text-white text-sm font-bold">in</span>
                    </div>
                    <span class="text-gray-700">LinkedIn</span>
                </a>
                
                <!-- Telegram -->
                <a href="#" 
                   onclick="shareToTelegram()" 
                   class="flex items-center w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                        <i data-lucide="send" class="h-4 w-4 text-white"></i>
                    </div>
                    <span class="text-gray-700">Telegram</span>
                </a>
                
                <!-- Email -->
                <a href="#" 
                   onclick="shareViaEmail()" 
                   class="flex items-center w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                        <i data-lucide="mail" class="h-4 w-4 text-white"></i>
                    </div>
                    <span class="text-gray-700">Email</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Share Modal Functions
function toggleShareModal() {
    const modal = document.getElementById('shareModal');
    modal.classList.toggle('hidden');
}

// Copy to clipboard function
function copyToClipboard() {
    const urlInput = document.getElementById('shareUrl');
    const copyMessage = document.getElementById('copyMessage');
    
    urlInput.select();
    urlInput.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        copyMessage.classList.remove('hidden');
        setTimeout(() => {
            copyMessage.classList.add('hidden');
        }, 2000);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
}

// Share functions for different platforms
function shareToWhatsApp() {
    const url = encodeURIComponent(document.getElementById('shareUrl').value);
    const text = encodeURIComponent(`Lihat program menarik ini: {{ $program->title }}`);
    window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
}

function shareToFacebook() {
    const url = encodeURIComponent(document.getElementById('shareUrl').value);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareToTwitter() {
    const url = encodeURIComponent(document.getElementById('shareUrl').value);
    const text = encodeURIComponent(`Lihat program menarik ini: {{ $program->title }}`);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
}

function shareToLinkedIn() {
    const url = encodeURIComponent(document.getElementById('shareUrl').value);
    const title = encodeURIComponent('{{ $program->title }}');
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}`, '_blank');
}

function shareToTelegram() {
    const url = encodeURIComponent(document.getElementById('shareUrl').value);
    const text = encodeURIComponent(`Lihat program menarik ini: {{ $program->title }}`);
    window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank');
}

function shareViaEmail() {
    const url = document.getElementById('shareUrl').value;
    const subject = encodeURIComponent(`Program: {{ $program->title }}`);
    const body = encodeURIComponent(`Halo,\n\nSaya ingin berbagi program menarik ini dengan Anda:\n\n{{ $program->title }}\n{{ $program->description }}\n\nLink: ${url}\n\nTerima kasih!`);
    window.location.href = `mailto:?subject=${subject}&body=${body}`;
}

// Close modal when clicking outside
document.getElementById('shareModal').addEventListener('click', function(e) {
    if (e.target === this) {
        toggleShareModal();
    }
});
</script>

@endsection