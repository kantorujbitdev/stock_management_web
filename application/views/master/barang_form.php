<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo $title; ?></h3>
    </div>
    <div class="card-body">
        <?php echo form_open_multipart('barang/add_process'); ?>

        <?php if ($this->session->userdata('id_role') == 5): ?>
            <div class="form-group">
                <label for="id_perusahaan">Perusahaan</label>
                <select name="id_perusahaan" id="id_perusahaan" class="form-control" required>
                    <option value="">-- Pilih Perusahaan --</option>
                    <?php foreach ($perusahaan as $p): ?>
                        <option value="<?php echo $p->id_perusahaan; ?>">
                            <?php echo $p->nama_perusahaan; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php else: ?>
            <input type="hidden" name="id_perusahaan" id="id_perusahaan" 
                value="<?php echo $this->session->userdata('id_perusahaan'); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="id_kategori">Kategori</label>
            <select name="id_kategori" id="id_kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" name="sku" id="sku" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label for="gambar">Gambar</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="<?php echo site_url('barang'); ?>" class="btn btn-secondary">Batal</a>

        <?php echo form_close(); ?>
    </div>
</div>

<script>
$(document).ready(function() {
        $.ajax({
            url: "<?php echo site_url('kategori/getKategori') ?>",
            type: "GET",
            dataType: "json",
            success: function(data) {
                console.log("Kategori:", data);
                var select = $("#id_kategori");
                select.empty();
                $.each(data, function(i, item) {
                    select.append('<option value="'+item.id+'">'+item.nama_kategori+'</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error("Error load kategori:", error);
            }
        });
});

    $(document).ready(function() {
        function loadKategori(id_perusahaan) {
            console.log("loadKategori jalan dengan id:", id_perusahaan);
            $.post("<?= base_url('barang/get_kategori_by_perusahaan') ?>", 
                {id_perusahaan: id_perusahaan}, 
                function(data) {
                    console.log("Response kategori:", data);
                    $('#id_kategori').html(data);
                }
            ).fail(function(xhr, status, error) {
                console.error("AJAX error:", status, error);
            });
        }

        <?php if ($this->session->userdata('id_role') != 5): ?>
            var perusahaan_id = "<?= $this->session->userdata('id_perusahaan') ?>";
            console.log("Session perusahaan_id:", perusahaan_id);
            loadKategori(perusahaan_id);
        <?php else: ?>
            $('#id_perusahaan').change(function() {
                var perusahaan_id = $(this).val();
                console.log("Perusahaan dipilih:", perusahaan_id);
                if (perusahaan_id) {
                    loadKategori(perusahaan_id);
                } else {
                    $('#id_kategori').html('<option value="">-- Pilih Kategori --</option>');
                }
            });
        <?php endif; ?>
    });

</script>
