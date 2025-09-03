<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Penjualan</h3>
    </div>

    <div class="card-body">
        <?php echo form_open('penjualan/add_process'); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="id_pelanggan">Pelanggan <span class="text-danger">*</span></label>
                    <select name="id_pelanggan" class="form-control" id="id_pelanggan" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php foreach ($pelanggan as $p): ?>
                            <option value="<?php echo $p->id_pelanggan; ?>"><?php echo $p->nama_pelanggan; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="text" class="form-control" value="<?php echo date('d-m-Y H:i'); ?>" readonly>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Items <span class="text-danger">*</span></label>
            <div class="table-responsive">
                <table class="table table-bordered" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Gudang</th>
                            <th>Stok</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="itemRow">
                            <td>
                                <select name="items[0][id_barang]" class="form-control item-barang" required>
                                    <option value="">-- Pilih Barang --</option>
                                </select>
                            </td>
                            <td>
                                <select name="items[0][id_gudang]" class="form-control item-gudang" required>
                                    <option value="">-- Pilih Gudang --</option>
                                </select>
                            </td>
                            <td><span class="item-stock">-</span></td>
                            <td>
                                <input type="number" name="items[0][jumlah]" class="form-control item-jumlah" required
                                    min="1" value="1">
                            </td>
                            <td>
                                <input type="number" name="items[0][harga_satuan]" class="form-control item-harga"
                                    required min="0" step="100">
                            </td>
                            <td><span class="item-subtotal">Rp 0</span></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-success btn-sm" id="addItem">
                <i class="fas fa-plus"></i> Tambah Item
            </button>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="diskon">Diskon</label>
                    <input type="number" name="diskon" class="form-control" min="0" step="100" value="0">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="pajak">Pajak</label>
                    <input type="number" name="pajak" class="form-control" min="0" step="100" value="0">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Total Bayar</label>
                    <input type="text" class="form-control" id="totalBayar" readonly value="Rp 0">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2"></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('penjualan'); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        let itemCount = 0;

        // Get barang by perusahaan
        function getBarangByPerusahaan(select) {
            let id_perusahaan = <?php echo $this->session->userdata('id_role') == 5 ? '$("#id_perusahaan").val()' : '"' . $this->session->userdata('id_perusahaan') . '"'; ?>;

            $.ajax({
                url: "<?php echo site_url('penjualan/get_barang_by_perusahaan'); ?>",
                method: "GET",
                data: { id_perusahaan: id_perusahaan },
                dataType: "json",
                success: function (data) {
                    let options = '<option value="">-- Pilih Barang --</option>';
                    data.forEach(function (item) {
                        options += `<option value="${item.id_barang}">${item.nama_barang} - ${item.sku}</option>`;
                    });
                    select.html(options);
                }
            });
        }

        // Get stock by barang
        function getStockByBarang(select, id_barang) {
            $.ajax({
                url: "<?php echo site_url('penjualan/get_stock_by_barang'); ?>",
                method: "GET",
                data: { id_barang: id_barang },
                dataType: "json",
                success: function (data) {
                    let options = '<option value="">-- Pilih Gudang --</option>';
                    data.forEach(function (item) {
                        options += `<option value="${item.id_gudang}">${item.nama_gudang} (Stok: ${item.jumlah})</option>`;
                    });
                    select.html(options);

                    // Update stock display
                    if (data.length > 0) {
                        select.closest('tr').find('.item-stock').text(data[0].jumlah);
                    }
                }
            });
        }

        // Add new item row
        $('#addItem').click(function () {
            itemCount++;
            let newRow = `
            <tr>
                <td>
                    <select name="items[${itemCount}][id_barang]" class="form-control item-barang" required>
                        <option value="">-- Pilih Barang --</option>
                    </select>
                </td>
                <td>
                    <select name="items[${itemCount}][id_gudang]" class="form-control item-gudang" required>
                        <option value="">-- Pilih Gudang --</option>
                    </select>
                </td>
                <td><span class="item-stock">-</span></td>
                <td>
                    <input type="number" name="items[${itemCount}][jumlah]" class="form-control item-jumlah" required min="1" value="1">
                </td>
                <td>
                    <input type="number" name="items[${itemCount}][harga_satuan]" class="form-control item-harga" required min="0" step="100">
                </td>
                <td><span class="item-subtotal">Rp 0</span></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
            $('#itemsTable tbody').append(newRow);
        });

        // Remove item row
        $(document).on('click', '.remove-item', function () {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        // Barang change event
        $(document).on('change', '.item-barang', function () {
            let row = $(this).closest('tr');
            let id_barang = $(this).val();
            let gudangSelect = row.find('.item-gudang');

            if (id_barang) {
                getStockByBarang(gudangSelect, id_barang);
            } else {
                gudangSelect.html('<option value="">-- Pilih Gudang --</option>');
                row.find('.item-stock').text('-');
            }

            calculateTotal();
        });

        // Calculate subtotal
        $(document).on('input', '.item-jumlah, .item-harga', function () {
            let row = $(this).closest('tr');
            let jumlah = row.find('.item-jumlah').val() || 0;
            let harga = row.find('.item-harga').val() || 0;
            let subtotal = jumlah * harga;

            row.find('.item-subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
            calculateTotal();
        });

        // Calculate total
        function calculateTotal() {
            let subtotal = 0;
            $('.item-subtotal').each(function () {
                let value = $(this).text().replace('Rp ', '').replace(/\./g, '');
                subtotal += parseInt(value) || 0;
            });

            let diskon = parseInt($('input[name="diskon"]').val()) || 0;
            let pajak = parseInt($('input[name="pajak"]').val()) || 0;
            let total = subtotal - diskon + pajak;

            $('#totalBayar').val('Rp ' + total.toLocaleString('id-ID'));
        }

        // Initialize first row
        getBarangByPerusahaan($('.item-barang').first());

        // Diskon and pajak change
        $('input[name="diskon"], input[name="pajak"]').on('input', calculateTotal);
    });
</script>