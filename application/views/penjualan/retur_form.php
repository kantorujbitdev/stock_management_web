<div class="form-group text-left mt-4">
    <?php echo back_button('retur/add'); ?>
</div>
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <?php echo responsive_title('Tambah Retur Penjualan') ?>
    </div>
    <div class="card-body">
        <!-- Informasi Penjualan -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Penjualan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. Invoice</label>
                            <input type="text" class="form-control" value="<?php echo $penjualan->no_invoice; ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Penjualan</label>
                            <input type="text" class="form-control"
                                value="<?php echo date('d-m-Y H:i:s', strtotime($penjualan->tanggal_penjualan)); ?>"
                                readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pelanggan</label>
                            <input type="text" class="form-control" value="<?php echo $penjualan->nama_pelanggan; ?>"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No. Retur</label>
                            <input type="text" class="form-control" value="<?php echo 'RET-' . date('Ym') . '0001'; ?>"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php echo form_open('retur/add_process', ['id' => 'formRetur']); ?>
        <input type="hidden" name="id_penjualan" value="<?php echo $penjualan->id_penjualan; ?>">

        <!-- Alasan Retur -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Alasan Retur <span class="text-danger">*</span></h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <textarea name="alasan_retur" class="form-control" rows="2" required
                        placeholder="Jelaskan alasan retur"></textarea>
                </div>
            </div>
        </div>

        <!-- Detail Barang -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detail Barang <span class="text-danger">*</span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="itemsTable">
                        <thead class="thead-dark">
                            <tr>
                                <th width="35%">Barang</th>
                                <th width="15%">Gudang</th>
                                <th width="10%">Jumlah Jual</th>
                                <th width="15%">Jumlah Retur</th>
                                <th width="15%">Alasan Barang</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 0; ?>
                            <?php foreach ($detail_penjualan as $detail): ?>
                                <tr class="item-row">
                                    <td>
                                        <input type="hidden" name="items[<?php echo $index; ?>][id_barang]"
                                            value="<?php echo $detail->id_barang; ?>">
                                        <input type="text" class="form-control" value="<?php echo $detail->nama_barang; ?>"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="hidden" name="items[<?php echo $index; ?>][id_gudang]"
                                            value="<?php echo $detail->id_gudang; ?>">
                                        <input type="text" class="form-control" value="<?php echo $detail->nama_gudang; ?>"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="<?php echo $detail->jumlah; ?>"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="items[<?php echo $index; ?>][jumlah]"
                                            class="form-control item-jumlah" required min="1"
                                            max="<?php echo $detail->jumlah; ?>" value="0">
                                        <div class="invalid-feedback">Jumlah retur tidak boleh melebihi jumlah jual</div>
                                    </td>
                                    <td>
                                        <select name="items[<?php echo $index; ?>][kondisi]" class="form-control" required>
                                            <option value="">-- Pilih Alasan --</option>
                                            <option value="Barang Rusak">Barang Rusak</option>
                                            <option value="Tidak Lengkap">Tidak Lengkap</option>
                                            <option value="Tidak Sesuai Pesanan">Tidak Sesuai Pesanan</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input item-check"
                                                id="itemCheck<?php echo $index; ?>">
                                            <label class="custom-control-label"
                                                for="itemCheck<?php echo $index; ?>">Retur</label>
                                        </div>
                                    </td>
                                </tr>
                                <?php $index++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('retur/add'); ?>" class="btn btn-secondary">
                <i class="fa fa-times"></i> Batal
            </a>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Default disable all jumlah inputs
        $('.item-jumlah').prop('disabled', true);
        $('.item-jumlah').closest('td').next().find('select').prop('disabled', true);

        // Enable/disable when checkbox is checked/unchecked
        $('.item-check').change(function () {
            var row = $(this).closest('tr');
            var jumlahInput = row.find('.item-jumlah');
            var kondisiSelect = row.find('select');

            if ($(this).is(':checked')) {
                jumlahInput.prop('disabled', false);
                kondisiSelect.prop('disabled', false);
                jumlahInput.val(1); // Set default value to 1
            } else {
                jumlahInput.prop('disabled', true);
                kondisiSelect.prop('disabled', true);
                jumlahInput.val(0);
            }
        });

        // Validate jumlah retur
        $('.item-jumlah').on('input', function () {
            var row = $(this).closest('tr');
            var maxJual = parseInt(row.find('td:nth-child(3) input').val());
            var jumlahRetur = parseInt($(this).val()) || 0;

            if (jumlahRetur > maxJual) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Form submit validation
        $('#formRetur').on('submit', function (e) {
            var hasCheckedItem = $('.item-check:checked').length > 0;

            if (!hasCheckedItem) {
                e.preventDefault();
                alert('Pilih minimal satu barang untuk diretur!');
                return false;
            }

            // Check if all checked items have valid jumlah
            var hasInvalidJumlah = false;
            $('.item-check:checked').each(function () {
                var row = $(this).closest('tr');
                var jumlahInput = row.find('.item-jumlah');

                if (jumlahInput.val() == 0 || jumlahInput.hasClass('is-invalid')) {
                    hasInvalidJumlah = true;
                }
            });

            if (hasInvalidJumlah) {
                e.preventDefault();
                alert('Perbaiki dulu kesalahan pada jumlah retur!');
                return false;
            }

            // Show loading
            $('#btnSubmit').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
        });
    });
</script>