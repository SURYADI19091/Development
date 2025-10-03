@extends('backend.layout.main')

@push('styles')
<style>
    .detail-card {
        border-left: 4px solid #007bff;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
    }
    .info-item {
        border-bottom: 1px solid #f1f3f4;
        padding: 0.75rem 0;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .document-preview {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 1rem;
    }
    .timeline {
        position: relative;
        padding-left: 3rem;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 0.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6c757d;
    }
    .timeline-item.active::before {
        background: #007bff;
    }
    .timeline-item.completed::before {
        background: #28a745;
    }
    .timeline-item::after {
        content: '';
        position: absolute;
        left: -1.25rem;
        top: 1rem;
        width: 2px;
        height: calc(100% - 0.5rem);
        background: #dee2e6;
    }
    .timeline-item:last-child::after {
        display: none;
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
                <li class="breadcrumb-item active" aria-current="page">Detail Pengajuan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Letter Request Details -->
        <div class="card detail-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt mr-2"></i>Detail Pengajuan Surat
                </h5>
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
                @endphp
                <span class="status-badge {{ $statusColors[$letterRequest->status] }}">
                    {{ $statusTexts[$letterRequest->status] }}
                </span>
            </div>

            <div class="card-body">
                <!-- Applicant Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">INFORMASI PEMOHON</h6>
                        <div class="info-item">
                            <strong>Nama Lengkap:</strong>
                            <div>{{ $letterRequest->full_name }}</div>
                        </div>
                        <div class="info-item">
                            <strong>NIK:</strong>
                            <div>{{ $letterRequest->nik }}</div>
                        </div>
                        <div class="info-item">
                            <strong>Tempat, Tanggal Lahir:</strong>
                            <div>{{ $letterRequest->birth_place }}, {{ \Carbon\Carbon::parse($letterRequest->birth_date)->format('d M Y') }}</div>
                        </div>
                        <div class="info-item">
                            <strong>Jenis Kelamin:</strong>
                            <div>{{ $letterRequest->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>
                        <div class="info-item">
                            <strong>Agama:</strong>
                            <div>{{ $letterRequest->religion }}</div>
                        </div>
                        <div class="info-item">
                            <strong>Status Perkawinan:</strong>
                            <div>{{ $letterRequest->marital_status }}</div>
                        </div>
                        <div class="info-item">
                            <strong>Pekerjaan:</strong>
                            <div>{{ $letterRequest->occupation }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">KONTAK & ALAMAT</h6>
                        <div class="info-item">
                            <strong>Alamat:</strong>
                            <div>{{ $letterRequest->address }}</div>
                        </div>
                        <div class="info-item">
                            <strong>RT/RW:</strong>
                            <div>{{ $letterRequest->rt }}/{{ $letterRequest->rw }}</div>
                        </div>
                        @if($letterRequest->phone)
                            <div class="info-item">
                                <strong>No. Telepon:</strong>
                                <div>{{ $letterRequest->phone }}</div>
                            </div>
                        @endif
                        @if($letterRequest->email)
                            <div class="info-item">
                                <strong>Email:</strong>
                                <div>{{ $letterRequest->email }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Letter Information -->
                <div class="mb-4">
                    <h6 class="text-muted mb-3">INFORMASI SURAT</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong>Jenis Surat:</strong>
                                <div>
                                    @php
                                        $letterTypeNames = [
                                            'domisili' => 'Surat Keterangan Domisili',
                                            'usaha' => 'Surat Keterangan Usaha',
                                            'tidak_mampu' => 'Surat Keterangan Tidak Mampu',
                                            'penghasilan' => 'Surat Keterangan Penghasilan',
                                            'pengantar_ktp' => 'Surat Pengantar KTP',
                                            'pengantar_kk' => 'Surat Pengantar KK',
                                            'pengantar_akta' => 'Surat Pengantar Akta',
                                            'pengantar_nikah' => 'Surat Pengantar Nikah',
                                            'lainnya' => $letterRequest->custom_letter_type ?? 'Lainnya'
                                        ];
                                    @endphp
                                    {{ $letterTypeNames[$letterRequest->letter_type] ?? $letterRequest->letter_type }}
                                    @if($letterRequest->letter_type == 'lainnya' && $letterRequest->custom_letter_type)
                                        <br><small class="text-muted">({{ $letterRequest->custom_letter_type }})</small>
                                    @endif
                                </div>
                            </div>
                            <div class="info-item">
                                <strong>No. Pengajuan:</strong>
                                <div class="font-monospace">{{ $letterRequest->request_number }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong>Tanggal Pengajuan:</strong>
                                <div>{{ $letterRequest->created_at->format('d M Y H:i') }} WIB</div>
                            </div>
                            @if($letterRequest->processed_at)
                                <div class="info-item">
                                    <strong>Tanggal Diproses:</strong>
                                    <div>{{ $letterRequest->processed_at->format('d M Y H:i') }} WIB</div>
                                </div>
                            @endif
                            @if($letterRequest->completion_date)
                                <div class="info-item">
                                    <strong>Tanggal Selesai:</strong>
                                    <div>{{ \Carbon\Carbon::parse($letterRequest->completion_date)->format('d M Y') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Purpose -->
                <div class="mb-4">
                    <h6 class="text-muted mb-3">KEPERLUAN</h6>
                    <div class="document-preview bg-light">
                        {{ $letterRequest->purpose }}
                    </div>
                </div>

                <!-- Documents -->
                @if($letterRequest->ktp_file_path || $letterRequest->kk_file_path || $letterRequest->other_files)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">DOKUMEN PENDUKUNG</h6>
                        <div class="row">
                            @if($letterRequest->ktp_file_path)
                                <div class="col-md-4 mb-2">
                                    <div class="card">
                                        <div class="card-body text-center p-3">
                                            <i class="fas fa-id-card fa-2x text-primary mb-2"></i>
                                            <div class="small">KTP</div>
                                            <a href="{{ Storage::url($letterRequest->ktp_file_path) }}" 
                                               target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($letterRequest->kk_file_path)
                                <div class="col-md-4 mb-2">
                                    <div class="card">
                                        <div class="card-body text-center p-3">
                                            <i class="fas fa-users fa-2x text-success mb-2"></i>
                                            <div class="small">Kartu Keluarga</div>
                                            <a href="{{ Storage::url($letterRequest->kk_file_path) }}" 
                                               target="_blank" class="btn btn-outline-success btn-sm mt-2">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($letterRequest->other_files && is_array(json_decode($letterRequest->other_files, true)))
                                @foreach(json_decode($letterRequest->other_files, true) as $index => $file)
                                    <div class="col-md-4 mb-2">
                                        <div class="card">
                                            <div class="card-body text-center p-3">
                                                <i class="fas fa-file fa-2x text-info mb-2"></i>
                                                <div class="small">Dokumen {{ $index + 1 }}</div>
                                                <a href="{{ Storage::url($file) }}" 
                                                   target="_blank" class="btn btn-outline-info btn-sm mt-2">
                                                    <i class="fas fa-eye mr-1"></i>Lihat
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Admin Notes -->
                @if($letterRequest->notes || $letterRequest->rejection_reason)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">CATATAN ADMIN</h6>
                        <div class="document-preview {{ $letterRequest->status == 'rejected' ? 'bg-danger text-white' : 'bg-light' }}">
                            @if($letterRequest->status == 'rejected' && $letterRequest->rejection_reason)
                                <strong>Alasan Penolakan:</strong><br>
                                {{ $letterRequest->rejection_reason }}
                            @else
                                {{ $letterRequest->notes ?? 'Tidak ada catatan khusus.' }}
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('backend.letter-requests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar
                    </a>
                    
                    <div class="btn-group">
                        @if($letterRequest->status == 'pending')
                            <button type="button" class="btn btn-success" onclick="processRequest()">
                                <i class="fas fa-play mr-1"></i>Proses Pengajuan
                            </button>
                        @endif
                        
                        @if(in_array($letterRequest->status, ['processing', 'ready']))
                            <button type="button" class="btn btn-primary" onclick="completeRequest()">
                                <i class="fas fa-check mr-1"></i>Selesaikan
                            </button>
                        @endif
                        
                        @if(!in_array($letterRequest->status, ['completed', 'rejected']))
                            <button type="button" class="btn btn-danger" onclick="rejectRequest()">
                                <i class="fas fa-times mr-1"></i>Tolak Pengajuan
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Status Timeline -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-history mr-2"></i>Status Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item completed">
                        <div class="font-weight-bold">Pengajuan Diterima</div>
                        <div class="text-muted small">{{ $letterRequest->created_at->format('d M Y H:i') }}</div>
                        <div class="text-muted small">Pengajuan surat telah diterima sistem</div>
                    </div>
                    
                    <div class="timeline-item {{ in_array($letterRequest->status, ['processing', 'ready', 'completed']) ? 'completed' : ($letterRequest->status == 'pending' ? 'active' : '') }}">
                        <div class="font-weight-bold">Sedang Diproses</div>
                        @if($letterRequest->processed_at)
                            <div class="text-muted small">{{ $letterRequest->processed_at->format('d M Y H:i') }}</div>
                            <div class="text-muted small">Pengajuan sedang diverifikasi</div>
                        @else
                            <div class="text-muted small">Menunggu diproses</div>
                        @endif
                    </div>
                    
                    <div class="timeline-item {{ in_array($letterRequest->status, ['ready', 'completed']) ? 'completed' : ($letterRequest->status == 'processing' ? 'active' : '') }}">
                        <div class="font-weight-bold">Surat Siap</div>
                        @if($letterRequest->status == 'ready')
                            <div class="text-muted small">Surat siap diambil</div>
                        @elseif($letterRequest->status == 'completed')
                            <div class="text-muted small">Surat telah diselesaikan</div>
                        @else
                            <div class="text-muted small">Menunggu penyelesaian</div>
                        @endif
                    </div>
                    
                    <div class="timeline-item {{ $letterRequest->status == 'completed' ? 'completed' : ($letterRequest->status == 'ready' ? 'active' : '') }}">
                        <div class="font-weight-bold">Selesai</div>
                        @if($letterRequest->completion_date)
                            <div class="text-muted small">{{ \Carbon\Carbon::parse($letterRequest->completion_date)->format('d M Y') }}</div>
                            <div class="text-muted small">Surat telah diserahkan</div>
                        @else
                            <div class="text-muted small">Belum selesai</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <strong>Durasi Pengajuan:</strong>
                    <div>{{ $letterRequest->created_at->diffForHumans() }}</div>
                </div>
                
                @if($letterRequest->processor)
                    <div class="info-item">
                        <strong>Diproses oleh:</strong>
                        <div>{{ $letterRequest->processor->name }}</div>
                    </div>
                @endif
                
                <div class="info-item">
                    <strong>Prioritas:</strong>
                    @php
                        $daysDiff = $letterRequest->created_at->diffInDays(now());
                    @endphp
                    @if($daysDiff > 3 && in_array($letterRequest->status, ['pending', 'processing']))
                        <span class="badge badge-danger">Urgent</span>
                    @elseif($daysDiff > 1)
                        <span class="badge badge-warning">Normal</span>
                    @else
                        <span class="badge badge-info">Baru</span>
                    @endif
                </div>
                
                @if($letterRequest->phone)
                    <div class="mt-3">
                        <a href="tel:{{ $letterRequest->phone }}" class="btn btn-outline-success btn-block btn-sm">
                            <i class="fas fa-phone mr-1"></i>Hubungi Pemohon
                        </a>
                    </div>
                @endif
                
                @if($letterRequest->email)
                    <div class="mt-2">
                        <a href="mailto:{{ $letterRequest->email }}" class="btn btn-outline-primary btn-block btn-sm">
                            <i class="fas fa-envelope mr-1"></i>Kirim Email
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include modals from the index page for actions -->
@include('backend.services.partials.action-modals')
@endsection

@push('scripts')
<script>
function processRequest() {
    $('#processForm').attr('action', `{{ route('backend.letter-requests.process', $letterRequest) }}`);
    $('#processModal').modal('show');
}

function completeRequest() {
    $('#completeForm').attr('action', `{{ route('backend.letter-requests.complete', $letterRequest) }}`);
    $('#completeModal').modal('show');
}

function rejectRequest() {
    $('#rejectForm').attr('action', `{{ route('backend.letter-requests.reject', $letterRequest) }}`);
    $('#rejectModal').modal('show');
}
</script>
@endpush