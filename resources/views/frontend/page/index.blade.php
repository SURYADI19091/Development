@extends('frontend.main')

@section('title', 'Website - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'WEBSITE DESA')
@section('header_icon', 'fas fa-tachometer-alt')
@section('header_bg_color', 'bg-teal-600')

@section('content')
<div class="xl:col-span-3">
        <!-- Banner Section -->
        @if($banners->count() > 0)
        <div class="mb-6">
            <div class="relative rounded-lg overflow-hidden shadow-lg">
                <div class="carousel-container">
                    @foreach($banners as $index => $banner)
                        <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            @if($banner->image_path)
                                <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}" class="w-full h-48 sm:h-64 lg:h-80 object-cover">
                            @else
                                <div class="w-full h-48 sm:h-64 lg:h-80 bg-gradient-to-r from-green-500 to-teal-600"></div>
                            @endif
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center">
                                <div class="container mx-auto px-4">
                                    <div class="text-white">
                                        <h2 class="text-2xl sm:text-4xl font-bold mb-2">{{ $banner->title }}</h2>
                                        @if($banner->subtitle)
                                            <p class="text-lg sm:text-xl mb-4 opacity-90">{{ $banner->subtitle }}</p>
                                        @endif
                                        @if($banner->description)
                                            <p class="text-sm sm:text-base mb-4 opacity-75">{{ $banner->description }}</p>
                                        @endif
                                        @if($banner->button_text && $banner->button_link)
                                            <a href="{{ $banner->button_link }}" class="inline-block bg-white text-green-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                                                {{ $banner->button_text }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($banners->count() > 1)
                    <!-- Carousel Navigation -->
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                        @foreach($banners as $index => $banner)
                            <button onclick="showSlide({{ $index }})" class="w-3 h-3 rounded-full bg-white bg-opacity-50 hover:bg-opacity-75 transition-all {{ $index === 0 ? 'bg-opacity-100' : '' }}" data-slide="{{ $index }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 text-white rounded-lg shadow-lg p-4 sm:p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-3xl font-bold mb-2">
                        @if(isset($user))
                            Halo, {{ $user->name }}!
                        @else
                            Selamat Datang di {{ $villageProfile->village_name ?? 'Desa Ciwulan' }}
                        @endif
                    </h1>
                    <p class="text-sm sm:text-lg opacity-90">{{ $villageProfile->village_name ?? 'Desa Ciwulan' }}, {{ $villageProfile->district ?? 'Telagasari' }}, {{ $villageProfile->regency ?? 'Karawang' }}</p>
                    @if(isset($welcomeMessage))
                        <p class="text-xs sm:text-sm opacity-90 mt-1">{{ $welcomeMessage }}</p>
                    @else
                        <p class="text-xs sm:text-sm opacity-75 mt-1">Sistem Informasi Desa Terpadu</p>
                    @endif
                    @if(isset($user))
                        <div class="flex items-center mt-2">
                            <span class="bg-white bg-opacity-20 px-2 py-1 rounded text-xs">
                                @switch($user->role)
                                    @case('admin')
                                        👑 Administrator
                                        @break
                                    @case('secretary')
                                        📝 Sekretaris Desa
                                        @break
                                    @case('village_head')
                                        🏛️ Kepala Desa
                                        @break
                                    @case('staff')
                                        👨‍💼 Staff Desa
                                        @break
                                    @default
                                        👤 User
                                @endswitch
                            </span>
                            @if($user->employee_id)
                                <span class="bg-white bg-opacity-20 px-2 py-1 rounded text-xs ml-2">
                                    ID: {{ $user->employee_id }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="hidden sm:block">
                    @if(isset($user))
                        <div class="text-right">
                            <i class="fas fa-user-circle text-4xl opacity-50 mb-2"></i>
                            <p class="text-xs opacity-75">{{ $user->email }}</p>
                        </div>
                    @else
                        <i class="fas fa-home text-4xl opacity-50"></i>
                    @endif
                </div>
            </div>
        </div>

        <!-- Role Permissions Info (only show if user is logged in) -->
        @if(isset($user) && isset($rolePermissions) && count($rolePermissions) > 0)
        <div class="bg-white rounded-lg shadow-md p-4 mb-6 border-l-4 border-indigo-500">
            <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-key text-indigo-500 mr-2"></i>
                Hak Akses Anda
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($rolePermissions as $permission)
                    <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-medium">
                        ✓ {{ $permission }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Penduduk</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ number_format($statistics['total_population']) }}</p>
                    </div>
                    <i class="fas fa-users text-blue-500 text-xl sm:text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Kepala Keluarga</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ number_format($statistics['total_families']) }}</p>
                    </div>
                    <i class="fas fa-home text-green-500 text-xl sm:text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Laki-laki</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ number_format($statistics['male_population']) }}</p>
                    </div>
                    <i class="fas fa-male text-yellow-500 text-xl sm:text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-3 sm:p-4 border-l-4 border-pink-500">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Perempuan</p>
                        <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ number_format($statistics['female_population']) }}</p>
                    </div>
                    <i class="fas fa-female text-pink-500 text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <!-- Demographics Chart -->
            <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-teal-600"></i>
                    Demografi Penduduk
                </h3>
                
                <div class="bg-gray-50 rounded-lg p-4 sm:p-6 text-center">
                    @if($__env->yieldContent('chart_content'))
                        @yield('chart_content')
                    @else
                        <div class="w-32 h-32 sm:w-48 sm:h-48 mx-auto bg-gradient-to-br from-teal-100 to-green-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-chart-pie text-3xl sm:text-5xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 text-sm sm:text-base">Grafik demografi akan ditampilkan di sini</p>
                    @endif
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-blue-600"></i>
                    Aktivitas Terbaru
                </h3>
                
                <div class="space-y-3">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <i class="{{ $activity['icon'] }} {{ $activity['color'] }} mt-1"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium">{{ $activity['title'] }}</p>
                                <p class="text-xs text-gray-600">{{ $activity['time'] }} • {{ $activity['author'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-info-circle text-gray-400 mt-1"></i>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">Belum ada aktivitas terbaru</p>
                                <p class="text-xs text-gray-400">Aktivitas akan muncul di sini</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-4 text-center">
                    <a href="#" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                        Lihat Semua Aktivitas →
                    </a>
                </div>
            </div>
        </div>

        <!-- Village Services -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-concierge-bell mr-2 text-indigo-600"></i>
                Layanan Desa
            </h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                <div class="text-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
                    <i class="fas fa-id-card text-2xl text-blue-600 mb-2"></i>
                    <p class="text-xs font-medium">Surat Pengantar</p>
                </div>
                
                <div class="text-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
                    <i class="fas fa-home text-2xl text-green-600 mb-2"></i>
                    <p class="text-xs font-medium">Surat Domisili</p>
                </div>
                
                <div class="text-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
                    <i class="fas fa-user-friends text-2xl text-purple-600 mb-2"></i>
                    <p class="text-xs font-medium">Surat Nikah</p>
                </div>
                
                <div class="text-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
                    <i class="fas fa-graduation-cap text-2xl text-orange-600 mb-2"></i>
                    <p class="text-xs font-medium">Surat Sekolah</p>
                </div>
                
                <div class="text-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
                    <i class="fas fa-briefcase text-2xl text-teal-600 mb-2"></i>
                    <p class="text-xs font-medium">Surat Usaha</p>
                </div>
                
                <div class="text-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors">
                    <i class="fas fa-ellipsis-h text-2xl text-gray-600 mb-2"></i>
                    <p class="text-xs font-medium">Lainnya</p>
                </div>
            </div>
        </div>

        <!-- Village Map Section -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-map-marked-alt mr-2 text-green-600"></i>
                Peta Wilayah Desa
            </h3>
            
            <div class="bg-gray-50 rounded-lg p-2 sm:p-4">
                @if($__env->yieldContent('village_map'))
                    @yield('village_map')
                @else
                    <!-- OpenStreetMap Container -->
                    <div id="village-map" class="w-full h-64 sm:h-80 lg:h-96 rounded-lg bg-gray-200 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin text-2xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500 text-sm">Memuat peta desa...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Map Controls -->
                    <div class="mt-3 flex flex-wrap gap-2 justify-center sm:justify-start">
                        <button onclick="centerMap()" class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-home mr-1"></i> Pusat Desa
                        </button>
                        <button onclick="toggleSatellite()" class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-satellite mr-1"></i> Satelit
                        </button>
                        <button onclick="fullscreenMap()" class="px-3 py-1 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-expand mr-1"></i> Layar Penuh
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Info -->
        <div class="grid sm:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-check mr-2 text-red-600"></i>
                    Agenda Mendatang
                </h3>
                
                <div class="space-y-3">
                    @forelse($upcomingAgenda as $agenda)
                        @php
                            $eventDate = \Carbon\Carbon::parse($agenda->event_date);
                            $colors = ['red', 'blue', 'green', 'purple', 'yellow', 'pink'];
                            $color = $colors[array_rand($colors)];
                        @endphp
                        <div class="flex items-start space-x-3 p-3 border-l-4 border-{{ $color }}-500 bg-{{ $color }}-50">
                            <div class="text-center">
                                <p class="text-xs font-bold text-{{ $color }}-600">{{ $eventDate->format('d') }}</p>
                                <p class="text-xs text-{{ $color }}-600">{{ $eventDate->format('M') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium">{{ $agenda->title }}</p>
                                <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($agenda->start_time)->format('H:i') }} WIB • {{ $agenda->location }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-start space-x-3 p-3 border-l-4 border-gray-500 bg-gray-50">
                            <div class="text-center">
                                <p class="text-xs font-bold text-gray-600">--</p>
                                <p class="text-xs text-gray-600">---</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Belum ada agenda</p>
                                <p class="text-xs text-gray-400">Agenda akan muncul di sini</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bullhorn mr-2 text-yellow-600"></i>
                    Pengumuman Penting
                </h3>
                
                <div class="space-y-3">
                    @forelse($importantAnnouncements as $announcement)
                        @php
                            $priorityColors = [
                                'urgent' => 'red',
                                'high' => 'orange', 
                                'medium' => 'yellow',
                                'low' => 'blue'
                            ];
                            $color = $priorityColors[$announcement->priority] ?? 'gray';
                        @endphp
                        <div class="p-3 bg-{{ $color }}-50 border-l-4 border-{{ $color }}-500">
                            <p class="text-sm font-medium text-{{ $color }}-800">{{ $announcement->title }}</p>
                            <p class="text-xs text-{{ $color }}-700 mt-1">
                                @if($announcement->valid_until)
                                    Berlaku hingga: {{ \Carbon\Carbon::parse($announcement->valid_until)->format('d M Y') }}
                                @else
                                    {{ \Str::limit(strip_tags($announcement->content), 80) }}
                                @endif
                            </p>
                        </div>
                    @empty
                        <div class="p-3 bg-gray-50 border-l-4 border-gray-500">
                            <p class="text-sm font-medium text-gray-500">Belum ada pengumuman penting</p>
                            <p class="text-xs text-gray-400 mt-1">Pengumuman akan muncul di sini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent News Section -->
        @if($recentNews->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-newspaper mr-2 text-blue-600"></i>
                Berita Terbaru
            </h3>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($recentNews as $news)
                    <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                        @if($news->featured_image)
                            <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" class="w-full h-32 object-cover">
                        @else
                            <div class="w-full h-32 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                <i class="fas fa-newspaper text-2xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="p-3">
                            <h4 class="font-semibold text-sm text-gray-800 mb-1 line-clamp-2">{{ $news->title }}</h4>
                            <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ $news->excerpt }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $news->created_at->diffForHumans() }}</span>
                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded">{{ $news->category_label }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 text-center">
                <a href="{{ route('news.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Lihat Semua Berita →
                </a>
            </div>
        </div>
        @endif

        <!-- Recent Gallery Section -->
        @if($recentGallery->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 mb-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-images mr-2 text-purple-600"></i>
                Galeri Terbaru
            </h3>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($recentGallery as $gallery)
                    <div class="relative group cursor-pointer overflow-hidden rounded-lg">
                        <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-24 sm:h-32 object-cover group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-2">
                            <p class="text-white text-xs font-medium truncate">{{ $gallery->title }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 text-center">
                <a href="{{ route('gallery.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                    Lihat Semua Galeri →
                </a>
            </div>
        </div>
        @endif

    <!-- Additional Content -->
    @yield('additional_content')
</div>
@endsection

@section('scripts')

<!-- Banner Carousel Styles and Scripts -->
<style>
    .carousel-container {
        position: relative;
    }
    
    .carousel-slide {
        width: 100%;
        transition: opacity 0.5s ease-in-out;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    let currentSlide = 0;
    let slides = [];
    let slideInterval = null;
    
    function initCarousel() {
        slides = document.querySelectorAll('.carousel-slide');
        if (slides.length > 1) {
            startAutoSlide();
        }
    }
    
    function showSlide(index) {
        // Hide all slides
        slides.forEach((slide, i) => {
            slide.style.display = 'none';
            const button = document.querySelector(`[data-slide="${i}"]`);
            if (button) {
                button.classList.remove('bg-opacity-100');
                button.classList.add('bg-opacity-50');
            }
        });
        
        // Show current slide
        if (slides[index]) {
            slides[index].style.display = 'block';
            const button = document.querySelector(`[data-slide="${index}"]`);
            if (button) {
                button.classList.remove('bg-opacity-50');
                button.classList.add('bg-opacity-100');
            }
        }
        
        currentSlide = index;
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    function startAutoSlide() {
        slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }
    
    function stopAutoSlide() {
        if (slideInterval) {
            clearInterval(slideInterval);
        }
    }
    
    // Initialize carousel when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        initCarousel();
        
        // Pause auto-slide on hover
        const carouselContainer = document.querySelector('.carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoSlide);
            carouselContainer.addEventListener('mouseleave', () => {
                if (slides.length > 1) {
                    startAutoSlide();
                }
            });
        }
    });
</script>

<!-- Leaflet CSS and JS for OpenStreetMap -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<!-- Village Map Script -->
<script>
    let villageMap = null;
    let isSatellite = false;
    let villageMarker = null;
    
    // Village coordinates (Desa Ciwulan, Telagasari, Karawang)
    const villageCoords = [-6.258346, 107.435520]; // Latitude, Longitude
    
    function initVillageMap() {
        // Check if map container exists
        if (!document.getElementById('village-map')) return;
        
        try {
            // Initialize map
            villageMap = L.map('village-map').setView(villageCoords, 15);
            
            // Add OpenStreetMap tile layer
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(villageMap);
            
            // Add village marker
            villageMarker = L.marker(villageCoords).addTo(villageMap)
                .bindPopup('<b>Desa Ciuwlan</b><br>Telagasari, Karawang<br><small>Kantor Desa & Balai Desa</small>')
                .openPopup();
            
            // Load locations from database
            loadDatabaseLocations();
            
            console.log('Village map initialized successfully');
            
        } catch (error) {
            console.error('Error initializing map:', error);
            document.getElementById('village-map').innerHTML = 
                '<div class="flex items-center justify-center h-full"><div class="text-center"><i class="fas fa-exclamation-triangle text-2xl text-red-400 mb-2"></i><p class="text-red-500 text-sm">Gagal memuat peta</p></div></div>';
        }
    }
    
    function centerMap() {
        if (villageMap) {
            villageMap.setView(villageCoords, 15);
            if (villageMarker) {
                villageMarker.openPopup();
            }
        }
    }
    
    function toggleSatellite() {
        if (!villageMap) return;
        
        // Remove all tile layers
        villageMap.eachLayer(function(layer) {
            if (layer instanceof L.TileLayer) {
                villageMap.removeLayer(layer);
            }
        });
        
        if (!isSatellite) {
            // Switch to satellite view
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri, Maxar, Earthstar Geographics'
            }).addTo(villageMap);
            isSatellite = true;
        } else {
            // Switch back to street view
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(villageMap);
            isSatellite = false;
        }
    }
    
    function fullscreenMap() {
        const mapContainer = document.getElementById('village-map');
        if (mapContainer) {
            if (mapContainer.requestFullscreen) {
                mapContainer.requestFullscreen();
            } else if (mapContainer.webkitRequestFullscreen) {
                mapContainer.webkitRequestFullscreen();
            } else if (mapContainer.msRequestFullscreen) {
                mapContainer.msRequestFullscreen();
            }
            
            // Resize map after entering fullscreen
            setTimeout(() => {
                if (villageMap) {
                    villageMap.invalidateSize();
                }
            }, 500);
        }
    }
    
    function loadDatabaseLocations() {
        // Fetch locations from database via API
        fetch('/api/locations')
            .then(response => response.json())
            .then(locations => {
                locations.forEach(location => {
                    const customIcon = L.divIcon({
                        html: `<div style="background-color: ${location.color}; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"><i class="${location.icon}" style="color: white; font-size: 12px;"></i></div>`,
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    });
                    
                    const popupContent = `
                        <div style="min-width: 200px;">
                            <h4 style="margin: 0 0 8px 0; font-weight: bold;">${location.name}</h4>
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #666;">${location.description}</p>
                            ${location.address ? `<p style="margin: 0 0 4px 0; font-size: 11px;"><i class="fas fa-map-marker-alt" style="width: 12px;"></i> ${location.address}</p>` : ''}
                            ${location.phone ? `<p style="margin: 0 0 4px 0; font-size: 11px;"><i class="fas fa-phone" style="width: 12px;"></i> ${location.phone}</p>` : ''}
                            <p style="margin: 0; font-size: 11px; color: #888;"><strong>Tipe:</strong> ${location.type_name}</p>
                        </div>
                    `;
                    
                    L.marker([location.latitude, location.longitude], { icon: customIcon })
                        .addTo(villageMap)
                        .bindPopup(popupContent);
                });
            })
            .catch(error => {
                console.error('Error loading locations:', error);
            });
    }
    
    // Initialize map when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Add a small delay to ensure the container is properly rendered
        setTimeout(initVillageMap, 100);
    });
    
    // Handle fullscreen exit
    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement && villageMap) {
            setTimeout(() => {
                villageMap.invalidateSize();
            }, 500);
        }
    });
</script>
@endsection
