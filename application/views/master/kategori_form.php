<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <a href="<?php echo site_url('kategori'); ?>" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h5 class="mb-0 ml-3">
            <i class="fas fa-tags"></i>
            <?php echo isset($kategori) ? 'Edit Data Master Kategori' : 'Tambah Data Master Kategori'; ?>
        </h5>
    </div>

    <div class="card-body px-4 py-4">
        <?php echo form_open(isset($kategori) ? 'kategori/edit_process' : 'kategori/add_process'); ?>

        <?php if (isset($kategori)): ?>
            <input type="hidden" name="id_kategori" value="<?php echo $kategori->id_kategori; ?>">
        <?php endif; ?>
        <!-- Row untuk Perusahaan & Nama Kategori -->
        <div class="form-row">
            <!-- Perusahaan -->
            <div class="form-group col-md-6">
                <?php if ($this->session->userdata('id_role') == 5): ?>
                    <label for="id_perusahaan" class="font-weight-bold">Perusahaan <span
                            class="text-danger">*</span></label>
                    <select name="id_perusahaan" class="form-control select2" required>
                        <option value="">-- Pilih Perusahaan --</option>
                        <?php foreach ($perusahaan as $p): ?>
                            <option value="<?php echo $p->id_perusahaan; ?>" <?php echo (isset($kategori) && $kategori->id_perusahaan == $p->id_perusahaan) ? 'selected' : ''; ?>>
                                <?php echo $p->nama_perusahaan; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input type="hidden" name="id_perusahaan"
                        value="<?php echo $this->session->userdata('id_perusahaan'); ?>">
                    <label class="font-weight-bold">Perusahaan</label>
                    <input type="text" class="form-control bg-light" value="<?php echo $perusahaan[0]->nama_perusahaan; ?>"
                        readonly>
                <?php endif; ?>
            </div>

            <!-- Nama Kategori -->
            <div class="form-group col-md-6">
                <label for="nama_kategori" class="font-weight-bold">Nama Kategori <span
                        class="text-danger">*</span></label>
                <input type="text" name="nama_kategori" class="form-control" placeholder="Masukkan nama kategori..."
                    value="<?php echo isset($kategori) ? $kategori->nama_kategori : ''; ?>" required>
            </div>
        </div>


        <!-- Deskripsi -->
        <div class="form-group">
            <label for="deskripsi" class="font-weight-bold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"
                placeholder="Tuliskan deskripsi kategori..."><?php echo isset($kategori) ? $kategori->deskripsi : ''; ?></textarea>
        </div>

        <!-- Tombol -->
        <div class="form-group text-right mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('kategori'); ?>" class="btn btn-secondary px-4">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>