@extends('frontend.main')

@section('title', 'Data Penduduk - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'DATA PENDUDUK')
@section('header_icon', 'fas fa-users')
@section('header_bg_color', 'bg-sky-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Penduduk</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($statistics['total_population']) }}</p>
                    <p class="text-xs text-gray-500">Jiwa</p>
                </div>
                <i class="fas fa-users text-3xl text-blue-500"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Kepala Keluarga</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($statistics['total_families']) }}</p>
                    <p class="text-xs text-gray-500">KK</p>
                </div>
                <i class="fas fa-home text-3xl text-green-500"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Laki-laki</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ number_format($statistics['male_population']) }}</p>
                    <p class="text-xs text-gray-500">Jiwa ({{ $statistics['male_percentage'] }}%)</p>
                </div>
                <i class="fas fa-male text-3xl text-yellow-500"></i>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-pink-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Perempuan</p>
                    <p class="text-2xl font-bold text-pink-600">{{ number_format($statistics['female_population']) }}</p>
                    <p class="text-xs text-gray-500">Jiwa ({{ $statistics['female_percentage'] }}%)</p>
                </div>
                <i class="fas fa-female text-3xl text-pink-500"></i>
            </div>
        </div>
    </div>

    <!-- Age Distribution -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
            Distribusi Penduduk Berdasarkan Usia
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">0-4 Tahun</h3>
                <div class="flex justify-between text-sm mb-2">
                    <span>Balita</span>
                    <span class="font-bold">{{ number_format($ageDistribution['balita']) }} jiwa</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $ageDistribution['balita_percentage'] }}%"></div>
                </div>
                <p class="text-xs text-gray-600 mt-1">{{ $ageDistribution['balita_percentage'] }}% dari total penduduk</p>
            </div>
            
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">5-17 Tahun</h3>
                <div class="flex justify-between text-sm mb-2">
                    <span>Anak & Remaja</span>
                    <span class="font-bold">{{ number_format($ageDistribution['anak_remaja']) }} jiwa</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $ageDistribution['anak_remaja_percentage'] }}%"></div>
                </div>
                <p class="text-xs text-gray-600 mt-1">{{ $ageDistribution['anak_remaja_percentage'] }}% dari total penduduk</p>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">18-64 Tahun</h3>
                <div class="flex justify-between text-sm mb-2">
                    <span>Usia Produktif</span>
                    <span class="font-bold">{{ number_format($ageDistribution['produktif']) }} jiwa</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $ageDistribution['produktif_percentage'] }}%"></div>
                </div>
                <p class="text-xs text-gray-600 mt-1">{{ $ageDistribution['produktif_percentage'] }}% dari total penduduk</p>
            </div>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-2">65+ Tahun</h3>
                <div class="flex justify-between text-sm mb-2">
                    <span>Lansia</span>
                    <span class="font-bold">{{ number_format($ageDistribution['lansia']) }} jiwa</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $ageDistribution['lansia_percentage'] }}%"></div>
                </div>
                <p class="text-xs text-gray-600 mt-1">{{ $ageDistribution['lansia_percentage'] }}% dari total penduduk</p>
            </div>
        </div>
    </div>

    <!-- Education Level -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-graduation-cap text-indigo-600 mr-2"></i>
            Tingkat Pendidikan (Usia 15+)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-700">Tidak/Belum Sekolah</span>
                    <span class="font-bold text-gray-900">{{ number_format($educationLevels['tidak_sekolah']) }} orang</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $educationLevels['tidak_sekolah'] > 0 ? round(($educationLevels['tidak_sekolah'] / array_sum($educationLevels)) * 100, 1) : 0 }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-700">SD/Sederajat</span>
                    <span class="font-bold text-gray-900">{{ number_format($educationLevels['sd']) }} orang</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $educationLevels['sd'] > 0 ? round(($educationLevels['sd'] / array_sum($educationLevels)) * 100, 1) : 0 }}%"></div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-700">SMP/Sederajat</span>
                    <span class="font-bold text-gray-900">{{ number_format($educationLevels['smp']) }} orang</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $educationLevels['smp'] > 0 ? round(($educationLevels['smp'] / array_sum($educationLevels)) * 100, 1) : 0 }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-700">SMA/Sederajat</span>
                    <span class="font-bold text-gray-900">{{ number_format($educationLevels['sma']) }} orang</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $educationLevels['sma'] > 0 ? round(($educationLevels['sma'] / array_sum($educationLevels)) * 100, 1) : 0 }}%"></div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-700">Diploma/S1</span>
                    <span class="font-bold text-gray-900">{{ number_format($educationLevels['diploma']) }} orang</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $educationLevels['diploma'] > 0 ? round(($educationLevels['diploma'] / array_sum($educationLevels)) * 100, 1) : 0 }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-700">S2/S3</span>
                    <span class="font-bold text-gray-900">{{ number_format($educationLevels['pascasarjana']) }} orang</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $educationLevels['pascasarjana'] > 0 ? round(($educationLevels['pascasarjana'] / array_sum($educationLevels)) * 100, 1) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Occupation -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-briefcase text-teal-600 mr-2"></i>
            Mata Pencaharian (Usia Kerja)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-seedling text-green-600 mr-3"></i>
                        <span class="text-sm font-medium text-gray-700">Petani</span>
                    </div>
                    <span class="font-bold text-green-600">{{ number_format($occupations['petani']) }} orang</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-hammer text-yellow-600 mr-3"></i>
                        <span class="text-sm font-medium text-gray-700">Buruh Tani</span>
                    </div>
                    <span class="font-bold text-yellow-600">{{ number_format($occupations['buruh_tani']) }} orang</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-store text-blue-600 mr-3"></i>
                        <span class="text-sm font-medium text-gray-700">Wiraswasta/Dagang</span>
                    </div>
                    <span class="font-bold text-blue-600">{{ number_format($occupations['wiraswasta']) }} orang</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-user-tie text-purple-600 mr-3"></i>
                        <span class="text-sm font-medium text-gray-700">PNS/TNI/POLRI</span>
                    </div>
                    <span class="font-bold text-purple-600">{{ number_format($occupations['pns']) }} orang</span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-chalkboard-teacher text-orange-600 mr-3"></i>
                        <span class="text-sm font-medium text-gray-700">Guru/Pendidik</span>
                    </div>
                    <span class="font-bold text-orange-600">{{ number_format($occupations['guru']) }} orang</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-heartbeat text-red-600 mr-3"></i>
                        <span class="text-sm font-medium text-gray-700">Tenaga Kesehatan</span>
                    </div>
                    <span class="font-bold text-red-600">{{ number_format($occupations['kesehatan']) }} orang</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-laptop text-indigo-600 mr-3"></i>
                        <span class="text-sm font-medium text-gray-700">IT/Teknologi</span>
                    </div>
                    <span class="font-bold text-indigo-600">{{ number_format($occupations['teknologi']) }} orang</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-ellipsis-h text-gray-600 mr-3"></i>
                        <span class="text-sm font-medium text-gray-700">Lainnya</span>
                    </div>
                    <span class="font-bold text-gray-600">{{ number_format($occupations['lainnya']) }} orang</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Population by Village Area -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-map-marked-alt text-cyan-600 mr-2"></i>
            Sebaran Penduduk Per Dusun
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                <div class="text-center mb-4">
                    <h3 class="font-bold text-gray-900 text-lg">Dusun I Krandegan</h3>
                    <p class="text-3xl font-bold text-orange-600">{{ number_format($populationByArea['dusun_1']) }}</p>
                    <p class="text-sm text-gray-600">jiwa</p>
                </div>
                <div class="space-y-2 text-sm bg-white rounded-lg p-3">
                    <div class="flex justify-between">
                        <span class="text-gray-700">RT 01:</span>
                        <span class="font-medium text-gray-900">{{ number_format(round($populationByArea['dusun_1'] * 0.33)) }} jiwa</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">RT 02:</span>
                        <span class="font-medium text-gray-900">{{ number_format(round($populationByArea['dusun_1'] * 0.34)) }} jiwa</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-700">RT 03:</span>
                        <span class="font-medium text-gray-900">{{ number_format($populationByArea['dusun_1'] - round($populationByArea['dusun_1'] * 0.33) - round($populationByArea['dusun_1'] * 0.34)) }} jiwa</span>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-600 text-center">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    Wilayah Utara - 3 RT, 1 RW
                </div>
            </div>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="text-center mb-4">
                    <h3 class="font-bold text-gray-900 text-lg">Dusun II Krandegan</h3>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($populationByArea['dusun_2']) }}</p>
                    <p class="text-sm text-gray-600">jiwa</p>
                </div>
                <div class="space-y-2 text-sm bg-white rounded-lg p-3">
                    <div class="flex justify-between">
                        <span class="text-gray-700">RT 04:</span>
                        <span class="font-medium text-gray-900">{{ number_format(round($populationByArea['dusun_2'] * 0.34)) }} jiwa</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">RT 05:</span>
                        <span class="font-medium text-gray-900">{{ number_format(round($populationByArea['dusun_2'] * 0.32)) }} jiwa</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-gray-700">RT 06:</span>
                        <span class="font-medium text-gray-900">{{ number_format($populationByArea['dusun_2'] - round($populationByArea['dusun_2'] * 0.34) - round($populationByArea['dusun_2'] * 0.32)) }} jiwa</span>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-600 text-center">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    Wilayah Selatan - 3 RT, 1 RW
                </div>
            </div>
        </div>
        
        <!-- Settlement Summary -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                Ringkasan Sebaran Penduduk
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex justify-between items-center p-3 bg-white rounded">
                    <span class="text-gray-700">Total Dusun I:</span>
                    <span class="font-bold text-orange-600">{{ number_format($populationByArea['dusun_1']) }} jiwa ({{ round(($populationByArea['dusun_1'] / ($populationByArea['dusun_1'] + $populationByArea['dusun_2'])) * 100, 1) }}%)</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-white rounded">
                    <span class="text-gray-700">Total Dusun II:</span>
                    <span class="font-bold text-green-600">{{ number_format($populationByArea['dusun_2']) }} jiwa ({{ round(($populationByArea['dusun_2'] / ($populationByArea['dusun_1'] + $populationByArea['dusun_2'])) * 100, 1) }}%)</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection