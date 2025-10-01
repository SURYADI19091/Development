@forelse($umkms as $umkm)
<div class="umkm-card {{ $umkm->category }} bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
    <div class="relative">
        @php
            $photos = $umkm->photos ? json_decode($umkm->photos) : [];
            $mainPhoto = !empty($photos) ? $photos[0] : 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop';
        @endphp
        <img src="{{ $mainPhoto }}" 
             alt="{{ $umkm->business_name }}" class="w-full h-48 object-cover">
        <div class="absolute top-4 left-4">
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                {{ $umkm->is_active ? 'Buka' : 'Tutup' }}
            </span>
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
            <span class="{{ $color }} text-white px-2 py-1 rounded-full text-xs font-medium">
                {{ ucfirst($umkm->category) }}
            </span>
        </div>
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
                <span class="text-sm text-gray-600 ml-2">{{ number_format($umkm->rating, 1) }} ({{ $umkm->total_reviews }} ulasan)</span>
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
    <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada UMKM</h3>
    <p class="text-gray-400">Belum ada data UMKM yang tersedia saat ini.</p>
</div>
@endforelse