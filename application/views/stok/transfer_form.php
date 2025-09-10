<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <?php echo back_button('transfer'); ?>
        <h5 class="mb-0 ml-3 d-flex align-items-center">
            <i class="fas fa-tags mr-1"></i>
            <?php echo isset($pelanggan) ? 'Edit Transfer Stok' : 'Tambah Transfer Stok'; ?>
        </h5>
    </div>
    <div class="card-body">
        <?php echo form_open('transfer/add_process'); ?>

        <div class="row">
            <!-- Perusahaan -->
            <div class="col-md-6 mb-3">
                <label for="id_perusahaan" class="font-weight-bold">Perusahaan</label>
                <select name="id_perusahaan" class="form-control" id="id_perusahaan" required>
                    <option value="">-- Pilih Perusahaan --</option>
                    <?php foreach ($perusahaan as $p): ?>
                        <option value="<?php echo $p->id_perusahaan; ?>"><?php echo $p->nama_perusahaan; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Gudang Asal -->
            <div class="col-md-6 mb-3">
                <label for="id_gudang_asal" class="font-weight-bold">Gudang Asal</label>
                <select name="id_gudang_asal" class="form-control" id="id_gudang_asal" required>
                    <option value="">-- Pilih Gudang --</option>
                </select>
            </div>

            <!-- Gudang Tujuan -->
            <div class="col-md-6 mb-3">
                <label for="id_gudang_tujuan" class="font-weight-bold">Gudang Tujuan</label>
                <select name="id_gudang_tujuan" class="form-control" id="id_gudang_tujuan" required>
                    <option value="">-- Pilih Gudang --</option>
                </select>
            </div>

            <!-- Barang -->
            <div class="col-md-6 mb-3">
                <label for="id_barang" class="font-weight-bold">Barang</label>
                <select name="id_barang" class="form-control" id="id_barang" required>
                    <option value="">-- Pilih Barang --</option>
                </select>
            </div>

            <!-- Jumlah -->
            <div class="col-md-6 mb-3">
                <label for="jumlah" class="font-weight-bold">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" id="jumlah" required min="1">
                <small class="form-text text-muted">
                    Stok tersedia: <span id="stok_tersedia" class="font-weight-bold text-success">0</span>
                </small>
            </div>

            <!-- Keterangan -->
            <div class="col-md-12 mb-3">
                <label for="keterangan" class="font-weight-bold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Tambahkan catatan jika perlu..."></textarea>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="form-group text-right mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('transfer'); ?>" class="btn btn-secondary">
                <i class="fa fa-times"></i> Batal
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