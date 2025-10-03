@extends('backend.layout.main')

@push('styles')
<style>
    .template-info {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .info-item {
        margin-bottom: 0.75rem;
    }
    .info-label {
        font-weight: 600;
        color: #495057;
        margin-right: 0.5rem;
    }
    .field-tag {
        background: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        margin: 0.25rem;
        display: inline-block;
        font-size: 0.875rem;
    }
    .template-content {
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 2rem;
        margin: 1.5rem 0;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        min-height: 500px;
        font-family: 'Times New Roman', serif;
        line-height: 1.6;
    }
    .template-header {
        text-align: center;
        border-bottom: 2px solid #000;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }
    .template-footer {
        margin-top: 3rem;
        padding-top: 1rem;
    }
    .logo-preview {
        max-width: 200px;
        max-height: 100px;
        margin-bottom: 1rem;
    }
    .status-badge {
        font-size: 0.875rem;
    }
    .variable-list {
        background: #e7f3ff;
        border: 1px solid #bee5eb;
        border-radius: 0.25rem;
        padding: 1rem;
        margin: 1rem 0;
    }
    .action-buttons {
        position: sticky;
        top: 20px;
        z-index: 100;
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
                <li class="breadcrumb-item"><a href="{{ route('backend.letter-templates.index') }}">Template Surat</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $letterTemplate->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Template Info Sidebar -->
    <div class="col-lg-4">
        <div class="action-buttons">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-cog mr-2"></i>Aksi Template</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('letter_templates.edit')
                        <a href="{{ route('backend.letter-templates.edit', $letterTemplate->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i>Edit Template
                        </a>
                        @endcan
                        
                        @can('letter_templates.create')
                        <form action="{{ route('backend.letter-templates.duplicate', $letterTemplate->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-info w-100" onclick="return confirm('Yakin ingin menduplikasi template ini?')">
                                <i class="fas fa-copy mr-1"></i>Duplikasi Template
                            </button>
                        </form>
                        @endcan
                        
                        <button type="button" class="btn btn-success" onclick="previewTemplate()">
                            <i class="fas fa-eye mr-1"></i>Preview PDF
                        </button>
                        
                        <a href="{{ route('backend.letter-templates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar
                        </a>
                        
                        @can('letter_templates.delete')
                        <form action="{{ route('backend.letter-templates.destroy', $letterTemplate->id) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus template ini?')" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash mr-1"></i>Hapus Template
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Template Information -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Informasi Template</h6>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <span class="info-label">Nama:</span>
                        <span>{{ $letterTemplate->name }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Kode:</span>
                        <code>{{ $letterTemplate->code }}</code>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Jenis Surat:</span>
                        <span class="badge badge-info">{{ $letterTemplate->letter_type_label }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="badge {{ $letterTemplate->is_active ? 'badge-success' : 'badge-secondary' }}">
                            {{ $letterTemplate->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Format:</span>
                        <span>{{ $letterTemplate->format }} - {{ ucfirst($letterTemplate->orientation) }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Urutan:</span>
                        <span>{{ $letterTemplate->sort_order }}</span>
                    </div>
                    
                    @if($letterTemplate->description)
                    <div class="info-item">
                        <span class="info-label">Deskripsi:</span>
                        <p class="mb-0">{{ $letterTemplate->description }}</p>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <span class="info-label">Dibuat:</span>
                        <small class="text-muted">
                            {{ $letterTemplate->created_at->format('d/m/Y H:i') }}<br>
                            oleh {{ $letterTemplate->creator->name ?? 'System' }}
                        </small>
                    </div>
                    
                    @if($letterTemplate->updated_at != $letterTemplate->created_at)
                    <div class="info-item">
                        <span class="info-label">Diperbarui:</span>
                        <small class="text-muted">
                            {{ $letterTemplate->updated_at->format('d/m/Y H:i') }}<br>
                            oleh {{ $letterTemplate->updater->name ?? 'System' }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Logo Preview -->
            @if($template->header_logo)
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-image mr-2"></i>Logo Kop Surat</h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ Storage::url($letterTemplate->header_logo) }}" alt="Logo" class="logo-preview img-fluid">
                </div>
            </div>
            @endif

            <!-- Fields & Variables -->
            @if(count($letterTemplate->required_fields) > 0 || count($letterTemplate->variables) > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-list mr-2"></i>Konfigurasi Field</h6>
                </div>
                <div class="card-body">
                    @if(count($letterTemplate->required_fields) > 0)
                    <div class="mb-3">
                        <strong>Field Wajib:</strong><br>
                        @foreach($letterTemplate->required_fields as $field)
                            <span class="field-tag">{{ $field }}</span>
                        @endforeach
                    </div>
                    @endif
                    
                    @if(count($letterTemplate->variables) > 0)
                    <div>
                        <strong>Variabel Custom:</strong><br>
                        @foreach($letterTemplate->variables as $variable)
                            <span class="field-tag">{{ $variable }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Template Content -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt mr-2"></i>Preview Template: {{ $letterTemplate->name }}
                </h5>
                <span class="badge {{ $letterTemplate->is_active ? 'badge-success' : 'badge-secondary' }} status-badge">
                    {{ $letterTemplate->is_active ? 'AKTIF' : 'NONAKTIF' }}
                </span>
            </div>

            <div class="card-body p-0">
                <div class="template-content">
                    <!-- Header -->
                    @if($letterTemplate->letter_header || $letterTemplate->header_logo)
                    <div class="template-header">
                        @if($letterTemplate->header_logo)
                            <img src="{{ Storage::url($letterTemplate->header_logo) }}" alt="Logo" style="max-height: 80px; margin-bottom: 1rem;">
                        @endif
                        @if($letterTemplate->letter_header)
                            {!! nl2br($letterTemplate->letter_header) !!}
                        @endif
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="template-body">
                        {!! $letterTemplate->template_content !!}
                    </div>

                    <!-- Footer -->
                    @if($letterTemplate->letter_footer)
                    <div class="template-footer">
                        {!! nl2br($letterTemplate->letter_footer) !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Variable Helper -->
        <div class="variable-list mt-3">
            <h6 class="text-info"><i class="fas fa-code mr-2"></i>Variabel yang Tersedia dalam Template</h6>
            <div class="row">
                <div class="col-md-6">
                    <strong>Data Pemohon:</strong><br>
                    <code>{{ '{' }}{{ '{' }}full_name{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}nik{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}birth_place{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}birth_date{{ '}' }}{{ '}' }}</code><br>
                    <code>{{ '{' }}{{ '{' }}gender{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}religion{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}marital_status{{ '}' }}{{ '}' }}</code><br>
                    <code>{{ '{' }}{{ '{' }}occupation{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}address{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}rt{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}rw{{ '}' }}{{ '}' }}</code><br>
                    <code>{{ '{' }}{{ '{' }}phone{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}email{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}purpose{{ '}' }}{{ '}' }}</code>
                </div>
                <div class="col-md-6">
                    <strong>Data Desa:</strong><br>
                    <code>{{ '{' }}{{ '{' }}village_name{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}village_address{{ '}' }}{{ '}' }}</code><br>
                    <code>{{ '{' }}{{ '{' }}village_phone{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}village_email{{ '}' }}{{ '}' }}</code><br>
                    <code>{{ '{' }}{{ '{' }}head_name{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}head_nip{{ '}' }}{{ '}' }}</code><br>
                    <strong>Lainnya:</strong><br>
                    <code>{{ '{' }}{{ '{' }}letter_number{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}current_date{{ '}' }}{{ '}' }}</code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewTemplate() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.target = '_blank';
    form.action = '{{ route("backend.letter-templates.preview", $letterTemplate->id) }}';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Auto-scroll to top when page loads
$(document).ready(function() {
    window.scrollTo(0, 0);
});
</script>
@endpush