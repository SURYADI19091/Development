@extends('backend.layout.main')

@section('page_title', 'Manajemen Data Penduduk')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item active">Data Penduduk</li>
@endsection

@section('page_actions')
<div class="btn-group">
    @can('manage-population-data')
    <a href="{{ route('backend.population.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Data Penduduk
    </a>
    @endcan
    @can('export-data')
    <a href="{{ route('backend.population.export') }}" class="btn btn-success">
        <i class="fas fa-download"></i> Ekspor Data
    </a>
    @endcan
    @can('manage-population-data')
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importModal">
        <i class="fas fa-upload"></i> Impor Data
    </button>
    @endcan
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Statistics Cards Row 1 -->
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Penduduk</span>
                    <span class="info-box-number">{{ number_format($stats['total'] ?? 0) }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-male"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Laki-laki</span>
                    <span class="info-box-number">{{ number_format($stats['male'] ?? 0) }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-pink"><i class="fas fa-female"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Perempuan</span>
                    <span class="info-box-number">{{ number_format($stats['female'] ?? 0) }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-home"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Kepala Keluarga</span>
                    <span class="info-box-number">{{ number_format($stats['households'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Statistics Cards Row 2 - Status -->
        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-heartbeat"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Status Hidup</span>
                    <span class="info-box-number">{{ number_format($stats['alive'] ?? 0) }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-cross"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Status Mati</span>
                    <span class="info-box-number">{{ number_format($stats['dead'] ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Penduduk</h3>
                </div>
                
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Cari nama, NIK, atau alamat..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="gender" class="form-control">
                                    <option value="">Semua Jenis Kelamin</option>
                                    <option value="M" {{ request('gender') == 'M' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="F" {{ request('gender') == 'F' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="Hidup" {{ request('status') == 'Hidup' ? 'selected' : '' }}>Hidup</option>
                                    <option value="Mati" {{ request('status') == 'Mati' ? 'selected' : '' }}>Mati</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <select name="age_range" class="form-control">
                                    <option value="">Semua Umur</option>
                                    <option value="0-17" {{ request('age_range') == '0-17' ? 'selected' : '' }}>0-17 Tahun</option>
                                    <option value="18-30" {{ request('age_range') == '18-30' ? 'selected' : '' }}>18-30 Tahun</option>
                                    <option value="31-50" {{ request('age_range') == '31-50' ? 'selected' : '' }}>31-50 Tahun</option>
                                    <option value="51+" {{ request('age_range') == '51+' ? 'selected' : '' }}>51+ Tahun</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="rt" class="form-control">
                                    <option value="">Semua RT</option>
                                    @for($i = 1; $i <= 20; $i++)
                                        <option value="{{ sprintf('%03d', $i) }}" {{ request('rt') == sprintf('%03d', $i) ? 'selected' : '' }}>
                                            RT {{ sprintf('%03d', $i) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="rw" class="form-control">
                                    <option value="">Semua RW</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ sprintf('%03d', $i) }}" {{ request('rw') == sprintf('%03d', $i) ? 'selected' : '' }}>
                                            RW {{ sprintf('%03d', $i) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </form>

                    <!-- Bulk Actions -->
                    @can('manage-population-data')
                    <form id="bulkActionForm" method="POST" action="{{ route('backend.population.bulk-delete') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select name="action" id="bulkAction" class="form-control">
                                    <option value="">Pilih Aksi</option>
                                    <option value="delete">Hapus Terpilih</option>
                                    <option value="export">Ekspor Terpilih</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-warning" disabled id="bulkActionBtn">
                                    Terapkan Aksi
                                </button>
                            </div>
                        </div>
                    @endcan

                        <!-- Population Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        @can('manage-population-data')
                                        <th width="30">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        @endcan
                                        <th>NIK</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Umur</th>
                                        <th>Status</th>
                                        <th>Alamat</th>
                                        <th>RT/RW</th>
                                        <th>Pekerjaan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($population as $person)
                                        <tr>
                                            @can('manage-population-data')
                                            <td>
                                                <input type="checkbox" name="population[]" 
                                                       value="{{ $person->id }}" class="population-checkbox">
                                            </td>
                                            @endcan
                                            <td>{{ $person->identity_card_number ?? '-' }}</td>
                                            <td>
                                                <strong>{{ $person->name }}</strong>
                                                @if($person->family_relationship == 'Kepala Keluarga')
                                                    <span class="badge badge-info ml-1">Kepala Keluarga</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $person->gender == 'M' ? 'primary' : 'pink' }}">
                                                    {{ $person->gender == 'M' ? 'Laki-laki' : 'Perempuan' }}
                                                </span>
                                            </td>
                                            <td>{{ $person->birth_date ? \Carbon\Carbon::parse($person->birth_date)->format('d M Y') : '-' }}</td>
                                            <td>{{ $person->age ?? '-' }} tahun</td>
                                            <td>
                                                <span class="badge badge-{{ $person->status == 'Hidup' ? 'success' : 'danger' }}">
                                                    {{ $person->status ?? 'Hidup' }}
                                                </span>
                                                @if($person->status == 'Mati' && $person->death_date)
                                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($person->death_date)->format('d M Y') }}</small>
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($person->address, 30) }}</td>
                                            <td>
                                                @if($person->settlement)
                                                    {{ $person->settlement->name }}<br>
                                                    <small class="text-muted">
                                                        RT {{ $person->settlement->neighborhood_number ?? '-' }} / 
                                                        RW {{ $person->settlement->community_number ?? '-' }}
                                                    </small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $person->occupation ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('backend.population.show', $person->id) }}" 
                                                       class="btn btn-sm btn-info" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @can('manage-population-data')
                                                    <a href="{{ route('backend.population.edit', $person->id) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('backend.population.destroy', $person->id) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penduduk ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">
                                                Data penduduk tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @can('manage-population-data')
                    </form>
                    @endcan

                    <!-- Pagination -->
                    @if(isset($population) && $population->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $population->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
@can('manage-population-data')
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('backend.population.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Impor Data Penduduk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="import_file">File Excel (.xlsx, .xls, .csv)</label>
                        <input type="file" class="form-control-file" id="import_file" name="import_file" 
                               accept=".xlsx,.xls,.csv" required>
                        <small class="form-text text-muted">
                            Ukuran file maksimal: 10MB. 
                            <a href="{{ route('backend.population.template') }}" target="_blank">Unduh Template</a>
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Petunjuk Impor:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Gunakan template yang disediakan untuk hasil terbaik</li>
                            <li>NIK harus unik (16 digit)</li>
                            <li>Jenis kelamin: M (Laki-laki) / F (Perempuan)</li>
                            <li>Format tanggal: YYYY-MM-DD</li>
                            <li>Format RT/RW: 001, 002, dst.</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Impor Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan
@endsection

@push('scripts')
<script>
    // Select All functionality
    $('#selectAll').change(function() {
        $('.population-checkbox').prop('checked', $(this).prop('checked'));
        toggleBulkActionButton();
    });

    $('.population-checkbox').change(function() {
        toggleBulkActionButton();
    });

    $('#bulkAction').change(function() {
        toggleBulkActionButton();
    });

    function toggleBulkActionButton() {
        const checkedCount = $('.population-checkbox:checked').length;
        const actionSelected = $('#bulkAction').val();
        $('#bulkActionBtn').prop('disabled', checkedCount === 0 || !actionSelected);
    }

    // Bulk action form submission
    $('#bulkActionForm').submit(function(e) {
        const action = $('#bulkAction').val();
        const checkedCount = $('.population-checkbox:checked').length;
        
        if (checkedCount === 0) {
            e.preventDefault();
            alert('Silakan pilih setidaknya satu data penduduk.');
            return false;
        }

        if (action === 'delete') {
            if (!confirm(`Apakah Anda yakin ingin menghapus ${checkedCount} data penduduk?`)) {
                e.preventDefault();
                return false;
            }
        }
    });

    // Auto-refresh statistics every 30 seconds
    setInterval(function() {
        if (!document.hidden) {
            // You can implement AJAX call to refresh statistics here
        }
    }, 30000);
</script>
@endpush