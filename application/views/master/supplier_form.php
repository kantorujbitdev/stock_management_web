<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?php echo isset($supplier) ? 'Edit Data Supplier' : 'Tambah Data Supplier'; ?>
        </h3>
    </div>
    <div class="card-body">
        <?php echo form_open(isset($supplier) ? 'supplier/edit_process' : 'supplier/add_process'); ?>
        
        <?php if (isset($supplier)): ?>
            <input type="hidden" name="id_supplier" value="<?php echo $supplier->id_supplier; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <?php if ($this->session->userdata('id_role') == 5): ?>
                <!-- Super Admin harus pilih perusahaan -->
                <label for="id_perusahaan">Perusahaan</label>
                <select name="id_perusahaan" class="form-control" required>
                    <option value="">-- Pilih Perusahaan --</option>
                    <?php foreach ($perusahaan as $p): ?>
                        <option value="<?php echo $p->id_perusahaan; ?>"
                            <?php 
                                if (isset($supplier) && $supplier->id_perusahaan == $p->id_perusahaan) {
                                    echo 'selected';
                                }
                            ?>>
                            <?php echo $p->nama_perusahaan; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <!-- Role lain otomatis pakai perusahaan dari session -->
                <input type="hidden" name="id_perusahaan" value="<?php echo $this->session->userdata('id_perusahaan'); ?>">
                <div class="form-group">
                    <label>Perusahaan</label>
                    <input type="text" class="form-control" value="<?php echo $perusahaan[0]->nama_perusahaan; ?>" readonly>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="nama_supplier">Nama Supplier</label>
            <input type="text" name="nama_supplier" class="form-control" 
                   value="<?php echo isset($supplier) ? $supplier->nama_supplier : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required><?php echo isset($supplier) ? $supplier->alamat : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="telepon">Telepon</label>
            <input type="text" name="telepon" class="form-control" 
                   value="<?php echo isset($supplier) ? $supplier->telepon : ''; ?>">
        </div>
        
        <?php if (isset($supplier)): ?>
        <div class="form-group">
            <label for="status_aktif">Status Aktif</label>
            <select name="status_aktif" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="1" <?php echo (isset($supplier) && $supplier->status_aktif == '1') ? 'selected' : ''; ?>>Aktif</option>
                <option value="0" <?php echo (isset($supplier) && $supplier->status_aktif == '0') ? 'selected' : ''; ?>>Tidak Aktif</option>
            </select>
        </div>
        <?php endif; ?>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('supplier'); ?>" class="btn btn-secondary">Batal</a>
        </div>
        
        <?php echo form_close(); ?>
    </div>
</div>