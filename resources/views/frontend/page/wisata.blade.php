@extends('frontend.main')

@section('title', 'Wisata Desa - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'WISATA DESA')
@section('header_icon', 'fas fa-mountain')
@section('header_bg_color', 'bg-teal-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Tourism Hero -->
    <div class="bg-gradient-to-r from-teal-500 to-blue-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">Pesona Wisata Krandegan</h2>
                <p class="text-lg opacity-90 mb-4">
                    Nikmati keindahan alam dan kearifan lokal Desa Krandegan
                </p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $tourism ? count($tourism) : 0 }}</div>
                        <div class="text-sm opacity-90">Destinasi Wisata</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $featuredTourism ? count($featuredTourism) : 0 }}</div>
                        <div class="text-sm opacity-90">Destinasi Unggulan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            @php
                                $categories = $tourism ? $tourism->pluck('category')->unique()->count() : 0;
                            @endphp
                            {{ $categories }}
                        </div>
                        <div class="text-sm opacity-90">Kategori Wisata</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            {{ $tourism ? $tourism->where('ticket_price', 0)->count() : 0 }}
                        </div>
                        <div class="text-sm opacity-90">Wisata Gratis</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-camera text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Featured Destinations -->
    @if($featuredTourism && count($featuredTourism) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Main Featured -->
            @php $featured = $featuredTourism->first(); @endphp
            <div class="lg:col-span-2 relative bg-white rounded-lg shadow-lg overflow-hidden">
                @php 
                    $images = is_string($featured->images) ? json_decode($featured->images, true) : $featured->images;
                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=400&fit=crop';
                @endphp
                <img src="{{ $firstImage }}" 
                     alt="{{ $featured->name }}" class="w-full h-64 object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">{{ $featured->name }}</h3>
                            <p class="opacity-90 mb-2">
                                {{ $featured->description }}
                            </p>
                            <div class="flex items-center space-x-4 text-sm">
                                <span><i class="fas fa-tag mr-1"></i>{{ ucfirst($featured->category) }}</span>
                                @if($featured->operating_hours)
                                    <span><i class="fas fa-clock mr-1"></i>{{ $featured->operating_hours }}</span>
                                @endif
                                @if($featured->ticket_price > 0)
                                    <span><i class="fas fa-ticket-alt mr-1"></i>Rp {{ number_format($featured->ticket_price, 0, ',', '.') }}</span>
                                @else
                                    <span><i class="fas fa-ticket-alt mr-1"></i>Gratis</span>
                                @endif
                            </div>
                        </div>
                        <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition duration-200">
                            <i class="fas fa-info-circle mr-2"></i>Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Destination Categories -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h3 class="text-xl font-bold text-gray-900">Jelajahi Destinasi</h3>
            <div class="flex flex-wrap gap-2">
                <button class="dest-filter active px-4 py-2 rounded-lg text-sm font-medium transition duration-200" data-filter="all">
                    Semua
                </button>
                @if($tourism && count($tourism) > 0)
                    @php
                        $categories = $tourism->pluck('category')->unique()->sort();
                        $categoryNames = [
                            'alam' => 'Wisata Alam',
                            'budaya' => 'Wisata Budaya',
                            'kuliner' => 'Kuliner',
                            'edukasi' => 'Edukasi',
                            'rekreasi' => 'Rekreasi',
                            'religi' => 'Religi'
                        ];
                    @endphp
                    @foreach($categories as $category)
                        <button class="dest-filter px-4 py-2 rounded-lg text-sm font-medium transition duration-200" data-filter="{{ $category }}">
                            {{ $categoryNames[$category] ?? ucfirst($category) }}
                        </button>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Destination Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6" id="destination-grid">
        @if($tourism && count($tourism) > 0)
            @foreach($tourism as $item)
                @php
                    $images = is_string($item->images) ? json_decode($item->images, true) : $item->images;
                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : 'https://images.unsplash.com/photo-1571115764595-644a1f56a55c?w=400&h=250&fit=crop';
                    
                    // Category color mapping
                    $categoryColors = [
                        'alam' => 'bg-green-500',
                        'budaya' => 'bg-red-500', 
                        'kuliner' => 'bg-yellow-500',
                        'edukasi' => 'bg-blue-500',
                        'rekreasi' => 'bg-purple-500',
                        'religi' => 'bg-indigo-500'
                    ];
                    $categoryColor = $categoryColors[$item->category] ?? 'bg-gray-500';
                @endphp
                
                <div class="destination-card {{ $item->category }} bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                    <div class="relative">
                        <img src="{{ $firstImage }}" 
                             alt="{{ $item->name }}" class="w-full h-48 object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="{{ $categoryColor }} text-white px-2 py-1 rounded-full text-xs font-medium">
                                {{ ucfirst($item->category) }}
                            </span>
                        </div>
                        @if($item->is_featured)
                            <div class="absolute top-4 right-4">
                                <div class="flex items-center bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span>Featured</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-2">{{ $item->name }}</h3>
                        <p class="text-gray-600 text-sm mb-3">
                            {{ Str::limit($item->description, 100) }}
                        </p>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-teal-500"></i>
                                <span>{{ $item->address }}</span>
                            </div>
                            @if($item->operating_hours)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock mr-2 text-teal-500"></i>
                                    <span>{{ $item->operating_hours }}</span>
                                </div>
                            @endif
                            @if($item->ticket_price !== null)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-ticket-alt mr-2 text-teal-500"></i>
                                    <span>
                                        @if($item->ticket_price > 0)
                                            Rp {{ number_format($item->ticket_price, 0, ',', '.') }}
                                        @else
                                            Gratis
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t">
                            <div class="flex space-x-2">
                                @if($item->facilities)
                                    @php
                                        $facilities = explode(',', $item->facilities);
                                    @endphp
                                    @foreach(array_slice($facilities, 0, 2) as $facility)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            {{ trim($facility) }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                            <button class="px-3 py-1 bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 text-sm transition duration-200">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-span-3 text-center py-12">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-map-marked-alt text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-500 mb-2">Belum Ada Destinasi Wisata</h3>
                <p class="text-gray-400">Data destinasi wisata akan segera ditambahkan</p>
            </div>
        @endif
    </div>

    <!-- Tourism Services -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6">Layanan Wisata</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Homestay -->
            <div class="text-center p-6 border border-gray-200 rounded-lg hover:shadow-md transition duration-200">
                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-home text-teal-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Homestay</h4>
                <p class="text-gray-600 text-sm mb-4">
                    15 homestay bersertifikat dengan fasilitas lengkap dan pelayanan ramah
                </p>
                <div class="space-y-1 text-sm text-gray-600 mb-4">
                    <div>• AC & Air Panas</div>
                    <div>• WiFi Gratis</div>
                    <div>• Sarapan Tradisional</div>
                    <div>• Mulai Rp 150k/malam</div>
                </div>
                <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm transition duration-200">
                    Lihat Homestay
                </button>
            </div>

            <!-- Tour Guide -->
            <div class="text-center p-6 border border-gray-200 rounded-lg hover:shadow-md transition duration-200">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-tie text-blue-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Pemandu Wisata</h4>
                <p class="text-gray-600 text-sm mb-4">
                    Pemandu lokal berpengalaman yang menguasai sejarah dan budaya desa
                </p>
                <div class="space-y-1 text-sm text-gray-600 mb-4">
                    <div>• Bersertifikat Resmi</div>
                    <div>• Bahasa Indonesia & Jawa</div>
                    <div>• Paket Tur Harian</div>
                    <div>• Rp 100k/hari</div>
                </div>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition duration-200">
                    Booking Guide
                </button>
            </div>

            <!-- Transportation -->
            <div class="text-center p-6 border border-gray-200 rounded-lg hover:shadow-md transition duration-200">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-car text-green-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Transportasi</h4>
                <p class="text-gray-600 text-sm mb-4">
                    Layanan antar-jemput dan rental kendaraan untuk kemudahan wisatawan
                </p>
                <div class="space-y-1 text-sm text-gray-600 mb-4">
                    <div>• Mobil & Motor</div>
                    <div>• Sopir Berpengalaman</div>
                    <div>• Antar-Jemput Bandara</div>
                    <div>• Mulai Rp 300k/hari</div>
                </div>
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm transition duration-200">
                    Sewa Kendaraan
                </button>
            </div>
        </div>
    </div>

    <!-- Tourism Packages -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-gray-900">Paket Wisata</h3>
            <button class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                Lihat Semua Paket <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Package 1 -->
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-gray-900">Paket Alam 2D1N</h4>
                    <span class="text-xl font-bold text-teal-600">Rp 450K</span>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Puncak Krandegan + Telaga Hijau</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Homestay 1 malam + 3x makan</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Pemandu wisata + transportasi</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Trekking + fotografi</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Min. 2 orang</span>
                    <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition duration-200">
                        Pesan Sekarang
                    </button>
                </div>
            </div>

            <!-- Package 2 -->
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-gray-900">Paket Edukasi 1 Hari</h4>
                    <span class="text-xl font-bold text-teal-600">Rp 125K</span>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Agrowisata Organik + Rumah Budaya</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Makan siang tradisional</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Workshop kerajinan tangan</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span>Oleh-oleh produk lokal</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Min. 10 orang</span>
                    <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition duration-200">
                        Pesan Sekarang
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t text-center">
            <p class="text-gray-600 mb-4">
                Ingin paket wisata yang disesuaikan dengan kebutuhan Anda?
            </p>
            <button class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-envelope mr-2"></i>
                Konsultasi Custom Package
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Destination filtering
    const destFilters = document.querySelectorAll('.dest-filter');
    const destCards = document.querySelectorAll('.destination-card');

    destFilters.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            destFilters.forEach(btn => {
                btn.classList.remove('active', 'bg-teal-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            // Add active class to clicked button
            this.classList.add('active', 'bg-teal-600', 'text-white');
            this.classList.remove('bg-gray-200', 'text-gray-700');
            
            const filter = this.getAttribute('data-filter');
            
            // Filter destination cards
            destCards.forEach(card => {
                if (filter === 'all' || card.classList.contains(filter)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Initialize filter buttons
    destFilters.forEach(button => {
        if (button.classList.contains('active')) {
            button.classList.add('bg-teal-600', 'text-white');
        } else {
            button.classList.add('bg-gray-200', 'text-gray-700');
        }
    });

    // Destination detail buttons
    document.querySelectorAll('button[class*="bg-teal-100"]').forEach(button => {
        button.addEventListener('click', function() {
            const destCard = this.closest('.destination-card');
            const destName = destCard.querySelector('h3').textContent;
            
            // In a real application, this would navigate to destination detail page
            alert(`Menampilkan detail ${destName}...`);
        });
    });

    // Tourism service buttons
    document.querySelectorAll('button[class*="bg-teal-600"], button[class*="bg-blue-600"], button[class*="bg-green-600"]').forEach(button => {
        if (button.textContent.includes('Homestay') || button.textContent.includes('Guide') || button.textContent.includes('Kendaraan')) {
            button.addEventListener('click', function() {
                const service = this.textContent.trim();
                // In a real application, this would navigate to booking page
                alert(`Membuka halaman booking untuk ${service}...`);
            });
        }
    });

    // Package booking buttons
    document.querySelectorAll('button[class*="bg-teal-600"]:not([class*="bg-teal-100"])').forEach(button => {
        if (button.textContent.includes('Pesan Sekarang')) {
            button.addEventListener('click', function() {
                const packageCard = this.closest('.border');
                const packageName = packageCard.querySelector('h4').textContent;
                
                // In a real application, this would open booking form
                alert(`Membuka formulir pemesanan untuk ${packageName}...`);
            });
        }
    });

    // Custom package consultation
    document.querySelector('button[class*="bg-orange-600"]').addEventListener('click', function() {
        // In a real application, this would open consultation form or WhatsApp
        alert('Menghubungi tim konsultasi untuk paket wisata khusus...');
    });

    // Star rating hover effect
    document.querySelectorAll('.fas.fa-star').forEach(star => {
        const ratingContainer = star.closest('.flex.items-center');
        if (ratingContainer) {
            ratingContainer.addEventListener('mouseenter', function() {
                this.classList.add('transform', 'scale-110');
            });
            
            ratingContainer.addEventListener('mouseleave', function() {
                this.classList.remove('transform', 'scale-110');
            });
        }
    });

    // Parallax effect for hero section (simple)
    window.addEventListener('scroll', function() {
        const hero = document.querySelector('.bg-gradient-to-r.from-teal-500');
        if (hero) {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            hero.style.transform = `translateY(${rate}px)`;
        }
    });
</script>
@endsection