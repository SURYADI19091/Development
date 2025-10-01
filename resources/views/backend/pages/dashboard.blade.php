@extends('backend.layout.main')

@section('title', 'Dashboard')
@section('header', 'Dashboard')
@section('description', 'Selamat datang di Admin Panel Desa Ciuwlan')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg text-white">
        <div class="px-6 py-8">
            <div class="flex items-center">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold mb-2">
                        Selamat datang, {{ Auth::user()->name }}!
                    </h1>
                    <p class="text-blue-100 mb-4">
                        Hari ini adalah {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </p>
                    <div class="flex items-center text-sm text-blue-100">
                        <i class="fas fa-user-tag mr-2"></i>
                        <span>{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</span>
                        <i class="fas fa-circle mx-2" style="font-size: 4px;"></i>
                        <span>Login terakhir: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'Pertama kali' }}</span>
                    </div>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-tachometer-alt text-6xl text-blue-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Users Stats -->
        @can('manage-users')
        <div class="bg-white overflow-hidden shadow rounded-lg hover-scale">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-users text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900" id="total-users">{{ $stats['total_users'] ?? 0 }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                                    +{{ $stats['new_users_this_month'] ?? 0 }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('backend.users.index') }}" class="font-medium text-blue-700 hover:text-blue-900">
                        Kelola pengguna
                    </a>
                </div>
            </div>
        </div>
        @endcan

        <!-- Population Stats -->
        @can('manage-population-data')
        <div class="bg-white overflow-hidden shadow rounded-lg hover-scale">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-chart-pie text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Penduduk</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900" id="total-population">{{ $stats['total_population'] ?? 0 }}</div>
                                <div class="ml-2 text-sm text-gray-500">jiwa</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('backend.population.index') }}" class="font-medium text-green-700 hover:text-green-900">
                        Lihat data penduduk
                    </a>
                </div>
            </div>
        </div>
        @endcan

        <!-- Content Stats -->
        @can('manage-content')
        <div class="bg-white overflow-hidden shadow rounded-lg hover-scale">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Berita</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900" id="total-news">{{ $stats['total_news'] ?? 0 }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <i class="fas fa-arrow-up text-xs mr-1"></i>
                                    +{{ $stats['news_this_month'] ?? 0 }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('backend.news.index') }}" class="font-medium text-purple-700 hover:text-purple-900">
                        Kelola konten
                    </a>
                </div>
            </div>
        </div>
        @endcan

        <!-- Messages Stats -->
        @can('manage-contact-messages')
        <div class="bg-white overflow-hidden shadow rounded-lg hover-scale">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-envelope text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pesan Masuk</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900" id="total-messages">{{ $stats['total_messages'] ?? 0 }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-red-600">
                                    {{ $stats['unread_messages'] ?? 0 }} belum dibaca
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('backend.contact.index') }}" class="font-medium text-orange-700 hover:text-orange-900">
                        Baca pesan
                    </a>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <!-- Charts and Recent Activity Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Statistics Chart -->
        <div class="lg:col-span-2 bg-white shadow rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Statistik Bulanan
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Grafik aktivitas pengguna dan konten dalam 6 bulan terakhir
                </p>
            </div>
            <div class="p-6">
                <canvas id="monthlyStatsChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Aktivitas Terkini
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Aktivitas sistem terbaru
                </p>
            </div>
            <div class="flow-root">
                <ul class="divide-y divide-gray-200">
                    @forelse($recent_activities ?? [] as $activity)
                    <li class="px-6 py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-{{ $activity['color'] ?? 'gray' }}-100 rounded-full flex items-center justify-center">
                                    <i class="{{ $activity['icon'] ?? 'fas fa-info' }} text-{{ $activity['color'] ?? 'gray' }}-600 text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $activity['title'] ?? 'Aktivitas' }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $activity['description'] ?? 'Deskripsi aktivitas' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-sm text-gray-500">
                                {{ $activity['time'] ?? 'Baru saja' }}
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-info-circle mb-2"></i>
                        <p>Belum ada aktivitas terkini</p>
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="bg-gray-50 px-6 py-3">
                <div class="text-sm">
                    @can('view-activity-logs')
                    <a href="{{ route('backend.activity-logs') }}" class="font-medium text-gray-700 hover:text-gray-900">
                        Lihat semua aktivitas
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions and System Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Aksi Cepat
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Akses cepat ke fitur yang sering digunakan
                </p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    @can('manage-content')
                    <a href="{{ route('backend.news.create') }}" 
                       class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                        <div class="text-center">
                            <i class="fas fa-plus-circle text-2xl text-blue-600 group-hover:text-blue-700 mb-2"></i>
                            <p class="text-sm font-medium text-blue-900">Buat Berita</p>
                        </div>
                    </a>
                    @endcan
                    
                    @can('create-user')
                    <a href="{{ route('backend.users.create') }}" 
                       class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                        <div class="text-center">
                            <i class="fas fa-user-plus text-2xl text-green-600 group-hover:text-green-700 mb-2"></i>
                            <p class="text-sm font-medium text-green-900">Tambah User</p>
                        </div>
                    </a>
                    @endcan
                    
                    @can('manage-village-data')
                    <a href="{{ route('backend.agenda.create') }}" 
                       class="flex items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                        <div class="text-center">
                            <i class="fas fa-calendar-plus text-2xl text-purple-600 group-hover:text-purple-700 mb-2"></i>
                            <p class="text-sm font-medium text-purple-900">Buat Agenda</p>
                        </div>
                    </a>
                    @endcan
                    
                    @can('generate-reports')
                    <a href="{{ route('backend.reports') }}" 
                       class="flex items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors group">
                        <div class="text-center">
                            <i class="fas fa-chart-bar text-2xl text-orange-600 group-hover:text-orange-700 mb-2"></i>
                            <p class="text-sm font-medium text-orange-900">Laporan</p>
                        </div>
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Status Sistem
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Informasi kesehatan dan performa sistem
                </p>
            </div>
            <div class="p-6 space-y-4">
                <!-- Server Status -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900">Server Status</span>
                    </div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>
                
                <!-- Database Status -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-900">Database</span>
                    </div>
                    <span class="text-sm text-green-600 font-medium">Connected</span>
                </div>
                
                <!-- Storage Usage -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-900">Storage Usage</span>
                        <span class="text-sm text-gray-500">{{ $stats['storage_used'] ?? '0 MB' }} / {{ $stats['storage_total'] ?? '1 GB' }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['storage_percentage'] ?? 0 }}%"></div>
                    </div>
                </div>
                
                <!-- Last Backup -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">Last Backup</span>
                    <span class="text-sm text-gray-500">{{ $stats['last_backup'] ?? 'Never' }}</span>
                </div>
                
                @can('manage-system-backup')
                <div class="pt-4 border-t border-gray-200">
                    <a href="{{ route('backend.backup.index') }}" 
                       class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Kelola backup sistem
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize monthly stats chart
        const ctx = document.getElementById('monthlyStatsChart').getContext('2d');
        const monthlyStatsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_data['months'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!},
                datasets: [
                    {
                        label: 'Pengguna Baru',
                        data: {!! json_encode($chart_data['users'] ?? [5, 12, 8, 15, 22, 18]) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Berita Dipublikasi',
                        data: {!! json_encode($chart_data['news'] ?? [3, 7, 5, 9, 12, 8]) !!},
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Pesan Kontak',
                        data: {!! json_encode($chart_data['messages'] ?? [8, 15, 12, 18, 25, 20]) !!},
                        borderColor: 'rgb(245, 158, 11)',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        });

        // Auto-refresh stats every 30 seconds
        setInterval(function() {
            refreshStats();
        }, 30000);
    });

    // Function to refresh dashboard stats
    function refreshStats() {
        fetch('{{ route('backend.dashboard') }}?ajax=stats')
            .then(response => response.json())
            .then(data => {
                // Update stat counters
                if (data.total_users !== undefined) {
                    document.getElementById('total-users').textContent = data.total_users;
                }
                if (data.total_population !== undefined) {
                    document.getElementById('total-population').textContent = data.total_population;
                }
                if (data.total_news !== undefined) {
                    document.getElementById('total-news').textContent = data.total_news;
                }
                if (data.total_messages !== undefined) {
                    document.getElementById('total-messages').textContent = data.total_messages;
                }
            })
            .catch(error => {
                console.log('Stats refresh failed:', error);
            });
    }
</script>
@endpush
