<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?php echo isset($stok_awal) ? 'Edit Stok Awal' : 'Tambah Stok Awal'; ?></h3>
    </div>
    <div class="card-body">
        <?php echo form_open(isset($stok_awal) ? 'stok_awal/edit_process' : 'stok_awal/add_process'); ?>

        <?php if (isset($stok_awal)): ?>
            <input type="hidden" name="id_stok_awal" value="<?php echo $stok_awal->id_stok_awal; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="id_perusahaan">Perusahaan <span class="text-danger">*</span></label>
            <select name="id_perusahaan"
                class="form-control <?php echo form_error('id_perusahaan') ? 'is-invalid' : ''; ?>" id="id_perusahaan"
                required>
                <option value="">-- Pilih Perusahaan --</option>
                <?php foreach ($perusahaan as $p): ?>
                    <option value="<?php echo $p->id_perusahaan; ?>" <?php echo (isset($stok_awal) && $stok_awal->id_perusahaan == $p->id_perusahaan) ? 'selected' : ''; ?>>
                        <?php echo $p->nama_perusahaan; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php echo form_error('id_perusahaan', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
            <label for="id_gudang">Gudang <span class="text-danger">*</span></label>
            <select name="id_gudang" class="form-control <?php echo form_error('id_gudang') ? 'is-invalid' : ''; ?>"
                id="id_gudang" required disabled>
                <option value="">-- Pilih Perusahaan Dulu --</option>
                <?php if (isset($gudang)): ?>
                    <?php foreach ($gudang as $g): ?>
                        <option value="<?php echo $g->id_gudang; ?>" <?php echo (isset($stok_awal) && $stok_awal->id_gudang == $g->id_gudang) ? 'selected' : ''; ?>>
                            <?php echo $g->nama_gudang; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php echo form_error('id_gudang', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
            <label for="id_barang">Barang <span class="text-danger">*</span></label>
            <select name="id_barang" class="form-control <?php echo form_error('id_barang') ? 'is-invalid' : ''; ?>"
                id="id_barang" required disabled>
                <option value="">-- Pilih Gudang Dulu --</option>
                <?php if (isset($barang)): ?>
                    <?php foreach ($barang as $b): ?>
                        <option value="<?php echo $b->id_barang; ?>" <?php echo (isset($stok_awal) && $stok_awal->id_barang == $b->id_barang) ? 'selected' : ''; ?>>
                            <?php echo $b->nama_barang; ?> - <?php echo $b->sku; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php echo form_error('id_barang', '<div class="invalid-feedback">', '</div>'); ?>
        </div>
        <div class="form-group">
            <label for="qty_awal">Qty Awal <span class="text-danger">*</span></label>
            <input type="number" name="qty_awal"
                class="form-control <?php echo form_error('qty_awal') ? 'is-invalid' : ''; ?>"
                value="<?php echo isset($stok_awal) ? $stok_awal->qty_awal : ''; ?>" required min="0">
            <?php echo form_error('qty_awal', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control <?php echo form_error('keterangan') ? 'is-invalid' : ''; ?>"
                rows="3"><?php echo isset($stok_awal) ? $stok_awal->keterangan : ''; ?></textarea>
            <?php echo form_error('keterangan', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('stok_awal'); ?>" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>


<script>
    $(document).ready(function () {
        console.log("Document ready - Stok Awal Form");

        // Debug: Cek status awal dropdown
        console.log("Status awal gudang:", $('#id_gudang').prop('disabled'));
        console.log("Status awal barang:", $('#id_barang').prop('disabled'));

        // Event untuk perusahaan
        $('#id_perusahaan').on('change', function () {
            var id_perusahaan = $(this).val();
            console.log("Perusahaan dipilih:", id_perusahaan);

            // Reset dropdown gudang dan barang
            $('#id_gudang').html('<option value="">-- Loading... --</option>').prop('disabled', true);
            $('#id_barang').html('<option value="">-- Pilih Gudang Dulu --</option>').prop('disabled', true);

            if (id_perusahaan != '') {
                // AJAX untuk gudang
                $.ajax({
                    url: "<?php echo site_url('stok_awal/get_gudang_by_perusahaan'); ?>",
                    type: "GET",
                    data: { id_perusahaan: id_perusahaan },
                    dataType: "html",
                    success: function (response) {
                        console.log("Response gudang:", response);
                        $('#id_gudang').html(response).prop('disabled', false);
                        console.log("Gudang dropdown di-enable");
                    },
                    error: function (xhr, status, error) {
                        console.error("Error gudang:", error);
                        $('#id_gudang').html('<option value="">-- Error --</option>');
                    }
                });
            } else {
                $('#id_gudang').html('<option value="">-- Pilih Perusahaan Dulu --</option>');
                $('#id_barang').html('<option value="">-- Pilih Gudang Dulu --</option>');
            }
        });

        // Event untuk gudang - PAKAI EVENT DELEGATION
        $(document).on('change', '#id_gudang', function () {
            var id_perusahaan = $('#id_perusahaan').val();
            var id_gudang = $(this).val();
            console.log("Gudang dipilih:", id_gudang);

            // Reset dropdown barang
            $('#id_barang').html('<option value="">-- Loading... --</option>').prop('disabled', true);
            console.log("Barang dropdown di-reset dan di-disable");

            if (id_gudang != '') {
                // AJAX untuk barang
                $.ajax({
                    url: "<?php echo site_url('stok_awal/get_barang_by_perusahaan'); ?>",
                    type: "GET",
                    data: { id_perusahaan: id_perusahaan },
                    dataType: "html",
                    success: function (response) {
                        console.log("Response barang:", response);

                        // Cek apakah ada data
                        if (response.includes('<option value=')) {
                            $('#id_barang').html(response).prop('disabled', false);
                            console.log("Barang dropdown di-enable dan diisi");
                        } else {
                            $('#id_barang').html('<option value="">-- Tidak Ada Data --</option>').prop('disabled', true);
                            console.log("Tidak ada data barang");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error barang:", error);
                        $('#id_barang').html('<option value="">-- Error --</option>');
                    }
                });
            } else {
                $('#id_barang').html('<option value="">-- Pilih Gudang Dulu --</option>');
            }
        });


        // Jika ini halaman edit, trigger perubahan dropdown
        <?php if (isset($stok_awal)): ?>
            console.log("Mode edit, siapkan dropdown...");

            // Enable dropdown gudang dan barang karena data sudah ada
            $('#id_gudang').prop('disabled', false);
            $('#id_barang').prop('disabled', false);

            // Set nilai perusahaan, gudang, dan barang sesuai data
            $('#id_perusahaan').val('<?php echo $stok_awal->id_perusahaan; ?>');
            $('#id_gudang').val('<?php echo $stok_awal->id_gudang; ?>');
            $('#id_barang').val('<?php echo $stok_awal->id_barang; ?>');

            console.log("Dropdown di-set dengan data existing");
        <?php endif; ?>
    });
</script>