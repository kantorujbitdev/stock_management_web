<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <a href="<?php echo site_url('supplier'); ?>" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h5 class="mb-0 ml-3">
            <i class="fas fa-tags"></i>
            <?php echo isset($supplier) ? 'Edit Data Supplier' : 'Tambah Data Supplier'; ?>
        </h5>
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
                        <option value="<?php echo $p->id_perusahaan; ?>" <?php
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
            <textarea name="alamat" class="form-control" rows="3"
                required><?php echo isset($supplier) ? $supplier->alamat : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="telepon">Telepon</label>
            <input type="text" name="telepon" class="form-control"
                value="<?php echo isset($supplier) ? $supplier->telepon : ''; ?>">
        </div>

        <!-- Tombol -->
        <div class="form-group text-right mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('supplier'); ?>" class="btn btn-secondary px-4">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>