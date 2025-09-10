<div class="form-group text-left mt-4">
    <?php echo back_button('perusahaan'); ?>
</div>
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <?php echo responsive_title(isset($perusahaan) ? 'Edit Data Perusahaan' : 'Tambah Data Perusahaan') ?>
    </div>
    <div class="card-body px-4 py-4">
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
            <textarea name="alamat" class="form-control"
                rows="3"><?php echo isset($perusahaan) ? $perusahaan->alamat : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="telepon">Telepon</label>
            <input type="text" name="telepon" class="form-control"
                value="<?php echo isset($perusahaan) ? $perusahaan->telepon : ''; ?>">
        </div>

        <!-- Tombol -->
        <div class="form-group text-right mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('gudang'); ?>" class="btn btn-secondary px-4">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>