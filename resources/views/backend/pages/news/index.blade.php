@extends('backend.layout.main')

@section('title', 'Manajemen Berita')
@section('header', 'Manajemen Berita')
@section('description', 'Kelola berita dan artikel website desa')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Berita</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-check-circle text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Dipublikasi</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['published'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Draft</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['draft'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-eye text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Views</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_views'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="search-input" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                               placeholder="Cari judul, konten, atau penulis...">
                    </div>
                </div>
                <div class="flex space-x-2">
                    <select id="status-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white text-sm">
                        <option value="">Semua Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                    <select id="category-filter" class="border border-gray-300 rounded-md px-3 py-2 bg-white text-sm">
                        <option value="">Semua Kategori</option>
                        <option value="pengumuman">Pengumuman</option>
                        <option value="berita">Berita</option>
                        <option value="artikel">Artikel</option>
                        <option value="informasi">Informasi</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 sm:ml-4 flex space-x-2">
            @can('export-content')
            <button type="button" onclick="exportNews()" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-download mr-2"></i>
                Export
            </button>
            @endcan
            
            @can('manage-content')
            <a href="{{ route('admin.news.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Buat Berita
            </a>
            @endcan
        </div>
    </div>

    <!-- News Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Berita</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Berita
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statistik
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="news-table">
                    @forelse($news ?? [] as $article)
                    <tr class="hover:bg-gray-50" data-news-id="{{ $article->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="news-checkbox rounded border-gray-300 text-blue-600" value="{{ $article->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-start">
                                @if($article->featured_image)
                                <div class="flex-shrink-0 mr-4">
                                    <img class="h-16 w-20 rounded-lg object-cover" src="{{ Storage::url($article->featured_image) }}" alt="">
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 mb-1">
                                        <a href="{{ route('admin.news.show', $article) }}" class="hover:text-blue-600">
                                            {{ Str::limit($article->title, 60) }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500 mb-2">
                                        {{ Str::limit(strip_tags($article->content), 100) }}
                                    </div>
                                    <div class="flex items-center space-x-3 text-xs text-gray-500">
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $article->author->name ?? 'Unknown' }}
                                        </span>
                                        @if($article->category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($article->category) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($article->status === 'published') bg-green-100 text-green-800
                                    @elseif($article->status === 'draft') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <div class="w-1.5 h-1.5 rounded-full mr-1.5
                                        @if($article->status === 'published') bg-green-400
                                        @elseif($article->status === 'draft') bg-yellow-400
                                        @else bg-gray-400 @endif"></div>
                                    {{ ucfirst($article->status) }}
                                </span>
                                @if($article->is_featured)
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">
                                        <i class="fas fa-star mr-1"></i>
                                        Featured
                                    </span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="space-y-1">
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-eye mr-1"></i>
                                    {{ number_format($article->views_count ?? 0) }} views
                                </div>
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-comments mr-1"></i>
                                    {{ $article->comments_count ?? 0 }} komentar
                                </div>
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="fas fa-share mr-1"></i>
                                    {{ $article->shares_count ?? 0 }} share
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="space-y-1">
                                <div>Dibuat: {{ $article->created_at->format('d M Y') }}</div>
                                @if($article->published_at)
                                <div>Publish: {{ $article->published_at->format('d M Y H:i') }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                @can('view-content')
                                <a href="{{ route('news.show', $article) }}" target="_blank"
                                   class="text-gray-600 hover:text-gray-900" title="Lihat di Website">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                @endcan
                                
                                @can('view-content')
                                <a href="{{ route('admin.news.show', $article) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                
                                @can('edit-content')
                                <a href="{{ route('admin.news.edit', $article) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                
                                @can('manage-content')
                                @if($article->status === 'draft')
                                    <button type="button" onclick="publishNews({{ $article->id }})" 
                                            class="text-green-600 hover:text-green-900" title="Publish">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                @elseif($article->status === 'published')
                                    <button type="button" onclick="unpublishNews({{ $article->id }})" 
                                            class="text-orange-600 hover:text-orange-900" title="Unpublish">
                                        <i class="fas fa-pause"></i>
                                    </button>
                                @endif
                                @endcan
                                
                                @can('delete-content')
                                <button type="button" onclick="deleteNews({{ $article->id }})" 
                                        class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-newspaper text-4xl mb-4"></i>
                                <p class="text-lg font-medium">Belum ada berita</p>
                                <p class="text-sm">Mulai membuat konten untuk website desa</p>
                                @can('manage-content')
                                <div class="mt-4">
                                    <a href="{{ route('admin.news.create') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <i class="fas fa-plus mr-2"></i>
                                        Buat Berita Pertama
                                    </a>
                                </div>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($news) && $news->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $news->links() }}
        </div>
        @endif
    </div>

    <!-- Bulk Actions Panel -->
    <div id="bulk-actions" class="hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-3 shadow-lg">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center">
                <span class="text-sm text-gray-700">
                    <span id="selected-count">0</span> berita dipilih
                </span>
            </div>
            <div class="flex items-center space-x-2">
                @can('manage-content')
                <button type="button" onclick="bulkAction('publish')" 
                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-paper-plane mr-1"></i>
                    Publish
                </button>
                <button type="button" onclick="bulkAction('unpublish')" 
                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-pause mr-1"></i>
                    Unpublish
                </button>
                @endcan
                
                @can('delete-content')
                <button type="button" onclick="bulkAction('delete')" 
                        class="inline-flex items-center px-3 py-1.5 border border-red-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50">
                    <i class="fas fa-trash mr-1"></i>
                    Hapus
                </button>
                @endcan
                
                <button type="button" onclick="clearSelection()" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-500 mb-4" id="delete-message">
                Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-center space-x-4">
                <button type="button" onclick="hideDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    Batal
                </button>
                <button type="button" id="confirm-delete" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedNews = [];
    let deleteNewsId = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Search and filter functionality
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        const categoryFilter = document.getElementById('category-filter');

        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterNews();
            }, 300);
        });

        statusFilter.addEventListener('change', filterNews);
        categoryFilter.addEventListener('change', filterNews);

        // Checkbox functionality
        const selectAll = document.getElementById('select-all');
        selectAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.news-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
                if (this.checked) {
                    addToSelection(cb.value);
                } else {
                    removeFromSelection(cb.value);
                }
            });
            updateBulkActions();
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('news-checkbox')) {
                if (e.target.checked) {
                    addToSelection(e.target.value);
                } else {
                    removeFromSelection(e.target.value);
                }
                updateBulkActions();
                updateSelectAll();
            }
        });
    });

    function addToSelection(newsId) {
        if (!selectedNews.includes(newsId)) {
            selectedNews.push(newsId);
        }
    }

    function removeFromSelection(newsId) {
        selectedNews = selectedNews.filter(id => id !== newsId);
    }

    function updateBulkActions() {
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');
        
        if (selectedNews.length > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = selectedNews.length;
        } else {
            bulkActions.classList.add('hidden');
        }
    }

    function updateSelectAll() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.news-checkbox');
        const checkedBoxes = document.querySelectorAll('.news-checkbox:checked');
        
        if (checkedBoxes.length === 0) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        } else if (checkedBoxes.length === checkboxes.length) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        }
    }

    function clearSelection() {
        selectedNews = [];
        document.querySelectorAll('.news-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('select-all').checked = false;
        document.getElementById('select-all').indeterminate = false;
        updateBulkActions();
    }

    function filterNews() {
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;
        const category = document.getElementById('category-filter').value;

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status) params.append('status', status);
        if (category) params.append('category', category);

        fetch(`{{ route('admin.news.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.querySelector('#news-table');
            
            if (newTable) {
                document.getElementById('news-table').innerHTML = newTable.innerHTML;
            }
            
            clearSelection();
        })
        .catch(error => {
            console.error('Filter error:', error);
            showNotification('Terjadi kesalahan saat memfilter data', 'error');
        });
    }

    function publishNews(newsId) {
        updateNewsStatus(newsId, 'published');
    }

    function unpublishNews(newsId) {
        updateNewsStatus(newsId, 'draft');
    }

    function updateNewsStatus(newsId, status) {
        fetch(`{{ route('admin.news.index') }}/${newsId}/status`, {
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
                showNotification(data.message, 'success');
                filterNews();
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Status update error:', error);
            showNotification('Terjadi kesalahan saat mengubah status', 'error');
        });
    }

    function deleteNews(newsId) {
        deleteNewsId = newsId;
        document.getElementById('delete-message').textContent = 'Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.';
        document.getElementById('delete-modal').classList.remove('hidden');
        
        document.getElementById('confirm-delete').onclick = function() {
            performDelete(newsId);
        };
    }

    function performDelete(newsId) {
        fetch(`{{ route('admin.news.index') }}/${newsId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            hideDeleteModal();
            if (data.success) {
                showNotification(data.message, 'success');
                filterNews();
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            hideDeleteModal();
            console.error('Delete error:', error);
            showNotification('Terjadi kesalahan saat menghapus berita', 'error');
        });
    }

    function hideDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        deleteNewsId = null;
    }

    function bulkAction(action) {
        if (selectedNews.length === 0) return;

        let message = '';
        switch (action) {
            case 'publish':
                message = `Publish ${selectedNews.length} berita yang dipilih?`;
                break;
            case 'unpublish':
                message = `Unpublish ${selectedNews.length} berita yang dipilih?`;
                break;
            case 'delete':
                message = `Hapus ${selectedNews.length} berita yang dipilih? Tindakan ini tidak dapat dibatalkan.`;
                break;
        }

        if (confirm(message)) {
            fetch(`{{ route('admin.news.bulk-action') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: action,
                    news_ids: selectedNews
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    clearSelection();
                    filterNews();
                } else {
                    showNotification(data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Bulk action error:', error);
                showNotification('Terjadi kesalahan saat melakukan bulk action', 'error');
            });
        }
    }

    function exportNews() {
        const params = new URLSearchParams();
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;
        const category = document.getElementById('category-filter').value;
        
        if (search) params.append('search', search);
        if (status) params.append('status', status);
        if (category) params.append('category', category);

        window.open(`{{ route('admin.news.export') }}?${params.toString()}`, '_blank');
    }
</script>
@endpush