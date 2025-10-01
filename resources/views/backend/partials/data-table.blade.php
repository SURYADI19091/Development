<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-{{ $icon ?? 'table' }} mr-1"></i>
            {{ $title ?? 'Data Table' }}
        </h3>
        <div class="card-tools">
            @if(isset($showExport) && $showExport)
                <button type="button" class="btn btn-tool" onclick="exportTable()" title="Export Data">
                    <i class="fas fa-download"></i>
                </button>
            @endif
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    @if(isset($showCheckbox) && $showCheckbox)
                        <th style="width: 40px;">
                            <div class="icheck-primary">
                                <input type="checkbox" id="select-all">
                                <label for="select-all"></label>
                            </div>
                        </th>
                    @endif
                    
                    @foreach($headers as $header)
                        <th @if(isset($header['width'])) style="width: {{ $header['width'] }};" @endif
                            @if(isset($header['sortable']) && $header['sortable']) 
                                class="sortable-header {{ request('sort') == $header['field'] ? 'active' : '' }}" 
                                onclick="sortBy('{{ $header['field'] }}')"
                                style="cursor: pointer;"
                            @endif>
                            {{ $header['label'] }}
                            @if(isset($header['sortable']) && $header['sortable'] && request('sort') == $header['field'])
                                <i class="fas fa-sort-{{ request('direction', 'desc') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @elseif(isset($header['sortable']) && $header['sortable'])
                                <i class="fas fa-sort ml-1 text-muted"></i>
                            @endif
                        </th>
                    @endforeach
                    
                    @if(isset($showActions) && $showActions)
                        <th style="width: {{ $actionsWidth ?? '120px' }};">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr @if(isset($showCheckbox) && $showCheckbox) data-id="{{ $row->id }}" @endif>
                        @if(isset($showCheckbox) && $showCheckbox)
                            <td>
                                <div class="icheck-primary">
                                    <input type="checkbox" class="row-checkbox" value="{{ $row->id }}" id="check{{ $row->id }}">
                                    <label for="check{{ $row->id }}"></label>
                                </div>
                            </td>
                        @endif
                        
                        {{ $slot }}
                        
                        @if(isset($showActions) && $showActions)
                            <td class="text-center">
                                @if(isset($actions))
                                    <div class="btn-group btn-group-sm">
                                        @foreach($actions as $action)
                                            @if(!isset($action['permission']) || auth()->user()->can($action['permission'], $row))
                                                @if(isset($action['route']))
                                                    <a href="{{ route($action['route'], $row) }}" 
                                                       class="btn btn-{{ $action['class'] ?? 'info' }} btn-xs"
                                                       @if(isset($action['title'])) title="{{ $action['title'] }}" @endif>
                                                        <i class="fas fa-{{ $action['icon'] }}"></i>
                                                    </a>
                                                @elseif(isset($action['onclick']))
                                                    <button type="button" 
                                                            onclick="{{ $action['onclick'] }}({{ $row->id }})"
                                                            class="btn btn-{{ $action['class'] ?? 'info' }} btn-xs"
                                                            @if(isset($action['title'])) title="{{ $action['title'] }}" @endif>
                                                        <i class="fas fa-{{ $action['icon'] }}"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) + ($showCheckbox ? 1 : 0) + ($showActions ? 1 : 0) }}" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-{{ $emptyIcon ?? 'inbox' }} fa-3x mb-3 d-block"></i>
                                <h5>{{ $emptyTitle ?? 'Tidak ada data' }}</h5>
                                <p>{{ $emptyMessage ?? 'Belum ada data yang tersedia' }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(method_exists($data, 'hasPages') && $data->hasPages())
        @include('backend.partials.pagination', [
            'paginator' => $data,
            'perPageOptions' => $paginationInfo['per_page_options'] ?? [10, 15, 25, 50]
        ])
    @endif
</div>

@if(isset($showCheckbox) && $showCheckbox)
<!-- Bulk Actions (if any items selected) -->
<div id="bulk-actions" class="alert alert-info alert-dismissible d-none mt-3">
    <button type="button" class="close" onclick="clearSelection()">
        <span>&times;</span>
    </button>
    <h5><i class="icon fas fa-check-circle"></i> Aksi Massal</h5>
    <div class="row align-items-center">
        <div class="col-md-6">
            <strong><span id="selected-count">0</span> item dipilih</strong>
        </div>
        <div class="col-md-6 text-right">
            @if(isset($bulkActions))
                <div class="btn-group btn-group-sm">
                    @foreach($bulkActions as $action)
                        <button type="button" 
                                onclick="{{ $action['onclick'] }}()" 
                                class="btn btn-{{ $action['class'] ?? 'primary' }}">
                            <i class="fas fa-{{ $action['icon'] }}"></i> {{ $action['label'] }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
let selectedRows = [];

// Select all functionality
document.getElementById('select-all')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = this.checked;
        if (this.checked) {
            if (!selectedRows.includes(cb.value)) {
                selectedRows.push(cb.value);
            }
        } else {
            selectedRows = selectedRows.filter(id => id !== cb.value);
        }
    });
    updateBulkActions();
});

// Individual checkbox functionality
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('row-checkbox')) {
        if (e.target.checked) {
            if (!selectedRows.includes(e.target.value)) {
                selectedRows.push(e.target.value);
            }
        } else {
            selectedRows = selectedRows.filter(id => id !== e.target.value);
        }
        updateBulkActions();
        updateSelectAll();
    }
});

function updateBulkActions() {
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    
    if (selectedRows.length > 0) {
        bulkActions?.classList.remove('d-none');
        selectedCount.textContent = selectedRows.length;
    } else {
        bulkActions?.classList.add('d-none');
    }
}

function updateSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    
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
    selectedRows = [];
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    }
    updateBulkActions();
}

function sortBy(field) {
    const url = new URL(window.location);
    const currentSort = url.searchParams.get('sort');
    const currentDirection = url.searchParams.get('direction');
    
    // Toggle direction if same field, otherwise default to desc
    if (currentSort === field) {
        const newDirection = currentDirection === 'desc' ? 'asc' : 'desc';
        url.searchParams.set('direction', newDirection);
    } else {
        url.searchParams.set('sort', field);
        url.searchParams.set('direction', 'desc');
    }
    
    url.searchParams.delete('page'); // Reset to first page
    window.location.href = url.toString();
}
</script>
@endif