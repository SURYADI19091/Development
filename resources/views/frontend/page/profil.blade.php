@extends('frontend.main')

@section('title', 'Profil Desa - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'PROFIL DESA')
@section('header_icon', 'fas fa-home')
@section('header_bg_color', 'bg-green-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Village Profile Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
            <div class="flex-shrink-0">
                <img src="/images/logo-desa.png" alt="Logo Desa" class="w-24 h-24 object-contain" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTYiIGhlaWdodD0iOTYiIHZpZXdCb3g9IjAgMCA5NiA5NiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9Ijk2IiBoZWlnaHQ9Ijk2IiByeD0iNDgiIGZpbGw9IiNGM0Y0RjYiLz4KPHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4PSIyNCIgeT0iMjQiPgo8cGF0aCBkPSJNMjQgMTJMMzYgMjBWMzZIMTJWMjBMMjQgMTJaIiBzdHJva2U9IiM2QjcyODAiIHN0cm9rZS13aWR0aD0iMiIgZmlsbD0ibm9uZSIvPgo8L3N2Zz4KPC9zdmc+'">
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $villageProfile->village_name ?? 'Desa Krandegan' }}</h1>
                <p class="text-gray-600 mb-4">Kecamatan {{ $villageProfile->district ?? 'Telagasari' }}, Kabupaten {{ $villageProfile->regency ?? 'Karawang' }}, Provinsi {{ $villageProfile->province ?? 'Jawa Barat' }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Kode Desa:</span>
                        <span class="text-gray-600 ml-2">{{ $villageProfile->village_code ?? '32.15.08.2008' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Kode Pos:</span>
                        <span class="text-gray-600 ml-2">{{ $villageProfile->postal_code ?? '41361' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Luas Wilayah:</span>
                        <span class="text-gray-600 ml-2">{{ number_format((float)($villageProfile->area_size ?? 485.76), 2) }} Ha</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Jumlah Penduduk:</span>
                        <span class="text-gray-600 ml-2">{{ number_format((int)($villageProfile->total_population ?? 3032)) }} Jiwa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Geographic Information -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
            Letak Geografis
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Batas Wilayah</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex">
                        <span class="font-medium text-gray-700 w-20">Utara:</span>
                        <span class="text-gray-600">{{ $villageProfile->north_border ?? 'Desa Kutamekar' }}</span>
                    </li>
                    <li class="flex">
                        <span class="font-medium text-gray-700 w-20">Selatan:</span>
                        <span class="text-gray-600">{{ $villageProfile->south_border ?? 'Desa Telagasari' }}</span>
                    </li>
                    <li class="flex">
                        <span class="font-medium text-gray-700 w-20">Timur:</span>
                        <span class="text-gray-600">{{ $villageProfile->east_border ?? 'Desa Sukamandi' }}</span>
                    </li>
                    <li class="flex">
                        <span class="font-medium text-gray-700 w-20">Barat:</span>
                        <span class="text-gray-600">{{ $villageProfile->west_border ?? 'Desa Margamukti' }}</span>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Koordinat & Topografi</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex">
                        <span class="font-medium text-gray-700 w-24">Latitude:</span>
                        <span class="text-gray-600">{{ $villageProfile->latitude ?? '-6.2384' }}°</span>
                    </li>
                    <li class="flex">
                        <span class="font-medium text-gray-700 w-24">Longitude:</span>
                        <span class="text-gray-600">{{ $villageProfile->longitude ?? '107.2734' }}°</span>
                    </li>
                    <li class="flex">
                        <span class="font-medium text-gray-700 w-24">Ketinggian:</span>
                        <span class="text-gray-600">{{ $villageProfile->altitude ?? '15-25 mdpl' }}</span>
                    </li>
                    <li class="flex">
                        <span class="font-medium text-gray-700 w-24">Topografi:</span>
                        <span class="text-gray-600">{{ $villageProfile->topography ?? 'Dataran rendah' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Village Description -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            Tentang {{ $villageProfile->village_name ?? 'Desa Krandegan' }}
        </h2>
        <div class="prose prose-gray max-w-none">
            @if($villageProfile && $villageProfile->description)
                <div class="text-gray-700 leading-relaxed">
                    {!! nl2br(e($villageProfile->description)) !!}
                </div>
            @else
            <p class="text-gray-700 leading-relaxed mb-4">
                {{ $villageProfile->village_name ?? 'Desa Krandegan' }} adalah salah satu desa yang terletak di Kecamatan {{ $villageProfile->district ?? 'Telagasari' }}, Kabupaten {{ $villageProfile->regency ?? 'Karawang' }}, Provinsi {{ $villageProfile->province ?? 'Jawa Barat' }}. 
                Desa ini memiliki luas wilayah {{ number_format((float)($villageProfile->area_size ?? 485.76), 2) }} hektar dengan jumlah penduduk sebanyak {{ number_format((int)($villageProfile->total_population ?? 3032)) }} jiwa yang terdiri dari {{ number_format((int)($villageProfile->total_families ?? 986)) }} kepala keluarga.
            </p>
            <p class="text-gray-700 leading-relaxed mb-4">
                {{ $villageProfile->village_name ?? 'Desa Krandegan' }} memiliki potensi yang cukup baik dalam bidang pertanian, dengan mayoritas penduduknya bermata pencaharian 
                sebagai petani dan buruh tani. Wilayah desa ini sebagian besar merupakan lahan pertanian yang subur dengan sistem pengairan 
                yang cukup memadai.
            </p>
            <p class="text-gray-700 leading-relaxed">
                Sebagai bagian dari Kabupaten {{ $villageProfile->regency ?? 'Karawang' }} yang dikenal sebagai lumbung padi Jawa Barat, {{ $villageProfile->village_name ?? 'Desa Krandegan' }} turut berkontribusi 
                dalam pemenuhan kebutuhan pangan nasional melalui produksi padi dan komoditas pertanian lainnya.
            </p>
            @endif
        </div>
    </div>

    <!-- Infrastructure and Facilities -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-building text-purple-600 mr-2"></i>
            Sarana dan Prasarana
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Pendidikan</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    @if(isset($infrastructure['education']))
                        @foreach($infrastructure['education'] as $item)
                            <li>• {{ number_format($item->value) }} {{ $item->label }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Kesehatan</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    @if(isset($infrastructure['health']))
                        @foreach($infrastructure['health'] as $item)
                            <li>• {{ number_format($item->value) }} {{ $item->label }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Peribadatan</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    @if(isset($infrastructure['worship']))
                        @foreach($infrastructure['worship'] as $item)
                            <li>• {{ number_format($item->value) }} {{ $item->label }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Ekonomi</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    @if(isset($infrastructure['economy']))
                        @foreach($infrastructure['economy'] as $item)
                            <li>• {{ number_format($item->value) }} {{ $item->label }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Transportasi</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    @if(isset($infrastructure['transportation']))
                        @foreach($infrastructure['transportation'] as $item)
                            <li>• {{ $item->label }}: {{ number_format($item->value, ($item->unit == 'km' ? 1 : 0)) }} {{ $item->unit }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Utilitas</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    @if(isset($infrastructure['utilities']))
                        @foreach($infrastructure['utilities'] as $item)
                            <li>• {{ $item->label }}: {{ number_format($item->value) }}{{ $item->unit == '%' ? '%' : ' ' . $item->unit }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection