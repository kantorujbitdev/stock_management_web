<!-- Modal Input Stok Awal -->
<div class="modal fade" id="inputStokModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Input Stok Awal</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="inputStokForm" action="<?php echo site_url('barang/input_stok_awal_process'); ?>" method="post">
                <!-- Tambahkan CSRF Token -->
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <input type="hidden" name="id_barang" id="stokIdBarang">
                    <input type="hidden" name="id_perusahaan" id="stokIdPerusahaan">
                    <div class="form-group">
                        <label for="namaBarangDisplay">Nama Barang</label>
                        <input type="text" class="form-control" id="namaBarangDisplay" readonly>
                    </div>
                    <div class="form-group">
                        <label for="id_gudang">Gudang <span class="text-danger">*</span></label>
                        <select name="id_gudang" class="form-control" id="id_gudang" required>
                            <option value="">-- Pilih Gudang --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qty_awal">Qty Awal <span class="text-danger">*</span></label>
                        <input type="number" name="qty_awal" class="form-control" id="qty_awal" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control" id="keterangan" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>