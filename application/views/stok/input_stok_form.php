<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <a href="<?php echo site_url('stok_awal'); ?>" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h5 class="mb-0 ml-3">
            <i class="fas fa-tags"></i>
            Input Stok Awal - <?php echo $barang->nama_barang; ?>
        </h5>
    </div>

    <div class="card-body">
        <?php echo form_open('stok_awal/process_input_stok'); ?>

        <input type="hidden" name="id_barang" value="<?php echo $barang->id_barang; ?>">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Barang</label>
                    <input type="text" class="form-control"
                        value="<?php echo $barang->nama_barang; ?> (<?php echo $barang->sku; ?>)" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Perusahaan</label>
                    <input type="text" class="form-control" value="<?php echo $barang->nama_perusahaan; ?>" readonly>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="id_gudang">Gudang <span class="text-danger">*</span></label>
            <select name="id_gudang" class="form-control" required>
                <option value="">-- Pilih Gudang --</option>
                <?php foreach ($gudang as $g): ?>
                    <option value="<?php echo $g->id_gudang; ?>"><?php echo $g->nama_gudang; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="qty_awal">Qty Awal <span class="text-danger">*</span></label>
                    <input type="number" name="qty_awal" class="form-control" required min="1" value="1">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                </div>
            </div>
        </div>

        <!-- Tombol -->
        <div class="form-group text-right mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('stok_awal'); ?>" class="btn btn-secondary px-4">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>