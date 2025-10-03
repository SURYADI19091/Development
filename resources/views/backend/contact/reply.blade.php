@extends('backend.layout.main')

@push('styles')
<style>
    .contact-detail-card {
        border-left: 4px solid #28a745;
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
        max-height: 200px;
        overflow-y: auto;
    }
    .reply-form {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
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
                <li class="breadcrumb-item"><a href="{{ route('backend.contact.show', $contact) }}">Detail Pesan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Balas Pesan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Reply Form -->
        <div class="card contact-detail-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-reply mr-2"></i>Balas Pesan dari {{ $contact->name }}
                </h5>
            </div>

            <form action="{{ route('backend.contact.store-reply', $contact) }}" method="POST">
                @csrf
                
                <div class="card-body">
                    <!-- Original Message Preview -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-2">PESAN ASLI:</h6>
                        <div class="d-flex align-items-start mb-2 p-3" style="background: #f1f3f4; border-radius: 0.25rem;">
                            <div class="avatar-circle bg-primary text-white mr-3 d-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px; border-radius: 50%; font-size: 14px; font-weight: bold;">
                                {{ strtoupper(substr($contact->name, 0, 2)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $contact->name }} &lt;{{ $contact->email }}&gt;</div>
                                <div class="text-muted small mb-2">
                                    {{ $contact->created_at->format('d M Y, H:i') }} WIB • {{ $contact->subject }}
                                </div>
                                <div class="message-content bg-white">
                                    {!! nl2br(e($contact->message)) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reply Form -->
                    <div class="reply-form p-4">
                        <div class="form-group">
                            <label for="admin_reply" class="fw-bold">
                                <i class="fas fa-pen mr-1"></i>Tulis Balasan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('admin_reply') is-invalid @enderror" 
                                      id="admin_reply" name="admin_reply" rows="8" 
                                      placeholder="Tulis balasan untuk {{ $contact->name }}..." required>{{ old('admin_reply') }}</textarea>
                            @error('admin_reply')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Minimal 10 karakter. Gunakan bahasa yang sopan dan profesional.
                            </div>
                        </div>

                        <!-- Email Template Helper -->
                        <div class="form-group">
                            <label class="fw-bold text-muted">Template Balasan Cepat:</label>
                            <div class="btn-group-vertical w-100">
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-1" 
                                        onclick="insertTemplate('thankyou')">
                                    <i class="fas fa-heart mr-1"></i>Terima Kasih
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-1" 
                                        onclick="insertTemplate('info')">
                                    <i class="fas fa-info mr-1"></i>Memberikan Informasi
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-1" 
                                        onclick="insertTemplate('followup')">
                                    <i class="fas fa-arrow-right mr-1"></i>Tindak Lanjut
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" 
                                        onclick="insertTemplate('sorry')">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Permintaan Maaf
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <a href="{{ route('backend.contact.show', $contact) }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i>Batal
                            </a>
                            <a href="{{ route('backend.contact.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar
                            </a>
                        </div>
                        
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary" onclick="previewReply()">
                                <i class="fas fa-eye mr-1"></i>Preview
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane mr-1"></i>Kirim Balasan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Contact Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user mr-2"></i>Informasi Pengirim
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-circle bg-primary text-white mx-auto d-flex align-items-center justify-content-center" 
                         style="width: 64px; height: 64px; border-radius: 50%; font-size: 24px; font-weight: bold;">
                        {{ strtoupper(substr($contact->name, 0, 2)) }}
                    </div>
                </div>
                
                <div class="text-center">
                    <h6 class="fw-bold">{{ $contact->name }}</h6>
                    <p class="text-muted mb-2">{{ $contact->email }}</p>
                    @if($contact->phone)
                        <p class="text-muted mb-3">
                            <i class="fas fa-phone fa-sm mr-1"></i>{{ $contact->phone }}
                        </p>
                    @endif
                    
                    <span class="status-badge {{ $contact->status_badge }} mb-3 d-inline-block">
                        {{ $contact->status_text }}
                    </span>
                </div>

                <hr>

                <div class="small text-muted">
                    <div class="mb-2">
                        <i class="fas fa-calendar mr-1"></i>
                        Dikirim: {{ $contact->created_at->format('d M Y, H:i') }} WIB
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-tag mr-1"></i>
                        Subjek: {{ $contact->subject }}
                    </div>
                    @if($contact->ip_address)
                        <div class="mb-2">
                            <i class="fas fa-globe mr-1"></i>
                            IP: {{ $contact->ip_address }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-lightbulb mr-2"></i>Tips Membalas
                </h6>
            </div>
            <div class="card-body">
                <div class="small">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-1"></i>
                            Gunakan bahasa yang sopan dan profesional
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-1"></i>
                            Jawab pertanyaan dengan jelas dan lengkap
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-1"></i>
                            Berikan informasi kontak jika diperlukan
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-1"></i>
                            Tutup dengan salam dan terima kasih
                        </li>
                        <li>
                            <i class="fas fa-check text-success mr-1"></i>
                            Periksa kembali sebelum mengirim
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye mr-2"></i>Preview Balasan
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-3" style="background: #f8f9fa; border-radius: 0.25rem;">
                    <div class="fw-bold mb-2">Kepada: {{ $contact->name }} &lt;{{ $contact->email }}&gt;</div>
                    <div class="fw-bold mb-3">Re: {{ $contact->subject }}</div>
                    <hr>
                    <div id="preview-content" class="mb-3" style="line-height: 1.6;"></div>
                    <hr>
                    <div class="text-muted small">
                        Salam,<br>
                        {{ auth()->user()->name }}<br>
                        {{ config('app.name', 'Admin Desa') }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="$('#admin_reply').focus()">
                    <i class="fas fa-edit mr-1"></i>Edit Balasan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const templates = {
    thankyou: `Terima kasih atas pesan yang Anda sampaikan kepada kami.

Kami sangat menghargai partisipasi dan perhatian Anda terhadap pelayanan desa. Masukan Anda sangat bermanfaat bagi kami untuk terus meningkatkan kualitas pelayanan.

Jika ada hal lain yang dapat kami bantu, jangan ragu untuk menghubungi kami kembali.`,

    info: `Terima kasih atas pertanyaan yang Anda sampaikan.

Berikut informasi yang dapat kami berikan:

[SILAKAN ISI INFORMASI DI SINI]

Jika masih ada yang kurang jelas atau ada pertanyaan lanjutan, silakan hubungi kami kembali.`,

    followup: `Terima kasih atas pesan Anda.

Terkait dengan hal yang Anda sampaikan, kami akan melakukan tindak lanjut sebagai berikut:

[SILAKAN ISI TINDAK LANJUT DI SINI]

Kami akan memberitahukan perkembangannya kepada Anda segera setelah ada informasi lebih lanjut.`,

    sorry: `Terima kasih atas pesan yang Anda sampaikan.

Kami mohon maaf atas ketidaknyamanan yang Anda alami. Hal ini tentu bukan yang kami inginkan dan kami akan segera memperbaikinya.

[SILAKAN ISI PENJELASAN DAN SOLUSI DI SINI]

Sekali lagi kami mohon maaf dan terima kasih atas pengertian Anda.`
};

function insertTemplate(type) {
    const textarea = document.getElementById('admin_reply');
    const template = templates[type];
    
    if (textarea.value.trim() === '') {
        textarea.value = template;
    } else {
        if (confirm('Ini akan mengganti isi balasan yang sudah ada. Lanjutkan?')) {
            textarea.value = template;
        }
    }
    
    textarea.focus();
}

function previewReply() {
    const replyContent = document.getElementById('admin_reply').value;
    
    if (replyContent.trim() === '') {
        alert('Silakan tulis balasan terlebih dahulu.');
        return;
    }
    
    document.getElementById('preview-content').innerHTML = replyContent.replace(/\n/g, '<br>');
    $('#previewModal').modal('show');
}

// Character counter
$(document).ready(function() {
    const textarea = $('#admin_reply');
    const minLength = 10;
    
    textarea.on('input', function() {
        const currentLength = $(this).val().length;
        let feedback = $(this).siblings('.invalid-feedback');
        
        if (feedback.length === 0) {
            feedback = $('<div class="invalid-feedback"></div>');
            $(this).after(feedback);
        }
        
        if (currentLength < minLength) {
            $(this).addClass('is-invalid');
            feedback.text(`Minimal ${minLength} karakter (saat ini: ${currentLength})`);
        } else {
            $(this).removeClass('is-invalid');
            feedback.text('');
        }
    });
});
</script>
@endpush