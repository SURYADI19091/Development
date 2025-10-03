@extends('backend.layout.main')

@section('page_title', 'Detail Anggaran')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.budget.index') }}">Anggaran Desa</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Detail Anggaran: {{ $budget->item_name ?? $budget->name ?? 'Unknown' }}</h3>
                        <div>
                            @can('manage-village-budget')
                            <a href="{{ route('backend.budget.edit', $budget->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @endcan
                            @if(method_exists($budget, 'transactions') || isset($transactions))
                            <a href="{{ route('backend.budget.transactions', $budget->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-list"></i> Transaksi
                            </a>
                            @endif
                            <a href="{{ route('backend.budget.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Nama Item</th>
                                    <td><strong>{{ $budget->item_name ?? $budget->name ?? 'Tidak diketahui' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Kode Rekening</th>
                                    <td>{{ $budget->account_code ?? $budget->code ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Fiskal</th>
                                    <td>{{ $budget->fiscal_year ?? $budget->year ?? date('Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>
                                        @if(isset($budget->category))
                                            <span class="badge badge-info">{{ $budget->category }}</span>
                                        @elseif(isset($budget->item_type))
                                            <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $budget->item_type)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak dikategorikan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($budget->is_active ?? true)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Jumlah Anggaran</th>
                                    <td><strong class="text-primary">Rp {{ number_format($budget->planned_amount ?? $budget->amount ?? 0, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Realisasi</th>
                                    <td><strong class="text-success">Rp {{ number_format($budget->realized_amount ?? $budget->realization ?? 0, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Sisa Anggaran</th>
                                    <td>
                                        @php
                                            $planned = $budget->planned_amount ?? $budget->amount ?? 0;
                                            $realized = $budget->realized_amount ?? $budget->realization ?? 0;
                                            $remaining = $planned - $realized;
                                        @endphp
                                        <strong class="{{ $remaining >= 0 ? 'text-info' : 'text-danger' }}">
                                            Rp {{ number_format($remaining, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Persentase Realisasi</th>
                                    <td>
                                        @php
                                            $planned = $budget->planned_amount ?? $budget->amount ?? 0;
                                            $realized = $budget->realized_amount ?? $budget->realization ?? 0;
                                            $percentage = $planned > 0 ? ($realized / $planned) * 100 : 0;
                                        @endphp
                                        <div class="progress mb-1">
                                            <div class="progress-bar {{ $percentage > 100 ? 'bg-danger' : ($percentage > 80 ? 'bg-warning' : 'bg-success') }}" 
                                                 role="progressbar" style="width: {{ min($percentage, 100) }}%">
                                                {{ number_format($percentage, 1) }}%
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ number_format($percentage, 1) }}% dari target</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>{{ $budget->created_at ? $budget->created_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if(!empty($budget->description))
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Deskripsi</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-0">{{ $budget->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Budget Statistics -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Anggaran</span>
                                    <span class="info-box-number">Rp {{ number_format($budget->planned_amount ?? $budget->amount ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Realisasi</span>
                                    <span class="info-box-number">Rp {{ number_format($budget->realized_amount ?? $budget->realization ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-balance-scale"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sisa</span>
                                    @php
                                        $planned = $budget->planned_amount ?? $budget->amount ?? 0;
                                        $realized = $budget->realized_amount ?? $budget->realization ?? 0;
                                        $remaining = $planned - $realized;
                                    @endphp
                                    <span class="info-box-number">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($transactions) && count($transactions) > 0)
                    <!-- Recent Transactions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="mb-0">Transaksi Terbaru</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" id="searchTransaction" class="form-control form-control-sm" placeholder="Cari transaksi...">
                                                </div>
                                                <div class="col-md-3">
                                                    <select id="filterType" class="form-control form-control-sm">
                                                        <option value="">Semua Tipe</option>
                                                        <option value="income">Pemasukan</option>
                                                        <option value="expense">Pengeluaran</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="btn-group btn-group-sm w-100">
                                                        <button type="button" class="btn btn-info" onclick="printReport()" title="Cetak Laporan">
                                                            <i class="fas fa-print"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-success" onclick="exportToExcel()" title="Export Excel">
                                                            <i class="fas fa-file-excel"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="transactionTable" class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Deskripsi</th>
                                                    <th>Jumlah</th>
                                                    <th>Tipe</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($transactions as $transaction)
                                                <tr class="transaction-row fade-in">
                                                    <td>{{ $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : ($transaction->created_at ? $transaction->created_at->format('d/m/Y') : '-') }}</td>
                                                    <td>{{ $transaction->description ?? $transaction->notes ?? 'Tidak ada deskripsi' }}</td>
                                                    <td>
                                                        <strong class="text-{{ ($transaction->transaction_type ?? $transaction->type) == 'income' ? 'success' : 'danger' }}">
                                                            Rp {{ number_format($transaction->amount ?? 0, 0, ',', '.') }}
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        @if(($transaction->transaction_type ?? $transaction->type) == 'income')
                                                            <span class="badge badge-success">Pemasukan</span>
                                                        @else
                                                            <span class="badge badge-danger">Pengeluaran</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($transaction->status))
                                                            <span class="badge badge-{{ $transaction->status == 'completed' ? 'success' : 'warning' }}">
                                                                {{ ucfirst($transaction->status) }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-info">Processed</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ route('backend.budget.transactions', $budget->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-list"></i> Lihat Semua Transaksi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <h5>Belum ada transaksi</h5>
                                    <p class="text-muted">Belum ada transaksi yang terkait dengan anggaran ini.</p>
                                    @can('manage-village-budget')
                                    <a href="{{ route('backend.budget.transactions', $budget->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Tambah Transaksi
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-scale {
    transition: transform 0.2s ease-in-out;
}

.hover-scale:hover {
    transform: scale(1.02);
}

.info-box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.info-box-icon {
    border-radius: 8px 0 0 8px;
}

.progress {
    height: 8px;
}

.badge {
    font-size: 0.75em;
}

.card {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn {
    border-radius: 6px;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.transaction-row {
    transition: all 0.3s ease;
}

.transaction-row:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.search-highlight {
    background-color: yellow;
    padding: 1px 2px;
    border-radius: 2px;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .info-box {
        margin-bottom: 15px;
    }
    
    .small-box {
        margin-bottom: 15px;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 6px !important;
        margin-bottom: 5px;
    }
    
    .card-header .row {
        flex-direction: column;
    }
    
    .card-header .col-md-6 {
        margin-bottom: 10px;
    }
    
    .table-responsive {
        font-size: 0.9em;
    }
    
    .info-box-number {
        font-size: 1.2rem !important;
    }
}

@media (max-width: 576px) {
    .info-box-text {
        font-size: 0.8rem;
    }
    
    .info-box-number {
        font-size: 1rem !important;
    }
    
    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
}

/* Print styles */
@media print {
    .btn, .card-header .row .col-md-6:last-child {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add tooltips to badges and buttons
    $('[data-toggle="tooltip"]').tooltip();
    
    // Add hover effects to info boxes
    $('.info-box').addClass('hover-scale');
    
    // Format numbers on hover
    $('.info-box-number').each(function() {
        const number = $(this).text().replace(/[^\d]/g, '');
        if (number) {
            $(this).attr('title', 'Nilai: ' + parseInt(number).toLocaleString('id-ID'));
        }
    });
    
    // Progress bar animation
    $('.progress-bar').each(function() {
        const width = $(this).css('width');
        $(this).css('width', '0%').animate({ width: width }, 1000);
    });
    
    // Add confirmation for delete actions
    $('.btn-danger').on('click', function(e) {
        if (!confirm('Apakah Anda yakin ingin melanjutkan?')) {
            e.preventDefault();
        }
    });
    
    // Transaction search and filter functionality
    $('#searchTransaction').on('keyup', function() {
        filterTransactions();
    });
    
    $('#filterType').on('change', function() {
        filterTransactions();
    });
    
    function filterTransactions() {
        var searchText = $('#searchTransaction').val().toLowerCase();
        var filterType = $('#filterType').val();
        
        // Add loading state
        $('#transactionTable').addClass('loading');
        
        setTimeout(function() {
            $('#transactionTable tbody tr').each(function() {
                var row = $(this);
                var descriptionCell = row.find('td:eq(1)');
                var description = descriptionCell.text().toLowerCase();
                var type = row.find('.badge').text().toLowerCase();
                
                var matchSearch = searchText === '' || description.includes(searchText);
                var matchType = filterType === '' || type.includes(filterType);
                
                // Highlight search terms
                if (searchText && matchSearch && searchText.length > 0) {
                    var originalText = descriptionCell.text();
                    var highlightedText = originalText.replace(
                        new RegExp(searchText.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi'),
                        '<span class="search-highlight">$&</span>'
                    );
                    descriptionCell.html(highlightedText);
                } else {
                    // Remove highlights
                    var plainText = descriptionCell.text();
                    descriptionCell.text(plainText);
                }
                
                if (matchSearch && matchType) {
                    row.show().addClass('fade-in');
                } else {
                    row.hide().removeClass('fade-in');
                }
            });
            
            // Remove loading state
            $('#transactionTable').removeClass('loading');
        }, 200);
        
        // Update visible count
        var visibleRows = $('#transactionTable tbody tr:visible').length;
        if (visibleRows === 0 && ($('#searchTransaction').val() || $('#filterType').val())) {
            if (!$('#noResults').length) {
                $('#transactionTable tbody').append(
                    '<tr id="noResults"><td colspan="5" class="text-center text-muted py-3">' +
                    '<i class="fas fa-search"></i><br>Tidak ada transaksi yang sesuai dengan filter' +
                    '</td></tr>'
                );
            }
        } else {
            $('#noResults').remove();
        }
        
        // Update filtered statistics
        updateFilteredStats();
    }
    
    function updateFilteredStats() {
        var visibleRows = $('#transactionTable tbody tr:visible').not('#noResults');
        var totalIncome = 0;
        var totalExpense = 0;
        var count = 0;
        
        visibleRows.each(function() {
            var row = $(this);
            var amountText = row.find('td:eq(2)').text().replace(/[^\d]/g, '');
            var amount = parseInt(amountText) || 0;
            var type = row.find('.badge').text().toLowerCase();
            
            if (type.includes('pemasukan') || type.includes('income')) {
                totalIncome += amount;
            } else {
                totalExpense += amount;
            }
            count++;
        });
        
        // Update or create filtered stats display
        if (!$('#filteredStats').length) {
            $('#transactionTable').after(`
                <div id="filteredStats" class="mt-3" style="display: none;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-filter"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Data Terfilter</span>
                                    <span class="info-box-number" id="filteredCount">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pemasukan</span>
                                    <span class="info-box-number" id="filteredIncome">Rp 0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pengeluaran</span>
                                    <span class="info-box-number" id="filteredExpense">Rp 0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Selisih</span>
                                    <span class="info-box-number" id="filteredBalance">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
        
        // Update values
        $('#filteredCount').text(count + ' transaksi');
        $('#filteredIncome').text('Rp ' + totalIncome.toLocaleString('id-ID'));
        $('#filteredExpense').text('Rp ' + totalExpense.toLocaleString('id-ID'));
        $('#filteredBalance').text('Rp ' + (totalIncome - totalExpense).toLocaleString('id-ID'));
        
        // Show/hide filtered stats
        if ($('#searchTransaction').val() || $('#filterType').val()) {
            $('#filteredStats').slideDown();
        } else {
            $('#filteredStats').slideUp();
        }
    }
    
    // Export and Print functions
    window.printReport = function() {
        // Check if there's data to print
        var visibleRows = $('#transactionTable tbody tr:visible').not('#noResults');
        if (visibleRows.length === 0) {
            showToast('Tidak ada data untuk dicetak', 'warning');
            return;
        }
        
        showToast('Mempersiapkan laporan untuk dicetak...', 'info');
        
        var printContent = `
            <html>
            <head>
                <title>Laporan Anggaran - {{ $budget->item_name ?? $budget->name ?? 'N/A' }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .info-section { margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .text-success { color: #28a745; }
                    .text-danger { color: #dc3545; }
                    @media print { body { margin: 0; } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>LAPORAN ANGGARAN DESA</h2>
                    <p>{{ $budget->item_name ?? $budget->name ?? 'N/A' }}</p>
                    <p>Tahun Anggaran: {{ $budget->fiscal_year ?? date('Y') }}</p>
                </div>
                
                <div class="info-section">
                    <h3>Ringkasan Anggaran</h3>
                    <table>
                        <tr><td><strong>Nama Item</strong></td><td>{{ $budget->item_name ?? $budget->name ?? 'N/A' }}</td></tr>
                        <tr><td><strong>Kode Rekening</strong></td><td>{{ $budget->account_code ?? 'N/A' }}</td></tr>
                        <tr><td><strong>Anggaran</strong></td><td>Rp {{ number_format($budget->planned_amount ?? $budget->amount ?? 0, 0, ',', '.') }}</td></tr>
                        <tr><td><strong>Realisasi</strong></td><td>Rp {{ number_format($budget->realized_amount ?? $budget->realization ?? 0, 0, ',', '.') }}</td></tr>
                    </table>
                </div>
                
                ${$('#transactionTable').length ? $('#transactionTable')[0].outerHTML : ''}
                
                <div style="margin-top: 30px; text-align: right;">
                    <p>Dicetak pada: ${new Date().toLocaleDateString('id-ID')}</p>
                </div>
            </body>
            </html>
        `;
        
        var printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    };
    
    window.exportToExcel = function() {
        // Check if there's data to export
        var visibleRows = $('#transactionTable tbody tr:visible').not('#noResults');
        if (visibleRows.length === 0) {
            showToast('Tidak ada data untuk di-export', 'warning');
            return;
        }
        
        // Simple CSV export that can be opened in Excel
        var csv = 'Tanggal,Deskripsi,Jumlah,Tipe,Status\n';
        
        $('#transactionTable tbody tr:visible').each(function() {
            var row = $(this);
            if (!row.attr('id') || row.attr('id') !== 'noResults') {
                var cols = row.find('td');
                var rowData = [];
                cols.each(function() {
                    var cellText = $(this).text().replace(/,/g, ';').replace(/\n/g, ' ').trim();
                    rowData.push('"' + cellText + '"');
                });
                csv += rowData.join(',') + '\n';
            }
        });
        
        var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement('a');
        var url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'anggaran_{{ Str::slug($budget->item_name ?? $budget->name ?? 'budget') }}_' + new Date().toISOString().split('T')[0] + '.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        showToast('Data berhasil di-export ke Excel', 'success');
    };
    
    // Toast notification function
    function showToast(message, type = 'info') {
        // Remove existing toast
        $('.toast-notification').remove();
        
        var toastHtml = `
            <div class="toast-notification alert alert-${type} alert-dismissible" 
                 style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                ${message}
            </div>
        `;
        
        $('body').append(toastHtml);
        
        // Auto remove after 3 seconds
        setTimeout(function() {
            $('.toast-notification').fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>
@endpush