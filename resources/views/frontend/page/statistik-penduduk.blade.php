@extends('frontend.main')

@section('title', 'Statistik Penduduk - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'STATISTIK PENDUDUK')
@section('header_icon', 'fas fa-chart-line')
@section('header_bg_color', 'bg-indigo-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Population Growth Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-chart-area text-indigo-600 mr-2"></i>
            Pertumbuhan Penduduk (5 Tahun Terakhir)
        </h2>
        
        <div class="mb-6">
            <canvas id="populationGrowthChart" width="400" height="200"></canvas>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center">
            @foreach($yearlyData as $index => $data)
            <div class="bg-indigo-50 rounded-lg p-3 {{ $loop->last ? 'bg-indigo-100 border-2 border-indigo-300' : '' }}">
                <p class="text-2xl font-bold {{ $loop->last ? 'text-indigo-700' : 'text-indigo-600' }}">
                    {{ number_format($data['total']) }}
                </p>
                <p class="text-sm {{ $loop->last ? 'text-indigo-600 font-medium' : 'text-gray-600' }}">
                    {{ $data['year'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Demographic Comparison -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Gender Distribution -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-venus-mars text-purple-600 mr-2"></i>
                Distribusi Jenis Kelamin
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-blue-600">Laki-laki</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ number_format($genderStats['male_count']) }} jiwa 
                            ({{ number_format($genderStats['male_percentage'], 1) }}%)
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $genderStats['male_percentage'] }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-pink-600">Perempuan</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ number_format($genderStats['female_count']) }} jiwa 
                            ({{ number_format($genderStats['female_percentage'], 1) }}%)
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-pink-500 h-3 rounded-full" style="width: {{ $genderStats['female_percentage'] }}%"></div>
                    </div>
                </div>
            </div>
            <div class="mt-4 p-3 bg-purple-50 rounded-lg">
                <p class="text-sm text-purple-700">
                    <i class="fas fa-balance-scale mr-1"></i>
                    Rasio jenis kelamin: {{ $genderStats['ratio'] }}
                </p>
            </div>
        </div>

        <!-- Age Pyramid -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                Piramida Penduduk
            </h3>
            <div class="space-y-2">
                @php
                    $ageGroups = [
                        ['label' => '65+', 'min' => 65, 'max' => 120],
                        ['label' => '50-64', 'min' => 50, 'max' => 64],
                        ['label' => '35-49', 'min' => 35, 'max' => 49],
                        ['label' => '18-34', 'min' => 18, 'max' => 34],
                        ['label' => '5-17', 'min' => 5, 'max' => 17],
                        ['label' => '0-4', 'min' => 0, 'max' => 4]
                    ];
                @endphp

                @foreach($ageGroups as $group)
                @php
                    $maleCount = $ageStats->where('gender', 'Laki-laki')
                                         ->whereBetween('age', [$group['min'], $group['max']])->sum('count');
                    $femaleCount = $ageStats->where('gender', 'Perempuan')
                                           ->whereBetween('age', [$group['min'], $group['max']])->sum('count');
                    
                    $maxCount = max($ageStats->max('count'), 1);
                    $maleWidth = ($maleCount / $maxCount) * 100;
                    $femaleWidth = ($femaleCount / $maxCount) * 100;
                @endphp
                <div class="flex items-center" title="Laki-laki: {{ $maleCount }}, Perempuan: {{ $femaleCount }}">
                    <span class="w-16 text-xs text-gray-600">{{ $group['label'] }}</span>
                    <div class="flex-1 flex">
                        <div class="w-1/2 flex justify-end pr-1">
                            <div class="bg-blue-500 h-4 rounded-l transition-all duration-300" 
                                 style="width: {{ $maleWidth }}%"></div>
                        </div>
                        <div class="w-1/2 pl-1">
                            <div class="bg-pink-500 h-4 rounded-r transition-all duration-300" 
                                 style="width: {{ $femaleWidth }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="flex justify-center mt-3 space-x-4 text-xs">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded mr-1"></div>
                    <span>Laki-laki</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-pink-500 rounded mr-1"></div>
                    <span>Perempuan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-table text-teal-600 mr-2"></i>
            Statistik Detail Kependudukan
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Status Perkawinan -->
            <div>
                <h3 class="font-bold text-gray-900 mb-3">Status Perkawinan</h3>
                <div class="space-y-2">
                    @php
                        $maritalColors = [
                            'Kawin' => 'green',
                            'Belum Kawin' => 'blue', 
                            'Cerai Hidup' => 'gray',
                            'Cerai Mati' => 'red'
                        ];
                    @endphp
                    @foreach($maritalStatus as $status)
                    @php $color = $maritalColors[$status->marital_status] ?? 'gray'; @endphp
                    <div class="flex justify-between items-center p-2 bg-{{ $color }}-50 rounded">
                        <span class="text-sm">{{ $status->marital_status }}</span>
                        <span class="font-bold text-{{ $color }}-600">{{ number_format($status->count) }} orang</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Agama -->
            <div>
                <h3 class="font-bold text-gray-900 mb-3">Agama</h3>
                <div class="space-y-2">
                    @php
                        $religionColors = [
                            'Islam' => 'green',
                            'Kristen' => 'blue',
                            'Katolik' => 'purple',
                            'Hindu' => 'orange',
                            'Buddha' => 'yellow'
                        ];
                    @endphp
                    @foreach($religions as $religion)
                    @php $color = $religionColors[$religion->religion] ?? 'gray'; @endphp
                    <div class="flex justify-between items-center p-2 bg-{{ $color }}-50 rounded">
                        <span class="text-sm">{{ $religion->religion }}</span>
                        <span class="font-bold text-{{ $color }}-600">{{ number_format($religion->count) }} orang</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pekerjaan -->
            <div>
                <h3 class="font-bold text-gray-900 mb-3">Pekerjaan Utama</h3>
                <div class="space-y-2">
                    @php
                        $occupationColors = [
                            'Petani' => 'green',
                            'Pedagang' => 'blue',
                            'Buruh' => 'gray',
                            'PNS' => 'purple',
                            'Swasta' => 'orange',
                            'Lainnya' => 'yellow'
                        ];
                    @endphp
                    @foreach($occupations->take(6) as $occupation)
                    @php $color = $occupationColors[$occupation->occupation] ?? 'gray'; @endphp
                    <div class="flex justify-between items-center p-2 bg-{{ $color }}-50 rounded">
                        <span class="text-sm">{{ $occupation->occupation }}</span>
                        <span class="font-bold text-{{ $color }}-600">{{ number_format($occupation->count) }} orang</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Birth & Death Statistics -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-heartbeat text-red-600 mr-2"></i>
            Statistik Kelahiran & Kematian (2025)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Birth Statistics -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="font-bold text-green-800 mb-4 flex items-center">
                    <i class="fas fa-baby text-green-600 mr-2"></i>
                    Data Kelahiran
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-700">Total Kelahiran</span>
                        <span class="font-bold text-green-700">{{ number_format($birthDeathStats['births']['total']) }} bayi</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-700">Laki-laki</span>
                        <span class="font-bold text-blue-600">{{ number_format($birthDeathStats['births']['male']) }} bayi</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-700">Perempuan</span>
                        <span class="font-bold text-pink-600">{{ number_format($birthDeathStats['births']['female']) }} bayi</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-sm font-medium text-gray-700">Angka Kelahiran</span>
                        <span class="font-bold text-green-700">{{ $birthDeathStats['births']['rate'] }}‰</span>
                    </div>
                </div>
            </div>

            <!-- Death Statistics -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-cross text-gray-600 mr-2"></i>
                    Data Kematian
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-700">Total Kematian</span>
                        <span class="font-bold text-gray-700">{{ number_format($birthDeathStats['deaths']['total']) }} orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-700">Laki-laki</span>
                        <span class="font-bold text-blue-600">{{ number_format($birthDeathStats['deaths']['male']) }} orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-700">Perempuan</span>
                        <span class="font-bold text-pink-600">{{ number_format($birthDeathStats['deaths']['female']) }} orang</span>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <span class="text-sm font-medium text-gray-700">Angka Kematian</span>
                        <span class="font-bold text-gray-700">{{ $birthDeathStats['deaths']['rate'] }}‰</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-blue-800">Pertumbuhan Alami</h4>
                    <p class="text-sm text-blue-700">Selisih kelahiran dan kematian</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-800">
                        {{ $birthDeathStats['natural_growth']['total'] >= 0 ? '+' : '' }}{{ number_format($birthDeathStats['natural_growth']['total']) }} jiwa
                    </p>
                    <p class="text-sm text-blue-600">{{ $birthDeathStats['natural_growth']['rate'] }}‰ per tahun</p>
                </div>
            </div>
        </div>

        <!-- Population Status Summary -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <i class="fas fa-users text-green-600 text-2xl mb-2"></i>
                <h4 class="font-bold text-green-800">Penduduk Hidup</h4>
                <p class="text-2xl font-bold text-green-700">{{ number_format($birthDeathStats['population_status']['living']) }}</p>
                <p class="text-sm text-green-600">jiwa</p>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                <i class="fas fa-cross text-gray-600 text-2xl mb-2"></i>
                <h4 class="font-bold text-gray-800">Penduduk Meninggal</h4>
                <p class="text-2xl font-bold text-gray-700">{{ number_format($birthDeathStats['population_status']['deceased']) }}</p>
                <p class="text-sm text-gray-600">jiwa</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                <i class="fas fa-clipboard-list text-blue-600 text-2xl mb-2"></i>
                <h4 class="font-bold text-blue-800">Total Terdaftar</h4>
                <p class="text-2xl font-bold text-blue-700">{{ number_format($birthDeathStats['population_status']['total']) }}</p>
                <p class="text-sm text-blue-600">jiwa</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Population Growth Chart
    const ctx = document.getElementById('populationGrowthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($yearlyData->pluck('year')) !!},
            datasets: [{
                label: 'Total Penduduk',
                data: {!! json_encode($yearlyData->pluck('total')) !!},
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Total: ' + context.parsed.y.toLocaleString() + ' jiwa';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' jiwa';
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
</script>
@endsection