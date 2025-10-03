@extends('frontend.main')

@section('title', 'UMKM Desa - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'UMKM DESA')
@section('header_icon', 'fas fa-store')
@section('header_bg_color', 'bg-orange-600')

@section('content')
<div class="xl:col-span-3">
    <!-- UMKM Overview -->
    <div class="bg-gradient-to-r fr                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);
                    
                    // Check if required data properties exist
                    if (!data.html || !data.pagination || !data.stats) {
                        throw new Error('Invalid response format');
                    }
                    
                    // Update UMKM cards with null check
                    const umkmContainer = document.getElementById('umkmContainer');
                    if (umkmContainer) {
                        umkmContainer.innerHTML = data.html;
                    } else {
                        throw new Error('umkmContainer element not found');
                    }
                    
                    // Update pagination with null check
                    const paginationContainer = document.getElementById('paginationContainer');
                    if (paginationContainer) {
                        paginationContainer.innerHTML = data.pagination;
                    } else {
                        throw new Error('paginationContainer element not found');
                    }
                    
                    // Update category statistics with null check
                    const categoryStats = document.getElementById('categoryStats');
                    if (categoryStats) {
                        categoryStats.innerHTML = data.stats;
                    } else {
                        throw new Error('categoryStats element not found');
                    }ed-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">UMKM Desa {{ $villageProfile->village_name ?? 'Krandegan' }}</h2>
                <p class="text-lg opacity-90 mb-4">
                    Memberdayakan ekonomi lokal melalui usaha mikro, kecil, dan menengah
                </p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $statistics['total_umkm'] }}</div>
                        <div class="text-sm opacity-90">Unit UMKM</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $statistics['total_workers'] }}</div>
                        <div class="text-sm opacity-90">Tenaga Kerja</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ number_format($statistics['monthly_revenue'] / 1000000, 1) }}M</div>
                        <div class="text-sm opacity-90">Omzet/Bulan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $statistics['categories_count'] }}</div>
                        <div class="text-sm opacity-90">Kategori Usaha</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-wrap gap-2" id="categoryFilters">
                <button data-category="" 
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ !request('category') ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Semua UMKM
                </button>
                <button data-category="makanan" 
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request('category') == 'makanan' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Makanan
                </button>
                <button data-category="kerajinan" 
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request('category') == 'kerajinan' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Kerajinan
                </button>
                <button data-category="pertanian" 
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request('category') == 'pertanian' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Pertanian
                </button>
                <button data-category="jasa" 
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request('category') == 'jasa' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Jasa
                </button>
                <button data-category="tekstil" 
                        class="category-filter px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ request('category') == 'tekstil' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Tekstil
                </button>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="flex items-center space-x-2">
                    <div class="relative">
                        <input type="text" id="searchInput" value="{{ request('search') }}" 
                               placeholder="Cari UMKM..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <button id="searchBtn" type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <button class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Daftar UMKM
                </button>
            </div>
        </div>
    </div>

    <!-- UMKM Directory -->
    <div id="umkmContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @forelse($umkms as $umkm)
        <div class="umkm-card {{ $umkm->category }} bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
            <div class="relative">
                @php
                    $photos = $umkm->photos ? (is_string($umkm->photos) ? json_decode($umkm->photos) : $umkm->photos) : [];
                    $mainPhoto = !empty($photos) ? $photos[0] : 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop';
                @endphp
                <img src="{{ $mainPhoto }}" 
                     alt="{{ $umkm->business_name }}" class="w-full h-48 object-cover">
                <div class="absolute top-4 left-4">
                    <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                        {{ $umkm->is_active ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
                <div class="absolute top-4 right-4">
                    @php
                        $categoryColors = [
                            'makanan' => 'bg-yellow-500',
                            'kerajinan' => 'bg-purple-500', 
                            'pertanian' => 'bg-green-500',
                            'jasa' => 'bg-blue-500',
                            'tekstil' => 'bg-pink-500',
                            'lainnya' => 'bg-gray-500'
                        ];
                        $color = $categoryColors[$umkm->category] ?? 'bg-gray-500';
                    @endphp
                    <span class="{{ $color }} text-white px-2 py-1 rounded-full text-xs font-medium">
                        {{ ucfirst($umkm->category) }}
                    </span>
                </div>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-gray-900 mb-2">{{ $umkm->business_name }}</h3>
                <p class="text-gray-600 text-sm mb-3">
                    {{ Str::limit($umkm->description, 100) }}
                </p>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-user mr-2 text-orange-500"></i>
                        <span>{{ $umkm->owner_name }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>
                        <span>{{ $umkm->address }}</span>
                    </div>
                    @if($umkm->phone)
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-phone mr-2 text-orange-500"></i>
                        <span>{{ $umkm->phone }}</span>
                    </div>
                    @endif
                    @if($umkm->operating_hours)
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-2 text-orange-500"></i>
                        <span>{{ $umkm->operating_hours }}</span>
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-3 border-t">
                    <div class="flex items-center">
                        <div class="flex text-yellow-400">
                            @php
                                $fullStars = floor($umkm->rating);
                                $hasHalfStar = ($umkm->rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                            @endphp
                            
                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                            
                            @if($hasHalfStar)
                                <i class="fas fa-star-half-alt"></i>
                            @endif
                            
                            @for($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600 ml-2">{{ number_format($umkm->rating, 1) }} ({{ $umkm->total_reviews }} ulasan)</span>
                    </div>
                    <a href="{{ route('umkm.show', $umkm->slug) }}" 
                       class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 text-sm transition duration-200">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-store text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada UMKM</h3>
            <p class="text-gray-400">Belum ada data UMKM yang tersedia saat ini.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination & Statistics -->
    <div id="paginationContainer" class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h3 id="paginationInfo" class="text-lg font-bold text-gray-900">
                Menampilkan {{ $umkms->firstItem() ?? 0 }} - {{ $umkms->lastItem() ?? 0 }} dari {{ $umkms->total() }} UMKM
            </h3>
            @if($umkms->hasPages())
                <div id="paginationLinks">{{ $umkms->links() }}</div>
            @endif
        </div>
        
        <!-- UMKM Statistics -->
        <div class="pt-6 border-t">
            <h3 class="font-bold text-gray-900 mb-4">Statistik UMKM Desa</h3>
            <div id="categoryStats" class="grid grid-cols-2 md:grid-cols-6 gap-4">
                @php
                    $categoryColors = [
                        'makanan' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
                        'kerajinan' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                        'pertanian' => ['bg' => 'bg-green-50', 'text' => 'text-green-600'],
                        'jasa' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                        'tekstil' => ['bg' => 'bg-pink-50', 'text' => 'text-pink-600'],
                        'lainnya' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600']
                    ];
                @endphp
                
                @foreach($categoryStats as $stat)
                    @php
                        $colors = $categoryColors[$stat->category] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
                    @endphp
                    <div class="text-center p-3 {{ $colors['bg'] }} rounded-lg">
                        <div class="text-xl font-bold {{ $colors['text'] }}">{{ $stat->count }}</div>
                        <div class="text-sm text-gray-600">{{ ucfirst($stat->category) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- UMKM Support Programs -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="font-bold text-gray-900 mb-4">Program Pemberdayaan UMKM</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-graduation-cap text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Pelatihan Kewirausahaan</h4>
                        <p class="text-sm text-gray-600">Program pelatihan rutin setiap bulan</p>
                    </div>
                </div>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li>• Digital Marketing & E-commerce</li>
                    <li>• Manajemen Keuangan Usaha</li>
                    <li>• Pengemasan & Branding Produk</li>
                    <li>• Legalitas & Perijinan Usaha</li>
                </ul>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-handshake text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Akses Permodalan</h4>
                        <p class="text-sm text-gray-600">Bantuan modal usaha terpadu</p>
                    </div>
                </div>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li>• Program Kredit Mikro Desa</li>
                    <li>• Bantuan Hibah UMKM</li>
                    <li>• Kemitraan dengan Bank Daerah</li>
                    <li>• Program CSR Perusahaan</li>
                </ul>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-store text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Pasar & Promosi</h4>
                        <p class="text-sm text-gray-600">Bantuan pemasaran dan promosi</p>
                    </div>
                </div>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li>• Bazar UMKM Bulanan</li>
                    <li>• Festival Produk Lokal</li>
                    <li>• Platform Online Desa</li>
                    <li>• Kemitraan Toko Modern</li>
                </ul>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-certificate text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Sertifikasi & Standardisasi</h4>
                        <p class="text-sm text-gray-600">Peningkatan kualitas produk</p>
                    </div>
                </div>
                <ul class="space-y-1 text-sm text-gray-600">
                    <li>• Sertifikasi Halal MUI</li>
                    <li>• PIRT (Pangan Industri Rumah Tangga)</li>
                    <li>• SNI (Standar Nasional Indonesia)</li>
                    <li>• Organic Certification</li>
                </ul>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t text-center">
            <p class="text-gray-600 mb-4">
                Tertarik bergabung atau ingin informasi lebih lanjut tentang program UMKM?
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar UMKM Baru
                </button>
                <button class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-info-circle mr-2"></i>
                    Info Program
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentCategory = '{{ request('category', '') }}';
        let currentSearch = '{{ request('search', '') }}';
        let isLoading = false;

        // Function to load UMKM data
        function loadUmkmData(category = '', search = '', showLoader = true) {
            console.log('loadUmkmData called with:', { category, search, showLoader });
            
            if (isLoading) {
                console.log('Already loading, skipping...');
                return;
            }
            isLoading = true;

            // Check if required elements exist
            const umkmContainer = document.getElementById('umkmContainer');
            const paginationContainer = document.getElementById('paginationContainer');
            const categoryStats = document.getElementById('categoryStats');
            
            // Debug: Log element existence
            console.log('Elements check before request:', {
                umkmContainer: umkmContainer ? 'found' : 'NOT FOUND',
                paginationContainer: paginationContainer ? 'found' : 'NOT FOUND', 
                categoryStats: categoryStats ? 'found' : 'NOT FOUND'
            });

            // If critical elements are missing, stop execution
            if (!umkmContainer) {
                console.error('Critical element umkmContainer not found - aborting AJAX call');
                isLoading = false;
                return;
            }            if (showLoader && umkmContainer) {
                umkmContainer.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-spinner fa-spin text-4xl text-orange-500 mb-4"></i>
                        <p class="text-gray-600">Memuat data UMKM...</p>
                    </div>
                `;
            }

            const url = new URL('/api/umkm-filter', window.location.origin);
            const params = new URLSearchParams();
            
            if (category) params.append('category', category);
            if (search) params.append('search', search);
            
            url.search = params.toString();
            console.log('Fetching URL:', url.toString());

            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);
                    
                    // Check if required data properties exist
                    if (!data.html || !data.pagination || !data.stats) {
                        throw new Error('Invalid response format');
                    }
                    
                    // Update UMKM cards with retry mechanism
                    setTimeout(() => {
                        const umkmContainer = document.getElementById('umkmContainer');
                        if (umkmContainer) {
                            umkmContainer.innerHTML = data.html;
                            console.log('Successfully updated umkmContainer');
                        } else {
                            console.error('umkmContainer element still not found after timeout');
                        }
                        
                        // Update pagination with null check
                        const paginationContainer = document.getElementById('paginationContainer');
                        if (paginationContainer) {
                            paginationContainer.innerHTML = data.pagination;
                            console.log('Successfully updated paginationContainer');
                        } else {
                            console.error('paginationContainer element not found');
                        }
                        
                        // Update category statistics with null check
                        const categoryStats = document.getElementById('categoryStats');
                        if (categoryStats) {
                            categoryStats.innerHTML = data.stats;
                            console.log('Successfully updated categoryStats');
                        } else {
                            console.error('categoryStats element not found');
                        }
                    }, 50);
                    
                    // Update overview statistics
                    if (data.statistics) {
                        updateOverviewStats(data.statistics);
                    }
                    
                    // Update URL without refresh
                    updateURL(category, search);
                    
                    // Re-attach event listeners for new elements
                    attachEventListeners();
                    
                    isLoading = false;
                })
                .catch(error => {
                    console.error('Error loading UMKM data:', error);
                    const umkmContainer = document.getElementById('umkmContainer');
                    if (umkmContainer) {
                        umkmContainer.innerHTML = `
                            <div class="col-span-full text-center py-12">
                                <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                                <p class="text-gray-600">Terjadi kesalahan saat memuat data: ${error.message}</p>
                                <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                                    <i class="fas fa-refresh mr-2"></i>Muat Ulang
                                </button>
                            </div>
                        `;
                    }
                    isLoading = false;
                });
        }

        // Function to update overview statistics
        function updateOverviewStats(stats) {
            const statsContainer = document.querySelector('.bg-gradient-to-r .grid');
            if (statsContainer) {
                const statElements = statsContainer.querySelectorAll('.text-center');
                if (statElements[0]) statElements[0].querySelector('.text-2xl').textContent = stats.total_umkm;
                if (statElements[1]) statElements[1].querySelector('.text-2xl').textContent = stats.total_workers;
                if (statElements[2]) statElements[2].querySelector('.text-2xl').textContent = (stats.monthly_revenue / 1000000).toFixed(1) + 'M';
                if (statElements[3]) statElements[3].querySelector('.text-2xl').textContent = stats.categories_count;
            }
        }

        // Function to update URL
        function updateURL(category, search) {
            const url = new URL(window.location);
            
            if (category) {
                url.searchParams.set('category', category);
            } else {
                url.searchParams.delete('category');
            }
            
            if (search) {
                url.searchParams.set('search', search);
            } else {
                url.searchParams.delete('search');
            }
            
            window.history.pushState({}, '', url);
        }

        // Function to update category filter buttons
        function updateCategoryButtons(activeCategory) {
            document.querySelectorAll('.category-filter').forEach(btn => {
                const category = btn.getAttribute('data-category');
                btn.classList.remove('bg-orange-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
                
                if (category === activeCategory) {
                    btn.classList.remove('bg-gray-200', 'text-gray-700');
                    btn.classList.add('bg-orange-600', 'text-white');
                }
            });
        }

        // Function to attach event listeners to new elements
        function attachEventListeners() {
            // Rating stars hover effect
            document.querySelectorAll('.fas.fa-star, .fas.fa-star-half-alt, .far.fa-star').forEach(star => {
                star.parentElement.removeEventListener('mouseenter', handleStarHover);
                star.parentElement.removeEventListener('mouseleave', handleStarLeave);
                star.parentElement.addEventListener('mouseenter', handleStarHover);
                star.parentElement.addEventListener('mouseleave', handleStarLeave);
            });
            
            // UMKM card hover effects
            document.querySelectorAll('.umkm-card').forEach(card => {
                card.removeEventListener('mouseenter', handleCardHover);
                card.removeEventListener('mouseleave', handleCardLeave);
                card.addEventListener('mouseenter', handleCardHover);
                card.addEventListener('mouseleave', handleCardLeave);
            });
        }

        // Hover event handlers
        function handleStarHover() {
            this.classList.add('transform', 'scale-110', 'transition-transform');
        }

        function handleStarLeave() {
            this.classList.remove('transform', 'scale-110');
        }

        function handleCardHover() {
            this.classList.add('transform', 'scale-105', 'transition-transform', 'duration-300');
        }

        function handleCardLeave() {
            this.classList.remove('transform', 'scale-105');
        }

        // Category filter event listeners
        const categoryButtons = document.querySelectorAll('.category-filter');
        console.log('Found category buttons:', categoryButtons.length);
        
        categoryButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const category = this.getAttribute('data-category');
                console.log('Category button clicked:', category);
                currentCategory = category;
                
                updateCategoryButtons(category);
                loadUmkmData(category, currentSearch);
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        
        console.log('Search elements:', {
            searchInput: searchInput ? 'found' : 'NOT FOUND',
            searchBtn: searchBtn ? 'found' : 'NOT FOUND'
        });

        function performSearch() {
            if (searchInput) {
                currentSearch = searchInput.value.trim();
                loadUmkmData(currentCategory, currentSearch);
            }
        }

        if (searchBtn) {
            searchBtn.addEventListener('click', performSearch);
        }
        
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });
        }

        // Debounced search input
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        currentSearch = this.value.trim();
                        loadUmkmData(currentCategory, currentSearch, false);
                    }
                }, 500);
            });
        }

        // UMKM registration button
        const registrationBtn = document.querySelector('button[class*="bg-orange-600"]:last-of-type');
        if (registrationBtn) {
            registrationBtn.addEventListener('click', function() {
                alert('Membuka formulir pendaftaran UMKM baru...');
            });
        }

        // Program info buttons
        const infoBtn = document.querySelector('button[class*="bg-gray-600"]');
        if (infoBtn) {
            infoBtn.addEventListener('click', function() {
                alert('Menampilkan informasi detail program pemberdayaan UMKM...');
            });
        }

        // Initialize event listeners
        attachEventListeners();

        // Handle browser back/forward
        window.addEventListener('popstate', function(e) {
            const url = new URL(window.location);
            const category = url.searchParams.get('category') || '';
            const search = url.searchParams.get('search') || '';
            
            currentCategory = category;
            currentSearch = search;
            
            document.getElementById('searchInput').value = search;
            updateCategoryButtons(category);
            loadUmkmData(category, search);
        });
    });
</script>
@endsection