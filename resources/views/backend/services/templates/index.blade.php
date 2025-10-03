@extends('backend.layout.main')

@push('styles')
<style>
    .template-card {
        transition: all 0.2s ease;
        border-left: 4px solid #007bff;
    }
    .template-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .template-card.inactive {
        border-left-color: #6c757d;
        opacity: 0.7;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
    }
    .letter-type-badge {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.letter-requests.index') }}">Pengajuan Surat</a></li>
                <li class="breadcrumb-item active" aria-current="page">Template Surat</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <div class="h3">{{ $stats['total'] }}</div>
                <div>Total Template</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <div class="h3">{{ $stats['active'] }}</div>
                <div>Template Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <div class="h3">{{ $stats['inactive'] }}</div>
                <div>Template Nonaktif</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter and Actions -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-contract mr-2"></i>Manajemen Template Surat
                </h5>
                <div class="btn-group">
                    <a href="{{ route('backend.letter-templates.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>Buat Template
                    </a>
                    <button type="button" class="btn btn-outline-secondary" onclick="refreshData()">
                        <i class="fas fa-sync mr-1"></i>Refresh
                    </button>
                </div>
            </div>

            <div class="card-body">
                <!-- Filters -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="letter_type">Jenis Surat</label>
                                <select name="letter_type" id="letter_type" class="form-control">
                                    <option value="">Semua Jenis</option>
                                    <option value="domisili" {{ request('letter_type') == 'domisili' ? 'selected' : '' }}>Surat Domisili</option>
                                    <option value="usaha" {{ request('letter_type') == 'usaha' ? 'selected' : '' }}>Surat Keterangan Usaha</option>
                                    <option value="tidak_mampu" {{ request('letter_type') == 'tidak_mampu' ? 'selected' : '' }}>Surat Tidak Mampu</option>
                                    <option value="penghasilan" {{ request('letter_type') == 'penghasilan' ? 'selected' : '' }}>Surat Keterangan Penghasilan</option>
                                    <option value="pengantar_ktp" {{ request('letter_type') == 'pengantar_ktp' ? 'selected' : '' }}>Surat Pengantar KTP</option>
                                    <option value="pengantar_kk" {{ request('letter_type') == 'pengantar_kk' ? 'selected' : '' }}>Surat Pengantar KK</option>
                                    <option value="pengantar_nikah" {{ request('letter_type') == 'pengantar_nikah' ? 'selected' : '' }}>Surat Pengantar Nikah</option>
                                    <option value="kelahiran" {{ request('letter_type') == 'kelahiran' ? 'selected' : '' }}>Surat Keterangan Kelahiran</option>
                                    <option value="kematian" {{ request('letter_type') == 'kematian' ? 'selected' : '' }}>Surat Keterangan Kematian</option>
                                    <option value="lainnya" {{ request('letter_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="search">Cari Template</label>
                                <div class="input-group">
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="Nama, kode, atau deskripsi..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(request()->hasAny(['letter_type', 'status', 'search']))
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('backend.letter-templates.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-times mr-1"></i>Reset Filter
                                </a>
                            </div>
                        </div>
                    @endif
                </form>

                <!-- Templates Grid -->
                @if($templates->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-file-contract fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada template surat</h5>
                        <p class="text-muted">Belum ada template surat yang dibuat.</p>
                        <a href="{{ route('backend.letter-templates.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>Buat Template Pertama
                        </a>
                    </div>
                @else
                    <div class="row">
                        @foreach($templates as $template)
                            <div class="col-lg-6 mb-4">
                                <div class="card template-card {{ !$template->is_active ? 'inactive' : '' }}">
                                    <div class="card-header d-flex justify-content-between align-items-center p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="letter-type-badge mr-2">
                                                {{ $template->letter_type_name }}
                                            </div>
                                            @if(!$template->is_active)
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    data-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{ route('backend.letter-templates.show', $template) }}">
                                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                                </a>
                                                <a class="dropdown-item" href="{{ route('backend.letter-templates.preview', $template) }}" target="_blank">
                                                    <i class="fas fa-search mr-2"></i>Preview
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('backend.letter-templates.edit', $template) }}">
                                                    <i class="fas fa-edit mr-2"></i>Edit
                                                </a>
                                                <button class="dropdown-item" onclick="duplicateTemplate({{ $template->id }})">
                                                    <i class="fas fa-copy mr-2"></i>Duplikasi
                                                </button>
                                                <button class="dropdown-item" onclick="toggleStatus({{ $template->id }})">
                                                    <i class="fas fa-{{ $template->is_active ? 'eye-slash' : 'eye' }} mr-2"></i>
                                                    {{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                                <div class="dropdown-divider"></div>
                                                <button class="dropdown-item text-danger" onclick="deleteTemplate({{ $template->id }})">
                                                    <i class="fas fa-trash mr-2"></i>Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body">
                                        <h6 class="card-title font-weight-bold">{{ $template->name }}</h6>
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-code mr-1"></i>{{ $template->code }}
                                        </p>
                                        
                                        @if($template->description)
                                            <p class="card-text small">{{ Str::limit($template->description, 100) }}</p>
                                        @endif
                                        
                                        <div class="row text-center small text-muted mt-3">
                                            <div class="col-6">
                                                <i class="fas fa-file mr-1"></i>
                                                {{ $template->format }} {{ ucfirst($template->orientation) }}
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-sort-numeric-up mr-1"></i>
                                                Urutan: {{ $template->sort_order }}
                                            </div>
                                        </div>
                                        
                                        @if($template->required_fields && count($template->required_fields) > 0)
                                            <div class="mt-2">
                                                <small class="text-muted">Field Wajib:</small>
                                                <div class="mt-1">
                                                    @foreach(array_slice($template->required_fields, 0, 3) as $field)
                                                        <span class="badge badge-light badge-sm mr-1">{{ $field }}</span>
                                                    @endforeach
                                                    @if(count($template->required_fields) > 3)
                                                        <span class="badge badge-light badge-sm">+{{ count($template->required_fields) - 3 }} lainnya</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                                        <div class="small text-muted">
                                            @if($template->creator)
                                                <i class="fas fa-user mr-1"></i>{{ $template->creator->name }}
                                            @endif
                                            <br>
                                            <i class="fas fa-clock mr-1"></i>{{ $template->created_at->diffForHumans() }}
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('backend.letter-templates.preview', $template) }}" 
                                               class="btn btn-outline-primary" target="_blank" title="Preview">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('backend.letter-templates.edit', $template) }}" 
                                               class="btn btn-outline-success" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            Menampilkan {{ $templates->firstItem() ?? 0 }} - {{ $templates->lastItem() ?? 0 }} 
                            dari {{ $templates->total() }} template
                        </div>
                        <div>
                            {{ $templates->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus template ini?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshData() {
    window.location.reload();
}

function duplicateTemplate(id) {
    if (confirm('Duplikasi template ini?')) {
        const form = $('<form>', {
            method: 'POST',
            action: `{{ url('backend/letter-templates') }}/${id}/duplicate`
        });
        
        form.append($('<input>', {
            type: 'hidden',
            name: '_token',
            value: $('meta[name="csrf-token"]').attr('content')
        }));
        
        $('body').append(form);
        form.submit();
    }
}

function toggleStatus(id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.post(`{{ url('backend/letter-templates') }}/${id}/toggle-status`)
        .done(function(response) {
            if (response.success) {
                alert(response.message);
                window.location.reload();
            } else {
                alert('Terjadi kesalahan: ' + response.message);
            }
        })
        .fail(function() {
            alert('Terjadi kesalahan saat mengubah status.');
        });
}

function deleteTemplate(id) {
    $('#deleteForm').attr('action', `{{ url('backend/letter-templates') }}/${id}`);
    $('#deleteModal').modal('show');
}
</script>
@endpush