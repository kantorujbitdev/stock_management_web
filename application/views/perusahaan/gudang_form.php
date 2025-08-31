<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo isset($gudang) ? 'Edit Gudang' : 'Tambah Gudang' ?></h1>
    
    <?php echo form_open(isset($gudang) ? 'gudang/edit_process' : 'gudang/add_process'); ?>
    <?php if (isset($gudang)): ?>
        <input type="hidden" name="id_gudang" value="<?php echo $gudang->id_gudang ?>">
    <?php endif; ?>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_perusahaan">Perusahaan</label>
                        <select class="form-control" id="id_perusahaan" name="id_perusahaan" required>
                            <option value="">-- Pilih Perusahaan --</option>
                            <?php foreach ($perusahaan as $p): ?>
                                <option value="<?php echo $p->id_perusahaan ?>" <?php echo isset($gudang) && $gudang->id_perusahaan == $p->id_perusahaan ? 'selected' : '' ?>><?php echo $p->nama_perusahaan ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo form_error('id_perusahaan', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama_gudang">Nama Gudang</label>
                        <input type="text" class="form-control" id="nama_gudang" name="nama_gudang" value="<?php echo isset($gudang) ? $gudang->nama_gudang : set_value('nama_gudang') ?>" required>
                        <?php echo form_error('nama_gudang', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo isset($gudang) ? $gudang->alamat : set_value('alamat') ?></textarea>
                        <?php echo form_error('alamat', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="telepon">Telepon</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" value="<?php echo isset($gudang) ? $gudang->telepon : set_value('telepon') ?>" required>
                        <?php echo form_error('telepon', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('gudang') ?>" class="btn btn-secondary">Batal</a>
        </div>
    </div>
    
    <?php echo form_close(); ?>
</div>