<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($stok_awal) ? 'Edit Stok Awal' : 'Tambah Stok Awal'; ?></h3>
    </div>
    <div class="card-body">
        <?php echo form_open(isset($stok_awal) ? 'stok_awal/edit_process' : 'stok_awal/add_process'); ?>
        
        <?php if (isset($stok_awal)): ?>
            <input type="hidden" name="id_stok_awal" value="<?php echo $stok_awal->id_stok_awal; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="id_perusahaan">Perusahaan</label>
            <select name="id_perusahaan" class="form-control" id="id_perusahaan" required>
                <option value="">-- Pilih Perusahaan --</option>
                <?php foreach ($perusahaan as $p): ?>
                    <option value="<?php echo $p->id_perusahaan; ?>" <?php echo (isset($stok_awal) && $stok_awal->id_perusahaan == $p->id_perusahaan) ? 'selected' : ''; ?>>
                        <?php echo $p->nama_perusahaan; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="id_gudang">Gudang</label>
            <select name="id_gudang" class="form-control" id="id_gudang" required>
                <option value="">-- Pilih Gudang --</option>
                <?php if (isset($gudang)): ?>
                    <?php foreach ($gudang as $g): ?>
                        <option value="<?php echo $g->id_gudang; ?>" <?php echo (isset($stok_awal) && $stok_awal->id_gudang == $g->id_gudang) ? 'selected' : ''; ?>>
                            <?php echo $g->nama_gudang; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="id_barang">Barang</label>
            <select name="id_barang" class="form-control" id="id_barang" required>
                <option value="">-- Pilih Barang --</option>
                <?php if (isset($barang)): ?>
                    <?php foreach ($barang as $b): ?>
                        <option value="<?php echo $b->id_barang; ?>" <?php echo (isset($stok_awal) && $stok_awal->id_barang == $b->id_barang) ? 'selected' : ''; ?>>
                            <?php echo $b->nama_barang; ?> - <?php echo $b->sku; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="qty_awal">Qty Awal</label>
            <input type="number" name="qty_awal" class="form-control" value="<?php echo isset($stok_awal) ? $stok_awal->qty_awal : ''; ?>" required min="0">
        </div>
        
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"><?php echo isset($stok_awal) ? $stok_awal->keterangan : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('stok_awal'); ?>" class="btn btn-secondary">Batal</a>
        </div>
        
        <?php echo form_close(); ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#id_perusahaan').change(function() {
        var id_perusahaan = $(this).val();
        
        if (id_perusahaan != '') {
            // Get gudang by perusahaan
            $.ajax({
                url: "<?php echo site_url('stok_awal/get_gudang_by_perusahaan') ?>",
                method: "POST",
                data: {id_perusahaan: id_perusahaan},
                success: function(data) {
                    $('#id_gudang').html(data);
                }
            });
            
            // Get barang by perusahaan
            $.ajax({
                url: "<?php echo site_url('stok_awal/get_barang_by_perusahaan') ?>",
                method: "POST",
                data: {id_perusahaan: id_perusahaan},
                success: function(data) {
                    $('#id_barang').html(data);
                }
            });
        } else {
            $('#id_gudang').html('<option value="">-- Pilih Gudang --</option>');
            $('#id_barang').html('<option value="">-- Pilih Barang --</option>');
        }
    });
});
</script>