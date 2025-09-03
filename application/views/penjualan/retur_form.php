<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Retur Penjualan</h3>
    </div>
    <div class="card-body">
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

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('retur'); ?>" class="btn btn-secondary">Batal</a>
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