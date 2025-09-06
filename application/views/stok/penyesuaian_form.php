<div class="card">
    <div class="card-header">
        <h5 class="card-title">Tambah Penyesuaian Stok</h3>
    </div>
    <div class="card-body">
        <?php echo form_open('penyesuaian/add_process'); ?>

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
            <label for="id_barang">Barang</label>
            <select name="id_barang" class="form-control" id="id_barang" required>
                <option value="">-- Pilih Barang --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah_saat_ini">Stok Saat Ini</label>
            <input type="text" name="jumlah_saat_ini" class="form-control" id="jumlah_saat_ini" value="0" readonly>
        </div>

        <div class="form-group">
            <label for="jumlah_baru">Stok Baru</label>
            <input type="number" name="jumlah_baru" class="form-control" id="jumlah_baru" required min="0">
        </div>

        <div class="form-group">
            <label for="alasan">Alasan Penyesuaian</label>
            <textarea name="alasan" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('penyesuaian'); ?>" class="btn btn-secondary">Batal</a>
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
                    url: "<?php echo site_url('penyesuaian/get_gudang_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#id_gudang').html(data);
                        $('#id_barang').html('<option value="">-- Pilih Barang --</option>');
                        $('#jumlah_saat_ini').val('0');
                    }
                });
            } else {
                $('#id_gudang').html('<option value="">-- Pilih Gudang --</option>');
                $('#id_barang').html('<option value="">-- Pilih Barang --</option>');
                $('#jumlah_saat_ini').val('0');
            }
        });

        $('#id_gudang').change(function () {
            var id_gudang = $(this).val();

            if (id_gudang != '') {
                $.ajax({
                    url: "<?php echo site_url('penyesuaian/get_barang_by_gudang') ?>",
                    method: "POST",
                    data: { id_gudang: id_gudang },
                    success: function (data) {
                        $('#id_barang').html(data);
                        $('#jumlah_saat_ini').val('0');
                    }
                });
            } else {
                $('#id_barang').html('<option value="">-- Pilih Barang --</option>');
                $('#jumlah_saat_ini').val('0');
            }
        });

        $('#id_barang').change(function () {
            var id_barang = $(this).val();
            var id_gudang = $('#id_gudang').val();

            if (id_barang != '' && id_gudang != '') {
                $.ajax({
                    url: "<?php echo site_url('penyesuaian/get_stok_barang') ?>",
                    method: "POST",
                    data: { id_barang: id_barang, id_gudang: id_gudang },
                    success: function (data) {
                        $('#jumlah_saat_ini').val(data);
                        $('#jumlah_baru').val(data);
                    }
                });
            } else {
                $('#jumlah_saat_ini').val('0');
                $('#jumlah_baru').val('0');
            }
        });
    });
</script>