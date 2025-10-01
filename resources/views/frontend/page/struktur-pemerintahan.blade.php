@extends('frontend.main')

@section('title', 'Struktur Pemerintahan - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'STRUKTUR PEMERINTAHAN')
@section('header_icon', 'fas fa-sitemap')
@section('header_bg_color', 'bg-cyan-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Struktur Organisasi Pemerintah Desa Krandegan</h1>
            <p class="text-gray-600">Periode 2024-2029</p>
        </div>
    </div>

    <!-- Village Head -->
    @php $kepala_desa = $groupedOfficials['kepala_desa']->first() ?? null; @endphp
    @if($kepala_desa)
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center mb-6">
            <div class="w-32 h-32 bg-cyan-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                @if($kepala_desa->photo_path)
                <img src="{{ asset('storage/' . $kepala_desa->photo_path) }}" alt="Kepala Desa" class="w-30 h-30 rounded-full object-cover" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                @endif
                <div class="w-30 h-30 bg-cyan-500 rounded-full flex items-center justify-center" style="{{ $kepala_desa->photo_path ? 'display: none;' : '' }}">
                    <i class="fas fa-user text-4xl text-white"></i>
                </div>
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ $kepala_desa->name }}</h2>
            <p class="text-cyan-600 font-medium">Kepala Desa Krandegan</p>
            <p class="text-sm text-gray-600 mt-2">Periode: {{ $kepala_desa->work_period }}</p>
            @if($kepala_desa->nip)
            <p class="text-xs text-gray-500">NIP: {{ $kepala_desa->nip }}</p>
            @endif
        </div>
        
        <div class="bg-cyan-50 rounded-lg p-4 border-l-4 border-cyan-500">
            <h3 class="font-bold text-gray-900 mb-2">Tugas dan Wewenang Kepala Desa:</h3>
            <ul class="text-sm text-gray-700 space-y-1">
                <li>• Memimpin penyelenggaraan pemerintahan desa</li>
                <li>• Mengkoordinasikan pembangunan dan pemberdayaan masyarakat</li>
                <li>• Melaksanakan tugas dari pemerintah dan pemerintah daerah</li>
                <li>• Membina ketentraman dan ketertiban masyarakat</li>
            </ul>
        </div>
    </div>
    @endif

    <!-- Village Secretary -->
    @php $sekretaris = $groupedOfficials['sekretaris_desa']->first() ?? null; @endphp
    @if($sekretaris)
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-6 text-center flex items-center justify-center">
            <i class="fas fa-user-tie text-blue-600 mr-2"></i>
            Sekretaris Desa
        </h2>
        
        <div class="bg-blue-50 rounded-lg p-6 max-w-md mx-auto">
            <div class="flex flex-col items-center text-center space-y-4">
                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center overflow-hidden mx-auto">
                    @if($sekretaris->photo_path)
                    <img src="{{ asset('storage/' . $sekretaris->photo_path) }}" alt="{{ $sekretaris->name }}" class="w-full h-full object-cover">
                    @else
                    <i class="fas fa-user text-white text-2xl"></i>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">{{ $sekretaris->name }}</h3>
                    <p class="text-blue-600 font-medium">Sekretaris Desa</p>
                    @if($sekretaris->nip)
                    <p class="text-xs text-gray-600 mt-1">NIP. {{ $sekretaris->nip }}</p>
                    @endif
                    @if($sekretaris->education)
                    <p class="text-xs text-gray-500">{{ $sekretaris->education }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Department Heads (Kaur) -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-users-cog text-indigo-600 mr-2"></i>
            Kepala Urusan (Kaur)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $kaur_positions = [
                    'kaur_pemerintahan' => ['title' => 'Kaur Pemerintahan', 'color' => 'green'],
                    'kaur_keuangan' => ['title' => 'Kaur Keuangan', 'color' => 'yellow'],
                    'kaur_pelayanan' => ['title' => 'Kaur Pelayanan', 'color' => 'purple']
                ];
            @endphp
            
            <!-- Kaur Pemerintahan -->
            @php $kaur_pemerintahan = $groupedOfficials->has('kaur_pemerintahan') ? $groupedOfficials['kaur_pemerintahan']->first() : null; @endphp
            <div class="bg-gray-50 rounded-lg p-4">
                @if($kaur_pemerintahan)
                <div class="text-center mb-3">
                    <div class="w-16 h-16 bg-green-500 rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden">
                        @if($kaur_pemerintahan->photo_path)
                        <img src="{{ asset('storage/' . $kaur_pemerintahan->photo_path) }}" alt="{{ $kaur_pemerintahan->name }}" class="w-full h-full object-cover">
                        @else
                        <i class="fas fa-user text-white text-lg"></i>
                        @endif
                    </div>
                    <h4 class="font-bold text-gray-900">{{ $kaur_pemerintahan->name }}</h4>
                    <p class="text-sm text-green-600 mb-2">Kaur Pemerintahan</p>
                    @if($kaur_pemerintahan->education)
                    <p class="text-xs text-gray-500 mb-2">{{ $kaur_pemerintahan->education }}</p>
                    @endif
                </div>
                @if($kaur_pemerintahan->specialization)
                <div class="text-xs text-gray-600 space-y-1">
                    @foreach(explode(',', $kaur_pemerintahan->specialization) as $spec)
                    <div>• {{ trim($spec) }}</div>
                    @endforeach
                </div>
                @endif
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-400 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-500">Kaur Pemerintahan</h4>
                    <p class="text-xs text-gray-400">Belum ada data</p>
                </div>
                @endif
            </div>

            <!-- Kaur Keuangan -->
            @php $kaur_keuangan = $groupedOfficials->has('kaur_keuangan') ? $groupedOfficials['kaur_keuangan']->first() : null; @endphp
            <div class="bg-gray-50 rounded-lg p-4">
                @if($kaur_keuangan)
                <div class="text-center mb-3">
                    <div class="w-16 h-16 bg-yellow-500 rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden">
                        @if($kaur_keuangan->photo_path)
                        <img src="{{ asset('storage/' . $kaur_keuangan->photo_path) }}" alt="{{ $kaur_keuangan->name }}" class="w-full h-full object-cover">
                        @else
                        <i class="fas fa-user text-white text-lg"></i>
                        @endif
                    </div>
                    <h4 class="font-bold text-gray-900">{{ $kaur_keuangan->name }}</h4>
                    <p class="text-sm text-yellow-600 mb-2">Kaur Keuangan</p>
                    @if($kaur_keuangan->education)
                    <p class="text-xs text-gray-500 mb-2">{{ $kaur_keuangan->education }}</p>
                    @endif
                </div>
                @if($kaur_keuangan->specialization)
                <div class="text-xs text-gray-600 space-y-1">
                    @foreach(explode(',', $kaur_keuangan->specialization) as $spec)
                    <div>• {{ trim($spec) }}</div>
                    @endforeach
                </div>
                @endif
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-400 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-500">Kaur Keuangan</h4>
                    <p class="text-xs text-gray-400">Belum ada data</p>
                </div>
                @endif
            </div>

            <!-- Kaur Pelayanan -->
            @php $kaur_pelayanan = $groupedOfficials->has('kaur_pelayanan') ? $groupedOfficials['kaur_pelayanan']->first() : null; @endphp
            <div class="bg-gray-50 rounded-lg p-4">
                @if($kaur_pelayanan)
                <div class="text-center mb-3">
                    <div class="w-16 h-16 bg-purple-500 rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden">
                        @if($kaur_pelayanan->photo_path)
                        <img src="{{ asset('storage/' . $kaur_pelayanan->photo_path) }}" alt="{{ $kaur_pelayanan->name }}" class="w-full h-full object-cover">
                        @else
                        <i class="fas fa-user text-white text-lg"></i>
                        @endif
                    </div>
                    <h4 class="font-bold text-gray-900">{{ $kaur_pelayanan->name }}</h4>
                    <p class="text-sm text-purple-600 mb-2">Kaur Pelayanan</p>
                    @if($kaur_pelayanan->education)
                    <p class="text-xs text-gray-500 mb-2">{{ $kaur_pelayanan->education }}</p>
                    @endif
                </div>
                @if($kaur_pelayanan->specialization)
                <div class="text-xs text-gray-600 space-y-1">
                    @foreach(explode(',', $kaur_pelayanan->specialization) as $spec)
                    <div>• {{ trim($spec) }}</div>
                    @endforeach
                </div>
                @endif
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-400 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <h4 class="font-medium text-gray-500">Kaur Pelayanan</h4>
                    <p class="text-xs text-gray-400">Belum ada data</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Village Heads (Kadus) -->
    @if($groupedOfficials->has('kadus'))
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-users text-orange-600 mr-2"></i>
            Kepala Dusun (Kadus)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $kadus_colors = ['orange', 'green', 'blue', 'purple', 'indigo', 'red'];
                $kadus_list = $groupedOfficials['kadus'];
            @endphp
            
            @foreach($kadus_list as $index => $kadus)
            @php
                $area_key = preg_match('/Dusun (\w+)/i', $kadus->work_area, $matches) ? "Dusun " . $matches[1] : $kadus->work_area;
                $population = $populationByArea[$area_key] ?? null;
                
                // Static color assignments to ensure Tailwind generates classes
                $colorClasses = [
                    0 => ['bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'circle' => 'bg-orange-500', 'text' => 'text-orange-600'],
                    1 => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'circle' => 'bg-green-500', 'text' => 'text-green-600'],
                    2 => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'circle' => 'bg-blue-500', 'text' => 'text-blue-600'],
                    3 => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'circle' => 'bg-purple-500', 'text' => 'text-purple-600'],
                ];
                $colors = $colorClasses[$index % count($colorClasses)];
            @endphp
            <div class="{{ $colors['bg'] }} rounded-lg p-4 border {{ $colors['border'] }}">
                <div class="text-center mb-3">
                    <div class="w-16 h-16 {{ $colors['circle'] }} rounded-full mx-auto mb-3 flex items-center justify-center overflow-hidden">
                        @if($kadus->photo_path)
                        <img src="{{ asset('storage/' . $kadus->photo_path) }}" alt="{{ $kadus->name }}" class="w-full h-full object-cover">
                        @else
                        <i class="fas fa-user text-white text-lg"></i>
                        @endif
                    </div>
                    <h4 class="font-bold text-gray-900">{{ $kadus->name }}</h4>
                    <p class="text-sm {{ $colors['text'] }} mb-2">Kadus {{ \Illuminate\Support\Str::title(\Illuminate\Support\Str::after($kadus->work_area, 'Dusun ')) }}</p>
                    <div class="text-xs text-gray-600">
                        <p>{{ str_replace(['Dusun I ', 'Dusun II ', 'Dusun III ', 'Dusun IV ', '(', ')'], '', $kadus->work_area) }}</p>
                        @if($population)
                        <p class="font-medium mt-1">{{ number_format($population->total_families ?? 0) }} KK • {{ number_format($population->total_people ?? 0) }} Jiwa</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Village Consultative Body (BPD) -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-balance-scale text-red-600 mr-2"></i>
            Badan Permusyawaratan Desa (BPD)
        </h2>
        
        <div class="bg-red-50 rounded-lg p-4 mb-4 border-l-4 border-red-500">
            <p class="text-sm text-gray-700 mb-2">
                <strong>Fungsi BPD:</strong> Membahas dan menyepakati rancangan peraturan desa bersama kepala desa, 
                menampung dan menyalurkan aspirasi masyarakat, dan melakukan pengawasan kinerja kepala desa.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($bpdMembers as $index => $member)
            @php 
                $bpdColors = [
                    0 => ['circle' => 'bg-red-500', 'text' => 'text-red-600'],
                    1 => ['circle' => 'bg-orange-500', 'text' => 'text-orange-600'],
                    2 => ['circle' => 'bg-blue-500', 'text' => 'text-blue-600'],
                ];
                $colors = $bpdColors[$index % count($bpdColors)];
            @endphp
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <div class="w-12 h-12 {{ $colors['circle'] }} rounded-full mx-auto mb-2 flex items-center justify-center overflow-hidden">
                    @if($member->photo_path)
                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                    @else
                    <i class="fas fa-user text-white"></i>
                    @endif
                </div>
                <h4 class="font-medium text-gray-900 text-sm">{{ $member->name }}</h4>
                <p class="text-xs {{ $colors['text'] }}">{{ $member->specialization }}</p>
                @if($member->education)
                <p class="text-xs text-gray-500 mt-1">{{ $member->education }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Additional Institutions -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-building text-teal-600 mr-2"></i>
            Lembaga Kemasyarakatan
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($communityInstitutions as $institution)
            @php
                // Define complete styling classes for each institution type
                $typeStyles = [
                    'women' => [
                        'container' => 'bg-pink-50 border-pink-200',
                        'icon' => 'fas fa-female text-pink-600'
                    ],
                    'youth' => [
                        'container' => 'bg-indigo-50 border-indigo-200',
                        'icon' => 'fas fa-users text-indigo-600'
                    ],
                    'community' => [
                        'container' => 'bg-green-50 border-green-200',
                        'icon' => 'fas fa-hands-helping text-green-600'
                    ],
                    'neighborhood' => [
                        'container' => 'bg-blue-50 border-blue-200',
                        'icon' => 'fas fa-home text-blue-600'
                    ],
                    'default' => [
                        'container' => 'bg-gray-50 border-gray-200',
                        'icon' => 'fas fa-building text-gray-600'
                    ]
                ];
                $styles = $typeStyles[$institution->type] ?? $typeStyles['default'];
            @endphp
            <div class="{{ $styles['container'] }} rounded-lg p-4 border">
                <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                    <i class="{{ $styles['icon'] }} mr-2"></i>
                    {{ $institution->name }}
                </h3>
                <p class="text-sm text-gray-700 mb-2">
                    @if($institution->leader_name)
                    <strong>{{ $institution->leader_title ?? 'Ketua' }}:</strong> {{ $institution->leader_name }}<br>
                    @endif
                    @if($institution->member_count)
                    <strong>{{ $institution->type === 'neighborhood' ? 'Jumlah' : 'Anggota' }}:</strong> {{ $institution->member_count }} {{ $institution->type === 'neighborhood' ? '' : 'orang' }}
                    @endif
                </p>
                @if($institution->description)
                <p class="text-xs text-gray-600">
                    {{ $institution->description }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection