@extends('backend.layout.main')

@push('styles')
<style>
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
    }
    .request-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.2s ease;
    }
    .letter-type-badge {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .priority-urgent {
        border-left: 4px solid #dc3545;
    }
    .priority-normal {
        border-left: 4px solid #28a745;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pengajuan Surat</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    @php
        $stats = [
            'total' => $letterRequests->total(),
            'pending' => \App\Models\LetterRequest::where('status', 'pending')->count(),
            'processing' => \App\Models\LetterRequest::where('status', 'processing')->count(),
            'ready' => \App\Models\LetterRequest::where('status', 'ready')->count(),
            'completed' => \App\Models\LetterRequest::where('status', 'completed')->count(),
            'rejected' => \App\Models\LetterRequest::where('status', 'rejected')->count(),
        ];
    @endphp
    
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <div class="h3">{{ $stats['total'] }}</div>
                <div>Total Pengajuan</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <div class="h3">{{ $stats['pending'] }}</div>
                <div>Menunggu</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <div class="h3">{{ $stats['processing'] }}</div>
                <div>Diproses</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <div class="h3">{{ $stats['ready'] }}</div>
                <div>Siap Diambil</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <div class="h3">{{ $stats['completed'] }}</div>
                <div>Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <div class="h3">{{ $stats['rejected'] }}</div>
                <div>Ditolak</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter and Search -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt mr-2"></i>Daftar Pengajuan Surat
                </h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshData()">
                        <i class="fas fa-sync mr-1"></i>Refresh
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm" onclick="exportData()">
                        <i class="fas fa-download mr-1"></i>Export
                    </button>
                </div>
            </div>

            <div class="card-body">
                <!-- Filters -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Diambil</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        </div>
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
                                    <option value="lainnya" {{ request('letter_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_filter">Tanggal</label>
                                <input type="date" name="date_filter" id="date_filter" class="form-control" value="{{ request('date_filter') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Cari Pengajuan</label>
                                <div class="input-group">
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="Nama, NIK, nomor surat..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(request()->hasAny(['status', 'letter_type', 'date_filter', 'search']))
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('backend.letter-requests.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-times mr-1"></i>Reset Filter
                                </a>
                            </div>
                        </div>
                    @endif
                </form>

                <!-- Letter Requests List -->
                @if($letterRequests->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada pengajuan surat</h5>
                        <p class="text-muted">Belum ada pengajuan surat dari masyarakat.</p>
                    </div>
                @else
                    <div class="row">
                        @foreach($letterRequests as $request)
                            @php
                                $statusColors = [
                                    'pending' => 'bg-warning text-dark',
                                    'processing' => 'bg-primary text-white',
                                    'ready' => 'bg-info text-white',
                                    'completed' => 'bg-success text-white',
                                    'rejected' => 'bg-danger text-white'
                                ];
                                $statusTexts = [
                                    'pending' => 'Menunggu',
                                    'processing' => 'Diproses',
                                    'ready' => 'Siap Diambil',
                                    'completed' => 'Selesai',
                                    'rejected' => 'Ditolak'
                                ];
                                $letterTypeNames = [
                                    'domisili' => 'Surat Domisili',
                                    'usaha' => 'Surat Keterangan Usaha',
                                    'tidak_mampu' => 'Surat Tidak Mampu',
                                    'penghasilan' => 'Surat Keterangan Penghasilan',
                                    'pengantar_ktp' => 'Surat Pengantar KTP',
                                    'pengantar_kk' => 'Surat Pengantar KK',
                                    'pengantar_akta' => 'Surat Pengantar Akta',
                                    'pengantar_nikah' => 'Surat Pengantar Nikah',
                                    'lainnya' => $request->custom_letter_type ?? 'Lainnya'
                                ];
                                $isUrgent = $request->created_at->diffInDays(now()) > 3 && in_array($request->status, ['pending', 'processing']);
                            @endphp
                            
                            <div class="col-md-6 mb-4">
                                <div class="card request-card h-100 {{ $isUrgent ? 'priority-urgent' : 'priority-normal' }}">
                                    <div class="card-header d-flex justify-content-between align-items-center p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="letter-type-badge mr-2">
                                                {{ $letterTypeNames[$request->letter_type] ?? $request->letter_type }}
                                            </div>
                                            @if($isUrgent)
                                                <span class="badge badge-danger badge-pill">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                                </span>
                                            @endif
                                        </div>
                                        <span class="status-badge {{ $statusColors[$request->status] }}">
                                            {{ $statusTexts[$request->status] }}
                                        </span>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="avatar-circle bg-primary text-white mr-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; border-radius: 50%; font-size: 14px; font-weight: bold;">
                                                {{ strtoupper(substr($request->full_name, 0, 2)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 font-weight-bold">{{ $request->full_name }}</h6>
                                                <div class="text-muted small">
                                                    <div><i class="fas fa-id-card mr-1"></i>NIK: {{ $request->nik }}</div>
                                                    @if($request->phone)
                                                        <div><i class="fas fa-phone mr-1"></i>{{ $request->phone }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="text-muted small mb-1">Keperluan:</div>
                                            <div class="small">{{ Str::limit($request->purpose, 100) }}</div>
                                        </div>
                                        
                                        <div class="row text-center small text-muted">
                                            <div class="col-6">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $request->created_at->format('d/m/Y') }}
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $request->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted small">
                                                No: {{ $request->request_number }}
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('backend.letter-requests.show', $request) }}" 
                                                   class="btn btn-outline-primary" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($request->status == 'pending')
                                                    <button type="button" class="btn btn-outline-success" 
                                                            onclick="processRequest({{ $request->id }})" title="Proses">
                                                        <i class="fas fa-play"></i>
                                                    </button>
                                                @endif
                                                
                                                @if(in_array($request->status, ['processing', 'ready']))
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="completeRequest({{ $request->id }})" title="Selesaikan">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                
                                                @if(!in_array($request->status, ['completed', 'rejected']))
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="rejectRequest({{ $request->id }})" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            Menampilkan {{ $letterRequests->firstItem() ?? 0 }} - {{ $letterRequests->lastItem() ?? 0 }} 
                            dari {{ $letterRequests->total() }} pengajuan
                        </div>
                        <div>
                            {{ $letterRequests->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Process Request Modal -->
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Pengajuan Surat</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="processForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="process_notes">Catatan (opsional)</label>
                        <textarea name="notes" id="process_notes" class="form-control" rows="3" 
                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-play mr-1"></i>Proses Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete Request Modal -->
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selesaikan Pengajuan Surat</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="completeForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="letter_number">Nomor Surat <span class="text-danger">*</span></label>
                        <input type="text" name="letter_number" id="letter_number" class="form-control" 
                               placeholder="Contoh: 001/KEL/2025" required>
                    </div>
                    <div class="form-group">
                        <label for="complete_notes">Catatan (opsional)</label>
                        <textarea name="notes" id="complete_notes" class="form-control" rows="3" 
                                  placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i>Selesaikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Request Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan Surat</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" 
                                  placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i>Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function processRequest(id) {
    $('#processForm').attr('action', `{{ url('backend/letter-requests') }}/${id}/process`);
    $('#processModal').modal('show');
}

function completeRequest(id) {
    $('#completeForm').attr('action', `{{ url('backend/letter-requests') }}/${id}/complete`);
    $('#completeModal').modal('show');
}

function rejectRequest(id) {
    $('#rejectForm').attr('action', `{{ url('backend/letter-requests') }}/${id}/reject`);
    $('#rejectModal').modal('show');
}

function refreshData() {
    window.location.reload();
}

function exportData() {
    // Implementation for data export
    alert('Fitur export akan segera tersedia.');
}
</script>
@endpush