<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Penjualan</h3>
    </div>
    <div class="card-body">
        <?php echo form_open('penjualan/add_process'); ?>

        <div class="form-group">
            <label for="id_pelanggan">Pelanggan</label>
            <select name="id_pelanggan" class="form-control" required>
                <option value="">-- Pilih Pelanggan --</option>
                <?php foreach ($pelanggan as $p): ?>
                    <option value="<?php echo $p->id_pelanggan; ?>"><?php echo $p->nama_pelanggan; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_perusahaan">Perusahaan</label>
            <select name="id_perusahaan" class="form-control" id="id_perusahaan" required>
                <option value="">-- Pilih Perusahaan --</option>
                <?php foreach ($perusahaan as $p): ?>
                    <option value="<?php echo $p->id_perusahaan; ?>"><?php echo $p->nama_perusahaan; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal_penjualan">Tanggal Penjualan</label>
            <input type="date" name="tanggal_penjualan" class="form-control" value="<?php echo date('Y-m-d'); ?>"
                required>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('penjualan'); ?>" class="btn btn-secondary">Batal</a>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>