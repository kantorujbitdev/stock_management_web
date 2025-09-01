<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?php echo isset($pelanggan) ? 'Edit Data Pelanggan' : 'Tambah Data Pelanggan'; ?>
        </h3>
    </div>
    <div class="card-body">
        <?php echo form_open(isset($pelanggan) ? 'pelanggan/edit_process' : 'pelanggan/add_process'); ?>
        
        <?php if (isset($pelanggan)): ?>
            <input type="hidden" name="id_pelanggan" value="<?php echo $pelanggan->id_pelanggan; ?>">
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
                                if (isset($pelanggan) && $pelanggan->id_perusahaan == $p->id_perusahaan) {
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
            <label for="nama_pelanggan">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" 
                   value="<?php echo isset($pelanggan) ? $pelanggan->nama_pelanggan : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required><?php echo isset($pelanggan) ? $pelanggan->alamat : ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="telepon">Telepon</label>
            <input type="text" name="telepon" class="form-control" 
                   value="<?php echo isset($pelanggan) ? $pelanggan->telepon : ''; ?>">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('pelanggan'); ?>" class="btn btn-secondary">Batal</a>
        </div>
        
        <?php echo form_close(); ?>
    </div>
</div>