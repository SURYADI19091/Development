@extends('backend.layout.main')

@section('title', 'Manajemen Agenda')
@section('header', 'Manajemen Agenda')
@section('description', 'Kelola agenda dan kegiatan desa')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manajemen Agenda</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('backend.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Agenda</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @include('backend.partials.search-filter', [
            'searchPlaceholder' => 'Cari judul, lokasi, atau deskripsi...',
            'filters' => [
                [
                    'name' => 'category',
                    'label' => 'Semua Kategori',
                    'options' => [
                        'rapat' => 'Rapat',
                        'pelayanan' => 'Pelayanan',
                        'olahraga' => 'Olahraga',
                        'gotong_royong' => 'Gotong Royong',
                        'keagamaan' => 'Keagamaan',
                        'pendidikan' => 'Pendidikan',
                        'kesehatan' => 'Kesehatan',
                        'budaya' => 'Budaya'
                    ]
                ],
                [
                    'name' => 'status',
                    'label' => 'Semua Status',
                    'options' => [
                        'draft' => 'Draft',
                        'published' => 'Dipublikasi',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan'
                    ]
                ],
                [
                    'name' => 'month',
                    'label' => 'Semua Bulan',
                    'options' => array_combine(
                        range(1, 12),
                        ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                         'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                    )
                ]
            ],
            'sortOptions' => [
                'event_date' => 'Tanggal Kegiatan',
                'title' => 'Judul',
                'created_at' => 'Tanggal Dibuat',
                'updated_at' => 'Terakhir Diubah'
            ],
            'showStats' => true,
            'stats' => $stats,
            'actionButtons' => [
                [
                    'label' => 'Export',
                    'url' => '#',
                    'class' => 'outline-secondary',
                    'icon' => 'download',
                    'onclick' => 'exportAgenda()',
                    'permission' => 'export-agenda'
                ],
                [
                    'label' => 'Tambah Agenda',
                    'url' => route('backend.agenda.create'),
                    'class' => 'primary',
                    'icon' => 'plus',
                    'permission' => 'create-agenda'
                ]
            ]
        ])

        @component('backend.partials.data-table', [
            'title' => 'Daftar Agenda',
            'icon' => 'calendar-alt',
            'data' => $agendas,
            'paginationInfo' => $paginationInfo,
            'showCheckbox' => true,
            'showActions' => true,
            'headers' => [
                ['label' => 'Agenda', 'field' => 'title', 'sortable' => true],
                ['label' => 'Kategori', 'field' => 'category', 'sortable' => true, 'width' => '120px'],
                ['label' => 'Tanggal & Waktu', 'field' => 'event_date', 'sortable' => true, 'width' => '180px'],
                ['label' => 'Lokasi', 'field' => 'location', 'sortable' => false, 'width' => '150px'],
                ['label' => 'Status', 'field' => 'status', 'sortable' => true, 'width' => '100px'],
            ],
            'actions' => [
                [
                    'route' => 'backend.agenda.show',
                    'icon' => 'eye',
                    'class' => 'info',
                    'title' => 'Lihat Detail',
                    'permission' => 'view-agenda'
                ],
                [
                    'route' => 'backend.agenda.edit',
                    'icon' => 'edit',
                    'class' => 'warning',
                    'title' => 'Edit',
                    'permission' => 'edit-agenda'
                ],
                [
                    'onclick' => 'deleteAgenda',
                    'icon' => 'trash',
                    'class' => 'danger',
                    'title' => 'Hapus',
                    'permission' => 'delete-agenda'
                ]
            ],
            'bulkActions' => [
                [
                    'label' => 'Publikasikan',
                    'onclick' => 'bulkPublish',
                    'icon' => 'eye',
                    'class' => 'success'
                ],
                [
                    'label' => 'Arsipkan',
                    'onclick' => 'bulkArchive',
                    'icon' => 'archive',
                    'class' => 'secondary'
                ],
                [
                    'label' => 'Hapus',
                    'onclick' => 'bulkDelete',
                    'icon' => 'trash',
                    'class' => 'danger'
                ]
            ],
            'emptyTitle' => 'Belum ada agenda',
            'emptyMessage' => 'Belum ada agenda yang ditambahkan ke sistem',
            'emptyIcon' => 'calendar-alt'
        ])
            @foreach($agendas as $agenda)
                <td>
                    <div>
                        <strong>{{ $agenda->title }}</strong>
                        @if($agenda->description)
                            <br><small class="text-muted">{{ Str::limit($agenda->description, 60) }}</small>
                        @endif
                    </div>
                </td>
                <td>
                    <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $agenda->category)) }}</span>
                </td>
                <td>
                    <small>
                        <strong>{{ $agenda->event_date ? \Carbon\Carbon::parse($agenda->event_date)->format('d M Y') : '-' }}</strong><br>
                        {{ $agenda->start_time ? \Carbon\Carbon::parse($agenda->start_time)->format('H:i') : '' }} 
                        @if($agenda->end_time) - {{ \Carbon\Carbon::parse($agenda->end_time)->format('H:i') }} @endif
                    </small>
                </td>
                <td>
                    <small>{{ $agenda->location ?: '-' }}</small>
                </td>
                <td>
                    <span class="badge 
                        @if($agenda->status === 'published') badge-success
                        @elseif($agenda->status === 'draft') badge-secondary
                        @elseif($agenda->status === 'ongoing') badge-warning
                        @elseif($agenda->status === 'completed') badge-primary
                        @else badge-danger
                        @endif">
                        {{ ucfirst($agenda->status) }}
                    </span>
                </td>
            @endforeach
        @endcomponent
    </div>
</section>

<script>
function exportAgenda() {
    const params = new URLSearchParams(window.location.search);
    window.open(`{{ route('backend.agenda.export') }}?${params.toString()}`, '_blank');
}

function deleteAgenda(id) {
    if (confirm('Apakah Anda yakin ingin menghapus agenda ini?')) {
        fetch(`{{ route('backend.agenda.destroy', '') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus agenda');
        });
    }
}

function bulkPublish() {
    if (selectedRows.length === 0) {
        alert('Pilih agenda yang ingin dipublikasikan');
        return;
    }
    
    if (confirm(`Publikasikan ${selectedRows.length} agenda yang dipilih?`)) {
        // Implementation for bulk publish
        console.log('Bulk publish:', selectedRows);
    }
}

function bulkArchive() {
    if (selectedRows.length === 0) {
        alert('Pilih agenda yang ingin diarsipkan');
        return;
    }
    
    if (confirm(`Arsipkan ${selectedRows.length} agenda yang dipilih?`)) {
        // Implementation for bulk archive
        console.log('Bulk archive:', selectedRows);
    }
}

function bulkDelete() {
    if (selectedRows.length === 0) {
        alert('Pilih agenda yang ingin dihapus');
        return;
    }
    
    if (confirm(`Hapus ${selectedRows.length} agenda yang dipilih? Tindakan ini tidak dapat dibatalkan.`)) {
        // Implementation for bulk delete
        console.log('Bulk delete:', selectedRows);
    }
}
</script>
@endsection