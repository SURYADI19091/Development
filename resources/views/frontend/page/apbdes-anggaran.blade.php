@extends('frontend.main')

@section('title', 'Anggaran APBDes - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'ANGGARAN APB DESA {{ $currentYear }}')
@section('header_icon', 'fas fa-calculator')
@section('header_bg_color', 'bg-blue-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Anggaran Summary -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">Rencana Anggaran {{ $currentYear }}</h2>
                <p class="text-lg opacity-90 mb-4">
                    Alokasi dana untuk pembangunan dan pelayanan masyarakat
                </p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">Rp {{ number_format($totalRevenue / 1000000, 1) }} M</div>
                        <div class="text-sm opacity-90">Total Anggaran</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $expenditureByCategory->count() }}</div>
                        <div class="text-sm opacity-90">Bidang Utama</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $budgets->count() }}+</div>
                        <div class="text-sm opacity-90">Program Kerja</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-balance-scale text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6 text-xl">Sumber Pendapatan Desa</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Chart Area -->
            <div class="relative">
                <canvas id="revenueChart" width="300" height="300"></canvas>
            </div>
            
            <!-- Legend & Details -->
            <div class="space-y-4">
                @if($revenueBreakdown['dana_desa'] > 0)
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>
                        <div>
                            <div class="font-medium text-gray-900">Dana Desa</div>
                            <div class="text-sm text-gray-600">Dari APBN</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-blue-600">Rp {{ number_format($revenueBreakdown['dana_desa'] / 1000000, 1) }} M</div>
                        <div class="text-sm text-gray-600">{{ $totalRevenue > 0 ? round(($revenueBreakdown['dana_desa'] / $totalRevenue) * 100, 1) : 0 }}%</div>
                    </div>
                </div>
                @endif

                @if($revenueBreakdown['add'] > 0)
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                        <div>
                            <div class="font-medium text-gray-900">ADD</div>
                            <div class="text-sm text-gray-600">Alokasi Dana Desa</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-green-600">Rp {{ number_format($revenueBreakdown['add'] / 1000000, 1) }} M</div>
                        <div class="text-sm text-gray-600">{{ $totalRevenue > 0 ? round(($revenueBreakdown['add'] / $totalRevenue) * 100, 1) : 0 }}%</div>
                    </div>
                </div>
                @endif

                @if($revenueBreakdown['dana_bantuan'] > 0)
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-3"></div>
                        <div>
                            <div class="font-medium text-gray-900">Dana Bantuan</div>
                            <div class="text-sm text-gray-600">APBD Kabupaten/Provinsi</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-yellow-600">Rp {{ number_format($revenueBreakdown['dana_bantuan'] / 1000000, 1) }} M</div>
                        <div class="text-sm text-gray-600">{{ $totalRevenue > 0 ? round(($revenueBreakdown['dana_bantuan'] / $totalRevenue) * 100, 1) : 0 }}%</div>
                    </div>
                </div>
                @endif

                @if($revenueBreakdown['pades'] > 0)
                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 rounded mr-3"></div>
                        <div>
                            <div class="font-medium text-gray-900">PADes</div>
                            <div class="text-sm text-gray-600">Pendapatan Asli Desa</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-purple-600">Rp {{ number_format($revenueBreakdown['pades'] / 1000000, 1) }} M</div>
                        <div class="text-sm text-gray-600">{{ $totalRevenue > 0 ? round(($revenueBreakdown['pades'] / $totalRevenue) * 100, 1) : 0 }}%</div>
                    </div>
                </div>
                @endif

                @if($revenueBreakdown['lain_lain'] > 0)
                <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-indigo-500 rounded mr-3"></div>
                        <div>
                            <div class="font-medium text-gray-900">Lain-lain</div>
                            <div class="text-sm text-gray-600">Pendapatan Lainnya</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-indigo-600">Rp {{ number_format($revenueBreakdown['lain_lain'] / 1000000, 1) }} M</div>
                        <div class="text-sm text-gray-600">{{ $totalRevenue > 0 ? round(($revenueBreakdown['lain_lain'] / $totalRevenue) * 100, 1) : 0 }}%</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Expenditure by Sector -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6 text-xl">Alokasi Belanja per Bidang</h3>
        
        <div class="space-y-6">
            @php
                $categoryIcons = [
                    'Penyelenggaraan Pemerintahan Desa' => ['icon' => 'fas fa-building', 'color' => 'blue'],
                    'Pelaksanaan Pembangunan Desa' => ['icon' => 'fas fa-hammer', 'color' => 'green'],
                    'Pembinaan Kemasyarakatan' => ['icon' => 'fas fa-users', 'color' => 'yellow'],
                    'Pemberdayaan Masyarakat' => ['icon' => 'fas fa-handshake', 'color' => 'purple'],
                    'Penanggulangan Bencana' => ['icon' => 'fas fa-shield-alt', 'color' => 'red'],
                ];
                $categoryDescriptions = [
                    'Penyelenggaraan Pemerintahan Desa' => 'Operasional dan administrasi pemerintahan',
                    'Pelaksanaan Pembangunan Desa' => 'Infrastruktur dan sarana prasarana',
                    'Pembinaan Kemasyarakatan' => 'Keamanan, ketertiban, dan sosial budaya',
                    'Pemberdayaan Masyarakat' => 'Ekonomi, kesehatan, dan pendidikan',
                    'Penanggulangan Bencana' => 'Mitigasi dan penanganan bencana',
                ];
                $colorIndex = 0;
                $colors = ['blue', 'green', 'yellow', 'purple', 'red', 'indigo'];
            @endphp

            @foreach($expenditureByCategory as $category => $data)
            @php
                $iconData = $categoryIcons[$category] ?? ['icon' => 'fas fa-folder', 'color' => $colors[$colorIndex % count($colors)]];
                $description = $categoryDescriptions[$category] ?? 'Program dan kegiatan lainnya';
                $colorIndex++;
            @endphp
            <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-{{ $iconData['color'] }}-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="{{ $iconData['icon'] }} text-{{ $iconData['color'] }}-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $category }}</h4>
                            <p class="text-sm text-gray-600">{{ $description }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-{{ $iconData['color'] }}-600">
                            Rp {{ number_format($data['planned_amount'] / 1000000, 1) }} M
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $totalExpenditure > 0 ? round(($data['planned_amount'] / $totalExpenditure) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>
                
                @if(count($data['items']) > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach(array_slice($data['items'], 0, 3) as $item)
                    <div class="text-center p-3 bg-gray-50 rounded">
                        <div class="font-bold text-gray-900">
                            Rp {{ number_format($item['planned_amount'] / 1000000, 1) }} M
                        </div>
                        <div class="text-sm text-gray-600">{{ Str::limit($item['description'], 30) }}</div>
                    </div>
                    @endforeach
                    
                    @if(count($data['items']) > 3)
                    <div class="text-center p-3 bg-gray-100 rounded border-2 border-dashed border-gray-300">
                        <div class="font-bold text-gray-600">+{{ count($data['items']) - 3 }}</div>
                        <div class="text-sm text-gray-600">Program Lainnya</div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Timeline & Milestones -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6 text-xl">Timeline Pelaksanaan Anggaran</h3>
        
        <div class="relative">
            <!-- Timeline Line -->
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-300"></div>
            
            <div class="space-y-8">
                @php
                    $quarterNames = ['Triwulan I (Jan - Mar)', 'Triwulan II (Apr - Jun)', 'Triwulan III (Jul - Sep)', 'Triwulan IV (Okt - Des)'];
                    $quarterFocus = ['Operasional dan Perencanaan', 'Pembangunan Infrastruktur', 'Pembinaan Masyarakat', 'Penyelesaian & Evaluasi'];
                    $currentQuarter = ceil(date('n') / 3);
                @endphp
                
                @foreach($quarterlyProgress as $q => $progress)
                @php
                    $quarterNum = (int)str_replace('q', '', $q);
                    $isCompleted = $quarterNum < $currentQuarter;
                    $isCurrent = $quarterNum == $currentQuarter;
                    $isFuture = $quarterNum > $currentQuarter;
                    
                    $bgColor = $isCompleted ? 'green' : ($isCurrent ? 'blue' : 'gray');
                    $icon = $isCompleted ? 'fas fa-check' : ($isCurrent ? 'fas fa-clock' : 'fas fa-hourglass-half');
                @endphp
                
                <div class="relative flex items-start">
                    <div class="w-8 h-8 bg-{{ $bgColor }}-{{ $isCompleted ? '500' : ($isCurrent ? '500' : '300') }} rounded-full flex items-center justify-center z-10">
                        <i class="{{ $icon }} text-{{ $isCompleted || $isCurrent ? 'white' : 'gray-600' }} text-sm"></i>
                    </div>
                    <div class="ml-6 flex-1">
                        <div class="bg-{{ $bgColor }}-50 border border-{{ $bgColor }}-200 rounded-lg p-4">
                            <h4 class="font-bold text-{{ $bgColor }}-900">{{ $quarterNames[$quarterNum-1] }}</h4>
                            <p class="text-sm text-{{ $bgColor }}-{{ $isFuture ? '600' : '800' }} mb-2">Fokus: {{ $quarterFocus[$quarterNum-1] }}</p>
                            <div class="text-sm text-{{ $bgColor }}-{{ $isFuture ? '700' : '700' }}">
                                @if($quarterNum <= 2)
                                <div>• Penghasilan tetap & tunjangan: Rp {{ number_format($progress['target'] * 0.3 / 1000000, 0) }} Jt</div>
                                <div>• Operasional perkantoran: Rp {{ number_format($progress['target'] * 0.2 / 1000000, 0) }} Jt</div>
                                <div>• {{ $quarterNum == 1 ? 'Perencanaan pembangunan' : 'Pembangunan infrastruktur' }}: Rp {{ number_format($progress['target'] * 0.5 / 1000000, 0) }} Jt</div>
                                @elseif($quarterNum == 3)
                                <div>• Program sosial: Rp {{ number_format($progress['target'] * 0.5 / 1000000, 0) }} Jt</div>
                                <div>• Kegiatan kemasyarakatan: Rp {{ number_format($progress['target'] * 0.3 / 1000000, 0) }} Jt</div>
                                <div>• Pelatihan & pemberdayaan: Rp {{ number_format($progress['target'] * 0.2 / 1000000, 0) }} Jt</div>
                                @else
                                <div>• Penyelesaian proyek: Rp {{ number_format($progress['target'] * 0.7 / 1000000, 0) }} Jt</div>
                                <div>• Evaluasi program: Rp {{ number_format($progress['target'] * 0.2 / 1000000, 0) }} Jt</div>
                                <div>• Persiapan anggaran {{ $currentYear + 1 }}: Rp {{ number_format($progress['target'] * 0.1 / 1000000, 0) }} Jt</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 text-right">
                        <div class="font-bold text-{{ $bgColor }}-600">Rp {{ number_format($progress['realized'] / 1000000, 0) }} Jt</div>
                        <div class="text-sm text-gray-600">
                            @if($isCompleted)
                                {{ round($progress['percentage'], 0) }}% Terealisasi
                            @elseif($isCurrent)
                                {{ round($progress['percentage'], 0) }}% Berjalan
                            @else
                                Direncanakan
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Budget Comparison -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="font-bold text-gray-900 mb-6 text-xl">Perbandingan Anggaran</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Bidang</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">{{ $currentYear - 2 }}</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">{{ $currentYear - 1 }}</th>
                        <th class="px-4 py-3 text-right text-sm font-medium text-gray-700">{{ $currentYear }}</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700">Trend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                        $currentTotal = $totalExpenditure;
                        $previousTotalYear1 = 0;
                        $previousTotalYear2 = 0;
                        $previousTotalYear3 = 0;
                        
                        foreach($previousYears as $yearData) {
                            if($previousTotalYear1 == 0) $previousTotalYear3 = $yearData['expenditure'];
                            elseif($previousTotalYear2 == 0) $previousTotalYear2 = $yearData['expenditure'];
                            else $previousTotalYear1 = $yearData['expenditure'];
                        }
                    @endphp
                    
                    @foreach($expenditureByCategory as $category => $data)
                    @php
                        $currentAmount = $data['planned_amount'];
                        $prevYear1Amount = isset($previousYears[$currentYear-1]) ? $previousYears[$currentYear-1]['expenditure'] * ($currentAmount / $currentTotal) : $currentAmount * 0.9;
                        $prevYear2Amount = isset($previousYears[$currentYear-2]) ? $previousYears[$currentYear-2]['expenditure'] * ($currentAmount / $currentTotal) : $currentAmount * 0.8;
                        $trend = $prevYear1Amount > 0 ? (($currentAmount - $prevYear1Amount) / $prevYear1Amount) * 100 : 0;
                        $trendIcon = $trend > 0 ? 'fas fa-arrow-up' : ($trend < 0 ? 'fas fa-arrow-down' : 'fas fa-minus');
                        $trendColor = $trend > 0 ? 'green' : ($trend < 0 ? 'red' : 'gray');
                    @endphp
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $category }}</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-700">Rp {{ number_format($prevYear2Amount / 1000000, 0) }} Jt</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-700">Rp {{ number_format($prevYear1Amount / 1000000, 0) }} Jt</td>
                        <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">Rp {{ number_format($currentAmount / 1000000, 0) }} Jt</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $trendColor }}-100 text-{{ $trendColor }}-800">
                                <i class="{{ $trendIcon }} mr-1"></i>{{ $trend >= 0 ? '+' : '' }}{{ number_format($trend, 1) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    
                    <tr class="bg-gray-50 font-medium">
                        <td class="px-4 py-3 text-sm text-gray-900">Total</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-700">Rp {{ number_format($previousTotalYear2 / 1000000, 1) }} M</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-700">Rp {{ number_format($previousTotalYear1 / 1000000, 1) }} M</td>
                        <td class="px-4 py-3 text-sm text-right text-gray-900">Rp {{ number_format($currentTotal / 1000000, 1) }} M</td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $totalTrend = $previousTotalYear1 > 0 ? (($currentTotal - $previousTotalYear1) / $previousTotalYear1) * 100 : 0;
                                $totalTrendIcon = $totalTrend > 0 ? 'fas fa-arrow-up' : ($totalTrend < 0 ? 'fas fa-arrow-down' : 'fas fa-minus');
                                $totalTrendColor = $totalTrend > 0 ? 'green' : ($totalTrend < 0 ? 'red' : 'gray');
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $totalTrendColor }}-100 text-{{ $totalTrendColor }}-800">
                                <i class="{{ $totalTrendIcon }} mr-1"></i>{{ $totalTrend >= 0 ? '+' : '' }}{{ number_format($totalTrend, 1) }}%
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $twoYearGrowth = $previousTotalYear2 > 0 ? (($currentTotal - $previousTotalYear2) / $previousTotalYear2) * 100 : 0;
                $oneYearGrowth = $previousTotalYear1 > 0 ? (($currentTotal - $previousTotalYear1) / $previousTotalYear1) * 100 : 0;
                $estimatedPopulation = 7500; // Approximate population
                $perCapita = $currentTotal / $estimatedPopulation;
            @endphp
            
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-lg font-bold text-blue-600">{{ $twoYearGrowth >= 0 ? '+' : '' }}{{ number_format($twoYearGrowth, 1) }}%</div>
                <div class="text-sm text-gray-600">Peningkatan dari {{ $currentYear - 2 }}</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-lg font-bold text-green-600">{{ $oneYearGrowth >= 0 ? '+' : '' }}{{ number_format($oneYearGrowth, 1) }}%</div>
                <div class="text-sm text-gray-600">Peningkatan dari {{ $currentYear - 1 }}</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-lg font-bold text-purple-600">Rp {{ number_format($perCapita / 1000, 0) }}k</div>
                <div class="text-sm text-gray-600">Per kapita (estimasi)</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Pie Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    
    // Dynamic revenue data from PHP
    const revenueLabels = [];
    const revenueData = [];
    const revenueColors = [];
    
    @if($revenueBreakdown['dana_desa'] > 0)
        revenueLabels.push('Dana Desa');
        revenueData.push({{ $revenueBreakdown['dana_desa'] / 1000000 }});
        revenueColors.push('#3B82F6');
    @endif
    
    @if($revenueBreakdown['add'] > 0)
        revenueLabels.push('ADD');
        revenueData.push({{ $revenueBreakdown['add'] / 1000000 }});
        revenueColors.push('#10B981');
    @endif
    
    @if($revenueBreakdown['dana_bantuan'] > 0)
        revenueLabels.push('Dana Bantuan');
        revenueData.push({{ $revenueBreakdown['dana_bantuan'] / 1000000 }});
        revenueColors.push('#F59E0B');
    @endif
    
    @if($revenueBreakdown['pades'] > 0)
        revenueLabels.push('PADes');
        revenueData.push({{ $revenueBreakdown['pades'] / 1000000 }});
        revenueColors.push('#8B5CF6');
    @endif
    
    @if($revenueBreakdown['lain_lain'] > 0)
        revenueLabels.push('Lain-lain');
        revenueData.push({{ $revenueBreakdown['lain_lain'] / 1000000 }});
        revenueColors.push('#6366F1');
    @endif

    const revenueChart = new Chart(revenueCtx, {
        type: 'doughnut',
        data: {
            labels: revenueLabels,
            datasets: [{
                data: revenueData,
                backgroundColor: revenueColors,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return context.label + ': Rp ' + value.toFixed(1) + ' M (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Number animation
    function animateNumbers() {
        const numbers = document.querySelectorAll('.text-2xl.font-bold, .text-xl.font-bold');
        numbers.forEach(num => {
            if (num.textContent.includes('Rp') || num.textContent.includes('%')) {
                num.style.opacity = '0';
                setTimeout(() => {
                    num.style.transition = 'opacity 1s ease-in-out';
                    num.style.opacity = '1';
                }, Math.random() * 500);
            }
        });
    }

    // Trigger animations on load
    window.addEventListener('load', function() {
        setTimeout(animateNumbers, 500);
    });

    // Hover effects for budget cards
    document.querySelectorAll('.border.border-gray-200').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('shadow-lg', 'transform', 'scale-105');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-lg', 'transform', 'scale-105');
        });
    });

    // Timeline animation
    function animateTimeline() {
        const timelineItems = document.querySelectorAll('.relative.flex.items-start');
        timelineItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.transform = 'translateY(20px)';
                item.style.opacity = '0';
                item.style.transition = 'all 0.6s ease-out';
                
                setTimeout(() => {
                    item.style.transform = 'translateY(0)';
                    item.style.opacity = '1';
                }, 100);
            }, index * 200);
        });
    }

    // Trigger timeline animation
    setTimeout(animateTimeline, 1000);

    // Format numbers with thousand separator
    function formatRupiah(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Table row hover effects
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.classList.add('bg-gray-50');
        });
        
        row.addEventListener('mouseleave', function() {
            this.classList.remove('bg-gray-50');
        });
    });
</script>
@endsection