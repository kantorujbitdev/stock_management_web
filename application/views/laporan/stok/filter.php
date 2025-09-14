<div class="card-body pt-0" id="filterCardBody" style="display: none;">
    <form method="get" action="<?php echo site_url('laporan_stok'); ?>" id="filterForm">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="id_perusahaan" class="form-label small text-muted">Perusahaan</label>
                <select name="id_perusahaan" class="form-control form-control-sm" id="id_perusahaan">
                    <option value="">-- Semua Perusahaan --</option>
                    <?php foreach ($perusahaan as $p): ?>
                        <option value="<?php echo $p->id_perusahaan; ?>" <?php echo (isset($filter['id_perusahaan']) && $filter['id_perusahaan'] == $p->id_perusahaan) ? 'selected' : ''; ?>>
                            <?php echo $p->nama_perusahaan; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="id_gudang" class="form-label small text-muted">Gudang</label>
                <select name="id_gudang" class="form-control form-control-sm" id="id_gudang">
                    <option value="">-- Semua Gudang --</option>
                    <?php if (isset($gudang) && is_array($gudang)): ?>
                        <?php foreach ($gudang as $g): ?>
                            <option value="<?php echo $g->id_gudang; ?>" <?php echo (isset($filter['id_gudang']) && $filter['id_gudang'] == $g->id_gudang) ? 'selected' : ''; ?>>
                                <?php echo $g->nama_gudang; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="id_kategori" class="form-label small text-muted">Kategori</label>
                <select name="id_kategori" class="form-control form-control-sm" id="id_kategori">
                    <option value="">-- Semua Kategori --</option>
                    <?php if (isset($kategori) && is_array($kategori)): ?>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?php echo $k->id_kategori; ?>" <?php echo (isset($filter['id_kategori']) && $filter['id_kategori'] == $k->id_kategori) ? 'selected' : ''; ?>>
                                <?php echo $k->nama_kategori; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="stock_status" class="form-label small text-muted">Status Stok</label>
                <select name="stock_status" class="form-control form-control-sm" id="stock_status">
                    <option value="">-- Semua Status --</option>
                    <option value="normal" <?php echo (isset($filter['stock_status']) && $filter['stock_status'] == 'normal') ? 'selected' : ''; ?>>
                        Normal
                    </option>
                    <option value="low" <?php echo (isset($filter['stock_status']) && $filter['stock_status'] == 'low') ? 'selected' : ''; ?>>
                        Stok Menipis
                    </option>
                    <option value="over" <?php echo (isset($filter['stock_status']) && $filter['stock_status'] == 'over') ? 'selected' : ''; ?>>
                        Stok Berlebih
                    </option>
                    <option value="empty" <?php echo (isset($filter['stock_status']) && $filter['stock_status'] == 'empty') ? 'selected' : ''; ?>>
                        Stok Habis
                    </option>
                </select>
            </div>
        </div>

        <!-- Tombol Reset -->
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-sm btn-outline-danger" id="resetFilter">
                    <i class="fas fa-redo mr-1"></i> Reset Filter
                </button>
            </div>
        </div>
    </form>
</div>