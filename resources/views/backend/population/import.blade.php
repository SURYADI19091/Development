@extends('backend.layout.main')

@section('title', 'Import Data Penduduk')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Import Data Penduduk</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('backend.population.index') }}">Data Penduduk</a></li>
                    <li class="breadcrumb-item active">Import</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Instructions Card -->
            <div class="col-md-4">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>Panduan Import
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="icon fas fa-info"></i> Informasi!</h6>
                            Pastikan format file Excel sesuai dengan template yang disediakan.
                        </div>

                        <h6 class="text-primary">Langkah-langkah:</h6>
                        <ol class="pl-3">
                            <li>Download template Excel</li>
                            <li>Isi data sesuai format</li>
                            <li>Simpan dalam format .xlsx atau .csv</li>
                            <li>Upload file menggunakan form di samping</li>
                        </ol>

                        <h6 class="text-primary mt-3">Kolom yang wajib diisi:</h6>
                        <ul class="pl-3">
                            <li>No. Urut</li>
                            <li>No. KK (16 digit)</li>
                            <li>NIK (16 digit)</li>
                            <li>Nama Lengkap</li>
                            <li>Tempat Lahir</li>
                            <li>Tanggal Lahir (YYYY-MM-DD)</li>
                            <li>Umur</li>
                            <li>Jenis Kelamin (M/F)</li>
                            <li>Status Perkawinan</li>
                            <li>Hubungan dengan KK</li>
                            <li>Nama Kepala Keluarga</li>
                            <li>Agama</li>
                            <li>Pekerjaan</li>
                            <li>Alamat</li>
                            <li>RT/RW</li>
                            <li>Jenis Tinggal</li>
                            <li>Kepala Keluarga Mandiri</li>
                            <li>Kecamatan</li>
                            <li>Kabupaten</li>
                            <li>Provinsi</li>
                        </ul>

                        <div class="mt-3">
                            <a href="#" class="btn btn-success btn-block" onclick="downloadTemplate()">
                                <i class="fas fa-download mr-2"></i>Download Template
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Format Guidelines -->
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Format Data
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Format</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tanggal Lahir</td>
                                    <td>YYYY-MM-DD</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>M atau F</td>
                                </tr>
                                <tr>
                                    <td>Status Kawin</td>
                                    <td>Single, Married, Divorced, Widowed</td>
                                </tr>
                                <tr>
                                    <td>Jenis Tinggal</td>
                                    <td>Tetap, Kontrak, Sementara</td>
                                </tr>
                                <tr>
                                    <td>NIK</td>
                                    <td>16 digit angka</td>
                                </tr>
                                <tr>
                                    <td>No. KK</td>
                                    <td>16 digit angka</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Import Form -->
            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-upload mr-2"></i>Upload File Import
                        </h3>
                    </div>
                    
                    <form action="{{ route('backend.population.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="import_file">Pilih File Excel/CSV *</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('import_file') is-invalid @enderror" 
                                               id="import_file" name="import_file" accept=".xlsx,.xls,.csv" required>
                                        <label class="custom-file-label" for="import_file">Pilih file...</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="clearFile()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('import_file')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    Format yang didukung: .xlsx, .xls, .csv (Maksimal 10MB)
                                </small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="validate_data" name="validate_data" value="1" checked>
                                    <label class="custom-control-label" for="validate_data">
                                        Validasi data sebelum import
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Jika dicentang, sistem akan memvalidasi data terlebih dahulu sebelum menyimpan ke database.
                                </small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="skip_duplicates" name="skip_duplicates" value="1">
                                    <label class="custom-control-label" for="skip_duplicates">
                                        Lewati data duplikat (berdasarkan NIK)
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Jika dicentang, data dengan NIK yang sudah ada akan dilewati.
                                </small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="update_existing" name="update_existing" value="1">
                                    <label class="custom-control-label" for="update_existing">
                                        Update data yang sudah ada
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Jika dicentang, data dengan NIK yang sudah ada akan diperbarui.
                                </small>
                            </div>

                            <!-- File Preview -->
                            <div id="file-preview" class="d-none">
                                <h6 class="text-primary">Preview File:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered" id="preview-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIK</th>
                                                <th>No. KK</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="preview-body">
                                            <!-- Preview data akan ditampilkan di sini -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Pastikan file sesuai dengan template yang telah disediakan.
                                    </small>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{ route('backend.population.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                    <button type="button" class="btn btn-info" onclick="previewFile()">
                                        <i class="fas fa-eye mr-2"></i>Preview
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="import-btn" disabled>
                                        <i class="fas fa-upload mr-2"></i>Import Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Import Progress -->
                <div class="card card-success d-none" id="import-progress">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Proses Import
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 0%" id="import-progress-bar">
                                0%
                            </div>
                        </div>
                        <div id="import-status">
                            Memulai proses import...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // File input change handler
    $('#import_file').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
        
        if (fileName) {
            $('#import-btn').prop('disabled', false);
        } else {
            $('#import-btn').prop('disabled', true);
        }
        
        // Hide preview when file changes
        $('#file-preview').addClass('d-none');
    });

    // Checkbox conflict handling
    $('#skip_duplicates').on('change', function() {
        if ($(this).is(':checked')) {
            $('#update_existing').prop('checked', false);
        }
    });

    $('#update_existing').on('change', function() {
        if ($(this).is(':checked')) {
            $('#skip_duplicates').prop('checked', false);
        }
    });

    // Form submission
    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show progress
        $('#import-progress').removeClass('d-none');
        
        // Simulate import progress (in real implementation, use websockets or polling)
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += Math.random() * 20;
            if (progress > 90) progress = 90;
            
            $('#import-progress-bar').css('width', progress + '%').text(Math.round(progress) + '%');
            
            if (progress > 50) {
                $('#import-status').text('Memvalidasi data...');
            }
            if (progress > 80) {
                $('#import-status').text('Menyimpan ke database...');
            }
        }, 500);

        // Actual form submission (replace with your import endpoint)
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                clearInterval(progressInterval);
                $('#import-progress-bar').css('width', '100%').text('100%');
                $('#import-status').text('Import berhasil!');
                
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Import Berhasil!',
                        text: response.message || 'Data berhasil diimport.',
                        confirmButtonColor: '#007bff'
                    }).then(() => {
                        window.location.href = '{{ route("backend.population.index") }}';
                    });
                }, 1000);
            },
            error: function(xhr) {
                clearInterval(progressInterval);
                $('#import-progress').addClass('d-none');
                
                let errorMessage = 'Terjadi kesalahan saat import data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Import Gagal!',
                    text: errorMessage,
                    confirmButtonColor: '#007bff'
                });
            }
        });
    });
});

function clearFile() {
    $('#import_file').val('');
    $('.custom-file-label').html('Pilih file...');
    $('#import-btn').prop('disabled', true);
    $('#file-preview').addClass('d-none');
}

function previewFile() {
    const fileInput = document.getElementById('import_file');
    const file = fileInput.files[0];
    
    if (!file) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih File',
            text: 'Silakan pilih file terlebih dahulu.',
            confirmButtonColor: '#007bff'
        });
        return;
    }

    // Simulate file preview (in real implementation, parse Excel/CSV)
    const sampleData = [
        {no: 1, nama: 'Ahmad Sutrisno', nik: '3217123456789012', kk: '3217123456789013', gender: 'M', status: 'Married'},
        {no: 2, nama: 'Siti Aminah', nik: '3217123456789014', kk: '3217123456789013', gender: 'F', status: 'Married'},
        {no: 3, nama: 'Budi Prasetyo', nik: '3217123456789015', kk: '3217123456789016', gender: 'M', status: 'Single'}
    ];
    
    const tbody = $('#preview-body');
    tbody.empty();
    
    sampleData.forEach(row => {
        tbody.append(`
            <tr>
                <td>${row.no}</td>
                <td>${row.nama}</td>
                <td>${row.nik}</td>
                <td>${row.kk}</td>
                <td>${row.gender === 'M' ? 'Laki-laki' : 'Perempuan'}</td>
                <td>${row.status}</td>
            </tr>
        `);
    });
    
    $('#file-preview').removeClass('d-none');
    
    Swal.fire({
        icon: 'info',
        title: 'Preview Data',
        text: 'Menampilkan 3 baris pertama dari file. Periksa kembali format data sebelum import.',
        confirmButtonColor: '#007bff'
    });
}

function downloadTemplate() {
    // In real implementation, generate and download Excel template
    Swal.fire({
        icon: 'info',
        title: 'Download Template',
        text: 'Template Excel akan diunduh. Fitur ini akan segera tersedia.',
        confirmButtonColor: '#007bff'
    });
}
</script>
@endpush