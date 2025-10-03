@extends('backend.layout.main')

@section('page_title', 'Detail Lokasi')
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.locations.index') }}">Lokasi</a></li>
<li class="breadcrumb-item active">{{ $location->name }}</li>
@endsection

@section('page_actions')
@can('manage.locations')
<a href="{{ route('backend.locations.edit', $location) }}" class="btn btn-warning">
    <i class="fas fa-edit"></i> Edit
</a>
<button class="btn btn-{{ $location->is_active ? 'secondary' : 'success' }}" 
        onclick="toggleStatus({{ $location->id }})">
    <i class="fas fa-{{ $location->is_active ? 'times' : 'check' }}"></i> 
    {{ $location->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
</button>
@endcan
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Location Info -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Lokasi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>Nama</strong></td>
                                    <td>: {{ $location->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe</strong></td>
                                    <td>: <span class="badge badge-primary">{{ $location->type_name }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>: {{ $location->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Koordinat</strong></td>
                                    <td>: 
                                        @if($location->latitude && $location->longitude)
                                            {{ $location->latitude }}, {{ $location->longitude }}
                                            <a href="https://maps.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}" 
                                               target="_blank" class="btn btn-sm btn-info ml-2">
                                                <i class="fas fa-map-marker-alt"></i> Lihat di Peta
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada koordinat</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon</strong></td>
                                    <td>: {{ $location->phone ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>: {{ $location->email ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="120"><strong>Status</strong></td>
                                    <td>: 
                                        <span class="badge {{ $location->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $location->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Peta</strong></td>
                                    <td>: 
                                        <span class="badge {{ $location->show_on_map ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $location->show_on_map ? 'Tampil di Peta' : 'Tidak Tampil' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Icon</strong></td>
                                    <td>: 
                                        <i class="{{ $location->icon }}" style="color: {{ $location->color }}"></i> 
                                        <code>{{ $location->icon }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Warna</strong></td>
                                    <td>: 
                                        <span class="badge" style="background-color: {{ $location->color }}">{{ $location->color }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Urutan</strong></td>
                                    <td>: {{ $location->sort_order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat</strong></td>
                                    <td>: {{ $location->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($location->description)
                    <div class="mt-3">
                        <h5>Deskripsi</h5>
                        <p>{{ $location->description }}</p>
                    </div>
                    @endif

                    @if($location->operating_hours)
                    <div class="mt-3">
                        <h5>Jam Operasional</h5>
                        <div class="row">
                            @foreach(['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'] as $day => $dayName)
                                @if(!empty($location->operating_hours[$day]))
                                    <div class="col-md-3 mb-2">
                                        <strong>{{ $dayName }}:</strong> {{ $location->operating_hours[$day] }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($location->latitude && $location->longitude)
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Peta Lokasi</h3>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>
            </div>
            @endif
        </div>

        <!-- Image and Additional Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Foto Lokasi</h3>
                </div>
                <div class="card-body text-center">
                    @if($location->image_path)
                        <img src="{{ asset($location->image_path) }}" alt="{{ $location->name }}" 
                             class="img-fluid rounded" style="max-height: 300px;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                             style="height: 200px;">
                            <div class="text-center">
                                <i class="fas fa-image text-muted fa-3x"></i>
                                <p class="text-muted mt-2">Tidak ada foto</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Informasi Sistem</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td>Dibuat oleh</td>
                            <td>: {{ $location->creator->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <td>Dibuat pada</td>
                            <td>: {{ $location->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td>Terakhir diubah</td>
                            <td>: {{ $location->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($location->latitude && $location->longitude)
<script>
    function initMap() {
        const location = {lat: {{ $location->latitude }}, lng: {{ $location->longitude }}};
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location,
        });
        const marker = new google.maps.Marker({
            position: location,
            map: map,
            title: "{{ $location->name }}",
        });
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
@endif

<script>
    function toggleStatus(locationId) {
        fetch(`{{ url('admin/locations') }}/${locationId}/toggle-status`, {
            method: 'POST',
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
            alert('Terjadi kesalahan saat mengubah status');
        });
    }
</script>
@endpush