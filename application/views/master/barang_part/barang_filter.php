<!-- Filter dan Pencarian -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center" id="filterHeader"
        style="cursor:pointer;">
        <h6 class="mb-0 text-primary font-weight-bold">
            <i class="fas fa-filter mr-2"></i>Filter Barang
        </h6>
        <button type="button" class="btn btn-sm btn-outline-primary no-hover" id="toggleFilter" disabled>
            <i class="fas fa-chevron-down" id="toggleIcon"></i>
        </button>
    </div>

    <!-- Pencarian (selalu tampil) -->
    <div class="card-body pt-3 pb-2">
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <label class="form-label small text-muted">Pencarian</label>
                <div class="input-group">
                    <input type="text" id="searchBarang" class="form-control" placeholder="Cari barang...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tambahan (bisa disembunyikan) -->
    <div class="card-body pt-0" id="filterCardBody" style="display: none;">
        <div class="row align-items-end">
            <!-- Perusahaan (untuk Super Admin) -->
            <?php if ($this->session->userdata('id_role') == 5): ?>
                <div class="col-md-3 mb-3">
                    <label class="form-label small text-muted">Perusahaan</label>
                    <select id="filterPerusahaan" class="form-control form-control-sm">
                        <option value="">Semua</option>
                        <?php foreach ($perusahaan as $p): ?>
                            <option value="<?php echo $p->id_perusahaan; ?>" <?php echo ($filter['id_perusahaan'] == $p->id_perusahaan) ? 'selected' : ''; ?>>
                                <?php echo substr($p->nama_perusahaan, 0, 25) . '...'; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <!-- Gudang -->
            <div class="col-md-3 mb-3">
                <label class="form-label small text-muted">Gudang</label>
                <select id="filterGudang" class="form-control form-control-sm">
                    <option value="">Semua Gudang</option>
                    <?php if (isset($gudang) && is_array($gudang)): ?>
                        <?php foreach ($gudang as $g): ?>
                            <option value="<?php echo $g->id_gudang; ?>" <?php echo ($filter['id_gudang'] == $g->id_gudang) ? 'selected' : ''; ?>>
                                <?php echo $g->nama_gudang; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Kategori -->
            <div class="col-md-3 mb-3">
                <label class="form-label small text-muted">Kategori</label>
                <select id="filterKategori" class="form-control form-control-sm">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?php echo $k->id_kategori; ?>" <?php echo ($filter['id_kategori'] == $k->id_kategori) ? 'selected' : ''; ?>>
                            <?php echo $k->nama_kategori; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status Barang -->
            <div class="col-md-3 mb-3">
                <label class="form-label small text-muted">Status Barang</label>
                <select id="filterStatus" class="form-control form-control-sm">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <!-- Stok -->
            <div class="col-md-3 mb-3">
                <label class="form-label small text-muted">Stok</label>
                <select id="filterStok" class="form-control form-control-sm">
                    <option value="">Semua Stok</option>
                    <option value="empty" <?php echo ($filter['stock_status'] == 'empty') ? 'selected' : ''; ?>>Belum Ada
                        Stok</option>
                    <option value="has_stock" <?php echo ($filter['stock_status'] == 'has_stock') ? 'selected' : ''; ?>>
                        Sudah Ada Stok</option>
                </select>
            </div>
        </div>

        <!-- Urutkan dan Reset -->
        <div class="row mt-2">
            <div class="col-md-3 mb-3">
                <label class="form-label small text-muted">Urutkan</label>
                <select id="sortBy" class="form-control form-control-sm">
                    <option value="nama_barang">Nama</option>
                    <option value="sku">SKU</option>
                    <option value="stok">Stok</option>
                </select>
            </div>
            <div class="col-md-3 mb-3 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-outline-danger w-100" id="resetFilter">
                    <i class="fas fa-redo mr-1"></i> Reset Filter
                </button>
            </div>
        </div>
    </div>
</div>