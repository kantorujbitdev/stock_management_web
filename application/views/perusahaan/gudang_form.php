<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <a href="<?php echo site_url('gudang'); ?>" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h5 class="mb-0 ml-3">
            <i class="fas fa-tags"></i>
            <?php echo isset($gudang) ? 'Edit Data Gudang' : 'Tambah Data Gudang'; ?>
        </h5>
    </div>

    <div class="card-body px-4 py-4">
        <?php echo form_open(isset($gudang) ? 'gudang/edit_process' : 'gudang/add_process'); ?>
        <?php if (isset($gudang)): ?>
            <input type="hidden" name="id_gudang" value="<?php echo $gudang->id_gudang ?>">
        <?php endif; ?>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php if ($this->session->userdata('id_role') == 5): ?>
                        <!-- Super Admin harus pilih perusahaan -->
                        <label for="id_perusahaan">Perusahaan</label>
                        <select name="id_perusahaan" class="form-control" required>
                            <option value="">-- Pilih Perusahaan --</option>
                            <?php foreach ($perusahaan as $p): ?>
                                <option value="<?php echo $p->id_perusahaan; ?>" <?php
                                   if (isset($gudang) && $gudang->id_perusahaan == $p->id_perusahaan) {
                                       echo 'selected';
                                   }
                                   ?>>
                                    <?php echo $p->nama_perusahaan; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <!-- Role lain otomatis pakai perusahaan dari session -->
                        <input type="hidden" name="id_perusahaan"
                            value="<?php echo $this->session->userdata('id_perusahaan'); ?>">
                        <div class="form-group">
                            <label>Perusahaan</label>
                            <input type="text" class="form-control" value="<?php echo $perusahaan[0]->nama_perusahaan; ?>"
                                readonly>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"
                        required><?php echo isset($gudang) ? $gudang->alamat : set_value('alamat') ?></textarea>
                    <?php echo form_error('alamat', '<small class="text-danger">', '</small>'); ?>
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama_gudang">Nama Gudang</label>
                    <input type="text" class="form-control" id="nama_gudang" name="nama_gudang"
                        value="<?php echo isset($gudang) ? $gudang->nama_gudang : set_value('nama_gudang') ?>" required>
                    <?php echo form_error('nama_gudang', '<small class="text-danger">', '</small>'); ?>
                </div>

                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="text" class="form-control" id="telepon" name="telepon"
                        value="<?php echo isset($gudang) ? $gudang->telepon : set_value('telepon') ?>" required>
                    <?php echo form_error('telepon', '<small class="text-danger">', '</small>'); ?>
                </div>
            </div>
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