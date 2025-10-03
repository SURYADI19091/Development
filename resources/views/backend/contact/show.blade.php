@extends('backend.layout.main')

@push('styles')
<style>
    .contact-detail-card {
        border-left: 4px solid #007bff;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
    }
    .message-content {
        background: #f8f9fa;
        border-left: 4px solid #dee2e6;
        padding: 1rem;
        border-radius: 0.25rem;
        line-height: 1.6;
    }
    .reply-content {
        background: #e8f5e8;
        border-left: 4px solid #28a745;
        padding: 1rem;
        border-radius: 0.25rem;
        line-height: 1.6;
    }
    .contact-info-item {
        border-bottom: 1px solid #f1f3f4;
        padding: 0.75rem 0;
    }
    .contact-info-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('backend.contact.index') }}">Pesan Kontak</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pesan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Message Content -->
        <div class="card contact-detail-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-envelope-open-text mr-2"></i>{{ $contact->subject }}
                </h5>
                <span class="status-badge {{ $contact->status_badge }}">
                    {{ $contact->status_text }}
                </span>
            </div>

            <div class="card-body">
                <!-- Sender Info -->
                <div class="d-flex align-items-center mb-4 p-3" style="background: #f8f9fa; border-radius: 0.25rem;">
                    <div class="avatar-circle bg-primary text-white mr-3 d-flex align-items-center justify-content-center" 
                         style="width: 48px; height: 48px; border-radius: 50%; font-size: 18px; font-weight: bold;">
                        {{ strtoupper(substr($contact->name, 0, 2)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-lg">{{ $contact->name }}</div>
                        <div class="text-muted">{{ $contact->email }}</div>
                        @if($contact->phone)
                            <div class="text-muted">
                                <i class="fas fa-phone fa-sm mr-1"></i>{{ $contact->phone }}
                            </div>
                        @endif
                    </div>
                    <div class="text-right text-muted">
                        <div>{{ $contact->created_at->format('d M Y') }}</div>
                        <div>{{ $contact->created_at->format('H:i') }} WIB</div>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="mb-4">
                    <h6 class="fw-bold text-muted mb-2">PESAN:</h6>
                    <div class="message-content">
                        {!! nl2br(e($contact->message)) !!}
                    </div>
                </div>

                <!-- Admin Reply -->
                @if($contact->admin_reply)
                    <div class="mb-4">
                        <h6 class="fw-bold text-success mb-2">BALASAN ADMIN:</h6>
                        <div class="reply-content">
                            {!! nl2br(e($contact->admin_reply)) !!}
                        </div>
                        
                        @if($contact->repliedBy)
                            <div class="mt-2 text-muted small">
                                <i class="fas fa-user mr-1"></i>Dibalas oleh: {{ $contact->repliedBy->name }}
                                <i class="fas fa-clock ml-3 mr-1"></i>{{ $contact->replied_at->format('d M Y H:i') }} WIB
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <a href="{{ route('backend.contact.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar
                        </a>
                    </div>
                    
                    <div class="btn-group">
                        @if($contact->status != 'replied')
                            <a href="{{ route('backend.contact.reply', $contact) }}" class="btn btn-success">
                                <i class="fas fa-reply mr-1"></i>Balas Pesan
                            </a>
                        @endif
                        
                        <button type="button" class="btn btn-outline-danger" onclick="deleteContact()">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Contact Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Kontak
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="contact-info-item">
                    <div class="px-3 d-flex justify-content-between">
                        <strong>Status:</strong>
                        <span class="status-badge {{ $contact->status_badge }}">
                            {{ $contact->status_text }}
                        </span>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="px-3 d-flex justify-content-between">
                        <strong>Tanggal Kirim:</strong>
                        <span>{{ $contact->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
                
                @if($contact->replied_at)
                    <div class="contact-info-item">
                        <div class="px-3 d-flex justify-content-between">
                            <strong>Tanggal Balasan:</strong>
                            <span>{{ $contact->replied_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                @endif
                
                @if($contact->ip_address)
                    <div class="contact-info-item">
                        <div class="px-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>IP Address:</strong>
                                <span class="font-monospace">{{ $contact->ip_address }}</span>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($contact->user_agent)
                    <div class="contact-info-item">
                        <div class="px-3">
                            <strong>Browser:</strong>
                            <div class="mt-1 small text-muted" style="word-break: break-word;">
                                {{ Str::limit($contact->user_agent, 100) }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <!-- Change Status -->
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" 
                                data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-tag mr-1"></i>Ubah Status
                        </button>
                        <div class="dropdown-menu w-100">
                            @if($contact->status != 'unread')
                                <a class="dropdown-item" href="#" onclick="changeStatus('unread')">
                                    <i class="fas fa-envelope text-danger mr-2"></i>Belum Dibaca
                                </a>
                            @endif
                            @if($contact->status != 'read')
                                <a class="dropdown-item" href="#" onclick="changeStatus('read')">
                                    <i class="fas fa-envelope-open text-warning mr-2"></i>Sudah Dibaca
                                </a>
                            @endif
                            @if($contact->status != 'replied')
                                <a class="dropdown-item" href="#" onclick="changeStatus('replied')">
                                    <i class="fas fa-check-circle text-success mr-2"></i>Sudah Dibalas
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Actions -->
                    @if($contact->email)
                        <a href="mailto:{{ $contact->email }}" class="btn btn-outline-info">
                            <i class="fas fa-envelope mr-1"></i>Kirim Email
                        </a>
                    @endif
                    
                    @if($contact->phone)
                        <a href="tel:{{ $contact->phone }}" class="btn btn-outline-success">
                            <i class="fas fa-phone mr-1"></i>Telepon
                        </a>
                    @endif
                </div>
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
                <p>Apakah Anda yakin ingin menghapus pesan dari <strong>{{ $contact->name }}</strong>?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('backend.contact.destroy', $contact) }}" method="POST" class="d-inline">
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
function deleteContact() {
    $('#deleteModal').modal('show');
}

function changeStatus(status) {
    if (!confirm('Ubah status pesan ini?')) {
        return;
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.ajax({
        url: '{{ route("backend.contact.update-status", $contact) }}',
        method: 'PATCH',
        data: {
            status: status
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
                window.location.reload();
            } else {
                alert('Terjadi kesalahan: ' + response.message);
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat mengubah status.');
        }
    });
}
</script>
@endpush