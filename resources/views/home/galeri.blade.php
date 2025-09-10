@extends('layouts.app')
@section('title', 'Galeri')

@push('styles')
<style>
/* General Styles */
:root {
    --primary-color: #2563EB;
    --primary-hover: #1D4ED8;
    --text-dark: #111827;
    --text-light: #6B7280;
    --bg-light: #F9FAFB;
}

/* Filter Buttons */
.filter-nav {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem;
    margin-bottom: 2.5rem;
}
.filter-btn {
    background-color: #fff;
    color: var(--text-light);
    border: 1px solid #E5E7EB;
    padding: 0.6rem 1.25rem;
    border-radius: 9999px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    user-select: none;
}
.filter-btn:hover {
    background-color: #F3F4F6;
    border-color: #D1D5DB;
}
.filter-btn.active {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

/* Masonry Grid Layout */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    grid-auto-rows: auto;
    gap: 1.5rem;
}
.grid-item {
    position: relative;
    overflow: hidden;
    border-radius: 0.75rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    cursor: pointer;
    break-inside: avoid;
}
.grid-item img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    display: block;
    transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.grid-item:hover img {
    transform: scale(1.08);
}
.grid-item .overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 60%, rgba(0,0,0,0.8) 100%);
    display: flex;
    align-items: flex-end;
    padding: 1rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.grid-item:hover .overlay {
    opacity: 1;
}
.grid-item .overlay-title {
    color: white;
    font-weight: 600;
    transform: translateY(20px);
    transition: transform 0.3s ease;
}
.grid-item:hover .overlay-title {
    transform: translateY(0);
}

/* Error placeholder */
.image-error {
    background-color: #f3f4f6;
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 1rem;
    text-align: center;
    color: #6b7280;
}

/* Lightbox (Modal) */
.lightbox {
    position: fixed;
    inset: 0;
    z-index: 5000;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(17, 24, 39, 0.8);
    backdrop-filter: blur(8px);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}
.lightbox.show {
    opacity: 1;
    visibility: visible;
}
.lightbox-content {
    position: relative;
    width: 90%;
    max-width: 1000px;
    transform: scale(0.95);
    transition: transform 0.3s ease;
}
.lightbox.show .lightbox-content {
    transform: scale(1);
}
.lightbox-image {
    width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 0.5rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}
.lightbox-caption {
    text-align: center;
    margin-top: 1rem;
    color: #D1D5DB;
    padding: 0 2rem;
}
.lightbox-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.5rem;
}
.lightbox-description {
    font-size: 0.9rem;
    line-height: 1.5;
}
.lightbox-close, .lightbox-nav {
    position: absolute;
    color: white;
    background-color: rgba(17, 24, 39, 0.5);
    border: none;
    border-radius: 50%;
    width: 44px;
    height: 44px;
    font-size: 1.5rem;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.2s ease;
}
.lightbox-close:hover, .lightbox-nav:hover {
    background-color: rgba(17, 24, 39, 0.8);
}
.lightbox-close {
    top: -50px;
    right: 0;
}
.lightbox-nav {
    top: 50%;
    transform: translateY(-50%);
}
.lightbox-prev {
    left: -60px;
}
.lightbox-next {
    right: -60px;
}

/* Responsive */
@media (max-width: 768px) {
    .lightbox-nav { display: none; }
    .lightbox-close { top: 10px; right: 10px; background: none; }
    .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); }
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-light py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-text-dark mb-4">
                Dokumentasi <span class="text-primary-color">Kegiatan Kami</span>
            </h1>
            <p class="text-text-light max-w-2xl mx-auto">
                Jelajahi momen-momen berharga dari berbagai program pelatihan dan acara yang telah kami selenggarakan.
            </p>
        </div>

        @if(empty($gallery_sections) || count($gallery_sections) === 0)
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📷</div>
                <h3 class="text-xl font-medium text-gray-700 mb-2">Galeri Masih Kosong</h3>
                <p class="text-gray-500">Kami akan segera menambahkan dokumentasi kegiatan di sini.</p>
            </div>
        @else
            <nav class="filter-nav">
                <button class="filter-btn active" data-filter="all">Semua</button>
                @foreach($gallery_sections as $key => $section)
                    <button class="filter-btn" data-filter="{{ $key }}">{{ $section['title'] }}</button>
                @endforeach
            </nav>

            <div class="gallery-grid">
                @foreach($gallery_sections as $key => $section)
                    @foreach($section['images'] as $image)
                    <div class="grid-item" data-category="{{ $key }}"
                         data-src="{{ $image['url'] }}"
                         data-title="{{ $image['title'] }}"
                         data-description="{{ $image['description'] ?? '' }}">
                        
                        <img src="{{ $image['url'] }}" 
                             alt="{{ $image['alt'] }}" 
                             loading="lazy"
                             onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        
                        {{-- Error placeholder --}}
                        <div class="image-error" style="display: none;">
                            <div class="text-4xl mb-2">🖼️</div>
                            <p class="font-semibold text-red-600">Gambar Tidak Dapat Dimuat</p>
                            <p class="text-xs mt-1">{{ $image['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $image['url'] }}</p>
                        </div>

                        <div class="overlay">
                            <h3 class="overlay-title">{{ $image['title'] }}</h3>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Lightbox (Modal) -->
<div class="lightbox" id="lightbox">
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose">&times;</button>
        <button class="lightbox-nav lightbox-prev" id="lightboxPrev">&#10094;</button>
        <button class="lightbox-nav lightbox-next" id="lightboxNext">&#10095;</button>
        <img src="" alt="" class="lightbox-image" id="lightboxImage">
        <div class="lightbox-caption">
            <h3 class="lightbox-title" id="lightboxTitle"></h3>
            <p class="lightbox-description" id="lightboxDescription"></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryGrid = document.querySelector('.gallery-grid');
    let gridItems = document.querySelectorAll('.grid-item');
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxDescription = document.getElementById('lightboxDescription');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');

    let currentIndex = 0;

    const updateVisibleGridItems = () => {
        gridItems = Array.from(document.querySelectorAll('.grid-item')).filter(item => item.style.display !== 'none');
    };

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.getAttribute('data-filter');
            document.querySelectorAll('.grid-item').forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            updateVisibleGridItems();
        });
    });

    const openLightbox = (item) => {
        currentIndex = Array.from(gridItems).indexOf(item);
        updateLightboxContent();
        lightbox.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    const closeLightbox = () => {
        lightbox.classList.remove('show');
        document.body.style.overflow = 'auto';
    };

    const updateLightboxContent = () => {
        if (currentIndex < 0 || currentIndex >= gridItems.length) return;
        const item = gridItems[currentIndex];
        lightboxImage.src = item.dataset.src;
        lightboxTitle.textContent = item.dataset.title;
        lightboxDescription.textContent = item.dataset.description;
    };

    const showNextImage = () => {
        currentIndex = (currentIndex + 1) % gridItems.length;
        updateLightboxContent();
    };

    const showPrevImage = () => {
        currentIndex = (currentIndex - 1 + gridItems.length) % gridItems.length;
        updateLightboxContent();
    };

    if (galleryGrid) {
        galleryGrid.addEventListener('click', (e) => {
            const item = e.target.closest('.grid-item');
            if (item) openLightbox(item);
        });
    }

    lightboxClose.addEventListener('click', closeLightbox);
    lightboxPrev.addEventListener('click', showPrevImage);
    lightboxNext.addEventListener('click', showNextImage);
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
    });

    document.addEventListener('keydown', (e) => {
        if (!lightbox.classList.contains('show')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowRight') showNextImage();
        if (e.key === 'ArrowLeft') showPrevImage();
    });

    // Initial population
    updateVisibleGridItems();
});
</script>
@endpush