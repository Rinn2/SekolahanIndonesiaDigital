document.addEventListener('DOMContentLoaded', function() {

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    /**
     * @returns {string|null} 
     */
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
               document.querySelector('input[name="_token"]')?.value;
    }

    /**
     * @param {Response} response 
     * @returns {Promise<any>} 
     */
    async function handleResponse(response) {
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({
                message: 'Terjadi kesalahan tidak dikenal.'
            }));
            let errorMessage = errorData.message || 'Server error';
            if (errorData.errors) {
                errorMessage += '\n' + Object.values(errorData.errors).flat().join('\n');
            }
            throw new Error(errorMessage);
        }
        return response.json();
    }

    /**
     * @param {string} modalId 
     */
    window.openModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        } else {
            console.error(`Modal with ID "${modalId}" not found.`);
        }
    };

    /**
     * @param {string} modalId 
     */
    window.closeModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    document.querySelectorAll('[id$="-modal"]').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
        modal.querySelectorAll('[id*="close-"]').forEach(closeBtn => {
            closeBtn.addEventListener('click', () => closeModal(modal.id));
        });
    });

    // ============================
    // GALLERY CRUD OPERATIONS 
    // ============================

    const galleryModal = document.getElementById('gallery-modal');
    const galleryForm = document.getElementById('gallery-form');

    if (galleryForm) {
        const galleryModalTitle = document.getElementById('gallery-modal-title');
        const galleryIdInput = document.getElementById('gallery-id-1');
        const addGalleryButton = document.querySelector('button[onclick="openModal(\'addGalleryModal\')"]');

        const openGalleryModalForEdit = async (id = null) => {
            galleryForm.reset();
            galleryIdInput.value = '';

            if (id) {
                galleryModalTitle.textContent = 'Edit Galeri';
                try {
                    const response = await fetch(`/admin/gallery/${id}`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    const result = await handleResponse(response);
                    console.log('Gallery data received:', result); 

                    const data = result.data || result;

                    if (data) {
                        galleryIdInput.value = data.id || '';
                        
                        const titleInput = document.getElementById('gallery-title-1');
                        const descriptionInput = document.getElementById('gallery-description');
                        const categorySelect = document.getElementById('gallery-category');

                        if (titleInput) titleInput.value = data.title || '';
                        if (descriptionInput) descriptionInput.value = data.description || '';
                        if (categorySelect) {
                            categorySelect.value = data.gallery_category_id || data.category_id || '';
                        }

                        console.log('Form populated with:', {
                            id: data.id,
                            title: data.title,
                            description: data.description,
                            category_id: data.gallery_category_id || data.category_id
                        });
                    }

                } catch (error) {
                    console.error('Error fetching gallery data:', error);
                    alert(`Gagal mengambil data galeri: ${error.message}`);
                    return;
                }
            } else {
                galleryModalTitle.textContent = 'Tambah Galeri Baru';
            }
            
            openModal('gallery-modal');
        };

        window.editGallery = (id) => {
            console.log('Edit gallery called with ID:', id);
            openGalleryModalForEdit(id);
        };

        if (addGalleryButton) {
           addGalleryButton.onclick = () => openGalleryModalForEdit();
        }

        galleryForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = galleryIdInput.value;
            const url = id ? `/admin/gallery/${id}` : galleryForm.action;
            const formData = new FormData(galleryForm);

            if (id) {
                formData.append('_method', 'PUT');
            }

            try {
                const response = await fetch(url, {
                    method: 'POST', 
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Accept': 'application/json'
                    }
                });
                const result = await handleResponse(response);
                alert(result.message);
                window.location.reload();
            } catch (error) {
                console.error('Error saving gallery:', error);
                alert(`Gagal menyimpan galeri: ${error.message}`);
            }
        });
    }

    window.deleteGallery = async (id) => {
        if (!confirm('Apakah Anda yakin ingin menghapus galeri ini?')) return;

        try {
            const response = await fetch(`/admin/gallery/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                }
            });
            const result = await handleResponse(response);
            alert(result.message);
            window.location.reload();
        } catch (error) {
            alert(`Gagal menghapus galeri: ${error.message}`);
        }
    };

    window.toggleGalleryStatus = async (id) => {
        try {
            const response = await fetch(`/admin/gallery/${id}/status`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const result = await handleResponse(response);

            const statusButton = document.querySelector(`button[onclick="toggleGalleryStatus(${id})"]`);
            const statusText = statusButton.nextElementSibling;
            const isActive = result.is_active;

            statusButton.classList.toggle('bg-green-600', isActive);
            statusButton.classList.toggle('bg-gray-300', !isActive);
            statusButton.querySelector('span').classList.toggle('translate-x-6', isActive);
            statusButton.querySelector('span').classList.toggle('translate-x-1', !isActive);
            statusText.textContent = isActive ? 'Aktif' : 'Nonaktif';

        } catch (error) {
            alert(`Gagal memperbarui status: ${error.message}`);
        }
    };

    const gallerySearchInput = document.getElementById('gallerySearch');
    const galleryCategoryFilter = document.getElementById('galleryCategoryFilter');
    const galleryTableBody = document.querySelector('#gallery-tab table > tbody');

    function filterGalleryRows() {
        if (!galleryTableBody) return;
        const searchTerm = gallerySearchInput.value.toLowerCase();
        const categoryFilterValue = galleryCategoryFilter.value;

        galleryTableBody.querySelectorAll('tr').forEach(row => {
            if (row.querySelector('td[colspan]')) {
                row.style.display = '';
                return;
            }

            const title = row.cells[1].textContent.toLowerCase();
            const categoryName = row.cells[2].textContent.toLowerCase().trim();
            const rowCategoryId = row.dataset.categoryId || '';

            const matchesSearch = title.includes(searchTerm) || categoryName.includes(searchTerm);
            const matchesCategory = categoryFilterValue === '' || rowCategoryId === categoryFilterValue;

            row.style.display = (matchesSearch && matchesCategory) ? '' : 'none';
        });
    }

    if (gallerySearchInput) gallerySearchInput.addEventListener('input', filterGalleryRows);
    if (galleryCategoryFilter) galleryCategoryFilter.addEventListener('change', filterGalleryRows);

    const categorySearchInput = document.getElementById('categorySearch');
    const categoryTableBody = document.querySelector('#gallery-category-tab table > tbody');

    if (categorySearchInput && categoryTableBody) {
        categorySearchInput.addEventListener('input', () => {
            const searchTerm = categorySearchInput.value.toLowerCase();
            categoryTableBody.querySelectorAll('tr').forEach(row => {
                if (row.querySelector('td[colspan]')) {
                    row.style.display = '';
                    return;
                }
                const name = row.cells[0].textContent.toLowerCase();
                const description = row.cells[1].textContent.toLowerCase();
                row.style.display = (name.includes(searchTerm) || description.includes(searchTerm)) ? '' : 'none';
            });
        });
    }

    // ===================================================
    // GALLERY CATEGORY CRUD -
    // ===================================================
    const categoryModal = document.getElementById('gallery-category-modal');
    const categoryForm = document.getElementById('gallery-category-form');

    if (categoryForm) {
        const categoryModalTitle = document.getElementById('gallery-category-modal-title');
        const categoryIdInput = document.getElementById('gallery-category-id');
        const addCategoryButton = document.querySelector('button[onclick="openModal(\'addCategoryModal\')"]');

        let isSubmitting = false;

        const openCategoryModalForEdit = async (id = null) => {
            categoryForm.reset();
            categoryIdInput.value = '';
            isSubmitting = false; 

            if (id) {
                categoryModalTitle.textContent = 'Edit Kategori Galeri';
                try {
                    const response = await fetch(`/admin/gallery-categories/${id}/edit`, {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    console.log('Category data received:', result);

                    const data = result.data || result;
                    
                    if (data) {
                        categoryIdInput.value = data.id || '';
                        
                        const nameInput = document.getElementById('gallery-category-name');
                        const descriptionInput = document.getElementById('gallery-category-description');

                        if (nameInput) nameInput.value = data.name || '';
                        if (descriptionInput) descriptionInput.value = data.description || '';
                    }

                } catch (error) {
                    console.error('Error fetching category data:', error);
                    alert(`Gagal mengambil data kategori: ${error.message}`);
                    return;
                }
            } else {
                categoryModalTitle.textContent = 'Tambah Kategori Baru';
            }
            openModal('gallery-category-modal');
        };
        
        window.editCategory = (id) => {
            console.log('Edit category called with ID:', id);
            openCategoryModalForEdit(id);
        };

        if (addCategoryButton) {
            addCategoryButton.onclick = () => openCategoryModalForEdit();
        }

        const newForm = categoryForm.cloneNode(true);
        categoryForm.parentNode.replaceChild(newForm, categoryForm);

        document.getElementById('gallery-category-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Form submission started, isSubmitting:', isSubmitting);
            
            if (isSubmitting) {
                console.log('Already submitting, ignoring...');
                return;
            }
            
            isSubmitting = true;
            
            const form = e.target;
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';
            
            const id = document.getElementById('gallery-category-id').value;
            const url = id ? `/admin/gallery-categories/${id}` : '/admin/gallery-categories';

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            
            if (id) {
                data._method = 'PUT';
            }

            console.log('Sending request to:', url, 'with data:', data);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', [...response.headers.entries()]);

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({
                        message: `HTTP Error ${response.status}: ${response.statusText}`
                    }));
                    
                    let errorMessage = errorData.message || `Server error: ${response.status}`;
                    if (errorData.errors) {
                        const errorFields = Object.entries(errorData.errors)
                            .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                            .join('\n');
                        errorMessage = `Validation errors:\n${errorFields}`;
                    }
                    
                    throw new Error(errorMessage);
                }

                const result = await response.json();
                console.log('Success response:', result);
                
                closeModal('gallery-category-modal');
                
                alert(result.message || 'Kategori berhasil disimpan');
                window.location.reload();
                
            } catch (error) {
                console.error('Error saving category:', error);
                alert(`Gagal menyimpan kategori: ${error.message}`);
                
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                isSubmitting = false;
            }
        });
    }

    window.deleteCategory = async (id) => {
        if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) return;
        
        try {
            const response = await fetch(`/admin/gallery-categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                }
            });
            const result = await handleResponse(response);
            alert(result.message);
            window.location.reload();
        } catch (error) {
            alert(`Gagal menghapus kategori: ${error.message}`);
        }
    };

    window.toggleCategoryStatus = async (id) => {
        try {
const response = await fetch(`/admin/gallery-categories/${id}/toggle-status`, {                method: 'PUT',
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            });
            const result = await handleResponse(response);
            
            const statusButton = document.querySelector(`button[onclick="toggleCategoryStatus(${id})"]`);
            const statusText = statusButton.nextElementSibling;
            const isActive = result.data.is_active; 
            
            statusButton.classList.toggle('bg-green-600', isActive);
            statusButton.classList.toggle('bg-gray-300', !isActive);
            statusButton.querySelector('span').classList.toggle('translate-x-6', isActive);
            statusButton.querySelector('span').classList.toggle('translate-x-1', !isActive);
            statusText.textContent = isActive ? 'Aktif' : 'Nonaktif';
            
            if (result.message) {
                alert(result.message);
            }
        } catch (error) {
            alert(`Gagal memperbarui status: ${error.message}`);
        }
    };

    class Gallery {
        constructor() {
            this.currentImageIndex = 0;
            this.images = [];
            this.categories = [];
            this.currentCategory = 'all';
            this.init();
        }

        init() {
            this.bindEvents();
            this.loadGallery();
            this.setupLightbox();
        }

        bindEvents() {
            document.querySelectorAll('.filter-button').forEach(button => {
                button.addEventListener('click', (e) => {
                    const category = e.target.dataset.category;
                    this.filterByCategory(category);
                });
            });

            const searchInput = document.getElementById('gallery-search');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    this.searchImages(e.target.value);
                });
            }

            const backToTopBtn = document.querySelector('.back-to-top');
            if (backToTopBtn) {
                backToTopBtn.addEventListener('click', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            window.addEventListener('scroll', () => {
                this.toggleBackToTop();
            });
        }

        loadGallery() {
            this.images = Array.from(document.querySelectorAll('.gallery-item'));
            this.categories = Array.from(document.querySelectorAll('.filter-button'))
                .map(btn => btn.dataset.category)
                .filter(cat => cat !== 'all');
            
            console.log('Gallery loaded:', this.images.length, 'images');
        }

        filterByCategory(category) {
            this.currentCategory = category;
            
            document.querySelectorAll('.filter-button').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.category === category) {
                    btn.classList.add('active');
                }
            });

            this.images.forEach((image, index) => {
                const imageCategory = image.dataset.category;
                if (category === 'all' || imageCategory === category) {
                    image.style.display = 'block';
                    image.style.animation = 'fadeIn 0.5s ease-in';
                } else {
                    image.style.display = 'none';
                }
            });

            this.updateImageCount();
        }

        searchImages(query) {
            const searchTerm = query.toLowerCase();
            
            this.images.forEach(image => {
                const title = image.querySelector('.gallery-item-title')?.textContent.toLowerCase() || '';
                const description = image.querySelector('.gallery-item-description')?.textContent.toLowerCase() || '';
                const category = image.querySelector('.gallery-category')?.textContent.toLowerCase() || '';
                
                const matches = title.includes(searchTerm) || 
                               description.includes(searchTerm) || 
                               category.includes(searchTerm);
                
                if (matches) {
                    image.style.display = 'block';
                    image.style.animation = 'fadeIn 0.3s ease-in';
                } else {
                    image.style.display = 'none';
                }
            });

            this.updateImageCount();
        }

        updateImageCount() {
            const visibleImages = this.images.filter(img => img.style.display !== 'none');
            const countElement = document.getElementById('image-count');
            if (countElement) {
                countElement.textContent = `${visibleImages.length} gambar`;
            }
        }

        setupLightbox() {
            const lightbox = document.createElement('div');
            lightbox.className = 'lightbox';
            lightbox.innerHTML = `
                <div class="lightbox-content">
                    <img class="lightbox-image" src="" alt="">
                    <button class="lightbox-close">&times;</button>
                    <button class="lightbox-nav lightbox-prev">&lt;</button>
                    <button class="lightbox-nav lightbox-next">&gt;</button>
                </div>
            `;
            
            document.body.appendChild(lightbox);

            const closeBtn = lightbox.querySelector('.lightbox-close');
            const prevBtn = lightbox.querySelector('.lightbox-prev');
            const nextBtn = lightbox.querySelector('.lightbox-next');

            closeBtn.addEventListener('click', () => this.closeLightbox());
            prevBtn.addEventListener('click', () => this.showPreviousImage());
            nextBtn.addEventListener('click', () => this.showNextImage());

            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    this.closeLightbox();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (!lightbox.classList.contains('active')) return;
                
                switch(e.key) {
                    case 'Escape':
                        this.closeLightbox();
                        break;
                    case 'ArrowLeft':
                        this.showPreviousImage();
                        break;
                    case 'ArrowRight':
                        this.showNextImage();
                        break;
                }
            });

            this.images.forEach((image, index) => {
                image.addEventListener('click', () => {
                    this.openLightbox(index);
                });
            });
        }

        openLightbox(imageIndex) {
            this.currentImageIndex = imageIndex;
            const image = this.images[imageIndex];
            const imgSrc = image.querySelector('img').src;
            const lightbox = document.querySelector('.lightbox');
            const lightboxImg = lightbox.querySelector('.lightbox-image');

            lightboxImg.src = imgSrc;
            lightboxImg.alt = image.querySelector('.gallery-item-title')?.textContent || '';
            lightbox.classList.add('active');

            this.updateLightboxNavigation();
        }

        closeLightbox() {
            const lightbox = document.querySelector('.lightbox');
            lightbox.classList.remove('active');
        }

        showPreviousImage() {
            this.currentImageIndex = (this.currentImageIndex - 1 + this.images.length) % this.images.length;
            this.updateLightboxImage();
            this.updateLightboxNavigation();
        }

        showNextImage() {
            this.currentImageIndex = (this.currentImageIndex + 1) % this.images.length;
            this.updateLightboxImage();
            this.updateLightboxNavigation();
        }

        updateLightboxImage() {
            const image = this.images[this.currentImageIndex];
            const imgSrc = image.querySelector('img').src;
            const lightboxImg = document.querySelector('.lightbox-image');
            
            lightboxImg.src = imgSrc;
            lightboxImg.alt = image.querySelector('.gallery-item-title')?.textContent || '';
        }

        updateLightboxNavigation() {
            const prevBtn = document.querySelector('.lightbox-prev');
            const nextBtn = document.querySelector('.lightbox-next');
            
            if (this.images.length <= 1) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'block';
                nextBtn.style.display = 'block';
            }
        }

        toggleBackToTop() {
            const backToTopBtn = document.querySelector('.back-to-top');
            if (!backToTopBtn) return;

            if (window.scrollY > 300) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        }

        refresh() {
            this.loadGallery();
            this.updateImageCount();
        }

        addImage(imageData) {
            console.log('Adding new image:', imageData);
        }

        removeImage(imageId) {
            console.log('Removing image:', imageId);
        }
    }

    if (document.querySelector('.gallery-item')) {
        window.gallery = new Gallery();
    }
});