@extends('backend.layout.main')

@section('page_title', $news->title)
@section('breadcrumb')
<li class="breadcrumb-item">Admin</li>
<li class="breadcrumb-item"><a href="{{ route('backend.news.index') }}">Berita</a></li>
<li class="breadcrumb-item active">{{ $news->title }}</li>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-newspaper mr-2"></i>{{ $news->title }}
                    </h3>
                    <div class="card-tools">
                        @can('edit-content')
                        <a href="{{ route('backend.news.edit', $news) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        @endcan
                        <a href="{{ route('backend.news.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($news->featured_image)
                    <div class="text-center mb-4">
                        <img src="{{ Storage::url($news->featured_image) }}" 
                             alt="{{ $news->title }}" 
                             class="img-fluid rounded">
                    </div>
                    @endif

                    @if($news->excerpt)
                    <div class="alert alert-info">
                        <strong>Ringkasan:</strong> {{ $news->excerpt }}
                    </div>
                    @endif

                    <div class="content">
                        {!! $news->content !!}
                    </div>

                    @if($news->tags)
                    <div class="mt-4">
                        <strong>Tags:</strong>
                        @foreach(explode(',', $news->tags) as $tag)
                        <span class="badge badge-secondary mr-1">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Berita
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge {{ $news->is_published ? 'badge-success' : 'badge-warning' }}">
                                    {{ $news->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                        </tr>
                        @if($news->category)
                        <tr>
                            <td><strong>Kategori:</strong></td>
                            <td>{{ ucfirst($news->category) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Penulis:</strong></td>
                            <td>{{ $news->author->name ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>{{ $news->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diupdate:</strong></td>
                            <td>{{ $news->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                        @if($news->published_at)
                        <tr>
                            <td><strong>Dipublikasi:</strong></td>
                            <td>{{ $news->published_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Slug:</strong></td>
                            <td><code>{{ $news->slug }}</code></td>
                        </tr>
                        @if($news->is_featured)
                        <tr>
                            <td><strong>Unggulan:</strong></td>
                            <td><span class="badge badge-info">Ya</span></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-external-link-alt mr-2"></i>Aksi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($news->is_published)
                        <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="btn btn-info btn-block">
                            <i class="fas fa-eye mr-1"></i>Lihat di Website
                        </a>
                        @endif

                        @can('manage-content')
                        @if($news->is_published)
                        <button type="button" class="btn btn-warning btn-block" onclick="updateStatus('draft')">
                            <i class="fas fa-pause mr-1"></i>Unpublish
                        </button>
                        @else
                        <button type="button" class="btn btn-success btn-block" onclick="updateStatus('published')">
                            <i class="fas fa-paper-plane mr-1"></i>Publish
                        </button>
                        @endif

                        <button type="button" class="btn btn-danger btn-block" onclick="deleteNews()">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateStatus(status) {
    const action = status === 'published' ? 'publish' : 'unpublish';
    if (confirm(`Apakah Anda yakin ingin ${action} berita ini?`)) {
        fetch(`{{ route('backend.news.update-status', $news) }}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: status })
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
}

function deleteNews() {
    if (confirm('Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.')) {
        fetch(`{{ route('backend.news.destroy', $news) }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('backend.news.index') }}';
            } else {
                alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus berita');
        });
    }
}
</script>
@endpush