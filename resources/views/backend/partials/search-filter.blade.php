<!-- Search and Filter Bar -->
<div class="card mb-3">
    <div class="card-header">
        <div class="row align-items-center">
            <!-- Search Input -->
            <div class="col-md-{{ isset($filters) ? '4' : '6' }}">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" 
                           id="search-input" 
                           class="form-control" 
                           placeholder="{{ $searchPlaceholder ?? 'Cari data...' }}"
                           value="{{ request('search') }}"
                           onkeyup="debounceSearch(this.value)">
                </div>
            </div>

            <!-- Filters -->
            @if(isset($filters) && count($filters) > 0)
                @foreach($filters as $filter)
                <div class="col-md-2">
                    <select class="form-control" 
                            name="{{ $filter['name'] }}" 
                            onchange="applyFilter()"
                            id="filter-{{ $filter['name'] }}">
                        <option value="">{{ $filter['label'] }}</option>
                        @foreach($filter['options'] as $value => $label)
                            <option value="{{ $value }}" 
                                    {{ request($filter['name']) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            @endif

            <!-- Sort Options -->
            <div class="col-md-2">
                <select class="form-control" name="sort" onchange="applyFilter()" id="sort-select">
                    @if(isset($sortOptions))
                        @foreach($sortOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('sort') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    @else
                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
                        <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>Terakhir Diubah</option>
                    @endif
                </select>
            </div>

            <!-- Sort Direction -->
            <div class="col-md-2">
                <select class="form-control" name="direction" onchange="applyFilter()" id="direction-select">
                    <option value="desc" {{ request('direction', 'desc') == 'desc' ? 'selected' : '' }}>Menurun</option>
                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Menaik</option>
                </select>
            </div>
        </div>
    </div>
    
    @if(isset($showStats) && $showStats)
    <div class="card-body py-2">
        <div class="row justify-content-between align-items-center">
            <div class="col-auto">
                <small class="text-muted">
                    @if(isset($stats))
                        @foreach($stats as $key => $value)
                            <span class="mr-3">
                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}
                            </span>
                        @endforeach
                    @else
                        Total {{ $totalItems ?? 0 }} {{ $itemName ?? 'item' }}
                    @endif
                </small>
            </div>
            <div class="col-auto">
                @if(isset($actionButtons))
                    <div class="btn-group">
                        @foreach($actionButtons as $button)
                            @if(isset($button['permission']) && !auth()->user()->can($button['permission']))
                                @continue
                            @endif
                            <a href="{{ $button['url'] }}" 
                               class="btn btn-{{ $button['class'] ?? 'primary' }} btn-sm"
                               @if(isset($button['onclick'])) onclick="{{ $button['onclick'] }}" @endif>
                                @if(isset($button['icon']))
                                    <i class="fas fa-{{ $button['icon'] }}"></i>
                                @endif
                                {{ $button['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<script>
let searchTimeout;

function debounceSearch(value) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilter();
    }, 300);
}

function applyFilter() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = window.location.pathname;

    // Search
    const searchValue = document.getElementById('search-input').value;
    if (searchValue) {
        const searchInput = document.createElement('input');
        searchInput.type = 'hidden';
        searchInput.name = 'search';
        searchInput.value = searchValue;
        form.appendChild(searchInput);
    }

    // Filters
    @if(isset($filters))
        @foreach($filters as $filter)
            const {{ $filter['name'] }}Value = document.getElementById('filter-{{ $filter['name'] }}').value;
            if ({{ $filter['name'] }}Value) {
                const {{ $filter['name'] }}Input = document.createElement('input');
                {{ $filter['name'] }}Input.type = 'hidden';
                {{ $filter['name'] }}Input.name = '{{ $filter['name'] }}';
                {{ $filter['name'] }}Input.value = {{ $filter['name'] }}Value;
                form.appendChild({{ $filter['name'] }}Input);
            }
        @endforeach
    @endif

    // Sort
    const sortValue = document.getElementById('sort-select').value;
    if (sortValue) {
        const sortInput = document.createElement('input');
        sortInput.type = 'hidden';
        sortInput.name = 'sort';
        sortInput.value = sortValue;
        form.appendChild(sortInput);
    }

    // Direction
    const directionValue = document.getElementById('direction-select').value;
    if (directionValue) {
        const directionInput = document.createElement('input');
        directionInput.type = 'hidden';
        directionInput.name = 'direction';
        directionInput.value = directionValue;
        form.appendChild(directionInput);
    }

    // Per page (maintain current setting)
    const currentPerPage = new URLSearchParams(window.location.search).get('per_page');
    if (currentPerPage) {
        const perPageInput = document.createElement('input');
        perPageInput.type = 'hidden';
        perPageInput.name = 'per_page';
        perPageInput.value = currentPerPage;
        form.appendChild(perPageInput);
    }

    document.body.appendChild(form);
    form.submit();
}

// Clear filters
function clearFilters() {
    window.location.href = window.location.pathname;
}
</script>