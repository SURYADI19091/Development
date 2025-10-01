@extends('frontend.main')

@section('title', 'Berita ' . $categoryName . ' - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'BERITA ' . strtoupper($categoryName))
@section('header_icon', 'fas fa-newspaper')
@section('header_bg_color', 'bg-blue-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Category Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Kategori: {{ $categoryName }}</h2>
        <p class="text-gray-600">Menampilkan {{ $news->total() }} berita dalam kategori {{ strtolower($categoryName) }}</p>
    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @forelse($news as $item)
            @php
                $categoryColors = [
                    'kegiatan' => 'green',
                    'kesehatan' => 'red', 
                    'ekonomi' => 'purple',
                    'infrastruktur' => 'orange',
                    'pendidikan' => 'indigo',
                    'olahraga' => 'teal',
                    'sosial' => 'pink',
                    'lingkungan' => 'emerald',
                    'default' => 'gray'
                ];
                $color = $categoryColors[$item->category] ?? $categoryColors['default'];
            @endphp
            <article class="bg-white rounded-lg shadow-lg overflow-hidden group hover:shadow-xl transition-shadow duration-300">
                <div class="relative">
                    @if($item->featured_image)
                        <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-{{ $color }}-100 to-{{ $color }}-200 flex items-center justify-center">
                            <i class="fas fa-newspaper text-4xl text-{{ $color }}-400"></i>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-{{ $color }}-500 text-white px-2 py-1 rounded text-xs font-medium uppercase">
                            {{ $item->category }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                        <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                        {{ $item->excerpt }}
                    </p>
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            <span>{{ $item->published_at->format('d M Y') }}</span>
                        </div>
                        @if($item->views_count)
                            <div class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                <span>{{ number_format($item->views_count) }} views</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        @if($item->user)
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-user mr-1"></i>
                                <span>{{ $item->user->name }}</span>
                            </div>
                        @endif
                        <a href="{{ route('news.show', $item->slug) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Baca →
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <i class="fas fa-newspaper text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Berita</h3>
                    <p class="text-gray-500">Tidak ada berita dalam kategori {{ strtolower($categoryName) }}.</p>
                    <a href="{{ route('news.index') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Lihat Semua Berita
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($news->hasPages())
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $news->firstItem() }} sampai {{ $news->lastItem() }} dari {{ $news->total() }} berita
                </div>
                <div class="flex justify-center">
                    {{ $news->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Back Navigation -->
    <div class="mt-6">
        <a href="{{ route('news.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Semua Berita
        </a>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection