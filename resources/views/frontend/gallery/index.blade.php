@extends('frontend.main')

@section('title', 'Galeri Foto - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'GALERI FOTO')
@section('header_icon', 'fas fa-images')
@section('header_bg_color', 'bg-purple-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Gallery Filter -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <h2 class="text-xl font-bold text-gray-900">Galeri Kegiatan Desa</h2>
                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ $galleries->count() ?? 0 }} Foto
                </span>
            </div>
            <div class="flex items-center space-x-2">
                <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Semua Kategori</option>
                    <option value="kegiatan">Kegiatan</option>
                    <option value="infrastruktur">Infrastruktur</option>
                    <option value="wisata">Wisata</option>
                    <option value="budaya">Budaya</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6" id="galleryGrid">
        @if(isset($galleries) && $galleries->count() > 0)
            @foreach($galleries as $gallery)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                <div class="relative overflow-hidden">
                    <img src="{{ $gallery->image_path ? asset('storage/' . $gallery->image_path) : '/images/placeholder-gallery.jpg' }}" 
                         alt="{{ $gallery->alt_text ?? $gallery->title }}" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer"
                         onclick="openLightbox('{{ $gallery->image_path ? asset('storage/' . $gallery->image_path) : '/images/placeholder-gallery.jpg' }}', '{{ $gallery->title }}', '{{ $gallery->description }}')">
                    <div class="absolute top-2 right-2">
                        <span class="bg-purple-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ ucfirst($gallery->category ?? 'Kegiatan') }}
                        </span>
                    </div>
                    @if($gallery->created_at)
                    <div class="absolute bottom-2 left-2">
                        <span class="bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                            {{ $gallery->created_at->format('d M Y') }}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 mb-2">{{ $gallery->title ?? 'Foto Kegiatan' }}</h3>
                    <p class="text-gray-600 text-sm mb-3">
                        {{ Str::limit($gallery->description ?? 'Dokumentasi kegiatan desa', 80) }}
                    </p>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span><i class="fas fa-eye mr-1"></i>{{ $gallery->views_count ?? 0 }} views</span>
                        <span><i class="fas fa-heart mr-1"></i>{{ $gallery->likes_count ?? 0 }} likes</span>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="col-span-full text-center py-12">
                <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Foto</h3>
                <p class="text-gray-500 mb-4">Galeri foto akan ditampilkan di sini setelah admin mengunggah foto kegiatan desa</p>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($galleries) && method_exists($galleries, 'links'))
    <div class="bg-white rounded-lg shadow-lg p-6">
        {{ $galleries->links() }}
    </div>
    @endif
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-[9999]">
    <!-- Close button - positioned outside the content -->
    <button id="closeLightbox" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 z-[10000] bg-black bg-opacity-50 rounded-full w-12 h-12 flex items-center justify-center">
        <i class="fas fa-times"></i>
    </button>
    
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="relative max-w-4xl w-full">
            <img id="lightboxImage" src="" alt="" class="w-full h-auto rounded-lg shadow-2xl">
            <div class="bg-white rounded-b-lg p-4">
                <h3 id="lightboxTitle" class="font-bold text-gray-900 mb-2"></h3>
                <p id="lightboxDescription" class="text-gray-600"></p>
            </div>
        </div>
    </div>
    
    <!-- Instructions -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-sm bg-black bg-opacity-50 px-3 py-1 rounded">
        Tekan ESC atau klik di luar gambar untuk menutup
    </div>
</div>


@endsection

@section('scripts')
<script>
    // Lightbox functionality
    function openLightbox(imageUrl, title, description) {
        console.log('Opening lightbox with:', imageUrl, title, description);
        
        const modal = document.getElementById('lightboxModal');
        const image = document.getElementById('lightboxImage');
        const titleEl = document.getElementById('lightboxTitle');
        const descriptionEl = document.getElementById('lightboxDescription');
        
        if (!modal) {
            console.error('Lightbox modal not found');
            return;
        }
        
        image.src = imageUrl;
        image.alt = title;
        titleEl.textContent = title;
        descriptionEl.textContent = description;
        modal.classList.remove('hidden');
        
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }

    // Close lightbox function
    function closeLightbox() {
        console.log('Closing lightbox');
        const modal = document.getElementById('lightboxModal');
        if (modal) {
            modal.classList.add('hidden');
            // Restore body scroll
            document.body.style.overflow = '';
        }
    }

    // Initialize lightbox when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing gallery lightbox');
        
        // Close lightbox button
        const closeLightboxBtn = document.getElementById('closeLightbox');
        if (closeLightboxBtn) {
            closeLightboxBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Close button clicked');
                closeLightbox();
            });
        } else {
            console.error('Close lightbox button not found');
        }

        // Close lightbox when clicking on backdrop
        const lightboxModal = document.getElementById('lightboxModal');
        if (lightboxModal) {
            lightboxModal.addEventListener('click', function(e) {
                // Only close if clicking on the modal backdrop, not on the content
                if (e.target === this) {
                    console.log('Backdrop clicked');
                    closeLightbox();
                }
            });
        }
        
        // Close lightbox with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('lightboxModal');
                if (modal && !modal.classList.contains('hidden')) {
                    console.log('Escape key pressed');
                    closeLightbox();
                }
            }
        });
        
        // Prevent clicks inside the lightbox content from closing the modal
        const lightboxContent = lightboxModal?.querySelector('.relative');
        if (lightboxContent) {
            lightboxContent.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });



    // Category filter
    document.getElementById('categoryFilter').addEventListener('change', function() {
        const selectedCategory = this.value;
        console.log('Filter by category:', selectedCategory);
        
        // In real app, this would filter the gallery
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-purple-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        toast.textContent = selectedCategory ? `Filter: ${this.options[this.selectedIndex].text}` : 'Menampilkan semua foto';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 3000);
    });

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Gallery page loaded successfully');
        
        // Add keyboard navigation for lightbox
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('lightboxModal').classList.add('hidden');
            }
        });
    });
</script>
@endsection