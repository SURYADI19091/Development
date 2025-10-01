@extends('frontend.main')

@section('title', $announcement->title . ' - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'PENGUMUMAN')ends('frontend.main')

@section('title', $announcement->title . ' - {{ $villageProfile->village_name ?? "DESA CIWULAN" }}')
@section('page_title', 'DETAIL PENGUMUMAN')
@section('header_icon', 'fas fa-bullhorn')
@section('header_bg_color', 'bg-red-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Announcement Detail -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <!-- Priority Badge -->
            <div class="flex items-center justify-between mb-4">
                @php
                    $priorityColors = [
                        'urgent' => 'bg-red-100 text-red-800',
                        'important' => 'bg-yellow-100 text-yellow-800', 
                        'normal' => 'bg-blue-100 text-blue-800'
                    ];
                    $priorityColor = $priorityColors[$announcement->priority] ?? $priorityColors['normal'];
                @endphp
                <span class="{{ $priorityColor }} px-3 py-1 rounded-full text-sm font-medium uppercase">
                    {{ $announcement->priority === 'urgent' ? 'MENDESAK' : 
                        ($announcement->priority === 'important' ? 'PENTING' : 'NORMAL') }}
                </span>
                @if($announcement->valid_until)
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        Berlaku sampai: {{ \Carbon\Carbon::parse($announcement->valid_until)->format('d F Y') }}
                    </div>
                @endif
            </div>

            <!-- Title -->
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                {{ $announcement->title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex items-center text-gray-600 text-sm mb-6 border-b pb-4">
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>{{ $announcement->created_at->format('d F Y, H:i') }} WIB</span>
                </div>
            </div>

            <!-- Content -->
            <div class="prose max-w-none mb-8">
                {!! nl2br(e($announcement->content)) !!}
            </div>

            <!-- Contact Info if available -->
            @if($announcement->contact_person || $announcement->contact_phone)
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold mb-2">Informasi Kontak:</h3>
                    @if($announcement->contact_person)
                        <div class="flex items-center mb-1">
                            <i class="fas fa-user mr-2 text-gray-500"></i>
                            <span>{{ $announcement->contact_person }}</span>
                        </div>
                    @endif
                    @if($announcement->contact_phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone mr-2 text-gray-500"></i>
                            <span>{{ $announcement->contact_phone }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Share buttons -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-3">Bagikan Pengumuman</h3>
                <div class="flex space-x-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                       target="_blank"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fab fa-facebook-f mr-2"></i>Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($announcement->title) }}&url={{ urlencode(request()->fullUrl()) }}" 
                       target="_blank"
                       class="bg-sky-500 text-white px-4 py-2 rounded hover:bg-sky-600">
                        <i class="fab fa-twitter mr-2"></i>Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($announcement->title . ' ' . request()->fullUrl()) }}" 
                       target="_blank"
                       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Announcements -->
    @if($relatedAnnouncements->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Pengumuman Terkait</h3>
        <div class="space-y-3">
            @foreach($relatedAnnouncements as $related)
                <div class="border-l-4 border-blue-500 pl-4">
                    <h4 class="font-medium text-sm line-clamp-2 mb-1">
                        <a href="{{ route('announcements.show', $related->slug) }}" class="hover:text-blue-600">
                            {{ $related->title }}
                        </a>
                    </h4>
                    <p class="text-xs text-gray-500">
                        {{ $related->created_at->format('d M Y') }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Navigation -->
    <div class="mt-6">
        <a href="{{ route('announcements.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pengumuman
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