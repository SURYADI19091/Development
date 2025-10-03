@extends('frontend.main')

@section('title', 'UMKM ' . $categoryName . ' - Desa ' . strtoupper($villageProfile->village_name ?? 'Krandegan'))
@section('page_title', 'UMKM ' . strtoupper($categoryName))
@section('header_icon', 'fas fa-store')
@section('header_bg_color', 'bg-orange-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Back Navigation -->
    <div class="mb-6">
        <a href="{{ route('umkm.index') }}" class="inline-flex items-center text-orange-600 hover:text-orange-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar UMKM
        </a>
    </div>

    <!-- Category Header -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">UMKM {{ $categoryName }}</h2>
                <p class="text-lg opacity-90 mb-4">
                    Daftar usaha mikro, kecil, dan menengah dalam kategori {{ strtolower($categoryName) }}
                </p>
                <div class="flex items-center">
                    <div class="text-2xl font-bold">{{ $umkms->total() }}</div>
                    <div class="text-sm opacity-90 ml-2">UMKM {{ $categoryName }}</div>
                </div>
            </div>
            <div class="hidden md:block">
                @php
                    $categoryIcons = [
                        'makanan' => 'fas fa-utensils',
                        'kerajinan' => 'fas fa-hammer',
                        'pertanian' => 'fas fa-seedling',
                        'jasa' => 'fas fa-cogs',
                        'tekstil' => 'fas fa-tshirt',
                        'lainnya' => 'fas fa-ellipsis-h'
                    ];
                    $icon = $categoryIcons[$category] ?? 'fas fa-store';
                @endphp
                <i class="{{ $icon }} text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('umkm.index') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Semua UMKM
                </a>
                <a href="{{ route('umkm.category', 'makanan') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ $category == 'makanan' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Makanan
                </a>
                <a href="{{ route('umkm.category', 'kerajinan') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ $category == 'kerajinan' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Kerajinan
                </a>
                <a href="{{ route('umkm.category', 'pertanian') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ $category == 'pertanian' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Pertanian
                </a>
                <a href="{{ route('umkm.category', 'jasa') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ $category == 'jasa' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Jasa
                </a>
                <a href="{{ route('umkm.category', 'tekstil') }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition duration-200 {{ $category == 'tekstil' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Tekstil
                </a>
            </div>
            
            <div class="flex items-center space-x-2">
                <form method="GET" action="{{ route('umkm.category', $category) }}" class="flex items-center space-x-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari dalam {{ $categoryName }}..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- UMKM Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @forelse($umkms as $umkm)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
            <div class="relative">
                @php
                    $photos = $umkm->photos ? (is_string($umkm->photos) ? json_decode($umkm->photos) : $umkm->photos) : [];
                    $mainPhoto = !empty($photos) ? $photos[0] : 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop';
                @endphp
                <img src="{{ $mainPhoto }}" 
                     alt="{{ $umkm->business_name }}" class="w-full h-48 object-cover">
                <div class="absolute top-4 left-4">
                    <span class="bg-{{ $umkm->is_active ? 'green' : 'red' }}-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                        {{ $umkm->is_active ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
                @if($umkm->is_verified)
                <div class="absolute top-4 right-4">
                    <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-check-circle mr-1"></i>Verified
                    </span>
                </div>
                @endif
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
                        <span class="text-sm text-gray-600 ml-2">{{ number_format($umkm->rating, 1) }} ({{ $umkm->total_reviews }})</span>
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
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Tidak Ada UMKM {{ $categoryName }}</h3>
            <p class="text-gray-400 mb-4">Belum ada UMKM dalam kategori {{ strtolower($categoryName) }} saat ini.</p>
            <a href="{{ route('umkm.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Semua UMKM
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($umkms->hasPages())
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Menampilkan {{ $umkms->firstItem() }} - {{ $umkms->lastItem() }} dari {{ $umkms->total() }} UMKM {{ $categoryName }}
                </p>
            </div>
            <div>
                {{ $umkms->links() }}
            </div>
        </div>
    </div>
    @endif

    <!-- Category Description -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="font-bold text-gray-900 mb-4">Tentang UMKM {{ $categoryName }}</h3>
        
        @php
            $categoryDescriptions = [
                'makanan' => 'UMKM makanan mencakup warung makan, katering, produksi kue, dan usaha kuliner lainnya yang menyediakan berbagai cita rasa tradisional dan modern untuk masyarakat desa.',
                'kerajinan' => 'UMKM kerajinan menghasilkan berbagai produk handmade seperti kerajinan bambu, kayu, anyaman, dan karya seni tradisional yang mencerminkan kearifan lokal desa.',
                'pertanian' => 'UMKM pertanian meliputi produksi hasil tani organik, budidaya ikan, peternakan, dan pengolahan hasil pertanian yang mendukung ketahanan pangan desa.',
                'jasa' => 'UMKM jasa menyediakan berbagai layanan seperti bengkel, salon, laundry, dan jasa lainnya yang memenuhi kebutuhan sehari-hari masyarakat desa.',
                'tekstil' => 'UMKM tekstil bergerak dalam produksi pakaian, konveksi, bordir, dan kerajinan kain yang mengangkat motif dan budaya lokal desa.',
                'lainnya' => 'UMKM kategori lainnya mencakup berbagai usaha unik dan inovatif yang tidak termasuk dalam kategori utama namun tetap berkontribusi pada perekonomian desa.'
            ];
        @endphp
        
        <p class="text-gray-700 mb-4">
            {{ $categoryDescriptions[$category] ?? 'Kategori UMKM yang berkontribusi dalam perekonomian desa.' }}
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <div class="text-2xl font-bold text-orange-600 mb-2">{{ $umkms->total() }}</div>
                <div class="text-sm text-gray-600">Total UMKM</div>
            </div>
            
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600 mb-2">{{ $umkms->where('is_verified', true)->count() }}</div>
                <div class="text-sm text-gray-600">Terverifikasi</div>
            </div>
            
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600 mb-2">{{ $umkms->where('is_active', true)->count() }}</div>
                <div class="text-sm text-gray-600">Aktif</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // UMKM card hover effects
        document.querySelectorAll('.bg-white.rounded-lg.shadow-lg').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('transform', 'scale-105', 'transition-transform', 'duration-300');
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('transform', 'scale-105');
            });
        });

        // Rating stars hover effect
        document.querySelectorAll('.fas.fa-star, .fas.fa-star-half-alt, .far.fa-star').forEach(star => {
            star.parentElement.addEventListener('mouseenter', function() {
                this.classList.add('transform', 'scale-110', 'transition-transform');
            });
            
            star.parentElement.addEventListener('mouseleave', function() {
                this.classList.remove('transform', 'scale-110');
            });
        });
    });
</script>
@endsection