@extends('frontend.main')

@section('title', 'UMKM Terverifikasi - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'UMKM TERVERIFIKASI')
@section('header_icon', 'fas fa-certificate')
@section('header_bg_color', 'bg-green-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Verified UMKM Overview -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">UMKM Terverifikasi</h2>
                <p class="text-lg opacity-90 mb-4">
                    UMKM yang telah memenuhi standar kualitas dan legalitas
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ count($verifiedUmkms) }}</div>
                        <div class="text-sm opacity-90">UMKM Terverifikasi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            {{ $verifiedUmkms->avg('rating') ? number_format($verifiedUmkms->avg('rating'), 1) : '0.0' }}
                        </div>
                        <div class="text-sm opacity-90">Rating Rata-rata</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">
                            {{ $verifiedUmkms->unique('category')->count() }}
                        </div>
                        <div class="text-sm opacity-90">Kategori Usaha</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-certificate text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Verification Benefits -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">Keuntungan UMKM Terverifikasi</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Terpercaya</h4>
                <p class="text-sm text-gray-600">Telah melewati proses verifikasi ketat</p>
            </div>
            
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-star text-blue-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Kualitas Terjamin</h4>
                <p class="text-sm text-gray-600">Produk dan layanan berkualitas tinggi</p>
            </div>
            
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-certificate text-purple-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Sertifikat Legal</h4>
                <p class="text-sm text-gray-600">Memiliki legalitas dan sertifikasi</p>
            </div>
            
            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-handshake text-orange-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Partner Resmi</h4>
                <p class="text-sm text-gray-600">Bermitra dengan pemerintah desa</p>
            </div>
        </div>
    </div>

    <!-- Verified UMKM Directory -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @forelse($verifiedUmkms as $umkm)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 border-2 border-green-200">
            <!-- Verified Badge -->
            <div class="relative">
                @php
                    $photos = $umkm->photos ? (is_string($umkm->photos) ? json_decode($umkm->photos) : $umkm->photos) : [];
                    $mainPhoto = !empty($photos) ? $photos[0] : 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=250&fit=crop';
                @endphp
                <img src="{{ $mainPhoto }}" 
                     alt="{{ $umkm->business_name }}" class="w-full h-48 object-cover">
                
                <!-- Verified Badge -->
                <div class="absolute top-4 left-4">
                    <div class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium flex items-center">
                        <i class="fas fa-certificate mr-1"></i>
                        Terverifikasi
                    </div>
                </div>
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                        {{ $umkm->is_active ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
                
                <!-- Category Badge -->
                <div class="absolute bottom-4 right-4">
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
                <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                    {{ $umkm->business_name }}
                    <i class="fas fa-check-circle text-green-500 ml-2"></i>
                </h3>
                <p class="text-gray-600 text-sm mb-3">
                    {{ Str::limit($umkm->description, 100) }}
                </p>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-user mr-2 text-green-500"></i>
                        <span>{{ $umkm->owner_name }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>
                        <span>{{ $umkm->address }}</span>
                    </div>
                    @if($umkm->phone)
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-phone mr-2 text-green-500"></i>
                        <span>{{ $umkm->phone }}</span>
                    </div>
                    @endif
                    @if($umkm->operating_hours)
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-2 text-green-500"></i>
                        <span>{{ $umkm->operating_hours }}</span>
                    </div>
                    @endif
                </div>

                <!-- Verification Details -->
                <div class="bg-green-50 p-3 rounded-lg mb-4">
                    <h4 class="font-semibold text-green-800 text-sm mb-2">Sertifikasi & Legalitas</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="flex items-center text-green-700">
                            <i class="fas fa-check mr-1"></i>
                            <span>NIB Berusaha</span>
                        </div>
                        <div class="flex items-center text-green-700">
                            <i class="fas fa-check mr-1"></i>
                            <span>PIRT/Halal</span>
                        </div>
                        <div class="flex items-center text-green-700">
                            <i class="fas fa-check mr-1"></i>
                            <span>NPWP</span>
                        </div>
                        <div class="flex items-center text-green-700">
                            <i class="fas fa-check mr-1"></i>
                            <span>Standar Mutu</span>
                        </div>
                    </div>
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
                        <span class="text-sm text-gray-600 ml-2">{{ number_format($umkm->rating, 1) }}</span>
                    </div>
                    <a href="{{ route('umkm.show', $umkm->slug) }}" 
                       class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-sm transition duration-200">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada UMKM Terverifikasi</h3>
            <p class="text-gray-400">Belum ada UMKM yang telah melalui proses verifikasi.</p>
        </div>
        @endforelse
    </div>

    <!-- Verification Process -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6">Proses Verifikasi UMKM</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-blue-600">1</span>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Pendaftaran</h4>
                <p class="text-sm text-gray-600">Daftar dan lengkapi data UMKM Anda</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-yellow-600">2</span>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Verifikasi Dokumen</h4>
                <p class="text-sm text-gray-600">Upload dokumen legalitas dan sertifikat</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-purple-600">3</span>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Survei Lapangan</h4>
                <p class="text-sm text-gray-600">Tim melakukan survei dan evaluasi</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-green-600">4</span>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">Sertifikat</h4>
                <p class="text-sm text-gray-600">Terima sertifikat UMKM terverifikasi</p>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t text-center">
            <h4 class="font-bold text-gray-900 mb-4">Syarat Verifikasi UMKM</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                <div>
                    <h5 class="font-semibold text-gray-800 mb-3">Dokumen Legal:</h5>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            NIB (Nomor Induk Berusaha)
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            NPWP Usaha
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            PIRT/Sertifikat Halal (makanan)
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Izin Lingkungan (jika diperlukan)
                        </li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold text-gray-800 mb-3">Standar Kualitas:</h5>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Operasional minimal 6 bulan
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Memiliki tempat usaha tetap
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Produk/layanan berkualitas
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Laporan keuangan sederhana
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow-lg p-6">
        <div class="text-center">
            <h3 class="text-xl font-bold mb-3">Daftarkan UMKM Anda untuk Verifikasi</h3>
            <p class="mb-6 opacity-90">
                Tingkatkan kredibilitas dan kepercayaan pelanggan dengan menjadi UMKM terverifikasi
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="px-6 py-3 bg-white text-green-600 rounded-lg hover:bg-gray-100 font-semibold transition duration-200">
                    <i class="fas fa-file-alt mr-2"></i>
                    Ajukan Verifikasi
                </button>
                <a href="{{ route('umkm.index') }}" 
                   class="px-6 py-3 bg-green-700 text-white rounded-lg hover:bg-green-800 font-semibold transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke UMKM
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verification application button
        const verificationBtn = document.querySelector('button[class*="bg-white"][class*="text-green-600"]');
        if (verificationBtn) {
            verificationBtn.addEventListener('click', function() {
                alert('Membuka formulir pengajuan verifikasi UMKM...');
            });
        }
        
        // Card hover effects
        document.querySelectorAll('.border-2.border-green-200').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('transform', 'scale-105', 'border-green-300');
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('transform', 'scale-105', 'border-green-300');
            });
        });
        
        // Animate verification process steps
        const steps = document.querySelectorAll('.w-16.h-16.bg-blue-100, .w-16.h-16.bg-yellow-100, .w-16.h-16.bg-purple-100, .w-16.h-16.bg-green-100');
        steps.forEach((step, index) => {
            step.style.opacity = '0';
            step.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                step.style.transition = 'all 0.5s ease';
                step.style.opacity = '1';
                step.style.transform = 'translateY(0)';
            }, index * 200);
        });
    });
</script>
@endsection