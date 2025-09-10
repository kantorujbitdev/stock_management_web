<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <?php echo back_button('penjualan'); ?>
        <h5 class="mb-0 ml-3">
            <i class="fas fa-tags"></i>
            Tambah Penjualan
        </h5>
    </div>
    <div class="card-body">
        <?php echo form_open('penjualan/add_process', ['id' => 'formPenjualan']); ?>

        <!-- Informasi Penjualan -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Penjualan</h5>
            </div>
            <div class="card-body">
                <?php if ($this->session->userdata('id_role') == 5): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_perusahaan">Perusahaan <span class="text-danger">*</span></label>
                                <select name="id_perusahaan" class="form-control" id="id_perusahaan" required>
                                    <option value="">-- Pilih Perusahaan --</option>
                                    <?php foreach ($perusahaan as $p): ?>
                                        <option value="<?php echo $p->id_perusahaan; ?>"><?php echo $p->nama_perusahaan; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_pelanggan">Pelanggan <span class="text-danger">*</span></label>
                                <select name="id_pelanggan" class="form-control" id="id_pelanggan" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    <?php foreach ($pelanggan as $p): ?>
                                        <option value="<?php echo $p->id_pelanggan; ?>" data-alamat="<?php echo $p->alamat; ?>"
                                            data-telepon="<?php echo $p->telepon; ?>">
                                            <?php echo $p->nama_pelanggan; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_pelanggan">Pelanggan <span class="text-danger">*</span></label>
                                <select name="id_pelanggan" class="form-control" id="id_pelanggan" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    <?php foreach ($pelanggan as $p): ?>
                                        <option value="<?php echo $p->id_pelanggan; ?>" data-alamat="<?php echo $p->alamat; ?>"
                                            data-telepon="<?php echo $p->telepon; ?>">
                                            <?php echo $p->nama_pelanggan; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Invoice</label>
                                <input type="text" class="form-control" value="<?php echo 'INV-' . date('Ym') . '0001'; ?>"
                                    readonly>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row" id="infoPelanggan" style="display: none;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Alamat</label>
                            <p id="alamatPelanggan" class="form-control-plaintext"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Telepon</label>
                            <p id="teleponPelanggan" class="form-control-plaintext"></p>
                        </div>
                    </div>
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
                                <th>Barang</th>
                                <th>Gudang</th>
                                <th>Stok</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="item-row">
                                <td>
                                    <select name="items[0][id_barang]" class="form-control item-barang" required>
                                        <option value="">-- Pilih Barang --</option>
                                    </select>
                                </td>
                                <td>
                                    <span class="item-gudang">-</span>
                                    <input type="hidden" name="items[0][id_gudang]" class="item-gudang-id">
                                </td>
                                <td><span class="badge badge-info item-stock">-</span></td>
                                <td>
                                    <input type="number" name="items[0][jumlah]" class="form-control item-jumlah"
                                        required min="1" value="1">
                                    <div class="invalid-feedback stock-error"></div>
                                </td>
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
        </div>

        <!-- Keterangan -->
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2"
                placeholder="Catatan tambahan (opsional)"></textarea>
        </div>

        <!-- Tombol Aksi -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="btnSubmit">
                <i class="fas fa-save"></i> Simpan Penjualan
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
        console.log("jQuery siap digunakan");

        let itemCount = 0;

        // Tampilkan info pelanggan saat dipilih
        $('#id_pelanggan').change(function () {
            let selected = $(this).find('option:selected');
            let alamat = selected.data('alamat');
            let telepon = selected.data('telepon');
            if (alamat || telepon) {
                $('#alamatPelanggan').text(alamat || '-');
                $('#teleponPelanggan').text(telepon || '-');
                $('#infoPelanggan').slideDown();
            } else {
                $('#infoPelanggan').slideUp();
            }
        });

        // Get barang by perusahaan
        function getBarangByPerusahaan(select) {
            let id_perusahaan;
            // Check if user is superadmin (role 5)
            <?php if ($this->session->userdata('id_role') == 5): ?>
                id_perusahaan = $('#id_perusahaan').val();
                if (!id_perusahaan) {
                    select.html('<option value="">-- Pilih Perusahaan terlebih dahulu --</option>');
                    return;
                }
            <?php else: ?>
                id_perusahaan = "<?php echo $this->session->userdata('id_perusahaan'); ?>";
            <?php endif; ?>

            console.log('Getting barang for company:', id_perusahaan);

            // Tambahkan indikator loading
            select.html('<option value="">-- Memuat data... --</option>');

            $.ajax({
                url: "<?php echo site_url('penjualan/get_barang_by_perusahaan'); ?>",
                method: "GET",
                data: { id_perusahaan: id_perusahaan },
                dataType: "json",
                success: function (data) {
                    console.log('Barang data:', data);
                    let options = '<option value="">-- Pilih Barang --</option>';
                    if (data.length > 0) {
                        data.forEach(function (item) {
                            options += `<option value="${item.id_barang}">${item.nama_barang} - ${item.sku}</option>`;
                        });
                    } else {
                        options += '<option value="">-- Barang tidak tersedia --</option>';
                    }
                    select.html(options);
                },
                error: function (xhr, status, error) {
                    console.error('Error getting barang:', error);
                    console.error('Response:', xhr.responseText);
                    select.html('<option value="">-- Gagal memuat data --</option>');
                }
            });
        }

        // Get stock by barang
        function getStockByBarang(select, id_barang) {
            console.log('Getting stock for barang:', id_barang);
            $.ajax({
                url: "<?php echo site_url('penjualan/get_stock_by_barang'); ?>",
                method: "GET",
                data: { id_barang: id_barang },
                dataType: "json",
                success: function (data) {
                    console.log('Stock data:', data);
                    // Jika ada stok, ambil gudang dengan stok terbanyak
                    if (data.length > 0) {
                        // Urutkan berdasarkan jumlah stok terbanyak
                        data.sort((a, b) => b.jumlah - a.jumlah);
                        // Ambil gudang dengan stok terbanyak
                        let gudangTerbanyak = data[0];
                        // Tampilkan nama gudang
                        select.closest('tr').find('.item-gudang').text(gudangTerbanyak.nama_gudang);
                        select.closest('tr').find('.item-gudang-id').val(gudangTerbanyak.id_gudang);
                        // Tampilkan stok
                        select.closest('tr').find('.item-stock').text(gudangTerbanyak.jumlah);
                    } else {
                        // Jika tidak ada stok
                        select.closest('tr').find('.item-gudang').text('Tidak ada stok');
                        select.closest('tr').find('.item-gudang-id').val('');
                        select.closest('tr').find('.item-stock').text('0');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error getting stock:', error);
                    select.closest('tr').find('.item-gudang').text('Gagal memuat stok');
                    select.closest('tr').find('.item-gudang-id').val('');
                    select.closest('tr').find('.item-stock').text('0');
                }
            });
        }

        // Add new item row
        $('#addItem').click(function () {
            console.log('Add item clicked');
            itemCount++;
            let newRow = `
        <tr class="item-row">
            <td>
                <select name="items[${itemCount}][id_barang]" class="form-control item-barang" required>
                    <option value="">-- Pilih Barang --</option>
                </select>
            </td>
            <td>
                <span class="item-gudang">-</span>
                <input type="hidden" name="items[${itemCount}][id_gudang]" class="item-gudang-id">
            </td>
            <td><span class="badge badge-info item-stock">-</span></td>
            <td>
                <input type="number" name="items[${itemCount}][jumlah]" class="form-control item-jumlah" required min="1" value="1">
                <div class="invalid-feedback stock-error"></div>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        `;
            $('#itemsTable tbody').append(newRow);
            console.log('New row added');
            // Populate the new dropdown with barang
            let newDropdown = $('#itemsTable tbody tr:last .item-barang');
            getBarangByPerusahaan(newDropdown);
        });

        // Remove item row
        $(document).on('click', '.remove-item', function () {
            $(this).closest('tr').remove();
        });

        // Barang change event
        $(document).on('change', '.item-barang', function () {
            let row = $(this).closest('tr');
            let id_barang = $(this).val();
            console.log('Barang changed to:', id_barang);
            // Reset gudang dan stok
            row.find('.item-gudang').text('-');
            row.find('.item-gudang-id').val('');
            row.find('.item-stock').text('-');
            if (id_barang) {
                // Get gudang dengan stok terbanyak
                getStockByBarang($(this), id_barang);
            }
        });

        // Validasi stok saat input jumlah
        $(document).on('input', '.item-jumlah', function () {
            let row = $(this).closest('tr');
            let jumlah = parseInt($(this).val()) || 0;
            let stockText = row.find('.item-stock').text();
            let stock = parseInt(stockText) || 0;
            if (jumlah > stock) {
                $(this).addClass('is-invalid');
                row.find('.stock-error').text('Stok hanya tersedia: ' + stock);
                row.find('.item-stock').removeClass('badge-info').addClass('badge-danger');
            } else {
                $(this).removeClass('is-invalid');
                row.find('.stock-error').text('');
                if (stock > 0) {
                    row.find('.item-stock').removeClass('badge-danger').addClass('badge-info');
                }
            }
        });

        // Form submit validation
        $('#formPenjualan').on('submit', function (e) {
            // Cek apakah ada item yang invalid
            if ($('.item-jumlah.is-invalid').length > 0) {
                e.preventDefault();
                alert('Perbaiki dulu kesalahan pada jumlah barang!');
                return false;
            }
            // Cek apakah ada item yang belum dipilih
            if ($('.item-barang').filter(function () { return !this.value; }).length > 0) {
                e.preventDefault();
                alert('Pilih barang untuk semua item!');
                return false;
            }
            // Cek apakah ada item yang jumlahnya 0
            let hasZeroQuantity = false;
            $('.item-jumlah').each(function () {
                if ($(this).val() == 0) {
                    hasZeroQuantity = true;
                }
            });
            if (hasZeroQuantity) {
                e.preventDefault();
                alert('Jumlah barang tidak boleh 0!');
                return false;
            }
            // Show loading
            $('#btnSubmit').html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);
        });

        // Initialize first row
        getBarangByPerusahaan($('.item-barang').first());

        // For superadmin, update barang dropdown when company changes
        <?php if ($this->session->userdata('id_role') == 5): ?>
            $('#id_perusahaan').change(function () {
                console.log('Company changed to:', $(this).val());
                // Update all barang dropdowns
                $('.item-barang').each(function () {
                    getBarangByPerusahaan($(this));
                });
            });
        <?php endif; ?>
    });
</script>