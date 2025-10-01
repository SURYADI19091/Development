@extends('frontend.main')

@section('title', 'Pengumuman - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'PENGUMUMAN')
@section('header_icon', 'fas fa-bullhorn')
@section('header_bg_color', 'bg-yellow-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Important Announcement -->
    @if($importantAnnouncement)
    <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold mb-2">PENGUMUMAN PENTING!</h2>
                <p class="mb-3 leading-relaxed">
                    {{ $importantAnnouncement->content }}
                </p>
                <div class="flex items-center text-sm opacity-90">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>Dipublikasikan: {{ $importantAnnouncement->created_at->format('d F Y') }}</span>
                    @if($importantAnnouncement->valid_until)
                        <span class="mx-2">•</span>
                        <span>Berlaku sampai: {{ \Carbon\Carbon::parse($importantAnnouncement->valid_until)->format('d F Y') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <form method="GET" action="{{ route('announcements.index') }}" class="flex">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari pengumuman..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-r-lg hover:bg-yellow-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('announcements.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ !request('category') ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    Semua
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('announcements.index', ['category' => $category]) }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium {{ request('category') === $category ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ ucfirst($category) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="space-y-4 mb-6">
        @forelse($announcements as $announcement)
            @php
                $typeColors = [
                    'pengumuman' => 'yellow',
                    'pendaftaran' => 'blue', 
                    'kegiatan' => 'green',
                    'lomba' => 'purple',
                    'infrastruktur' => 'orange',
                    'kesehatan' => 'indigo',
                    'sosial' => 'pink',
                    'default' => 'gray'
                ];
                $color = $typeColors[$announcement->category ?? 'default'] ?? $typeColors['default'];
                
                $typeIcons = [
                    'pengumuman' => 'fas fa-bullhorn',
                    'pendaftaran' => 'fas fa-hand-holding-usd',
                    'kegiatan' => 'fas fa-vote-yea',
                    'lomba' => 'fas fa-trophy',
                    'infrastruktur' => 'fas fa-tools',
                    'kesehatan' => 'fas fa-heartbeat',
                    'sosial' => 'fas fa-users',
                    'default' => 'fas fa-info-circle'
                ];
                $icon = $typeIcons[$announcement->category ?? 'default'] ?? $typeIcons['default'];
            @endphp
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-{{ $color }}-500">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="bg-{{ $color }}-100 text-{{ $color }}-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">
                                {{ strtoupper($announcement->category ?? 'PENGUMUMAN') }}
                            </span>
                            @if($announcement->priority === 'urgent')
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">
                                    MENDESAK
                                </span>
                            @endif
                            <span class="text-sm text-gray-500">{{ $announcement->created_at->format('d F Y') }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                            <a href="{{ route('announcements.show', $announcement->id) }}" class="hover:text-{{ $color }}-600">
                                {{ $announcement->title }}
                            </a>
                        </h3>
                        <p class="text-gray-700 mb-3">
                            {{ Str::limit($announcement->content, 200) }}
                        </p>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                <span>Dipublikasikan: {{ $announcement->created_at->format('d M Y') }}</span>
                            </div>
                            @if($announcement->valid_until)
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>Berlaku sampai: {{ \Carbon\Carbon::parse($announcement->valid_until)->format('d M Y') }}</span>
                                </div>
                            @endif
                            @if($announcement->valid_from)
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    <span>Mulai: {{ \Carbon\Carbon::parse($announcement->valid_from)->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <i class="{{ $icon }} text-2xl text-{{ $color }}-500"></i>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-bullhorn text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Pengumuman</h3>
                <p class="text-gray-500">
                    @if(request('search') || request('category'))
                        Tidak ada pengumuman yang sesuai dengan pencarian Anda.
                    @else
                        Pengumuman akan muncul di sini ketika sudah dipublikasikan.
                    @endif
                </p>
                @if(request('search') || request('category'))
                    <a href="{{ route('announcements.index') }}" class="inline-block mt-4 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        Lihat Semua Pengumuman
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($announcements->hasPages())
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $announcements->firstItem() }} sampai {{ $announcements->lastItem() }} dari {{ $announcements->total() }} pengumuman
                </div>
                <div class="flex justify-center">
                    {{ $announcements->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-chart-bar mr-2 text-yellow-600"></i>
            Statistik Pengumuman
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ $announcementStats['this_month'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Bulan Ini</div>
            </div>
            <div class="text-center p-3 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $announcementStats['this_year'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Tahun Ini</div>
            </div>
            <div class="text-center p-3 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $announcementStats['read_percentage'] ?? 0 }}%</div>
                <div class="text-sm text-gray-600">Dibaca</div>
            </div>
            <div class="text-center p-3 bg-purple-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">{{ $announcementStats['rating'] ?? 0 }}</div>
                <div class="text-sm text-gray-600">Rating</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh for new announcements every 5 minutes
    setInterval(function() {
        if (!document.hidden) {
            // Check for new announcements
            fetch('{{ route("announcements.index") }}?format=json')
                .then(response => {
                    if (response.ok) {
                        // Could show notification if new announcements found
                        console.log('Checked for new announcements');
                    }
                })
                .catch(error => console.log('Auto-refresh failed:', error));
        }
    }, 300000); // 5 minutes

    // Smooth scroll for announcement links
    document.addEventListener('click', function(e) {
        if (e.target.closest('a[href^="#"]')) {
            e.preventDefault();
            const target = document.querySelector(e.target.closest('a').getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });

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
</script>
@endsection