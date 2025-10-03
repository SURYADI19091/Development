@extends('backend.layout.main')

@section('page_title', 'Manajemen Anggaran Desa')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Anggaran Desa</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $budgets->total() ?? 0 }}</h4>
                                    <small>Total Item Anggaran</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-wallet fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">Rp {{ number_format($summary['total_budget'] ?? 0, 0, ',', '.') }}</h4>
                                    <small>Total Anggaran</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-money-bill fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">Rp {{ number_format($summary['total_realized'] ?? 0, 0, ',', '.') }}</h4>
                                    <small>Total Realisasi</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">Rp {{ number_format($summary['remaining'] ?? 0, 0, ',', '.') }}</h4>
                                    <small>Sisa Anggaran</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-balance-scale fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget List -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Data Anggaran Desa</h3>
                        <div>
                            @can('create-budget')
                            <a href="{{ route('backend.budget.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Anggaran
                            </a>
                            @endcan
                            <button class="btn btn-success" onclick="exportData()">
                                <i class="fas fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Summary Info -->
                    @if(isset($summary) && ($summary['total_budget'] > 0 || $summary['total_realized'] > 0))
                    <div class="alert alert-info mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Ringkasan Tahun {{ request('year', date('Y')) }}:</strong><br>
                                <small>
                                    Realisasi: {{ number_format($summary['realization_percentage'], 1) }}% dari total anggaran<br>
                                    Sisa: Rp {{ number_format($summary['remaining'], 0, ',', '.') }}
                                </small>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-info" role="progressbar" 
                                         style="width: {{ min($summary['realization_percentage'], 100) }}%">
                                        {{ number_format($summary['realization_percentage'], 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label class="form-label">Tahun:</label>
                            <select class="form-control" id="yearFilter">
                                <option value="">Semua Tahun</option>
                                @for($year = date('Y') + 1; $year >= 2020; $year--)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori:</label>
                            <select class="form-control" id="categoryFilter">
                                <option value="">Semua Kategori</option>
                                <option value="pendapatan" {{ request('category') == 'pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                                <option value="belanja_pegawai" {{ request('category') == 'belanja_pegawai' ? 'selected' : '' }}>Belanja Pegawai</option>
                                <option value="belanja_barang" {{ request('category') == 'belanja_barang' ? 'selected' : '' }}>Belanja Barang</option>
                                <option value="belanja_modal" {{ request('category') == 'belanja_modal' ? 'selected' : '' }}>Belanja Modal</option>
                                <option value="belanja_sosial" {{ request('category') == 'belanja_sosial' ? 'selected' : '' }}>Belanja Sosial</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Pencarian:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari deskripsi, kategori..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="applyFilters()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-outline-secondary btn-block" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Reset
                            </button>
                        </div>
                    </div>                    <!-- Budget Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun Anggaran</th>
                                    <th>Tipe Anggaran</th>
                                    <th>Kategori</th>
                                    <th>Sub Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Rencana Anggaran</th>
                                    <th>Realisasi</th>
                                    <th>Persentase</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($budgets ?? [] as $index => $budget)
                                <tr>
                                    <td>{{ $budgets->firstItem() + $index }}</td>
                                    <td>{{ $budget->fiscal_year }}</td>
                                    <td>
                                        @if($budget->budget_type == 'pendapatan')
                                            <span class="badge badge-success">Pendapatan</span>
                                        @else
                                            <span class="badge badge-primary">Belanja</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $budget->category)) }}</td>
                                    <td>{{ $budget->sub_category ?? '-' }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $budget->description }}">
                                            {{ $budget->description }}
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($budget->planned_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $realized = $budget->transactions()->sum('amount') ?? 0;
                                        @endphp
                                        Rp {{ number_format($realized, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @php
                                            $realized = $budget->transactions()->sum('amount') ?? 0;
                                            $percentage = $budget->planned_amount > 0 ? ($realized / $budget->planned_amount) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $percentage > 100 ? 'bg-danger' : ($percentage > 80 ? 'bg-warning' : 'bg-success') }}" 
                                                 role="progressbar" style="width: {{ min($percentage, 100) }}%"
                                                 title="{{ number_format($percentage, 1) }}%">
                                                {{ number_format($percentage, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('backend.budget.show', $budget) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit-budget')
                                            <a href="{{ route('backend.budget.edit', $budget) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            <a href="{{ route('backend.budget.transactions', $budget) }}" class="btn btn-sm btn-primary" title="Transaksi">
                                                <i class="fas fa-list"></i>
                                            </a>
                                            @can('delete-budget')
                                            <button class="btn btn-sm btn-danger" onclick="deleteBudget({{ $budget->id }})" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data anggaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($budgets) && $budgets->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $budgets->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus anggaran ini?</p>
                <p class="text-danger"><small>Data yang sudah dihapus tidak dapat dikembalikan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
function applyFilters() {
    const year = document.getElementById('yearFilter').value;
    const category = document.getElementById('categoryFilter').value;
    const search = document.getElementById('searchInput').value;
    
    const params = new URLSearchParams();
    if (year) params.append('year', year);
    if (category) params.append('category', category);
    if (search) params.append('search', search);
    
    window.location.href = '{{ route("backend.budget.index") }}' + (params.toString() ? '?' + params.toString() : '');
}

function clearFilters() {
    window.location.href = '{{ route("backend.budget.index") }}';
}

function deleteBudget(id) {
    $('#deleteForm').attr('action', '{{ route("backend.budget.destroy", ":id") }}'.replace(':id', id));
    $('#deleteModal').modal('show');
}

function exportData() {
    const year = document.getElementById('yearFilter').value;
    const category = document.getElementById('categoryFilter').value;
    
    // For now, just show alert since export route needs to be implemented
    alert('Fitur export akan segera tersedia');
}

// Search on Enter key
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>
@endsection