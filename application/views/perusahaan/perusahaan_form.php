<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?php echo isset($perusahaan) ? 'Edit Data Perusahaan' : 'Tambah Data Perusahaan'; ?>
        </h3>
    </div>
    <div class="card-body">
        <?php echo form_open(isset($perusahaan) ? 'perusahaan/edit_process' : 'perusahaan/add_process'); ?>
        
        <?php if (isset($perusahaan)): ?>
            <input type="hidden" name="id_perusahaan" value="<?php echo $perusahaan->id_perusahaan; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="nama_perusahaan">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" class="form-control" 
                   value="<?php echo isset($perusahaan) ? $perusahaan->nama_perusahaan : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3"><?php echo isset($perusahaan) ? $perusahaan->alamat : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="telepon">Telepon</label>
            <input type="text" name="telepon" class="form-control" 
                   value="<?php echo isset($perusahaan) ? $perusahaan->telepon : ''; ?>">
        </div>
    
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('perusahaan'); ?>" class="btn btn-secondary">Batal</a>
        </div>
        
        <?php echo form_close(); ?>
    </div>
</div>