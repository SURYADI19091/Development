@extends('frontend.main')

@section('title', $news->title . ' - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'DETAIL BERITA')
@section('header_icon', 'fas fa-newspaper')
@section('header_bg_color', 'bg-blue-600')

@section('content')
<div class="xl:col-span-3">
    <!-- News Detail -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        @if($news->featured_image)
            <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" class="w-full h-64 md:h-96 object-cover">
        @endif
        
        <div class="p-6">
            <!-- Category and Meta -->
            <div class="flex items-center justify-between mb-4">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium uppercase">
                    {{ $news->category }}
                </span>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-eye mr-1"></i>
                    {{ number_format($news->views_count) }} views
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                {{ $news->title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex items-center text-gray-600 text-sm mb-6 border-b pb-4">
                <div class="flex items-center mr-6">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>{{ $news->published_at->format('d F Y') }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    <span>{{ $news->user->name ?? 'Admin Desa' }}</span>
                </div>
            </div>

            <!-- Content -->
            <div class="prose max-w-none mb-8">
                {!! nl2br(e($news->content)) !!}
            </div>

            <!-- Tags if available -->
            @if($news->tags)
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="text-sm text-gray-600 mr-2">Tags:</span>
                    @foreach($news->tags as $tag)
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- Share buttons -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-3">Bagikan Berita</h3>
                <div class="flex space-x-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                       target="_blank"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fab fa-facebook-f mr-2"></i>Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title) }}&url={{ urlencode(request()->fullUrl()) }}" 
                       target="_blank"
                       class="bg-sky-500 text-white px-4 py-2 rounded hover:bg-sky-600">
                        <i class="fab fa-twitter mr-2"></i>Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . request()->fullUrl()) }}" 
                       target="_blank"
                       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related News -->
    @if($relatedNews->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Berita Terkait</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($relatedNews as $related)
                <div class="flex space-x-3">
                    @if($related->featured_image)
                        <img src="{{ asset('storage/' . $related->featured_image) }}" 
                             alt="{{ $related->title }}" 
                             class="w-20 h-16 object-cover rounded">
                    @else
                        <div class="w-20 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-newspaper text-gray-400"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-medium text-sm line-clamp-2 mb-1">
                            <a href="{{ route('news.show', $related->slug) }}" class="hover:text-blue-600">
                                {{ $related->title }}
                            </a>
                        </h4>
                        <p class="text-xs text-gray-500">
                            {{ $related->published_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Navigation -->
    <div class="mt-6">
        <a href="{{ route('news.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Berita
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
</style>
@endsection