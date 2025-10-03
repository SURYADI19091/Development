@extends('backend.layout.main')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .form-section {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #f8f9fa;
    }
    .form-section h6                                     <code>{{ '{' }}{{ '{' }}village_name{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}village_address{{ '}' }}{{ '}' }}</code><br>
                                    <code>{{ '{' }}{{ '{' }}village_phone{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}village_email{{ '}' }}{{ '}' }}</code><br>
                                    <code>{{ '{' }}{{ '{' }}head_name{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}head_nip{{ '}' }}{{ '}' }}</code><br>
                                    <strong>Variabel Umum:</strong><br>
                                    <code>{{ '{' }}{{ '{' }}letter_number{{ '}' }}{{ '}' }}</code>, <code>{{ '{' }}{{ '{' }}current_date{{ '}' }}{{ '}' }}</code>      color: #495057;
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .field-tag {
        background: #e9ecef;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        padding: 0.25rem 0.5rem;
        margin: 0.25rem;
        display: inline-block;
        position: relative;
    }
    .field-tag .remove-tag {
        color: #dc3545;
        margin-left: 0.5rem;
        cursor: pointer;
    }
    .variable-help {
        background: #e7f3ff;
        border: 1px solid #bee5eb;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .template-editor {
        min-height: 400px;
    }
    #editor {
        height: 400px;
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
                <li class="breadcrumb-item active" aria-current="page">Buat Template</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-plus mr-2"></i>Buat Template Surat Baru
                </h5>
                <a href="{{ route('backend.letter-templates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
            </div>

            <form action="{{ route('backend.letter-templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h6><i class="fas fa-info-circle mr-2"></i>Informasi Dasar</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Template <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Contoh: Template Surat Domisili" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Kode Template <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}" 
                                           placeholder="Contoh: DOMISILI_001" required>
                                    <small class="form-text text-muted">Kode unik untuk identifikasi template</small>
                                    @error('code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="letter_type">Jenis Surat <span class="text-danger">*</span></label>
                                    <select class="form-control @error('letter_type') is-invalid @enderror" 
                                            id="letter_type" name="letter_type" required>
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        <option value="domisili" {{ old('letter_type') == 'domisili' ? 'selected' : '' }}>Surat Keterangan Domisili</option>
                                        <option value="usaha" {{ old('letter_type') == 'usaha' ? 'selected' : '' }}>Surat Keterangan Usaha</option>
                                        <option value="tidak_mampu" {{ old('letter_type') == 'tidak_mampu' ? 'selected' : '' }}>Surat Keterangan Tidak Mampu</option>
                                        <option value="penghasilan" {{ old('letter_type') == 'penghasilan' ? 'selected' : '' }}>Surat Keterangan Penghasilan</option>
                                        <option value="pengantar_ktp" {{ old('letter_type') == 'pengantar_ktp' ? 'selected' : '' }}>Surat Pengantar KTP</option>
                                        <option value="pengantar_kk" {{ old('letter_type') == 'pengantar_kk' ? 'selected' : '' }}>Surat Pengantar KK</option>
                                        <option value="pengantar_akta" {{ old('letter_type') == 'pengantar_akta' ? 'selected' : '' }}>Surat Pengantar Akta</option>
                                        <option value="pengantar_nikah" {{ old('letter_type') == 'pengantar_nikah' ? 'selected' : '' }}>Surat Pengantar Nikah</option>
                                        <option value="kelahiran" {{ old('letter_type') == 'kelahiran' ? 'selected' : '' }}>Surat Keterangan Kelahiran</option>
                                        <option value="kematian" {{ old('letter_type') == 'kematian' ? 'selected' : '' }}>Surat Keterangan Kematian</option>
                                        <option value="pindah" {{ old('letter_type') == 'pindah' ? 'selected' : '' }}>Surat Keterangan Pindah</option>
                                        <option value="beda_nama" {{ old('letter_type') == 'beda_nama' ? 'selected' : '' }}>Surat Keterangan Beda Nama</option>
                                        <option value="kehilangan" {{ old('letter_type') == 'kehilangan' ? 'selected' : '' }}>Surat Keterangan Kehilangan</option>
                                        <option value="lainnya" {{ old('letter_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('letter_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">Urutan Tampilan</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                                           min="0" placeholder="0">
                                    <small class="form-text text-muted">Urutan tampilan dalam daftar template</small>
                                    @error('sort_order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi Template</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Deskripsi singkat tentang template ini">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Template Type Selection -->
                    <div class="form-section">
                        <h6><i class="fas fa-file-alt mr-2"></i>Tipe Template</h6>
                        <div class="form-group">
                            <label>Pilih Tipe Template <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="template_type_html" name="template_type" value="html" 
                                               class="custom-control-input" {{ old('template_type', 'html') == 'html' ? 'checked' : '' }} required>
                                        <label class="custom-control-label" for="template_type_html">
                                            <strong>HTML Template</strong><br>
                                            <small class="text-muted">Template berbasis HTML dengan editor visual</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="template_type_word" name="template_type" value="word" 
                                               class="custom-control-input" {{ old('template_type') == 'word' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="template_type_word">
                                            <strong>Word Template</strong><br>
                                            <small class="text-muted">Upload file Word (.docx) dengan bookmark</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('template_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Word Template Upload -->
                        <div id="word_template_section" class="mt-4" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Petunjuk Upload Word Template:</strong><br>
                                1. Buat dokumen Word (.docx) dengan format yang diinginkan<br>
                                2. Tambahkan bookmark di tempat yang akan diganti data (Insert → Bookmark)<br>
                                3. Nama bookmark harus sesuai dengan variabel (contoh: village_name, citizen_name)<br>
                                4. Upload file dan sistem akan otomatis mendeteksi bookmark
                            </div>
                            
                            <div class="form-group">
                                <label for="template_file">Upload File Word Template <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('template_file') is-invalid @enderror" 
                                           id="template_file" name="template_file" accept=".docx,.doc">
                                    <label class="custom-file-label" for="template_file">Pilih file Word...</label>
                                </div>
                                <small class="form-text text-muted">Format: .docx atau .doc, Maksimal 10MB</small>
                                @error('template_file')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Bookmark Detection Result -->
                            <div id="bookmark_detection" class="mt-3" style="display: none;">
                                <div class="alert alert-success">
                                    <h6><i class="fas fa-check-circle mr-2"></i>Bookmark Terdeteksi:</h6>
                                    <div id="detected_bookmarks"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Settings -->
                    <div class="form-section">
                        <h6><i class="fas fa-cog mr-2"></i>Pengaturan Dokumen</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="format">Format Kertas <span class="text-danger">*</span></label>
                                    <select class="form-control @error('format') is-invalid @enderror" 
                                            id="format" name="format" required>
                                        <option value="A4" {{ old('format', 'A4') == 'A4' ? 'selected' : '' }}>A4</option>
                                        <option value="Legal" {{ old('format') == 'Legal' ? 'selected' : '' }}>Legal</option>
                                        <option value="Letter" {{ old('format') == 'Letter' ? 'selected' : '' }}>Letter</option>
                                    </select>
                                    @error('format')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="orientation">Orientasi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('orientation') is-invalid @enderror" 
                                            id="orientation" name="orientation" required>
                                        <option value="portrait" {{ old('orientation', 'portrait') == 'portrait' ? 'selected' : '' }}>Portrait</option>
                                        <option value="landscape" {{ old('orientation') == 'landscape' ? 'selected' : '' }}>Landscape</option>
                                    </select>
                                    @error('orientation')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="header_logo">Logo Kop Surat</label>
                                    <input type="file" class="form-control-file @error('header_logo') is-invalid @enderror" 
                                           id="header_logo" name="header_logo" accept="image/*">
                                    <small class="form-text text-muted">Format: PNG, JPG, JPEG. Maksimal 2MB</small>
                                    @error('header_logo')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Margins -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="margin_top">Margin Atas (cm)</label>
                                    <input type="number" class="form-control" id="margin_top" name="margin_top" 
                                           value="{{ old('margin_top', 2.5) }}" step="0.1" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="margin_bottom">Margin Bawah (cm)</label>
                                    <input type="number" class="form-control" id="margin_bottom" name="margin_bottom" 
                                           value="{{ old('margin_bottom', 2.5) }}" step="0.1" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="margin_left">Margin Kiri (cm)</label>
                                    <input type="number" class="form-control" id="margin_left" name="margin_left" 
                                           value="{{ old('margin_left', 2.5) }}" step="0.1" min="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="margin_right">Margin Kanan (cm)</label>
                                    <input type="number" class="form-control" id="margin_right" name="margin_right" 
                                           value="{{ old('margin_right', 2.5) }}" step="0.1" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Header & Footer -->
                    <div class="form-section">
                        <h6><i class="fas fa-align-center mr-2"></i>Kop Surat & Footer</h6>
                        <div class="form-group">
                            <label for="letter_header">Kop Surat</label>
                            <textarea class="form-control @error('letter_header') is-invalid @enderror" 
                                      id="letter_header" name="letter_header" rows="4" 
                                      placeholder="Contoh: PEMERINTAH DESA [NAMA_DESA]">{{ old('letter_header') }}</textarea>
                            <small class="form-text text-muted">Gunakan variabel seperti {{ '{' }}{{ '{' }}village_name{{ '}' }}{{ '}' }}, {{ '{' }}{{ '{' }}village_address{{ '}' }}{{ '}' }}</small>
                            @error('letter_header')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="letter_footer">Footer Surat</label>
                            <textarea class="form-control @error('letter_footer') is-invalid @enderror" 
                                      id="letter_footer" name="letter_footer" rows="3" 
                                      placeholder="Contoh: Kepala Desa [NAMA_DESA]">{{ old('letter_footer') }}</textarea>
                            <small class="form-text text-muted">Footer yang akan muncul di akhir surat</small>
                            @error('letter_footer')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Template Content -->
                    <div id="html_template_section" class="form-section">
                        <h6><i class="fas fa-file-alt mr-2"></i>Konten Template HTML</h6>
                        
                        <!-- Variable Helper -->
                        <div class="variable-help">
                            <h6 class="text-info">Variabel yang Tersedia:</h6>
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
                        
                        <div class="form-group">
                            <label for="template_content">Konten Template <span class="text-danger">*</span></label>
                            <div id="editor" class="@error('template_content') is-invalid @enderror"></div>
                            <textarea name="template_content" id="template_content" style="display: none;">{{ old('template_content') }}</textarea>
                            @error('template_content')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Fields Configuration -->
                    <div class="form-section">
                        <h6><i class="fas fa-list mr-2"></i>Konfigurasi Field</h6>
                        
                        <div class="form-group">
                            <label for="required_fields_input">Field yang Wajib Diisi</label>
                            <input type="text" class="form-control" id="required_fields_input" 
                                   placeholder="Ketik nama field dan tekan Enter">
                            <small class="form-text text-muted">Field yang wajib diisi saat menggunakan template ini</small>
                            <div id="required_fields_container" class="mt-2"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="variables_input">Variabel Custom</label>
                            <input type="text" class="form-control" id="variables_input" 
                                   placeholder="Ketik nama variabel dan tekan Enter">
                            <small class="form-text text-muted">Variabel tambahan yang dapat digunakan dalam template</small>
                            <div id="variables_container" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-section">
                        <h6><i class="fas fa-toggle-on mr-2"></i>Status & Aktivasi</h6>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Template Aktif
                            </label>
                            <small class="form-text text-muted">Template yang aktif akan muncul dalam pilihan saat membuat surat</small>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('backend.letter-templates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i>Batal
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-info" onclick="previewTemplate()">
                                <i class="fas fa-eye mr-1"></i>Preview
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Simpan Template
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
let quill;
let requiredFields = [];
let variables = [];

$(document).ready(function() {
    // Initialize Quill editor
    quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'align': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Update hidden textarea when quill content changes
    quill.on('text-change', function() {
        document.getElementById('template_content').value = quill.root.innerHTML;
    });

    // Handle template type switching
    $('input[name="template_type"]').change(function() {
        const templateType = $(this).val();
        
        if (templateType === 'word') {
            $('#html_template_section').hide();
            $('#word_template_section').show();
            // Make template_content not required for Word templates
            $('#template_content').removeAttr('required');
        } else {
            $('#html_template_section').show();
            $('#word_template_section').hide();
            $('#template_content').attr('required', 'required');
        }
    });

    // Handle Word file upload and bookmark detection
    $('#template_file').change(function() {
        const file = this.files[0];
        if (file) {
            // Update file label
            $(this).next('.custom-file-label').text(file.name);
            
            // Extract bookmarks via AJAX
            const formData = new FormData();
            formData.append('template_file', file);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            
            $.ajax({
                url: '{{ route("backend.letter-templates.extract-bookmarks") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success && response.bookmarks.length > 0) {
                        $('#bookmark_detection').show();
                        let bookmarksHtml = '<div class="row">';
                        response.bookmarks.forEach(bookmark => {
                            bookmarksHtml += `<div class="col-md-4 mb-2"><span class="badge badge-primary">${bookmark}</span></div>`;
                        });
                        bookmarksHtml += '</div>';
                        $('#detected_bookmarks').html(bookmarksHtml);
                    } else {
                        $('#bookmark_detection').hide();
                        toastr.warning('Tidak ada bookmark yang terdeteksi dalam file Word.');
                    }
                },
                error: function() {
                    toastr.error('Gagal mengekstrak bookmark dari file Word.');
                }
            });
        }
    });

    // Auto generate code from name
    $('#name').on('input', function() {
        const name = $(this).val();
        const code = name.toUpperCase()
                        .replace(/[^A-Z0-9\s]/g, '')
                        .replace(/\s+/g, '_')
                        .substring(0, 20);
        $('#code').val(code);
    });

    // Handle required fields input
    $('#required_fields_input').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const field = $(this).val().trim();
            if (field && !requiredFields.includes(field)) {
                requiredFields.push(field);
                updateFieldsDisplay('required_fields_container', requiredFields, 'required_fields');
                $(this).val('');
            }
        }
    });

    // Handle variables input
    $('#variables_input').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const variable = $(this).val().trim();
            if (variable && !variables.includes(variable)) {
                variables.push(variable);
                updateFieldsDisplay('variables_container', variables, 'variables');
                $(this).val('');
            }
        }
    });

    // Load old values if any
    @if(old('required_fields'))
        requiredFields = @json(old('required_fields'));
        updateFieldsDisplay('required_fields_container', requiredFields, 'required_fields');
    @endif

    @if(old('variables'))
        variables = @json(old('variables'));
        updateFieldsDisplay('variables_container', variables, 'variables');
    @endif

    // Set initial quill content
    @if(old('template_content'))
        quill.root.innerHTML = {!! json_encode(old('template_content')) !!};
    @endif

    // Initialize template type display
    const initialType = $('input[name="template_type"]:checked').val();
    if (initialType === 'word') {
        $('#html_template_section').hide();
        $('#word_template_section').show();
    }

    // Form validation before submit
    $('#templateForm').submit(function(e) {
        const templateType = $('input[name="template_type"]:checked').val();
        
        if (templateType === 'html') {
            // Ensure template content is not empty for HTML
            document.getElementById('template_content').value = quill.root.innerHTML;
            if (!quill.root.innerText.trim()) {
                e.preventDefault();
                toastr.error('Konten template HTML tidak boleh kosong.');
                return false;
            }
        } else if (templateType === 'word') {
            // Ensure Word file is uploaded
            const templateFile = $('#template_file').get(0).files[0];
            if (!templateFile) {
                e.preventDefault();
                toastr.error('File Word template harus diupload.');
                return false;
            }
        }
    });
});

function updateFieldsDisplay(containerId, fields, inputName) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    
    fields.forEach((field, index) => {
        const tag = document.createElement('span');
        tag.className = 'field-tag';
        tag.innerHTML = `
            ${field}
            <span class="remove-tag" onclick="removeField('${inputName}', ${index})">×</span>
            <input type="hidden" name="${inputName}[]" value="${field}">
        `;
        container.appendChild(tag);
    });
}

function removeField(type, index) {
    if (type === 'required_fields') {
        requiredFields.splice(index, 1);
        updateFieldsDisplay('required_fields_container', requiredFields, 'required_fields');
    } else if (type === 'variables') {
        variables.splice(index, 1);
        updateFieldsDisplay('variables_container', variables, 'variables');
    }
}

function previewTemplate() {
    // Update hidden textarea
    document.getElementById('template_content').value = quill.root.innerHTML;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.target = '_blank';
    form.action = '{{ route("backend.letter-templates.preview", ["letterTemplate" => "temp"]) }}';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfInput);
    
    // Add template content
    const contentInput = document.createElement('input');
    contentInput.type = 'hidden';
    contentInput.name = 'template_content';
    contentInput.value = quill.root.innerHTML;
    form.appendChild(contentInput);
    
    // Add other form data
    const formData = new FormData(document.querySelector('form'));
    for (let [key, value] of formData.entries()) {
        if (key !== 'template_content') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
    }
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endpush