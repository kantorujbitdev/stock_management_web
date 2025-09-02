<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Transfer Stok</h3>
    </div>
    <div class="card-body">
        <?php echo form_open('transfer/add_process'); ?>

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
            <label for="id_gudang_asal">Gudang Asal</label>
            <select name="id_gudang_asal" class="form-control" id="id_gudang_asal" required>
                <option value="">-- Pilih Gudang --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="id_gudang_tujuan">Gudang Tujuan</label>
            <select name="id_gudang_tujuan" class="form-control" id="id_gudang_tujuan" required>
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
            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" class="form-control" id="jumlah" required min="1">
            <small class="form-text text-muted">Stok tersedia: <span id="stok_tersedia">0</span></small>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('transfer'); ?>" class="btn btn-secondary">Batal</a>
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
                    url: "<?php echo site_url('transfer/get_gudang_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#id_gudang_asal').html(data);
                        $('#id_gudang_tujuan').html(data);
                        $('#id_barang').html('<option value="">-- Pilih Barang --</option>');
                        $('#stok_tersedia').text('0');
                    }
                });
            } else {
                $('#id_gudang_asal').html('<option value="">-- Pilih Gudang --</option>');
                $('#id_gudang_tujuan').html('<option value="">-- Pilih Gudang --</option>');
                $('#id_barang').html('<option value="">-- Pilih Barang --</option>');
                $('#stok_tersedia').text('0');
            }
        });

        $('#id_gudang_asal').change(function () {
            var id_gudang = $(this).val();

            if (id_gudang != '') {
                $.ajax({
                    url: "<?php echo site_url('transfer/get_barang_by_gudang') ?>",
                    method: "POST",
                    data: { id_gudang: id_gudang },
                    success: function (data) {
                        $('#id_barang').html(data);
                        $('#stok_tersedia').text('0');
                    }
                });
            } else {
                $('#id_barang').html('<option value="">-- Pilih Barang --</option>');
                $('#stok_tersedia').text('0');
            }
        });

        $('#id_barang').change(function () {
            var id_barang = $(this).val();
            var id_gudang = $('#id_gudang_asal').val();

            if (id_barang != '' && id_gudang != '') {
                $.ajax({
                    url: "<?php echo site_url('transfer/get_stok_barang') ?>",
                    method: "POST",
                    data: { id_barang: id_barang, id_gudang: id_gudang },
                    success: function (data) {
                        $('#stok_tersedia').text(data);
                        $('#jumlah').attr('max', data);
                    }
                });
            } else {
                $('#stok_tersedia').text('0');
                $('#jumlah').removeAttr('max');
            }
        });

        $('#id_gudang_tujuan').change(function () {
            var id_gudang_asal = $('#id_gudang_asal').val();
            var id_gudang_tujuan = $(this).val();

            if (id_gudang_asal == id_gudang_tujuan) {
                alert('Gudang asal dan tujuan tidak boleh sama');
                $(this).val('');
            }
        });
    });
</script>