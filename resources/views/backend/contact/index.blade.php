@extends('backend.layout.main')

@push('styles')
<style>
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
    }
    .message-preview {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .contact-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.2s ease;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pesan Kontak</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="fw-bold h4">{{ $stats['total'] }}</div>
                        <div>Total Pesan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-envelope-open fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="fw-bold h4">{{ $stats['unread'] }}</div>
                        <div>Belum Dibaca</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="fw-bold h4">{{ $stats['read'] }}</div>
                        <div>Sudah Dibaca</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-reply fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="fw-bold h4">{{ $stats['replied'] }}</div>
                        <div>Sudah Dibalas</div>
                    </div>
                </div>
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
                    <i class="fas fa-envelope mr-2"></i>Pesan Kontak
                </h5>
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
                                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                                    <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Sudah Dibalas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from">Dari Tanggal</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to">Sampai Tanggal</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">Cari Pesan</label>
                                <div class="input-group">
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="Nama, email, subjek..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(request()->hasAny(['status', 'date_from', 'date_to', 'search']))
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('backend.contact.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-times mr-1"></i>Reset Filter
                                </a>
                            </div>
                        </div>
                    @endif
                </form>

                <!-- Bulk Actions -->
                @if($contacts->isNotEmpty())
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">Pilih Semua</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">Batal Pilih</button>
                        
                        <div class="btn-group ml-2">
                            <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('mark_read')" disabled id="bulk-read-btn">
                                <i class="fas fa-eye mr-1"></i>Tandai Dibaca
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('mark_unread')" disabled id="bulk-unread-btn">
                                <i class="fas fa-eye-slash mr-1"></i>Tandai Belum Dibaca
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')" disabled id="bulk-delete-btn">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Messages List -->
                @if($contacts->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada pesan kontak</h5>
                        <p class="text-muted">Belum ada pesan dari pengunjung website.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Pengirim</th>
                                    <th>Subjek</th>
                                    <th>Pesan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                    <tr class="contact-row {{ $contact->status == 'unread' ? 'table-warning' : '' }}">
                                        <td>
                                            <input type="checkbox" class="contact-checkbox" value="{{ $contact->id }}" onchange="updateBulkButtons()">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle {{ $contact->status == 'unread' ? 'bg-danger' : 'bg-secondary' }} text-white mr-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 50%; font-size: 12px;">
                                                    {{ strtoupper(substr($contact->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $contact->name }}</div>
                                                    <small class="text-muted">{{ $contact->email }}</small>
                                                    @if($contact->phone)
                                                        <br><small class="text-muted">{{ $contact->phone }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $contact->subject }}</span>
                                        </td>
                                        <td>
                                            <div class="message-preview" title="{{ $contact->message }}">
                                                {{ Str::limit($contact->message, 50) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $contact->status_badge }}">
                                                {{ $contact->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $contact->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $contact->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('backend.contact.show', $contact) }}" 
                                                   class="btn btn-outline-primary" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($contact->status != 'replied')
                                                    <a href="{{ route('backend.contact.reply', $contact) }}" 
                                                       class="btn btn-outline-success" title="Balas">
                                                        <i class="fas fa-reply"></i>
                                                    </a>
                                                @endif

                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="deleteContact({{ $contact->id }})" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $contacts->firstItem() ?? 0 }} - {{ $contacts->lastItem() ?? 0 }} 
                            dari {{ $contacts->total() }} pesan
                        </div>
                        <div>
                            {{ $contacts->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectAll() {
    $('.contact-checkbox').prop('checked', true);
    $('#select-all').prop('checked', true);
    updateBulkButtons();
}

function deselectAll() {
    $('.contact-checkbox').prop('checked', false);
    $('#select-all').prop('checked', false);
    updateBulkButtons();
}

function toggleSelectAll() {
    const isChecked = $('#select-all').prop('checked');
    $('.contact-checkbox').prop('checked', isChecked);
    updateBulkButtons();
}

function updateBulkButtons() {
    const checkedBoxes = $('.contact-checkbox:checked');
    const hasSelected = checkedBoxes.length > 0;
    
    $('#bulk-read-btn, #bulk-unread-btn, #bulk-delete-btn').prop('disabled', !hasSelected);
    
    // Update select all checkbox
    const allBoxes = $('.contact-checkbox');
    $('#select-all').prop('checked', allBoxes.length === checkedBoxes.length);
}

function bulkAction(action) {
    const selectedIds = $('.contact-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        alert('Pilih pesan yang akan diproses.');
        return;
    }
    
    let confirmMessage = '';
    switch (action) {
        case 'mark_read':
            confirmMessage = `Tandai ${selectedIds.length} pesan sebagai sudah dibaca?`;
            break;
        case 'mark_unread':
            confirmMessage = `Tandai ${selectedIds.length} pesan sebagai belum dibaca?`;
            break;
        case 'delete':
            confirmMessage = `Hapus ${selectedIds.length} pesan? Tindakan ini tidak dapat dibatalkan.`;
            break;
    }
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.post('{{ route("backend.contact.bulk-action") }}', {
        action: action,
        ids: selectedIds
    })
    .done(function(response) {
        if (response.success) {
            alert(response.message);
            window.location.reload();
        } else {
            alert('Terjadi kesalahan: ' + response.message);
        }
    })
    .fail(function() {
        alert('Terjadi kesalahan saat memproses permintaan.');
    });
}

function deleteContact(id) {
    if (!confirm('Hapus pesan ini? Tindakan tidak dapat dibatalkan.')) {
        return;
    }
    
    const form = $('<form>', {
        method: 'POST',
        action: `{{ url('backend/contact') }}/${id}`
    });
    
    form.append($('<input>', {
        type: 'hidden',
        name: '_token',
        value: $('meta[name="csrf-token"]').attr('content')
    }));
    
    form.append($('<input>', {
        type: 'hidden',
        name: '_method',
        value: 'DELETE'
    }));
    
    $('body').append(form);
    form.submit();
}

// Initialize bulk buttons state
$(document).ready(function() {
    updateBulkButtons();
});
</script>
@endpush