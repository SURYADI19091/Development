@extends('frontend.main')

@section('title', 'Berita Desa - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'BERITA DESA')
@section('header_icon', 'fas fa-newspaper')
@section('header_bg_color', 'bg-blue-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <form method="GET" action="{{ route('news.index') }}" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari berita..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('news.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('news.index', ['category' => $category]) }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium {{ request('category') === $category ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ ucfirst($category) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Featured News -->
    @if($featuredNews)
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="relative">
            @if($featuredNews->featured_image)
                <img src="{{ asset('storage/' . $featuredNews->featured_image) }}" alt="{{ $featuredNews->title }}" class="w-full h-64 object-cover">
            @else
                <div class="w-full h-64 bg-gradient-to-r from-blue-500 to-purple-600"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <div class="absolute top-4 left-4">
                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-fire mr-1"></i>FEATURED
                </span>
            </div>
            <div class="absolute bottom-6 left-6 right-6">
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">
                    {{ $featuredNews->title }}
                </h1>
                <p class="text-white/90 text-sm mb-3">
                    {{ $featuredNews->excerpt }}
                </p>
                <div class="flex items-center text-white/80 text-sm">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>{{ $featuredNews->created_at->format('d M Y') }}</span>
                    <span class="mx-2">•</span>
                    <i class="fas fa-user mr-2"></i>
                    <span>{{ $featuredNews->author->name ?? 'Admin Desa' }}</span>
                    @if($featuredNews->views_count)
                        <span class="mx-2">•</span>
                        <i class="fas fa-eye mr-2"></i>
                        <span>{{ number_format($featuredNews->views_count) }} views</span>
                    @endif
                </div>
                <div class="mt-3">
                    <a href="{{ route('news.show', $featuredNews->slug) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Baca Selengkapnya
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                    @if($item->is_featured)
                        <div class="absolute top-3 right-3">
                            <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-medium">
                                <i class="fas fa-star mr-1"></i>PILIHAN
                            </span>
                        </div>
                    @endif
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
                            <span>{{ $item->created_at->format('d M Y') }}</span>
                        </div>
                        @if($item->views_count)
                            <div class="flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                <span>{{ number_format($item->views_count) }} views</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        @if($item->author)
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-user mr-1"></i>
                                <span>{{ $item->author->name }}</span>
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
                    <p class="text-gray-500">
                        @if(request('search') || request('category'))
                            Tidak ada berita yang sesuai dengan pencarian Anda.
                        @else
                            Berita akan muncul di sini ketika sudah dipublikasikan.
                        @endif
                    </p>
                    @if(request('search') || request('category'))
                        <a href="{{ route('news.index') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Lihat Semua Berita
                        </a>
                    @endif
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

    <!-- News Statistics -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
            Statistik Berita
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="text-center p-3 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $newsStats['total'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Total Berita</div>
            </div>
            <div class="text-center p-3 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $newsStats['published'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Dipublikasikan</div>
            </div>
            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ $newsStats['this_month'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Bulan Ini</div>
            </div>
            <div class="text-center p-3 bg-purple-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">{{ $newsStats['total_views'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Total Views</div>
            </div>
        </div>
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

<script>
    // Auto refresh news every 5 minutes
    setInterval(function() {
        if (!document.hidden) {
            // Check for new news
            fetch('{{ route("news.latest") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.newCount > 0) {
                        showNotification(`Ada ${data.newCount} berita baru tersedia`, 'info');
                    }
                })
                .catch(error => console.log('Auto-refresh failed:', error));
        }
    }, 300000); // 5 minutes

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'info' ? 'bg-blue-500 text-white' : 
            type === 'success' ? 'bg-green-500 text-white' : 
            'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    // Smooth scroll for read more links
    document.addEventListener('click', function(e) {
        if (e.target.closest('a[href^="#"]')) {
            e.preventDefault();
            const target = document.querySelector(e.target.closest('a').getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>
@endsection