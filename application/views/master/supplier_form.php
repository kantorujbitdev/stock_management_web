<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier'; ?></h3>
    </div>
    <div class="card-body">
        <?php echo form_open(isset($supplier) ? 'supplier/edit_process' : 'supplier/add_process'); ?>
        
        <?php if (isset($supplier)): ?>
            <input type="hidden" name="id_supplier" value="<?php echo $supplier->id_supplier; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="nama_supplier">Nama Supplier</label>
            <input type="text" name="nama_supplier" class="form-control" value="<?php echo isset($supplier) ? $supplier->nama_supplier : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required><?php echo isset($supplier) ? $supplier->alamat : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="telepon">Telepon</label>
            <input type="text" name="telepon" class="form-control" value="<?php echo isset($supplier) ? $supplier->telepon : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="status_aktif">Status</label>
            <select name="status_aktif" class="form-control">
                <option value="1" <?php echo (isset($supplier) && $supplier->status_aktif == 1) ? 'selected' : ''; ?>>Aktif</option>
                <option value="0" <?php echo (isset($supplier) && $supplier->status_aktif == 0) ? 'selected' : ''; ?>>Tidak Aktif</option>
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('supplier'); ?>" class="btn btn-secondary">Batal</a>
        </div>
        
        <?php echo form_close(); ?>
    </div>
</div>