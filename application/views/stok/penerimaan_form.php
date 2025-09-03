<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Penerimaan Barang</h3>
    </div>
    <div class="card-body">
        <?php echo form_open('penerimaan/add_process'); ?>

        <div class="form-group">
            <label for="id_supplier">Supplier</label>
            <select name="id_supplier" class="form-control" required>
                <option value="">-- Pilih Supplier --</option>
                <?php foreach ($supplier as $s): ?>
                    <option value="<?php echo $s->id_supplier; ?>"><?php echo $s->nama_supplier; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

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
            <label for="id_gudang">Gudang</label>
            <select name="id_gudang" class="form-control" id="id_gudang" required>
                <option value="">-- Pilih Gudang --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_penerimaan">Tanggal Penerimaan</label>
            <input type="date" name="tanggal_penerimaan" class="form-control" value="<?php echo date('Y-m-d'); ?>"
                required>
        </div>

        <div class="form-group">
            <label for="no_faktur">No Faktur</label>
            <input type="text" name="no_faktur" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('penerimaan'); ?>" class="btn btn-secondary">Batal</a>
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
                    url: "<?php echo site_url('penerimaan/get_gudang_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#id_gudang').html(data);
                    }
                });
            } else {
                $('#id_gudang').html('<option value="">-- Pilih Gudang --</option>');
            }
        });
    });
</script>