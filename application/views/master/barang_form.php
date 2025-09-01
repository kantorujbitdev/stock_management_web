<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?php echo isset($barang) ? 'Edit Data Barang' : 'Tambah Data Barang'; ?>
        </h3>
    </div>
    <div class="card-body">
        <?php echo form_open_multipart(isset($barang) ? 'barang/edit_process' : 'barang/add_process'); ?>
        
        <?php if (isset($barang)): ?>
            <input type="hidden" name="id_barang" value="<?php echo $barang->id_barang; ?>">
            <input type="hidden" name="gambar_lama" value="<?php echo $barang->gambar; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <?php if ($this->session->userdata('id_role') == 5): ?>
                <!-- Super Admin harus pilih perusahaan -->
                <label for="id_perusahaan">Perusahaan</label>
                <select name="id_perusahaan" class="form-control" id="id_perusahaan" required>
                    <option value="">-- Pilih Perusahaan --</option>
                    <?php foreach ($perusahaan as $p): ?>
                        <option value="<?php echo $p->id_perusahaan; ?>"><?php echo $p->nama_perusahaan; ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <!-- Role lain otomatis pakai perusahaan dari session -->
                <input type="hidden" name="id_perusahaan" id="id_perusahaan" value="<?php echo $this->session->userdata('id_perusahaan'); ?>">
                <div class="form-group">
                    <label>Perusahaan</label>
                    <input type="text" class="form-control" value="<?php echo $perusahaan[0]->nama_perusahaan; ?>" readonly>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="id_kategori">Kategori</label>
            <select name="id_kategori" class="form-control" id="id_kategori" required 
                    <?php echo ($this->session->userdata('id_role') == 5) ? 'disabled' : ''; ?>>
                <option value="">-- Pilih Kategori --</option>
                <?php if (!empty($kategori)): ?>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?php echo $k->id_kategori; ?>"
                            <?php 
                                if (isset($barang) && $barang->id_kategori == $k->id_kategori) {
                                    echo 'selected';
                                }
                            ?>>
                            <?php echo $k->nama_kategori; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <small class="form-text text-muted">
                <?php echo ($this->session->userdata('id_role') == 5) ? 'Pilih perusahaan terlebih dahulu' : ''; ?>
            </small>
        </div>
        
        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" 
                   value="<?php echo isset($barang) ? $barang->nama_barang : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" name="sku" class="form-control" 
                   value="<?php echo isset($barang) ? $barang->sku : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?php echo isset($barang) ? $barang->deskripsi : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="gambar">Gambar</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="gambar" id="gambar">
                <label class="custom-file-label" for="gambar">Pilih file</label>
            </div>
            <?php if (isset($barang) && $barang->gambar): ?>
                <div class="mt-2">
                    <img src="<?php echo base_url('uploads/barang/'.$barang->gambar); ?>" class="img-thumbnail" width="100">
                </div>
            <?php endif; ?>
        </div>
        
        <!-- <?php if (isset($barang)): ?>
        <div class="form-group">
            <label for="aktif">Status Aktif</label>
            <select name="aktif" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="1" <?php echo (isset($barang) && $barang->aktif == '1') ? 'selected' : ''; ?>>Aktif</option>
                <option value="0" <?php echo (isset($barang) && $barang->aktif == '0') ? 'selected' : ''; ?>>Tidak Aktif</option>
            </select>
        </div>
        <?php endif; ?> -->
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('barang'); ?>" class="btn btn-secondary">Batal</a>
        </div>
        
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Pastikan jQuery sudah di-load -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Debug: Pastikan jQuery sudah terload
console.log("jQuery version: " + $.fn.jquery);

$(document).ready(function() {
    console.log("Document ready, initializing scripts...");
    
    // Debug: Pastikan elemen ada
    console.log("Perusahaan dropdown found: " + $('#id_perusahaan').length);
    console.log("Kategori dropdown found: " + $('#id_kategori').length);
    
    // Untuk Super Admin: ketika perusahaan dipilih, aktifkan dropdown kategori dan load kategori
    $('#id_perusahaan').on('change', function() {
        console.log("Perusahaan changed");
        var id_perusahaan = $(this).val();
        console.log("Selected Perusahaan ID: " + id_perusahaan);
        
        if (id_perusahaan) {
            console.log("Enabling category dropdown");
            // Aktifkan dropdown kategori
            $('#id_kategori').prop('disabled', false);
            
            // Load kategori via AJAX
            $.ajax({
                url: '<?php echo site_url('barang/get_kategori_by_perusahaan'); ?>',
                type: 'GET',
                data: {id_perusahaan: id_perusahaan},
                dataType: 'html',
                beforeSend: function() {
                    console.log("Sending AJAX request...");
                },
                success: function(response) {
                    console.log("AJAX response: " + response);
                    $('#id_kategori').html(response);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log('AJAX Error: ' + xhr.status + ' ' + thrownError);
                    alert('Gagal memuat kategori. Status: ' + xhr.status);
                }
            });
        } else {
            console.log("No perusahaan selected, disabling category dropdown");
            // Nonaktifkan dropdown kategori jika perusahaan tidak dipilih
            $('#id_kategori').prop('disabled', true);
            $('#id_kategori').html('<option value="">-- Pilih Kategori --</option>');
        }
    });
    
    // Tampilkan nama file yang dipilih untuk gambar
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
        console.log("File selected: " + fileName);
    });
});
</script>