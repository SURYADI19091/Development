@extends('frontend.main')

@section('title', $umkm->business_name . ' - UMKM Desa ' . strtoupper($villageProfile->village_name ?? 'Krandegan'))
@section('page_title', $umkm->business_name)
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

    <!-- UMKM Detail Header -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="relative">
            @php
                $photos = $umkm->photos ? (is_string($umkm->photos) ? json_decode($umkm->photos) : $umkm->photos) : [];
                $mainPhoto = !empty($photos) ? $photos[0] : 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=800&h=400&fit=crop';
            @endphp
            <img src="{{ $mainPhoto }}" alt="{{ $umkm->business_name }}" class="w-full h-64 md:h-80 object-cover">
            
            <div class="absolute top-4 left-4 flex gap-2">
                <span class="bg-{{ $umkm->is_active ? 'green' : 'red' }}-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ $umkm->is_active ? 'Buka' : 'Tutup' }}
                </span>
                @if($umkm->is_verified)
                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                    </span>
                @endif
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
                <span class="{{ $color }} text-white px-3 py-1 rounded-full text-sm font-medium">
                    {{ ucfirst($umkm->category) }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
                <div class="flex-1 mb-4 md:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $umkm->business_name }}</h1>
                    <p class="text-lg text-gray-600 mb-4">{{ $umkm->description }}</p>
                    
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400 mr-3">
                            @php
                                $fullStars = floor($umkm->rating);
                                $hasHalfStar = ($umkm->rating - $fullStars) >= 0.5;
                                $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                            @endphp
                            
                            @for($i = 0; $i < $fullStars; $i++)
                                <i class="fas fa-star text-xl"></i>
                            @endfor
                            
                            @if($hasHalfStar)
                                <i class="fas fa-star-half-alt text-xl"></i>
                            @endif
                            
                            @for($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star text-xl"></i>
                            @endfor
                        </div>
                        <span class="text-lg font-semibold text-gray-700">{{ number_format($umkm->rating, 1) }}</span>
                        <span class="text-gray-500 ml-2">({{ $umkm->total_reviews }} ulasan)</span>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    @if($umkm->phone)
                        <a href="tel:{{ $umkm->phone }}" 
                           class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 text-center">
                            <i class="fas fa-phone mr-2"></i>Telepon
                        </a>
                    @endif
                    @if($umkm->email)
                        <a href="mailto:{{ $umkm->email }}" 
                           class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 text-center">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Business Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Informasi Usaha</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Kontak & Lokasi</h3>
                            <div class="space-y-2">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-user w-5 text-orange-500"></i>
                                    <span class="ml-2">{{ $umkm->owner_name }}</span>
                                </div>
                                <div class="flex items-start text-gray-600">
                                    <i class="fas fa-map-marker-alt w-5 text-orange-500 mt-1"></i>
                                    <span class="ml-2">{{ $umkm->address }}</span>
                                </div>
                                @if($umkm->phone)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-phone w-5 text-orange-500"></i>
                                    <span class="ml-2">{{ $umkm->phone }}</span>
                                </div>
                                @endif
                                @if($umkm->email)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-envelope w-5 text-orange-500"></i>
                                    <span class="ml-2">{{ $umkm->email }}</span>
                                </div>
                                @endif
                                @if($umkm->website)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-globe w-5 text-orange-500"></i>
                                    <a href="{{ $umkm->website }}" target="_blank" class="ml-2 text-blue-600 hover:underline">
                                        {{ $umkm->website }}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($umkm->operating_hours)
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Jam Operasional</h3>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-clock w-5 text-orange-500"></i>
                                <span class="ml-2">{{ $umkm->operating_hours }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="space-y-4">
                        @if($umkm->price_range)
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Kisaran Harga</h3>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-money-bill-wave w-5 text-orange-500"></i>
                                <span class="ml-2">{{ $umkm->price_range }}</span>
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Informasi Lainnya</h3>
                            <div class="space-y-2">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-users w-5 text-orange-500"></i>
                                    <span class="ml-2">{{ $umkm->employee_count }} karyawan</span>
                                </div>
                                @if($umkm->registered_at)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar w-5 text-orange-500"></i>
                                    <span class="ml-2">Terdaftar {{ $umkm->registered_at->format('d M Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products & Services -->
            @if($umkm->products || $umkm->services)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Produk & Layanan</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($umkm->products)
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-box mr-2 text-orange-500"></i>Produk
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $umkm->products }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($umkm->services)
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-concierge-bell mr-2 text-orange-500"></i>Layanan
                        </h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $umkm->services }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Reviews Section -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Ulasan Pelanggan</h2>
                    <button class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Tulis Ulasan
                    </button>
                </div>
                
                @if($umkm->reviews && $umkm->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($umkm->reviews->take(3) as $review)
                        <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $review->reviewer_name }}</h4>
                                    <div class="flex items-center mt-1">
                                        <div class="flex text-yellow-400 mr-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-sm"></i>
                                                @else
                                                    <i class="far fa-star text-sm"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700">{{ $review->review_text }}</p>
                        </div>
                        @endforeach
                        
                        @if($umkm->reviews->count() > 3)
                        <div class="text-center pt-4">
                            <button class="text-orange-600 hover:text-orange-700 font-medium">
                                Lihat semua {{ $umkm->reviews->count() }} ulasan
                            </button>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-comments text-4xl text-gray-300 mb-3"></i>
                        <h3 class="text-lg font-semibold text-gray-500 mb-2">Belum Ada Ulasan</h3>
                        <p class="text-gray-400 mb-4">Jadilah yang pertama memberikan ulasan untuk {{ $umkm->business_name }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    @if($umkm->phone)
                    <a href="tel:{{ $umkm->phone }}" 
                       class="flex items-center w-full px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition duration-200">
                        <i class="fas fa-phone mr-3"></i>
                        <span>Hubungi Sekarang</span>
                    </a>
                    @endif
                    
                    <button class="flex items-center w-full px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition duration-200">
                        <i class="fas fa-directions mr-3"></i>
                        <span>Petunjuk Arah</span>
                    </button>
                    
                    <button class="flex items-center w-full px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition duration-200">
                        <i class="fas fa-share-alt mr-3"></i>
                        <span>Bagikan UMKM</span>
                    </button>
                    
                    <button class="flex items-center w-full px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition duration-200">
                        <i class="fas fa-flag mr-3"></i>
                        <span>Laporkan</span>
                    </button>
                </div>
            </div>

            <!-- Business Stats -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-gray-900 mb-4">Statistik Usaha</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Rating</span>
                        <div class="flex items-center">
                            <span class="font-semibold text-lg mr-1">{{ number_format($umkm->rating, 1) }}</span>
                            <i class="fas fa-star text-yellow-400"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Total Ulasan</span>
                        <span class="font-semibold">{{ $umkm->total_reviews }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Karyawan</span>
                        <span class="font-semibold">{{ $umkm->employee_count }} orang</span>
                    </div>
                    @if($umkm->monthly_revenue)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Omzet Bulanan</span>
                        <span class="font-semibold">Rp {{ number_format($umkm->monthly_revenue, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Settlement Info -->
            @if($umkm->settlement)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="font-bold text-gray-900 mb-4">Wilayah</h3>
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt text-orange-500 mr-3"></i>
                    <div>
                        <div class="font-semibold text-gray-900">{{ $umkm->settlement->name }}</div>
                        <div class="text-sm text-gray-600">{{ $umkm->address }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Related UMKM -->
    @if(isset($relatedUmkm) && $relatedUmkm->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">UMKM {{ ucfirst($umkm->category) }} Lainnya</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedUmkm as $related)
            <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                @php
                    $relatedPhotos = $related->photos ? (is_string($related->photos) ? json_decode($related->photos) : $related->photos) : [];
                    $relatedPhoto = !empty($relatedPhotos) ? $relatedPhotos[0] : 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=300&h=200&fit=crop';
                @endphp
                <img src="{{ $relatedPhoto }}" alt="{{ $related->business_name }}" class="w-full h-32 object-cover">
                
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $related->business_name }}</h3>
                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($related->description, 60) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $related->rating)
                                    <i class="fas fa-star text-xs"></i>
                                @else
                                    <i class="far fa-star text-xs"></i>
                                @endif
                            @endfor
                            <span class="text-xs text-gray-600 ml-1">{{ number_format($related->rating, 1) }}</span>
                        </div>
                        
                        <a href="{{ route('umkm.show', $related->slug) }}" 
                           class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                            Lihat →
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Write Review Button
        const writeReviewBtn = document.querySelector('button:has(.fa-plus)');
        if (writeReviewBtn) {
            writeReviewBtn.addEventListener('click', function() {
                alert('Fitur tulis ulasan akan segera tersedia!');
            });
        }

        // Direction Button
        const directionBtn = document.querySelector('button:has(.fa-directions)');
        if (directionBtn) {
            directionBtn.addEventListener('click', function() {
                const address = "{{ $umkm->address }}";
                const googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
                window.open(googleMapsUrl, '_blank');
            });
        }

        // Share Button
        const shareBtn = document.querySelector('button:has(.fa-share-alt)');
        if (shareBtn) {
            shareBtn.addEventListener('click', function() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $umkm->business_name }}',
                        text: '{{ $umkm->description }}',
                        url: window.location.href
                    });
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(function() {
                        alert('Link telah disalin ke clipboard!');
                    });
                }
            });
        }

        // Report Button
        const reportBtn = document.querySelector('button:has(.fa-flag)');
        if (reportBtn) {
            reportBtn.addEventListener('click', function() {
                alert('Terima kasih atas laporannya. Tim kami akan meninjau dalam 24 jam.');
            });
        }

        // View All Reviews Button
        const viewAllReviewsBtn = document.querySelector('button:contains("Lihat semua")');
        if (viewAllReviewsBtn) {
            viewAllReviewsBtn.addEventListener('click', function() {
                alert('Halaman semua ulasan akan segera tersedia!');
            });
        }
    });
</script>
@endsection