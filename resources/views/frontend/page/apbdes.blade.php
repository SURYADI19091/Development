@extends('frontend.main')

@section('title', 'APBDes - ' . strtoupper($villageProfile->village_name ?? 'Desa Krandegan'))
@section('page_title', 'APB DESA')
@section('header_icon', 'fas fa-coins')
@section('header_bg_color', 'bg-emerald-600')

@section('content')
<div class="xl:col-span-3">
    <!-- APBDes Overview -->
    <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-2">Anggaran Pendapatan dan Belanja Desa</h2>
                <p class="text-lg opacity-90 mb-4">Tahun Anggaran 2025</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-xl font-bold">Rp {{ number_format($totalBudget / 1000000, 1) }} M</div>
                        <div class="text-sm opacity-90">Total Anggaran</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold">Rp {{ number_format($realization / 1000000, 1) }} M</div>
                        <div class="text-sm opacity-90">Realisasi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold">{{ $totalBudget > 0 ? number_format(($realization / $totalBudget) * 100, 0) : 0 }}%</div>
                        <div class="text-sm opacity-90">Persentase</div>
                    </div>
                    <div class="text-center">
                        <div class="text-xl font-bold">Q3</div>
                        <div class="text-sm opacity-90">Periode</div>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-pie text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Quick Navigation -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <a href="{{ route('budget.plan') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300 group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-200 transition duration-200">
                    <i class="fas fa-calculator text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 group-hover:text-blue-600">Anggaran 2025</h3>
                    <p class="text-sm text-gray-600">Rincian anggaran per bidang</p>
                </div>
            </div>
        </a>

        <a href="{{ route('budget.realization') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300 group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-green-200 transition duration-200">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 group-hover:text-green-600">Realisasi</h3>
                    <p class="text-sm text-gray-600">Progress realisasi anggaran</p>
                </div>
            </div>
        </a>

        <a href="{{ route('budget.report') }}" class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300 group">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 group-hover:bg-purple-200 transition duration-200">
                    <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 group-hover:text-purple-600">Laporan Keuangan</h3>
                    <p class="text-sm text-gray-600">Laporan dan transparansi</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Pendapatan -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900">Pendapatan Desa</h3>
                @php
                    $totalIncome = $budgetSummary->where('budget_type', 'pendapatan')->sum('total_allocated');
                @endphp
                <div class="text-2xl font-bold text-green-600">Rp {{ number_format($totalIncome / 1000000, 1) }} M</div>
            </div>
            
            <div class="space-y-3">
                @foreach($budgetSummary->where('budget_type', 'pendapatan') as $income)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-700">{{ $income->category }}</span>
                    <span class="font-medium">Rp {{ number_format($income->total_allocated) }}</span>
                </div>
                @endforeach
                <div class="flex justify-between items-center py-2 font-bold text-green-600">
                    <span>Total Pendapatan</span>
                    <span>Rp {{ number_format($totalIncome) }}</span>
                </div>
            </div>
        </div>

        <!-- Belanja -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900">Belanja Desa</h3>
                @php
                    $totalExpense = $budgetSummary->where('budget_type', 'belanja')->sum('total_allocated');
                @endphp
                <div class="text-2xl font-bold text-red-600">Rp {{ number_format($totalExpense / 1000000, 1) }} M</div>
            </div>
            
            <div class="space-y-3">
                @foreach($budgetSummary->where('budget_type', 'belanja') as $expense)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-700">{{ $expense->category }}</span>
                    <span class="font-medium">Rp {{ number_format($expense->total_allocated) }}</span>
                </div>
                @endforeach
                <div class="flex justify-between items-center py-2 font-bold text-red-600">
                    <span>Total Belanja</span>
                    <span>Rp {{ number_format($totalExpense) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">Realisasi Anggaran per Bidang</h3>
        
        <div class="space-y-4">
            @php
                $colors = ['blue-600', 'green-600', 'yellow-600', 'red-600', 'purple-600', 'indigo-600'];
                $colorIndex = 0;
            @endphp
            
            @foreach($budgetSummary->where('budget_type', 'belanja') as $budget)
                @php
                    // Simulate realization percentage (in real app, this would come from actual transaction data)
                    $realizationPercentage = rand(40, 85);
                    $realizationAmount = ($budget->total_allocated * $realizationPercentage) / 100;
                    $currentColor = $colors[$colorIndex % count($colors)];
                    $colorIndex++;
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">{{ $budget->category }}</span>
                        <span class="text-sm text-gray-600">{{ $realizationPercentage }}% (Rp {{ number_format($realizationAmount / 1000000, 1) }} Juta)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-{{ $currentColor }} h-3 rounded-full" style="width: {{ $realizationPercentage }}%"></div>
                    </div>
                </div>
            @endforeach
            
            @if($budgetSummary->where('budget_type', 'belanja')->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-chart-bar text-4xl mb-4"></i>
                    <p>Belum ada data anggaran belanja</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900">Aktivitas Keuangan Terbaru</h3>
            <button class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
        
        <div class="space-y-4">
            @forelse($recentTransactions as $transaction)
            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 {{ $transaction->transaction_type == 'income' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center flex-shrink-0">
                    @if($transaction->transaction_type == 'income')
                        <i class="fas fa-plus text-green-600"></i>
                    @else
                        <i class="fas fa-minus text-red-600"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">{{ $transaction->description }}</h4>
                    <p class="text-sm text-gray-600">{{ $transaction->budget->category ?? 'Kategori tidak diketahui' }}</p>
                    <span class="text-xs text-gray-500">{{ $transaction->transaction_date->format('d F Y') }}</span>
                </div>
                <div class="text-right">
                    <div class="text-sm font-medium {{ $transaction->transaction_type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->transaction_type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount) }}
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-receipt text-4xl mb-4"></i>
                <p>Belum ada aktivitas keuangan terbaru</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Transparency & Documentation -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="font-bold text-gray-900 mb-4">Transparansi & Dokumentasi</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Documents -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Dokumen APBDes</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-900">APBDes 2025</div>
                                <div class="text-sm text-gray-600">2.3 MB</div>
                            </div>
                        </div>
                        <button class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded hover:bg-emerald-200 text-sm">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center">
                            <i class="fas fa-file-excel text-green-500 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-900">Realisasi Q3 2025</div>
                                <div class="text-sm text-gray-600">1.8 MB</div>
                            </div>
                        </div>
                        <button class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded hover:bg-emerald-200 text-sm">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                    </div>

                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-900">Laporan Semester I</div>
                                <div class="text-sm text-gray-600">3.1 MB</div>
                            </div>
                        </div>
                        <button class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded hover:bg-emerald-200 text-sm">
                            <i class="fas fa-download mr-1"></i>Download
                        </button>
                    </div>
                </div>
            </div>

            <!-- Key Information -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3">Informasi Penting</h4>
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <h5 class="font-medium text-blue-900">Musyawarah APBDes</h5>
                        </div>
                        <p class="text-sm text-blue-800">
                            Musyawarah penyusunan APBDes 2026 akan dilaksanakan pada 15 November 2025. 
                            Seluruh warga diundang untuk berpartisipasi.
                        </p>
                    </div>

                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <h5 class="font-medium text-green-900">Audit BPK</h5>
                        </div>
                        <p class="text-sm text-green-800">
                            Hasil audit BPK untuk APBDes 2024 menunjukkan opini Wajar Tanpa Pengecualian (WTP). 
                            Pengelolaan keuangan desa dinilai baik.
                        </p>
                    </div>

                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-eye text-yellow-600 mr-2"></i>
                            <h5 class="font-medium text-yellow-900">Transparansi</h5>
                        </div>
                        <p class="text-sm text-yellow-800">
                            Laporan keuangan desa dipublikasikan setiap triwulan melalui website dan papan 
                            pengumuman di kantor desa.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t text-center">
            <p class="text-gray-600 mb-4">
                Komitmen transparansi dan akuntabilitas pengelolaan keuangan desa
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-200">
                    <i class="fas fa-download mr-2"></i>
                    Download Laporan Lengkap
                </button>
                <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Lihat Dashboard Keuangan
                </button>
                <button class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                    <i class="fas fa-question-circle mr-2"></i>
                    FAQ APBDes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Progress bar animation on page load
    window.addEventListener('load', function() {
        const progressBars = document.querySelectorAll('[style*="width:"]');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.transition = 'width 1.5s ease-in-out';
                bar.style.width = width;
            }, 500);
        });
    });

    // Download buttons
    document.querySelectorAll('button[class*="bg-emerald-100"]').forEach(button => {
        if (button.textContent.includes('Download')) {
            button.addEventListener('click', function() {
                const fileName = this.closest('.flex').querySelector('.font-medium').textContent;
                // In a real application, this would download the actual file
                alert(`Mengunduh file: ${fileName}`);
            });
        }
    });

    // Action buttons
    document.querySelector('button[class*="bg-emerald-600"]').addEventListener('click', function() {
        // Download complete report
        alert('Mengunduh laporan APBDes lengkap...');
    });

    document.querySelector('button[class*="bg-blue-600"]').addEventListener('click', function() {
        // Financial dashboard
        alert('Membuka dashboard keuangan desa...');
    });

    document.querySelector('button[class*="bg-gray-600"]').addEventListener('click', function() {
        // FAQ APBDes
        alert('Membuka halaman FAQ APBDes...');
    });

    // View all activities
    document.querySelector('button[class*="text-emerald-600"]').addEventListener('click', function() {
        alert('Menampilkan semua aktivitas keuangan...');
    });

    // Number formatting animation
    function animateNumbers() {
        const numbers = document.querySelectorAll('.text-xl.font-bold, .text-2xl.font-bold');
        numbers.forEach(num => {
            if (num.textContent.includes('Rp') || num.textContent.includes('%')) {
                // Add subtle pulse animation
                num.classList.add('animate-pulse');
                setTimeout(() => {
                    num.classList.remove('animate-pulse');
                }, 2000);
            }
        });
    }

    // Trigger number animation
    setTimeout(animateNumbers, 1000);

    // Hover effects for cards
    document.querySelectorAll('.hover\\:shadow-xl').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('transform', 'scale-105');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('transform', 'scale-105');
        });
    });
</script>
@endsection