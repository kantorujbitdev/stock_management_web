<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($pelanggan) ? 'Edit Pelanggan' : 'Tambah Pelanggan'; ?></h3>
    </div>
    <div class="card-body">
        <?php echo form_open(isset($pelanggan) ? 'pelanggan/edit_process' : 'pelanggan/add_process'); ?>
        
        <?php if (isset($pelanggan)): ?>
            <input type="hidden" name="id_pelanggan" value="<?php echo $pelanggan->id_pelanggan; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="nama_pelanggan">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" value="<?php echo isset($pelanggan) ? $pelanggan->nama_pelanggan : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required><?php echo isset($pelanggan) ? $pelanggan->alamat : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="telepon">Telepon</label>
            <input type="text" name="telepon" class="form-control" value="<?php echo isset($pelanggan) ? $pelanggan->telepon : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('pelanggan'); ?>" class="btn btn-secondary">Batal</a>
        </div>
        
        <?php echo form_close(); ?>
    </div>
</div>