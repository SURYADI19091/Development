@extends('frontend.main')

@section('title', 'Realisasi APBDes - DESA KRANDEGAN')
@section('page_title', 'REALISASI APB DESA 2025')
@section('header_icon', 'fas fa-chart-line')
@section('header_bg_color', 'bg-green-600')

@section('content')
<div class="xl:col-span-3">
    <!-- Realisasi Summary -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">Realisasi Anggaran 2025</h2>
                <p class="text-lg opacity-90 mb-4">
                    Progress pencapaian target anggaran per September 2025
                </p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold">72.3%</div>
                        <div class="text-sm opacity-90">Realisasi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">Rp 2.02 M</div>
                        <div class="text-sm opacity-90">Terealisasi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">Rp 780 Jt</div>
                        <div class="text-sm opacity-90">Sisa Anggaran</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold">85+</div>
                        <div class="text-sm opacity-90">Kegiatan</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Overall Progress -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6 text-xl">Progress Keseluruhan</h3>
        
        <div class="space-y-6">
            <!-- Total Progress Bar -->
            <div class="bg-gray-100 rounded-lg p-6">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-semibold text-gray-900">Total Realisasi Anggaran</span>
                    <span class="font-bold text-green-600">72.3%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-6 mb-2">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-6 rounded-full relative overflow-hidden" style="width: 72.3%">
                        <div class="absolute inset-0 bg-white opacity-20 animate-pulse"></div>
                    </div>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Rp 2.02 M dari Rp 2.8 M</span>
                    <span>Target Desember: 100%</span>
                </div>
            </div>

            <!-- Monthly Progress Chart -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-4">Progress Bulanan</h4>
                <div class="relative h-64">
                    <canvas id="monthlyProgressChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Sector Realization -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6 text-xl">Realisasi per Bidang</h3>
        
        <div class="space-y-6">
            <!-- Penyelenggaraan Pemerintahan -->
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-building text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Penyelenggaraan Pemerintahan Desa</h4>
                            <p class="text-sm text-gray-600">Target: Rp 980 Jt | Realisasi: Rp 784 Jt</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">80.0%</div>
                        <div class="text-sm text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>On Track
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full relative overflow-hidden" style="width: 80.0%">
                            <div class="absolute inset-0 bg-white opacity-20 animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-blue-50 rounded border-l-4 border-blue-500">
                        <div class="font-bold text-gray-900">Rp 336 Jt</div>
                        <div class="text-sm text-gray-600">Penghasilan & Tunjangan</div>
                        <div class="text-xs text-blue-600 mt-1">80% dari target</div>
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded border-l-4 border-blue-500">
                        <div class="font-bold text-gray-900">Rp 224 Jt</div>
                        <div class="text-sm text-gray-600">Operasional Perkantoran</div>
                        <div class="text-xs text-blue-600 mt-1">80% dari target</div>
                    </div>
                    <div class="text-center p-3 bg-blue-50 rounded border-l-4 border-blue-500">
                        <div class="font-bold text-gray-900">Rp 224 Jt</div>
                        <div class="text-sm text-gray-600">Operasional BPD & RT/RW</div>
                        <div class="text-xs text-blue-600 mt-1">80% dari target</div>
                    </div>
                </div>
            </div>

            <!-- Pelaksanaan Pembangunan -->
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-hammer text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Pelaksanaan Pembangunan Desa</h4>
                            <p class="text-sm text-gray-600">Target: Rp 1.12 M | Realisasi: Rp 728 Jt</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-600">65.0%</div>
                        <div class="text-sm text-yellow-600">
                            <i class="fas fa-clock mr-1"></i>In Progress
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full relative overflow-hidden" style="width: 65.0%">
                            <div class="absolute inset-0 bg-white opacity-20 animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-green-50 rounded border-l-4 border-green-500">
                        <div class="font-bold text-gray-900">Rp 392 Jt</div>
                        <div class="text-sm text-gray-600">Jalan & Jembatan</div>
                        <div class="text-xs text-green-600 mt-1">70% dari target</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded border-l-4 border-green-500">
                        <div class="font-bold text-gray-900">Rp 210 Jt</div>
                        <div class="text-sm text-gray-600">Air Bersih & Sanitasi</div>
                        <div class="text-xs text-green-600 mt-1">60% dari target</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded border-l-4 border-green-500">
                        <div class="font-bold text-gray-900">Rp 126 Jt</div>
                        <div class="text-sm text-gray-600">Sarana Umum</div>
                        <div class="text-xs text-green-600 mt-1">60% dari target</div>
                    </div>
                </div>
            </div>

            <!-- Pembinaan Kemasyarakatan -->
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-users text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Pembinaan Kemasyarakatan</h4>
                            <p class="text-sm text-gray-600">Target: Rp 420 Jt | Realisasi: Rp 315 Jt</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-yellow-600">75.0%</div>
                        <div class="text-sm text-green-600">
                            <i class="fas fa-check mr-1"></i>Good Progress
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-3 rounded-full relative overflow-hidden" style="width: 75.0%">
                            <div class="absolute inset-0 bg-white opacity-20 animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-yellow-50 rounded border-l-4 border-yellow-500">
                        <div class="font-bold text-gray-900">Rp 126 Jt</div>
                        <div class="text-sm text-gray-600">Keamanan & Ketertiban</div>
                        <div class="text-xs text-yellow-600 mt-1">75% dari target</div>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 rounded border-l-4 border-yellow-500">
                        <div class="font-bold text-gray-900">Rp 95 Jt</div>
                        <div class="text-sm text-gray-600">Keagamaan & Sosial</div>
                        <div class="text-xs text-yellow-600 mt-1">75% dari target</div>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 rounded border-l-4 border-yellow-500">
                        <div class="font-bold text-gray-900">Rp 95 Jt</div>
                        <div class="text-sm text-gray-600">Olahraga & Budaya</div>
                        <div class="text-xs text-yellow-600 mt-1">75% dari target</div>
                    </div>
                </div>
            </div>

            <!-- Pemberdayaan Masyarakat -->
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-handshake text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Pemberdayaan Masyarakat</h4>
                            <p class="text-sm text-gray-600">Target: Rp 280 Jt | Realisasi: Rp 196 Jt</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-purple-600">70.0%</div>
                        <div class="text-sm text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>Accelerating
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-3 rounded-full relative overflow-hidden" style="width: 70.0%">
                            <div class="absolute inset-0 bg-white opacity-20 animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-purple-50 rounded border-l-4 border-purple-500">
                        <div class="font-bold text-gray-900">Rp 98 Jt</div>
                        <div class="text-sm text-gray-600">UMKM & Ekonomi</div>
                        <div class="text-xs text-purple-600 mt-1">70% dari target</div>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded border-l-4 border-purple-500">
                        <div class="font-bold text-gray-900">Rp 59 Jt</div>
                        <div class="text-sm text-gray-600">Kesehatan & Gizi</div>
                        <div class="text-xs text-purple-600 mt-1">70% dari target</div>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded border-l-4 border-purple-500">
                        <div class="font-bold text-gray-900">Rp 39 Jt</div>
                        <div class="text-sm text-gray-600">Pendidikan & Pelatihan</div>
                        <div class="text-xs text-purple-600 mt-1">70% dari target</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Projects Status -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-6 text-xl">Status Proyek Unggulan</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pembangunan Jalan Desa -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-road text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Pembangunan Jalan Desa</h4>
                            <p class="text-sm text-gray-600">Ruas Krandegan - Pasar</p>
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                        75% Selesai
                    </span>
                </div>
                <div class="mb-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <div>Anggaran: Rp 280 Jt</div>
                    <div>Realisasi: Rp 210 Jt</div>
                    <div>Target Selesai: November 2025</div>
                </div>
            </div>

            <!-- Sistem Air Bersih -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-tint text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Sistem Air Bersih</h4>
                            <p class="text-sm text-gray-600">PDAM Desa Krandegan</p>
                        </div>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                        60% Selesai
                    </span>
                </div>
                <div class="mb-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 60%"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <div>Anggaran: Rp 175 Jt</div>
                    <div>Realisasi: Rp 105 Jt</div>
                    <div>Target Selesai: Desember 2025</div>
                </div>
            </div>

            <!-- Balai Desa -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-building text-yellow-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Renovasi Balai Desa</h4>
                            <p class="text-sm text-gray-600">Perbaikan & Modernisasi</p>
                        </div>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded">
                        90% Selesai
                    </span>
                </div>
                <div class="mb-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 90%"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <div>Anggaran: Rp 126 Jt</div>
                    <div>Realisasi: Rp 113 Jt</div>
                    <div>Target Selesai: Oktober 2025</div>
                </div>
            </div>

            <!-- Program UMKM -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-store text-purple-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Program UMKM</h4>
                            <p class="text-sm text-gray-600">Bantuan Modal & Pelatihan</p>
                        </div>
                    </div>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-1 rounded">
                        70% Selesai
                    </span>
                </div>
                <div class="mb-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600 space-y-1">
                    <div>Anggaran: Rp 98 Jt</div>
                    <div>Realisasi: Rp 69 Jt</div>
                    <div>Target Selesai: November 2025</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Analytics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Budget Efficiency -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="font-bold text-gray-900 mb-6 text-xl">Efisiensi Anggaran</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-thumbs-up text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Tepat Waktu</div>
                            <div class="text-sm text-gray-600">Proyek sesuai jadwal</div>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-green-600">85%</div>
                </div>

                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-coins text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Efisiensi Biaya</div>
                            <div class="text-sm text-gray-600">Hemat dari anggaran</div>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-blue-600">5.2%</div>
                </div>

                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-target text-yellow-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Kualitas Target</div>
                            <div class="text-sm text-gray-600">Standar tercapai</div>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-yellow-600">92%</div>
                </div>
            </div>
        </div>

        <!-- Quarterly Trends -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="font-bold text-gray-900 mb-6 text-xl">Tren Triwulan</h3>
            
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium text-gray-900">Triwulan I</span>
                        <span class="text-green-600 font-bold">100%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: 100%"></div>
                    </div>
                    <div class="text-xs text-gray-600 mt-1">Rp 210 Jt dari Rp 210 Jt</div>
                </div>

                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium text-gray-900">Triwulan II</span>
                        <span class="text-green-600 font-bold">95%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: 95%"></div>
                    </div>
                    <div class="text-xs text-gray-600 mt-1">Rp 499 Jt dari Rp 525 Jt</div>
                </div>

                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium text-gray-900">Triwulan III</span>
                        <span class="text-yellow-600 font-bold">68%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-yellow-500 h-3 rounded-full" style="width: 68%"></div>
                    </div>
                    <div class="text-xs text-gray-600 mt-1">Rp 286 Jt dari Rp 420 Jt</div>
                </div>

                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium text-gray-900">Triwulan IV</span>
                        <span class="text-gray-600 font-bold">Rencana</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gray-400 h-3 rounded-full" style="width: 0%"></div>
                    </div>
                    <div class="text-xs text-gray-600 mt-1">Target: Rp 343 Jt</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Progress Chart
    const monthlyCtx = document.getElementById('monthlyProgressChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Target Kumulatif (%)',
                data: [7.5, 15, 22.5, 37.5, 52.5, 67.5, 82.5, 90, 97.5, 100, 100, 100],
                borderColor: '#E5E7EB',
                backgroundColor: 'transparent',
                borderWidth: 2,
                borderDash: [5, 5],
                pointRadius: 0
            }, {
                label: 'Realisasi Kumulatif (%)',
                data: [8.2, 16.1, 23.8, 41.2, 58.9, 67.3, 72.3, 72.3, 72.3, null, null, null],
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10B981',
                pointBorderColor: '#ffffff',
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#10B981',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Progress bar animations
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.bg-gradient-to-r');
        progressBars.forEach((bar, index) => {
            const width = bar.style.width;
            bar.style.width = '0%';
            
            setTimeout(() => {
                bar.style.transition = 'width 2s ease-out';
                bar.style.width = width;
            }, index * 200);
        });
    }

    // Number counters
    function animateNumbers() {
        const numbers = document.querySelectorAll('.text-2xl.font-bold');
        numbers.forEach(number => {
            if (number.textContent.includes('%')) {
                const targetValue = parseFloat(number.textContent);
                let currentValue = 0;
                const increment = targetValue / 100;
                
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= targetValue) {
                        currentValue = targetValue;
                        clearInterval(timer);
                    }
                    number.textContent = currentValue.toFixed(1) + '%';
                }, 20);
            }
        });
    }

    // Card hover effects
    function addCardEffects() {
        const cards = document.querySelectorAll('.border.border-gray-200.rounded-lg');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('transform', 'scale-105', 'shadow-xl');
                this.style.transition = 'all 0.3s ease-out';
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('transform', 'scale-105', 'shadow-xl');
            });
        });
    }

    // Status badge animations
    function animateStatusBadges() {
        const badges = document.querySelectorAll('.bg-green-100, .bg-blue-100, .bg-yellow-100, .bg-purple-100');
        badges.forEach((badge, index) => {
            setTimeout(() => {
                badge.style.transform = 'scale(0.8)';
                badge.style.transition = 'transform 0.3s ease-out';
                
                setTimeout(() => {
                    badge.style.transform = 'scale(1)';
                }, 100);
            }, index * 100);
        });
    }

    // Initialize animations
    window.addEventListener('load', function() {
        setTimeout(animateProgressBars, 500);
        setTimeout(animateNumbers, 1000);
        setTimeout(animateStatusBadges, 1500);
        addCardEffects();
    });

    // Real-time updates simulation
    function simulateRealTimeUpdates() {
        setInterval(() => {
            // Simulate small progress updates
            const progressTexts = document.querySelectorAll('.text-2xl.font-bold');
            progressTexts.forEach(text => {
                if (text.textContent.includes('%') && Math.random() < 0.1) {
                    const currentValue = parseFloat(text.textContent);
                    const newValue = Math.min(currentValue + 0.1, 100);
                    text.textContent = newValue.toFixed(1) + '%';
                }
            });
        }, 30000); // Update every 30 seconds
    }

    // Start real-time simulation
    setTimeout(simulateRealTimeUpdates, 5000);

    // Format rupiah values
    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    // Tooltip for progress bars
    document.querySelectorAll('.h-3.rounded-full').forEach(bar => {
        bar.addEventListener('mouseenter', function(e) {
            const width = this.style.width;
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute bg-black text-white text-xs rounded px-2 py-1 z-50';
            tooltip.textContent = `Progress: ${width}`;
            tooltip.style.top = e.pageY - 30 + 'px';
            tooltip.style.left = e.pageX + 'px';
            document.body.appendChild(tooltip);
            
            this.tooltipElement = tooltip;
        });
        
        bar.addEventListener('mouseleave', function() {
            if (this.tooltipElement) {
                document.body.removeChild(this.tooltipElement);
                this.tooltipElement = null;
            }
        });
    });
</script>
@endsection