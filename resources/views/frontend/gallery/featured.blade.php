@extends('frontend.main')

@section('title', 'Galeri Unggulan - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'GALERI UNGGULAN')
@section('header_icon', 'fas fa-star')
@section('header_bg_color', 'bg-yellow-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Back to Gallery -->
    <div class="mb-6">
        <a href="{{ route('gallery.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Galeri
        </a>
    </div>

    <!-- Featured Gallery Info -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Galeri Unggulan</h2>
                <p class="text-gray-600">Koleksi foto terbaik dari berbagai kegiatan desa</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    <i class="fas fa-star mr-1"></i>{{ $featuredGalleries->count() }} Foto Unggulan
                </span>
            </div>
        </div>
    </div>

    <!-- Featured Gallery Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @forelse($featuredGalleries as $gallery)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden group hover:shadow-xl transition-shadow duration-300">
            <div class="relative overflow-hidden">
                <img src="{{ $gallery->image_path ? asset('storage/' . $gallery->image_path) : '/images/placeholder-gallery.jpg' }}" 
                     alt="{{ $gallery->alt_text ?? $gallery->title }}" 
                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                
                <!-- Featured Badge -->
                <div class="absolute top-2 left-2">
                    <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full flex items-center">
                        <i class="fas fa-star mr-1"></i>Unggulan
                    </span>
                </div>
                
                <!-- Category Badge -->
                <div class="absolute top-2 right-2">
                    <span class="bg-purple-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ ucfirst($gallery->category ?? 'Kegiatan') }}
                    </span>
                </div>
                
                <!-- Date -->
                @if($gallery->taken_at)
                <div class="absolute bottom-2 left-2">
                    <span class="bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                        {{ $gallery->taken_at->format('d M Y') }}
                    </span>
                </div>
                @endif
                
                <!-- View Button -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-opacity duration-300 flex items-center justify-center">
                    <a href="{{ route('gallery.show', $gallery->id) }}" class="opacity-0 group-hover:opacity-100 bg-white text-gray-900 px-4 py-2 rounded-lg font-medium transition-opacity duration-300">
                        <i class="fas fa-eye mr-2"></i>Lihat Detail
                    </a>
                </div>
            </div>
            
            <div class="p-4">
                <h3 class="font-bold text-gray-900 mb-2">{{ $gallery->title }}</h3>
                <p class="text-gray-600 text-sm mb-3">
                    {{ Str::limit($gallery->description ?? 'Foto unggulan dari dokumentasi kegiatan desa', 80) }}
                </p>
                
                <!-- Stats -->
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span><i class="fas fa-eye mr-1"></i>{{ $gallery->views_count ?? 0 }}</span>
                    <span><i class="fas fa-heart mr-1"></i>{{ $gallery->likes_count ?? 0 }}</span>
                    @if($gallery->photographer)
                    <span><i class="fas fa-camera mr-1"></i>{{ $gallery->photographer }}</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="col-span-full text-center py-12">
            <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-600 mb-2">Belum Ada Foto Unggulan</h3>
            <p class="text-gray-500 mb-4">Foto unggulan akan ditampilkan di sini setelah admin memilihnya</p>
            <a href="{{ route('gallery.index') }}" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">
                <i class="fas fa-images mr-2"></i>Lihat Semua Galeri
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($featuredGalleries->hasPages())
    <div class="bg-white rounded-lg shadow-lg p-6">
        {{ $featuredGalleries->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Featured Gallery page loaded');
        
        // Add any featured gallery specific functionality here
    });
</script>
@endsection