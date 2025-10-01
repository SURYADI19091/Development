@extends('backend.layout.main')

@section('title', 'Statistik Penduduk')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Statistik Penduduk</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('backend.population.index') }}">Data Penduduk</a></li>
                    <li class="breadcrumb-item active">Statistik</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Summary Stats -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($stats['total_population']) }}</h3>
                        <p>Total Penduduk</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('backend.population.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['gender_distribution']['M'] ?? 0 }}</h3>
                        <p>Laki-laki</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-male"></i>
                    </div>
                    <div class="small-box-footer">
                        {{ $stats['total_population'] > 0 ? round(($stats['gender_distribution']['M'] ?? 0) / $stats['total_population'] * 100, 1) : 0 }}% dari total
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-pink">
                    <div class="inner">
                        <h3>{{ $stats['gender_distribution']['F'] ?? 0 }}</h3>
                        <p>Perempuan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-female"></i>
                    </div>
                    <div class="small-box-footer">
                        {{ $stats['total_population'] > 0 ? round(($stats['gender_distribution']['F'] ?? 0) / $stats['total_population'] * 100, 1) : 0 }}% dari total
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['settlement_distribution']->count() }}</h3>
                        <p>Wilayah (RT/RW)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="small-box-footer">
                        Tersebar di {{ $stats['settlement_distribution']->count() }} RT/RW
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gender Distribution Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-2"></i>Distribusi Jenis Kelamin
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="genderChart" style="height: 300px;"></canvas>
                        <div class="row mt-3">
                            <div class="col-6 text-center">
                                <div class="progress-group">
                                    <span class="progress-text">Laki-laki</span>
                                    <span class="float-right">
                                        <b>{{ $stats['gender_distribution']['M'] ?? 0 }}</b>/{{ $stats['total_population'] }}
                                    </span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" 
                                             style="width: {{ $stats['total_population'] > 0 ? round(($stats['gender_distribution']['M'] ?? 0) / $stats['total_population'] * 100, 1) : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="progress-group">
                                    <span class="progress-text">Perempuan</span>
                                    <span class="float-right">
                                        <b>{{ $stats['gender_distribution']['F'] ?? 0 }}</b>/{{ $stats['total_population'] }}
                                    </span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-pink" 
                                             style="width: {{ $stats['total_population'] > 0 ? round(($stats['gender_distribution']['F'] ?? 0) / $stats['total_population'] * 100, 1) : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Age Distribution Chart -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>Distribusi Umur
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="ageChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Marital Status Distribution -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-heart mr-2"></i>Status Perkawinan
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($stats['marital_status_distribution'] as $status => $count)
                            <div class="progress-group">
                                <span class="progress-text">
                                    @switch($status)
                                        @case('Single') Belum Menikah @break
                                        @case('Married') Menikah @break
                                        @case('Divorced') Cerai Hidup @break
                                        @case('Widowed') Cerai Mati @break
                                        @default {{ $status }}
                                    @endswitch
                                </span>
                                <span class="float-right">
                                    <b>{{ $count }}</b>/{{ $stats['total_population'] }}
                                </span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar 
                                        @switch($status)
                                            @case('Single') bg-info @break
                                            @case('Married') bg-success @break
                                            @case('Divorced') bg-warning @break
                                            @case('Widowed') bg-secondary @break
                                            @default bg-primary
                                        @endswitch" 
                                         style="width: {{ $stats['total_population'] > 0 ? round($count / $stats['total_population'] * 100, 1) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Religion Distribution -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-praying-hands mr-2"></i>Distribusi Agama
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($stats['religion_distribution'] as $religion => $count)
                            <div class="progress-group">
                                <span class="progress-text">{{ $religion }}</span>
                                <span class="float-right">
                                    <b>{{ $count }}</b>/{{ $stats['total_population'] }}
                                </span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar 
                                        @switch($religion)
                                            @case('Islam') bg-success @break
                                            @case('Kristen') bg-primary @break
                                            @case('Katholik') bg-info @break
                                            @case('Hindu') bg-warning @break
                                            @case('Buddha') bg-secondary @break
                                            @case('Konghucu') bg-dark @break
                                            @default bg-primary
                                        @endswitch" 
                                         style="width: {{ $stats['total_population'] > 0 ? round($count / $stats['total_population'] * 100, 1) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Settlement Distribution -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt mr-2"></i>Distribusi per RT/RW
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($stats['settlement_distribution'] as $settlement)
                                <div class="col-md-4 mb-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info">
                                            <i class="fas fa-home"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">{{ $settlement->settlement->name ?? 'Tidak Diketahui' }}</span>
                                            <span class="info-box-number">{{ $settlement->count }} orang</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" 
                                                     style="width: {{ $stats['total_population'] > 0 ? round($settlement->count / $stats['total_population'] * 100, 1) : 0 }}%"></div>
                                            </div>
                                            <span class="progress-description">
                                                {{ $stats['total_population'] > 0 ? round($settlement->count / $stats['total_population'] * 100, 1) : 0 }}% dari total penduduk
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="{{ route('backend.population.index') }}" class="btn btn-primary">
                                <i class="fas fa-list mr-2"></i>Lihat Data Penduduk
                            </a>
                            <a href="{{ route('backend.population.create') }}" class="btn btn-success">
                                <i class="fas fa-plus mr-2"></i>Tambah Data Baru
                            </a>
                            <button type="button" class="btn btn-info" onclick="window.print()">
                                <i class="fas fa-print mr-2"></i>Cetak Laporan
                            </button>
                            <a href="{{ route('backend.population.export') }}" class="btn btn-warning">
                                <i class="fas fa-download mr-2"></i>Export Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .bg-pink {
        background-color: #e91e63 !important;
    }
    
    .progress-bar.bg-pink {
        background-color: #e91e63 !important;
    }
    
    @media print {
        .btn, .card-tools, .breadcrumb, .content-header {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .small-box {
            border: 1px solid #ddd !important;
            margin-bottom: 15px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Gender Distribution Pie Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [
                    {{ $stats['gender_distribution']['M'] ?? 0 }},
                    {{ $stats['gender_distribution']['F'] ?? 0 }}
                ],
                backgroundColor: [
                    '#007bff',
                    '#e91e63'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Age Distribution Bar Chart
    const ageCtx = document.getElementById('ageChart').getContext('2d');
    new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($stats['age_distribution'] as $ageGroup => $count)
                    '{{ $ageGroup }}',
                @endforeach
            ],
            datasets: [{
                label: 'Jumlah Penduduk',
                data: [
                    @foreach($stats['age_distribution'] as $ageGroup => $count)
                        {{ $count }},
                    @endforeach
                ],
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107',
                    '#dc3545',
                    '#6c757d'
                ],
                borderColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107',
                    '#dc3545',
                    '#6c757d'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Jumlah: ' + context.parsed.y + ' orang';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush