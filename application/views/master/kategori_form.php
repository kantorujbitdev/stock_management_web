<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?php echo isset($kategori) ? 'Edit Data Master Kategori' : 'Tambah Data Master Kategori'; ?>
        </h3>
    </div>
    <div class="card-body">
        <?php echo form_open(isset($kategori) ? 'kategori/edit_process' : 'kategori/add_process'); ?>
        
        <?php if (isset($kategori)): ?>
            <input type="hidden" name="id_kategori" value="<?php echo $kategori->id_kategori; ?>">
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
                                if (isset($kategori) && $kategori->id_perusahaan == $p->id_perusahaan) {
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
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="nama_kategori">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" 
                   value="<?php echo isset($kategori) ? $kategori->nama_kategori : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?php echo isset($kategori) ? $kategori->deskripsi : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('kategori'); ?>" class="btn btn-secondary">Batal</a>
        </div>
        
        <?php echo form_close(); ?>
    </div>
</div>
