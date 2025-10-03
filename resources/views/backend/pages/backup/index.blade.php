@extends('backend.layout.main')

@section('title', 'Backup Data')
@section('page_title', 'Backup Data')
@section('header_icon', 'fas fa-database')
@section('header_bg_color', 'bg-warning')

@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Backup</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-database mr-2"></i>
                        Backup & Restore Data
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" id="create-backup-btn">
                            <i class="fas fa-plus"></i> Buat Backup
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Backup Information -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-hdd"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Backup</span>
                                    <span class="info-box-number" id="total-backups">{{ $statistics['total_backups'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-calendar-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Backup Terakhir</span>
                                    <span class="info-box-number" id="last-backup">
                                        {{ $statistics['last_backup'] ? $statistics['last_backup']->format('d/m/Y') : 'Belum ada' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning">
                                    <i class="fas fa-folder-open"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Ukuran Total</span>
                                    <span class="info-box-number" id="total-size">{{ $statistics['total_size_formatted'] ?? '0 B' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Backup Options -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-download text-primary"></i>
                                        Buat Backup Baru
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form id="backup-form">
                                        <div class="form-group">
                                            <label>Pilih Data yang Akan di-Backup:</label>
                                            
                                            <!-- Select All Option -->
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" value="all" id="backup-all">
                                                <label class="form-check-label font-weight-bold text-primary" for="backup-all">
                                                    <i class="fas fa-database"></i> Backup Semua Data
                                                </label>
                                            </div>
                                            
                                            <hr class="my-3">
                                            
                                            <!-- User Management -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-2"><i class="fas fa-users"></i> Manajemen Pengguna</h6>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="users" id="backup-users" checked>
                                                        <label class="form-check-label" for="backup-users">
                                                            Data Pengguna & Autentikasi
                                                        </label>
                                                    </div>
                                                    
                                                    <h6 class="text-muted mb-2 mt-3"><i class="fas fa-map-marker-alt"></i> Lokasi & Geografi</h6>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="locations" id="backup-locations" checked>
                                                        <label class="form-check-label" for="backup-locations">
                                                            Data Lokasi
                                                        </label>
                                                    </div>
                                                    
                                                    <h6 class="text-muted mb-2 mt-3"><i class="fas fa-newspaper"></i> Konten & Berita</h6>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="news" id="backup-news" checked>
                                                        <label class="form-check-label" for="backup-news">
                                                            Data Berita
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="content" id="backup-content" checked>
                                                        <label class="form-check-label" for="backup-content">
                                                            Konten (Halaman, Pengumuman, Banner, Galeri)
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-2"><i class="fas fa-home"></i> Data Desa</h6>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="village" id="backup-village" checked>
                                                        <label class="form-check-label" for="backup-village">
                                                            Profil Desa, Penduduk, Anggaran
                                                        </label>
                                                    </div>
                                                    
                                                    <h6 class="text-muted mb-2 mt-3"><i class="fas fa-briefcase"></i> Layanan & Bisnis</h6>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="services" id="backup-services" checked>
                                                        <label class="form-check-label" for="backup-services">
                                                            Layanan Desa & Surat
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="business" id="backup-business" checked>
                                                        <label class="form-check-label" for="backup-business">
                                                            UMKM & Bisnis
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="tourism" id="backup-tourism" checked>
                                                        <label class="form-check-label" for="backup-tourism">
                                                            Data Wisata
                                                        </label>
                                                    </div>
                                                    
                                                    <h6 class="text-muted mb-2 mt-3"><i class="fas fa-cogs"></i> Sistem</h6>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="settings" id="backup-settings" checked>
                                                        <label class="form-check-label" for="backup-settings">
                                                            Pengaturan & Permissions
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input backup-category" type="checkbox" value="system" id="backup-system">
                                                        <label class="form-check-label" for="backup-system">
                                                            Tabel Sistem (Migrasi, Jobs, Cache)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Backup Preview -->
                                        <div id="backup-preview" class="mt-3" style="display: none;">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle"></i> Preview Backup</h6>
                                                <div id="preview-content">
                                                    <!-- Preview content will be loaded here -->
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-info" id="preview-btn">
                                                <i class="fas fa-eye"></i> Preview
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-download"></i> Buat Backup
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-upload text-success"></i>
                                        Restore Data
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form id="restore-form" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="restore-file">Pilih File Backup:</label>
                                            <input type="file" class="form-control-file" id="restore-file" accept=".sql,.zip,.gz">
                                            <small class="form-text text-muted">
                                                Format yang didukung: .sql, .zip, .gz
                                            </small>
                                        </div>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <strong>Peringatan:</strong> Proses restore akan mengganti data yang ada. Pastikan Anda sudah membuat backup terlebih dahulu.
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-upload"></i> Restore Data
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Backup History -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-history text-info"></i>
                                Riwayat Backup
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="backup-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama File</th>
                                            <th>Tanggal</th>
                                            <th>Ukuran</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($backups ?? [] as $index => $backup)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <i class="fas fa-file-archive text-primary mr-2"></i>
                                                    {{ $backup['name'] }}
                                                </td>
                                                <td>
                                                    <small>{{ $backup['created_at']->format('d/m/Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        @php
                                                            $size = $backup['size'];
                                                            $units = ['B', 'KB', 'MB', 'GB'];
                                                            $unitIndex = 0;
                                                            while ($size >= 1024 && $unitIndex < count($units) - 1) {
                                                                $size /= 1024;
                                                                $unitIndex++;
                                                            }
                                                            echo round($size, 2) . ' ' . $units[$unitIndex];
                                                        @endphp
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Tersedia
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-info btn-sm" onclick="downloadBackup('{{ $backup['name'] }}')" title="Download">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteBackup('{{ $backup['name'] }}')" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                                    <p>Belum ada data backup</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Handle "Select All" checkbox
    document.getElementById('backup-all').addEventListener('change', function() {
        const categoryCheckboxes = document.querySelectorAll('.backup-category');
        const isChecked = this.checked;
        
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
            checkbox.disabled = isChecked;
        });
    });

    // Handle category checkboxes
    document.querySelectorAll('.backup-category').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allCheckbox = document.getElementById('backup-all');
            const categoryCheckboxes = document.querySelectorAll('.backup-category');
            const checkedCategories = document.querySelectorAll('.backup-category:checked');
            
            // If all categories are checked, check "Select All"
            if (checkedCategories.length === categoryCheckboxes.length) {
                allCheckbox.checked = true;
            } else {
                allCheckbox.checked = false;
            }
        });
    });

    // Preview backup functionality
    document.getElementById('preview-btn').addEventListener('click', function() {
        const formData = new FormData();
        const allCheckbox = document.getElementById('backup-all');
        let backupTypes = [];
        
        if (allCheckbox.checked) {
            backupTypes = ['all'];
        } else {
            const categoryCheckboxes = document.querySelectorAll('.backup-category:checked');
            backupTypes = Array.from(categoryCheckboxes).map(cb => cb.value);
        }
        
        if (backupTypes.length === 0) {
            alert('Pilih minimal satu jenis data untuk preview');
            return;
        }
        
        formData.append('_token', csrfToken);
        backupTypes.forEach(type => {
            formData.append('backup_types[]', type);
        });
        
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        btn.disabled = true;
        
        fetch('{{ route("backend.backup.preview") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showBackupPreview(data.data);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat preview');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });

    // Show backup preview
    function showBackupPreview(data) {
        const previewDiv = document.getElementById('backup-preview');
        const contentDiv = document.getElementById('preview-content');
        
        let html = `
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Total Tabel:</strong> ${data.total_tables}
                </div>
                <div class="col-md-4">
                    <strong>Total Baris:</strong> ${data.total_rows.toLocaleString()}
                </div>
                <div class="col-md-4">
                    <strong>Estimasi Waktu:</strong> ${data.estimated_time}
                </div>
            </div>
            <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Tabel</th>
                            <th>Jumlah Baris</th>
                            <th>Ukuran (KB)</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        data.tables.forEach(table => {
            const rowClass = table.error ? 'table-warning' : '';
            const rows = table.error ? 'Error' : table.rows.toLocaleString();
            const size = table.size_estimate ? table.size_estimate + ' KB' : '-';
            
            html += `
                <tr class="${rowClass}">
                    <td>${table.table}</td>
                    <td>${rows}</td>
                    <td>${size}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        contentDiv.innerHTML = html;
        previewDiv.style.display = 'block';
    }

    // Backup functionality
    document.getElementById('backup-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const allCheckbox = document.getElementById('backup-all');
        let backupTypes = [];
        
        if (allCheckbox.checked) {
            backupTypes = ['all'];
        } else {
            const categoryCheckboxes = this.querySelectorAll('.backup-category:checked');
            backupTypes = Array.from(categoryCheckboxes).map(cb => cb.value);
        }
        
        if (backupTypes.length === 0) {
            alert('Pilih minimal satu jenis data untuk di-backup');
            return;
        }
        
        formData.append('_token', csrfToken);
        backupTypes.forEach(type => {
            formData.append('backup_types[]', type);
        });
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat Backup...';
        submitBtn.disabled = true;
        
        fetch('{{ route("backend.backup.create") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup berhasil dibuat: ' + data.filename);
                loadBackupHistory();
                loadStatistics();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membuat backup');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Restore functionality
    document.getElementById('restore-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('restore-file');
        if (!fileInput.files[0]) {
            alert('Pilih file backup terlebih dahulu');
            return;
        }
        
        if (!confirm('Apakah Anda yakin ingin melakukan restore? Ini akan mengganti data yang ada.')) {
            return;
        }
        
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('backup_file', fileInput.files[0]);
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Restoring...';
        submitBtn.disabled = true;
        
        fetch('{{ route("backend.backup.restore") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil dipulihkan dari backup!');
                fileInput.value = '';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat melakukan restore');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Quick backup button
    document.getElementById('create-backup-btn').addEventListener('click', function() {
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('backup_types[]', 'all');
        
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
        btn.disabled = true;
        
        fetch('{{ route("backend.backup.create") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup lengkap berhasil dibuat: ' + data.filename);
                loadBackupHistory();
                loadStatistics();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membuat backup');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });

    // Load backup statistics
    function loadStatistics() {
        fetch('{{ route("backend.backup.statistics") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.data;
                document.getElementById('total-backups').textContent = stats.total_backups;
                document.getElementById('last-backup').textContent = stats.last_backup ? 
                    new Date(stats.last_backup).toLocaleDateString('id-ID') : 'Belum ada';
                document.getElementById('total-size').textContent = stats.total_size_formatted || '0 B';
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
        });
    }

    // Load backup history
    function loadBackupHistory() {
        // This would typically load backup files via AJAX
        // For now, we'll reload the page to get updated data
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    // Download backup
    function downloadBackup(filename) {
        window.location.href = '{{ url("admin/backup") }}/' + filename + '/download';
    }

    // Delete backup
    function deleteBackup(filename) {
        if (!confirm('Apakah Anda yakin ingin menghapus backup ini?')) {
            return;
        }
        
        fetch('{{ url("admin/backup") }}/' + filename, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup berhasil dihapus');
                loadBackupHistory();
                loadStatistics();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus backup');
        });
    }

    // Toggle all backup categories
    function toggleAllBackup() {
        const allCheckbox = document.getElementById('backup-all');
        const categoryCheckboxes = document.querySelectorAll('.backup-category');
        
        if (allCheckbox.checked) {
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.disabled = true;
            });
        } else {
            categoryCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;
            });
        }
    }

    // Preview backup function
    function previewBackup() {
        const checkboxes = document.querySelectorAll('input[name="backup_types[]"]:checked, #backup-all:checked');
        const backupTypes = Array.from(checkboxes).map(cb => cb.value);
        
        if (backupTypes.length === 0) {
            alert('Pilih minimal satu kategori untuk preview');
            return;
        }

        const formData = new FormData();
        formData.append('_token', csrfToken);
        backupTypes.forEach(type => {
            formData.append('backup_types[]', type);
        });

        fetch('{{ route("backend.backup.preview") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPreviewModal(data.data);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat preview');
        });
    }

    // Show preview modal
    function showPreviewModal(data) {
        let modalContent = `
            <div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Preview Backup</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <strong>Total Tabel:</strong> ${data.total_tables} | 
                                <strong>Total Data:</strong> ${data.total_rows.toLocaleString()} records
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama Tabel</th>
                                            <th>Jumlah Data</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
        `;
        
        data.tables.forEach(table => {
            modalContent += `
                <tr>
                    <td>${table.name}</td>
                    <td>${table.rows.toLocaleString()}</td>
                    <td>
                        ${table.exists ? 
                            '<span class="badge badge-success">OK</span>' : 
                            '<span class="badge badge-warning">Tidak ada</span>'
                        }
                    </td>
                </tr>
            `;
        });
        
        modalContent += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" onclick="$('#previewModal').modal('hide'); document.getElementById('backup-form').dispatchEvent(new Event('submit'));">
                                Lanjutkan Backup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        const existingModal = document.getElementById('previewModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalContent);
        
        // Show modal
        $('#previewModal').modal('show');
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        loadStatistics();
        
        // Add preview button to backup form
        const backupForm = document.getElementById('backup-form');
        const submitBtn = backupForm.querySelector('button[type="submit"]');
        
        const previewBtn = document.createElement('button');
        previewBtn.type = 'button';
        previewBtn.className = 'btn btn-info mr-2';
        previewBtn.innerHTML = '<i class="fas fa-eye"></i> Preview';
        previewBtn.onclick = previewBackup;
        
        submitBtn.parentNode.insertBefore(previewBtn, submitBtn);
    });
</script>
@endpush