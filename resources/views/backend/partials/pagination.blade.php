@if($paginator->hasPages())
<div class="card-footer clearfix">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="dataTables_info">
                Menampilkan {{ $paginator->firstItem() }} sampai {{ $paginator->lastItem() }} 
                dari {{ $paginator->total() }} entri
                @if(isset($filtered) && $filtered > 0 && $filtered < $paginator->total())
                    (difilter dari {{ $paginator->total() }} total entri)
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="float-right">
                <!-- Per Page Selector -->
                <div class="d-inline-block mr-3">
                    <select class="form-control form-control-sm d-inline-block" style="width: auto;" onchange="changePerPage(this.value)">
                        @foreach($perPageOptions as $option)
                            <option value="{{ $option }}" {{ $paginator->perPage() == $option ? 'selected' : '' }}>
                                {{ $option }} per halaman
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pagination Links -->
                <ul class="pagination pagination-sm m-0 d-inline-block">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">‹</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $currentPage = $paginator->currentPage();
                        $lastPage = $paginator->lastPage();
                        $showPages = 5; // Number of pages to show around current page
                        $start = max(1, $currentPage - floor($showPages / 2));
                        $end = min($lastPage, $start + $showPages - 1);
                        
                        // Adjust start if we're near the end
                        if ($end - $start + 1 < $showPages && $start > 1) {
                            $start = max(1, $end - $showPages + 1);
                        }
                    @endphp

                    {{-- First page --}}
                    @if ($start > 1)
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
                        @if ($start > 2)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endif

                    {{-- Page range --}}
                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $currentPage)
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                        @endif
                    @endfor

                    {{-- Last page --}}
                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                        <li class="page-item"><a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a></li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">›</span></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // Reset to first page
    window.location.href = url.toString();
}
</script>
@endif