<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <a href="<?php echo site_url('retur'); ?>" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h5 class="mb-0 ml-3">
            <i class="fas fa-tags"></i>
            Tambah Retur Penjualan
        </h5>
    </div>

    <div class="card-body px-4 py-4">
        <?php echo form_open('retur/add_process'); ?>

        <div class="form-group">
            <label for="id_perusahaan">Perusahaan</label>
            <select name="id_perusahaan" class="form-control" id="id_perusahaan" required>
                <option value="">-- Pilih Perusahaan --</option>
                <?php foreach ($perusahaan as $p): ?>
                    <option value="<?php echo $p->id_perusahaan; ?>"><?php echo $p->nama_perusahaan; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_penjualan">Penjualan</label>
            <select name="id_penjualan" class="form-control" id="id_penjualan" required>
                <option value="">-- Pilih Penjualan --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_retur">Tanggal Retur</label>
            <input type="date" name="tanggal_retur" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group">
            <label for="alasan_retur">Alasan Retur</label>
            <textarea name="alasan_retur" class="form-control" rows="3" required></textarea>
        </div>

        <!-- Tombol -->
        <div class="form-group text-right mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('retur'); ?>" class="btn btn-secondary px-4">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#id_perusahaan').change(function () {
            var id_perusahaan = $(this).val();

            if (id_perusahaan != '') {
                $.ajax({
                    url: "<?php echo site_url('retur/get_penjualan_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#id_penjualan').html(data);
                    }
                });
            } else {
                $('#id_penjualan').html('<option value="">-- Pilih Penjualan --</option>');
            }
        });
    });
</script>