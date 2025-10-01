<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <h3 id="paginationInfo" class="text-lg font-bold text-gray-900">
        Menampilkan {{ $umkms->firstItem() ?? 0 }} - {{ $umkms->lastItem() ?? 0 }} dari {{ $umkms->total() }} UMKM
    </h3>
    @if($umkms->hasPages())
        <div id="paginationLinks">{{ $umkms->links() }}</div>
    @endif
</div>