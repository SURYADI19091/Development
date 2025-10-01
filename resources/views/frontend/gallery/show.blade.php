@extends('frontend.main')

@section('title', $gallery->title . ' - Galeri - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'DETAIL GALERI')
@section('header_icon', 'fas fa-image')
@section('header_bg_color', 'bg-purple-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('gallery.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Galeri
        </a>
    </div>

    <!-- Gallery Detail -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="relative">
            <img src="{{ $gallery->image_path ? asset('storage/' . $gallery->image_path) : '/images/placeholder-gallery.jpg' }}" 
                 alt="{{ $gallery->alt_text ?? $gallery->title }}" 
                 class="w-full h-96 object-cover">
            <div class="absolute top-4 right-4">
                <span class="bg-purple-500 text-white text-sm px-3 py-1 rounded-full">
                    {{ ucfirst($gallery->category ?? 'Kegiatan') }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $gallery->title }}</h1>
                    <p class="text-gray-600">{{ $gallery->description }}</p>
                </div>
                <button id="likeBtn" class="flex items-center px-4 py-2 {{ $hasLiked ?? false ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }} rounded-lg hover:bg-red-100 hover:text-red-600 transition duration-200">
                    <i class="fas fa-heart mr-2"></i>
                    <span id="likeCount">{{ $gallery->likes_count ?? 0 }}</span>
                </button>
            </div>
            
            <!-- Photo Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t">
                @if($gallery->photographer)
                <div>
                    <span class="text-sm text-gray-500">Fotografer:</span>
                    <p class="font-medium">{{ $gallery->photographer }}</p>
                </div>
                @endif
                
                @if($gallery->location)
                <div>
                    <span class="text-sm text-gray-500">Lokasi:</span>
                    <p class="font-medium">{{ $gallery->location }}</p>
                </div>
                @endif
                
                @if($gallery->taken_at)
                <div>
                    <span class="text-sm text-gray-500">Tanggal Foto:</span>
                    <p class="font-medium">{{ $gallery->taken_at->format('d F Y') }}</p>
                </div>
                @endif
                
                <div>
                    <span class="text-sm text-gray-500">Dilihat:</span>
                    <p class="font-medium">{{ $gallery->views_count ?? 0 }} kali</p>
                </div>
                
                <div>
                    <span class="text-sm text-gray-500">Disukai:</span>
                    <p class="font-medium">{{ $gallery->likes_count ?? 0 }} orang</p>
                </div>
                
                <div>
                    <span class="text-sm text-gray-500">Diupload:</span>
                    <p class="font-medium">{{ $gallery->created_at->format('d F Y') }}</p>
                </div>
            </div>
            
            <!-- Tags -->
            @if($gallery->tags)
            <div class="mt-4 pt-4 border-t">
                <span class="text-sm text-gray-500 mb-2 block">Tags:</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($gallery->tags as $tag)
                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                        #{{ $tag }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Related Photos -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Foto Terkait</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- In a real app, this would show related photos from the same category -->
            <div class="text-center py-8 col-span-full">
                <i class="fas fa-images text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">Foto terkait akan ditampilkan di sini</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Like functionality
    document.getElementById('likeBtn').addEventListener('click', function() {
        const galleryId = {{ $gallery->id }};
        
        fetch(`/galeri/${galleryId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            const likeBtn = document.getElementById('likeBtn');
            const likeCount = document.getElementById('likeCount');
            
            likeCount.textContent = data.likes_count;
            
            if (data.liked) {
                likeBtn.classList.add('bg-red-100', 'text-red-600');
                likeBtn.classList.remove('bg-gray-100', 'text-gray-600');
            } else {
                likeBtn.classList.add('bg-gray-100', 'text-gray-600');
                likeBtn.classList.remove('bg-red-100', 'text-red-600');
            }
            
            // Show toast
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = data.liked ? 'Foto disukai!' : 'Batal menyukai foto';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan, coba lagi nanti');
        });
    });
</script>
@endsection