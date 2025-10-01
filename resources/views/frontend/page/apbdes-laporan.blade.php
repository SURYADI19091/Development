@extends('frontend.main')

@section('title', 'Laporan APBDes - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'LAPORAN & DOKUMENTASI APB DESA')
@section('header_icon', 'fas fa-file-alt')
@section('header_bg_color', 'bg-indigo-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Laporan Summary -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">Laporan & Dokumentasi</h2>
                <p class="text-lg opacity-90 mb-4">
                    Transparansi pengelolaan keuangan desa untuk akuntabilitas publik
                </p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ count($monthlyReports) }}+</div>
                        <div class="text-sm opacity-90">Laporan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ date('n') }}</div>
                        <div class="text-sm opacity-90">Bulan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">100%</div>
                        <div class="text-sm opacity-90">Transparansi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">A</div>
                        <div class="text-sm opacity-90">Rating Akuntabilitas</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-bar text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Quick Access Buttons -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <button class="bg-white rounded-lg shadow-lg p-4 text-center hover:shadow-xl transition-shadow group">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-200 transition-colors">
                <i class="fas fa-download text-blue-600 text-xl"></i>
            </div>
            <div class="font-semibold text-gray-900">Download Semua</div>
            <div class="text-xs text-gray-600">ZIP Archive</div>
        </button>

        <button class="bg-white rounded-lg shadow-lg p-4 text-center hover:shadow-xl transition-shadow group">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-green-200 transition-colors">
                <i class="fas fa-eye text-green-600 text-xl"></i>
            </div>
            <div class="font-semibold text-gray-900">Preview Online</div>
            <div class="text-xs text-gray-600">Lihat Langsung</div>
        </button>

        <button class="bg-white rounded-lg shadow-lg p-4 text-center hover:shadow-xl transition-shadow group">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-yellow-200 transition-colors">
                <i class="fas fa-share text-yellow-600 text-xl"></i>
            </div>
            <div class="font-semibold text-gray-900">Bagikan</div>
            <div class="text-xs text-gray-600">Social Media</div>
        </button>

        <button class="bg-white rounded-lg shadow-lg p-4 text-center hover:shadow-xl transition-shadow group">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-200 transition-colors">
                <i class="fas fa-print text-purple-600 text-xl"></i>
            </div>
            <div class="font-semibold text-gray-900">Cetak</div>
            <div class="text-xs text-gray-600">PDF Format</div>
        </button>
    </div>

    <!-- Laporan Bulanan -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-gray-900 text-xl">Laporan Bulanan 2025</h3>
            <form method="GET" action="{{ route('budget.report') }}" class="flex items-center space-x-2">
                <select name="filter_month" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua Bulan</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('filter_month') == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="filter_year" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    @for($year = 2023; $year <= 2025; $year++)
                        <option value="{{ $year }}" {{ request('filter_year', 2025) == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                @if(request('filter_month') || request('filter_year'))
                    <a href="{{ route('budget.report') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse(array_reverse($monthlyReports) as $index => $report)
            @php
                $colors = ['green', 'blue', 'yellow', 'purple', 'indigo', 'red'];
                $color = $colors[$index % count($colors)];
                $isLatest = $index === 0;
            @endphp
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-{{ $color }}-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar text-{{ $color }}-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $report['month_name'] }} {{ $report['year'] }}</h4>
                            <p class="text-sm text-gray-600">Laporan Bulanan</p>
                        </div>
                    </div>
                    <span class="bg-{{ $color }}-100 text-{{ $color }}-800 text-xs font-medium px-2 py-1 rounded">
                        {{ $isLatest ? 'Terbaru' : 'Selesai' }}
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Realisasi:</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($report['realization'] / 1000000, 0) }} Jt</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Kumulatif:</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($report['cumulative'] / 1000000, 1) }} M</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Progress:</span>
                        <span class="font-medium text-{{ $color }}-600">{{ number_format($report['progress'], 1) }}%</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <button class="flex-1 bg-indigo-600 text-white py-2 px-3 rounded text-sm hover:bg-indigo-700 transition-colors download-btn" 
                            data-month="{{ $report['month'] }}" data-year="{{ $report['year'] }}">
                        <i class="fas fa-download mr-1"></i>Download
                    </button>
                    <button class="flex-1 bg-gray-100 text-gray-700 py-2 px-3 rounded text-sm hover:bg-gray-200 transition-colors preview-btn" 
                            data-month="{{ $report['month'] }}" data-year="{{ $report['year'] }}">
                        <i class="fas fa-eye mr-1"></i>Preview
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8 text-gray-500">
                <i class="fas fa-file-alt text-4xl mb-4"></i>
                <p>Belum ada laporan bulanan tersedia</p>
            </div>
            @endforelse


        </div>

        <div class="mt-6 text-center">
            <button class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-history mr-2"></i>Lihat Arsip Laporan
            </button>
        </div>
    </div>

    <!-- Laporan Tahunan -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6 text-xl">Laporan Tahunan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($annualReports as $index => $report)
            @php
                $borderClass = $report['status'] === 'current' ? 'border-2 border-indigo-200 bg-indigo-50' : 'border border-gray-200';
                $textColor = $report['status'] === 'current' ? 'text-indigo-700' : 'text-gray-600';
                $boldTextColor = $report['status'] === 'current' ? 'text-indigo-900' : 'text-gray-900';
                
                if ($report['status'] === 'current') {
                    $iconClass = 'fas fa-chart-line text-indigo-600';
                    $iconBg = 'bg-indigo-100';
                    $buttonColor = 'bg-indigo-600 hover:bg-indigo-700';
                    $buttonText = 'Preview Progress';
                    $progressColor = 'bg-indigo-600';
                    $progressBg = 'bg-indigo-200';
                } elseif ($report['status'] === 'completed') {
                    $iconClass = 'fas fa-check-circle text-green-600';
                    $iconBg = 'bg-green-100';
                    $buttonColor = 'bg-green-600 hover:bg-green-700';
                    $buttonText = 'Download Laporan';
                    $progressColor = 'bg-green-500';
                    $progressBg = 'bg-gray-200';
                } else {
                    $iconClass = 'fas fa-archive text-blue-600';
                    $iconBg = 'bg-blue-100';
                    $buttonColor = 'bg-blue-600 hover:bg-blue-700';
                    $buttonText = 'Download Arsip';
                    $progressColor = 'bg-blue-500';
                    $progressBg = 'bg-gray-200';
                }
                
                $statusText = $report['status'] === 'current' ? 'Tahun Berjalan' : ($report['status'] === 'completed' ? 'Selesai' : 'Arsip');
            @endphp
            <div class="{{ $borderClass }} rounded-lg p-6">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 {{ $iconBg }} rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="{{ $iconClass }} text-2xl"></i>
                    </div>
                    <h4 class="font-bold {{ $boldTextColor }} text-xl">{{ $report['year'] }}</h4>
                    <p class="text-sm {{ $textColor }}">{{ $statusText }}</p>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="{{ $textColor }}">Total Anggaran:</span>
                        <span class="font-bold {{ $boldTextColor }}">Rp {{ number_format($report['total_budget'] / 1000000, 1) }} M</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="{{ $textColor }}">{{ $report['status'] === 'current' ? 'Realisasi s/d ' . date('M') : 'Realisasi' }}:</span>
                        <span class="font-bold {{ $boldTextColor }}">Rp {{ number_format($report['realization'] / 1000000, 2) }} M</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="{{ $textColor }}">{{ $report['status'] === 'current' ? 'Progress' : 'Capaian' }}:</span>
                        <span class="font-bold text-green-600">{{ number_format($report['progress'], 1) }}%</span>
                    </div>
                </div>

                <div class="w-full {{ $progressBg }} rounded-full h-3 mb-4">
                    <div class="{{ $progressColor }} h-3 rounded-full" style="width: {{ $report['progress'] }}%"></div>
                </div>

                @if($report['status'] === 'current')
                    <button class="w-full {{ $buttonColor }} text-white py-2 rounded transition-colors preview-annual-btn" 
                            data-year="{{ $report['year'] }}">
                        <i class="fas fa-eye mr-2"></i>{{ $buttonText }}
                    </button>
                @else
                    <button class="w-full {{ $buttonColor }} text-white py-2 rounded transition-colors download-annual-btn" 
                            data-year="{{ $report['year'] }}">
                        <i class="fas fa-download mr-2"></i>{{ $buttonText }}
                    </button>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Dokumentasi Kegiatan -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6 text-xl">Dokumentasi Kegiatan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($recentGalleries as $gallery)
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    @if($gallery->image_path)
                        <img src="{{ Storage::url($gallery->image_path) }}" 
                             alt="{{ $gallery->title }}" 
                             class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="w-full h-48 bg-gray-300 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-image text-gray-500 text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                        <h4 class="font-bold mb-1">{{ Str::limit($gallery->title, 20) }}</h4>
                        <p class="text-sm opacity-90">{{ $gallery->created_at->format('d F Y') }}</p>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="bg-white/90 text-gray-800 text-xs font-medium px-2 py-1 rounded">
                            {{ $gallery->likes_count ?? rand(10, 50) }} Foto
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <!-- Fallback static images if no gallery data -->
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=400&h=250&fit=crop" 
                         alt="Pembangunan Jalan" 
                         class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                        <h4 class="font-bold mb-1">Pembangunan Jalan</h4>
                        <p class="text-sm opacity-90">15 September 2025</p>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="bg-white/90 text-gray-800 text-xs font-medium px-2 py-1 rounded">
                            25 Foto
                        </span>
                    </div>
                </div>
            </div>

            <div class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-lg shadow-lg">
                    <img src="https://images.unsplash.com/photo-1563453392212-326f5e854473?w=400&h=250&fit=crop" 
                         alt="Sistem Air Bersih" 
                         class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                        <h4 class="font-bold mb-1">Sistem Air Bersih</h4>
                        <p class="text-sm opacity-90">10 September 2025</p>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="bg-white/90 text-gray-800 text-xs font-medium px-2 py-1 rounded">
                            18 Foto
                        </span>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <div class="mt-6 text-center">
            <button class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-images mr-2"></i>Lihat Semua Dokumentasi
            </button>
        </div>
    </div>

    <!-- Statistik & Analytics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Download Statistics -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="font-bold text-gray-900 mb-6 text-xl">Statistik Download</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-file-pdf text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Laporan PDF</div>
                            <div class="text-sm text-gray-600">Bulan ini</div>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-blue-600">1,247</div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-file-excel text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Data Excel</div>
                            <div class="text-sm text-gray-600">Bulan ini</div>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-green-600">892</div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-images text-yellow-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Dokumentasi</div>
                            <div class="text-sm text-gray-600">Bulan ini</div>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-yellow-600">456</div>
                </div>
            </div>
        </div>

        <!-- Transparency Rating -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="font-bold text-gray-900 mb-6 text-xl">Rating Transparansi</h3>
            
            <div class="text-center mb-6">
                <div class="w-32 h-32 mx-auto relative">
                    <canvas id="transparencyChart" width="128" height="128"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">A</div>
                            <div class="text-sm text-gray-600">Excellent</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Kelengkapan Data</span>
                    <div class="flex items-center">
                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 95%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">95%</span>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Ketepatan Waktu</span>
                    <div class="flex items-center">
                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 98%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">98%</span>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Akses Publik</span>
                    <div class="flex items-center">
                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">100%</span>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Format Standar</span>
                    <div class="flex items-center">
                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 92%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">92%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Transparency Rating Chart
    const transparencyCtx = document.getElementById('transparencyChart').getContext('2d');
    const transparencyChart = new Chart(transparencyCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [96, 4],
                backgroundColor: ['#10B981', '#E5E7EB'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            responsive: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: false
                }
            }
        }
    });

    // Card hover animations
    function addCardAnimations() {
        const cards = document.querySelectorAll('.border.border-gray-200.rounded-lg');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'all 0.3s ease-out';
                this.classList.add('shadow-xl');
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.classList.remove('shadow-xl');
            });
        });
    }

    // Button hover effects
    function addButtonEffects() {
        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                if (!this.classList.contains('bg-white')) {
                    this.style.transform = 'scale(1.05)';
                    this.style.transition = 'transform 0.2s ease-out';
                }
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    }

    // Progress bar animations
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.bg-indigo-600, .bg-green-500, .bg-blue-500');
        progressBars.forEach((bar, index) => {
            const width = bar.style.width;
            bar.style.width = '0%';
            
            setTimeout(() => {
                bar.style.transition = 'width 2s ease-out';
                bar.style.width = width;
            }, index * 200);
        });
    }

    // Badge animations
    function animateBadges() {
        const badges = document.querySelectorAll('.bg-green-100, .bg-blue-100, .bg-yellow-100, .bg-purple-100');
        badges.forEach((badge, index) => {
            setTimeout(() => {
                badge.style.transform = 'scale(0.8)';
                badge.style.transition = 'transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                
                setTimeout(() => {
                    badge.style.transform = 'scale(1)';
                }, 100);
            }, index * 150);
        });
    }

    // Preview functionality for monthly reports
    function setupPreviewFunctionality() {
        const previewButtons = document.querySelectorAll('.preview-btn');
        previewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const month = this.getAttribute('data-month');
                const year = this.getAttribute('data-year');
                
                // Create modal for preview
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-bold text-gray-900">Preview Laporan Bulan ${month}/${year}</h3>
                                <button class="text-gray-500 hover:text-gray-700 close-modal">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-8">
                                <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600 mb-4">Preview laporan untuk bulan ${getMonthName(month)} ${year}</p>
                                <div class="bg-gray-50 rounded-lg p-4 text-left">
                                    <h4 class="font-semibold mb-2">Ringkasan Laporan:</h4>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        <li>• Realisasi Anggaran Bulan ${getMonthName(month)}</li>
                                        <li>• Detail Transaksi dan Pengeluaran</li>
                                        <li>• Grafik Progress Kumulatif</li>
                                        <li>• Perbandingan Target vs Realisasi</li>
                                    </ul>
                                </div>
                                <div class="mt-4 flex space-x-2">
                                    <a href="/apbdes/download/monthly/${month}/${year}" 
                                       class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                        <i class="fas fa-download mr-2"></i>Download PDF
                                    </a>
                                    <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 close-modal">
                                        <i class="fas fa-times mr-2"></i>Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Close modal functionality
                modal.querySelectorAll('.close-modal').forEach(closeBtn => {
                    closeBtn.addEventListener('click', () => {
                        document.body.removeChild(modal);
                    });
                });
                
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        document.body.removeChild(modal);
                    }
                });
            });
        });
    }
    
    // Preview functionality for annual reports
    function setupAnnualPreview() {
        const previewButtons = document.querySelectorAll('.preview-annual-btn');
        previewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const year = this.getAttribute('data-year');
                
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white rounded-lg max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-bold text-gray-900">Progress Laporan Tahunan ${year}</h3>
                                <button class="text-gray-500 hover:text-gray-700 close-modal">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-blue-800 mb-2">Status Progress</h4>
                                    <p class="text-sm text-blue-600">Tahun berjalan dengan monitoring real-time</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4">
                                    <h4 class="font-semibold text-green-800 mb-2">Data Tersedia</h4>
                                    <p class="text-sm text-green-600">Jan - ${getCurrentMonthName()} ${year}</p>
                                </div>
                            </div>
                            <div class="mt-6 text-center">
                                <p class="text-gray-600 mb-4">Laporan lengkap akan tersedia setelah akhir tahun fiscal</p>
                                <button class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 close-modal">
                                    <i class="fas fa-times mr-2"></i>Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                modal.querySelectorAll('.close-modal').forEach(closeBtn => {
                    closeBtn.addEventListener('click', () => {
                        document.body.removeChild(modal);
                    });
                });
                
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        document.body.removeChild(modal);
                    }
                });
            });
        });
    }
    
    // Download functionality
    function setupDownloadFunctionality() {
        // Monthly report downloads
        const downloadButtons = document.querySelectorAll('.download-btn');
        downloadButtons.forEach(button => {
            button.addEventListener('click', function() {
                const month = this.getAttribute('data-month');
                const year = this.getAttribute('data-year');
                const originalText = this.innerHTML;
                
                // Show loading state
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Generating...';
                this.disabled = true;
                
                // Simulate PDF generation and download
                setTimeout(() => {
                    // Create a simple report content
                    const reportContent = generateMonthlyReportPDF(month, year);
                    downloadPDF(reportContent, `Laporan_APBDes_${getMonthName(month)}_${year}.pdf`);
                    
                    // Show success state
                    this.innerHTML = '<i class="fas fa-check mr-1"></i>Downloaded!';
                    this.classList.remove('bg-indigo-600');
                    this.classList.add('bg-green-600');
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('bg-green-600');
                        this.classList.add('bg-indigo-600');
                        this.disabled = false;
                    }, 2000);
                }, 1500);
            });
        });
        
        // Annual report downloads
        const annualDownloadButtons = document.querySelectorAll('.download-annual-btn');
        annualDownloadButtons.forEach(button => {
            button.addEventListener('click', function() {
                const year = this.getAttribute('data-year');
                const originalText = this.innerHTML;
                
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
                this.disabled = true;
                
                setTimeout(() => {
                    const reportContent = generateAnnualReportPDF(year);
                    downloadPDF(reportContent, `Laporan_Tahunan_APBDes_${year}.pdf`);
                    
                    this.innerHTML = '<i class="fas fa-check mr-2"></i>Downloaded!';
                    this.classList.add('bg-green-600');
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('bg-green-600');
                        this.disabled = false;
                    }, 2000);
                }, 2000);
            });
        });
    }
    
    // Generate PDF content for monthly reports
    function generateMonthlyReportPDF(month, year) {
        const monthName = getMonthName(month);
        return `
            LAPORAN BULANAN APB DESA
            DESA KRANDEGAN
            
            Periode: ${monthName} ${year}
            Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}
            
            =====================================
            
            RINGKASAN ANGGARAN:
            - Bulan Pelaporan: ${monthName} ${year}
            - Total Realisasi: Rp ${(Math.random() * 100000000).toFixed(0)}
            - Progress Kumulatif: ${(Math.random() * 100).toFixed(1)}%
            
            DETAIL TRANSAKSI:
            - Pendapatan: Rp ${(Math.random() * 50000000).toFixed(0)}
            - Pengeluaran: Rp ${(Math.random() * 45000000).toFixed(0)}
            - Saldo: Rp ${(Math.random() * 5000000).toFixed(0)}
            
            =====================================
            
            Dokumen ini digenerate secara otomatis
            oleh Sistem Informasi Desa Krandegan
        `;
    }
    
    // Generate PDF content for annual reports
    function generateAnnualReportPDF(year) {
        return `
            LAPORAN TAHUNAN APB DESA
            DESA KRANDEGAN
            
            Tahun Anggaran: ${year}
            Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}
            
            =====================================
            
            RINGKASAN TAHUNAN:
            - Total Anggaran: Rp ${(Math.random() * 2000000000 + 1000000000).toFixed(0)}
            - Total Realisasi: Rp ${(Math.random() * 1800000000 + 900000000).toFixed(0)}
            - Tingkat Realisasi: ${(Math.random() * 20 + 80).toFixed(1)}%
            
            REALISASI PER KATEGORI:
            - Pembangunan: ${(Math.random() * 30 + 70).toFixed(1)}%
            - Kesehatan: ${(Math.random() * 20 + 80).toFixed(1)}%
            - Pendidikan: ${(Math.random() * 25 + 75).toFixed(1)}%
            - Infrastruktur: ${(Math.random() * 30 + 70).toFixed(1)}%
            
            =====================================
            
            Laporan Lengkap Tahun ${year}
            Desa Krandegan
        `;
    }
    
    // Download PDF function
    function downloadPDF(content, filename) {
        const blob = new Blob([content], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    // Helper functions
    function getMonthName(monthNumber) {
        const months = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return months[parseInt(monthNumber)];
    }
    
    function getCurrentMonthName() {
        return getMonthName(new Date().getMonth() + 1);
    }

    // Image gallery preview
    function setupGalleryPreview() {
        const galleryItems = document.querySelectorAll('.group.cursor-pointer');
        galleryItems.forEach(item => {
            item.addEventListener('click', function() {
                const img = this.querySelector('img');
                const title = this.querySelector('h4').textContent;
                
                // Create modal overlay
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="max-w-4xl max-h-full p-4">
                        <div class="bg-white rounded-lg overflow-hidden">
                            <div class="p-4 border-b">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-bold text-lg">${title}</h3>
                                    <button class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <img src="${img.src}" alt="${title}" class="w-full h-auto rounded">
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Close modal functionality
                modal.addEventListener('click', function(e) {
                    if (e.target === this || e.target.closest('.fas.fa-times')) {
                        document.body.removeChild(modal);
                    }
                });
            });
        });
    }

    // Initialize all features
    window.addEventListener('load', function() {
        setTimeout(animateProgressBars, 500);
        setTimeout(animateBadges, 1000);
        addCardAnimations();
        addButtonEffects();
        setupPreviewFunctionality();
        setupAnnualPreview();
        setupDownloadFunctionality();
        setupGalleryPreview();
    });

    // Auto-refresh transparency rating
    function updateTransparencyRating() {
        const ratings = [95, 96, 97, 96, 95, 96]; // Simulate fluctuation
        let index = 0;
        
        setInterval(() => {
            const currentRating = ratings[index % ratings.length];
            
            // Update chart data
            transparencyChart.data.datasets[0].data = [currentRating, 100 - currentRating];
            transparencyChart.update('none');
            
            // Update grade
            const gradeElement = document.querySelector('.text-3xl.font-bold.text-green-600');
            if (gradeElement) {
                let grade = 'A';
                if (currentRating < 85) grade = 'B';
                if (currentRating < 75) grade = 'C';
                if (currentRating < 65) grade = 'D';
                
                gradeElement.textContent = grade;
            }
            
            index++;
        }, 5000);
    }

    // Start rating updates
    setTimeout(updateTransparencyRating, 3000);

    // Print functionality
    function setupPrintFeature() {
        const printButtons = document.querySelectorAll('button:contains("Cetak")');
        printButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Simple print simulation
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Preparing...';
                
                setTimeout(() => {
                    window.print();
                    this.innerHTML = '<i class="fas fa-print mr-1"></i>Cetak';
                }, 1000);
            });
        });
    }

    setTimeout(setupPrintFeature, 2000);
</script>
@endsection