<!-- Process Request Modal -->
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Pengajuan Surat</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="processForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pengajuan akan diubah status menjadi "Sedang Diproses" dan Anda akan menjadi penanggung jawab pengajuan ini.
                    </div>
                    <div class="form-group">
                        <label for="process_notes">Catatan Proses (opsional)</label>
                        <textarea name="notes" id="process_notes" class="form-control" rows="3" 
                                  placeholder="Tambahkan catatan untuk pemohon jika diperlukan..."></textarea>
                        <small class="form-text text-muted">
                            Catatan ini akan terlihat oleh pemohon saat mengecek status pengajuan.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-play mr-1"></i>Mulai Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Complete Request Modal -->
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selesaikan Pengajuan Surat</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="completeForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        Surat akan ditandai sebagai selesai dan siap diserahkan kepada pemohon.
                    </div>
                    <div class="form-group">
                        <label for="letter_number">Nomor Surat <span class="text-danger">*</span></label>
                        <input type="text" name="letter_number" id="letter_number" class="form-control" 
                               placeholder="Contoh: 001/KEL/DS/{{ date('Y') }}" required>
                        <small class="form-text text-muted">
                            Format nomor surat sesuai dengan ketentuan yang berlaku.
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="complete_notes">Catatan Penyelesaian (opsional)</label>
                        <textarea name="notes" id="complete_notes" class="form-control" rows="3" 
                                  placeholder="Catatan tambahan untuk pemohon..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="completion_date">Tanggal Selesai</label>
                        <input type="date" name="completion_date" id="completion_date" class="form-control" 
                               value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i>Selesaikan Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Request Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Pengajuan Surat</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian!</strong> Pengajuan akan ditolak secara permanen. Pastikan alasan penolakan jelas dan dapat dipahami pemohon.
                    </div>
                    <div class="form-group">
                        <label for="rejection_reason">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" 
                                  placeholder="Jelaskan secara detail alasan penolakan pengajuan ini..." required></textarea>
                        <small class="form-text text-muted">
                            Alasan yang jelas akan membantu pemohon memahami dan memperbaiki pengajuan di masa mendatang.
                        </small>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="confirm_reject" required>
                            <label class="form-check-label" for="confirm_reject">
                                Saya yakin akan menolak pengajuan ini
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i>Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Status Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="changeStatusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="new_status">Status Baru</label>
                        <select name="status" id="new_status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="pending">Menunggu</option>
                            <option value="processing">Sedang Diproses</option>
                            <option value="ready">Siap Diambil</option>
                            <option value="completed">Selesai</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_notes">Catatan Perubahan Status</label>
                        <textarea name="notes" id="status_notes" class="form-control" rows="3" 
                                  placeholder="Jelaskan alasan perubahan status..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Ubah Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate letter number based on current date
    $('#completeModal').on('shown.bs.modal', function() {
        if (!$('#letter_number').val()) {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const suggestedNumber = `001/KEL/DS/${month}/${year}`;
            $('#letter_number').attr('placeholder', `Contoh: ${suggestedNumber}`);
        }
    });

    // Validate rejection form
    $('#rejectForm').on('submit', function(e) {
        const reason = $('#rejection_reason').val().trim();
        if (reason.length < 10) {
            e.preventDefault();
            alert('Alasan penolakan harus minimal 10 karakter.');
            return false;
        }
    });

    // Validate completion form
    $('#completeForm').on('submit', function(e) {
        const letterNumber = $('#letter_number').val().trim();
        if (!letterNumber) {
            e.preventDefault();
            alert('Nomor surat wajib diisi.');
            return false;
        }
    });
});
</script>
@endpush