<?php
$this->load->model('master/Barang_model');
$stok = $this->Barang_model->get_stok_barang($b->id_barang);
$stokClass = ($stok > 10) ? 'text-success' : (($stok > 0) ? 'text-warning' : 'text-danger');

// Perbaiki pengecekan stok awal
$hasStokAwal = false;
if (isset($b->has_stok_awal) && $b->has_stok_awal) {
    $hasStokAwal = true;
} else {
    // Cek di database jika has_stok_awal tidak tersedia
    $this->load->model('stok/Stok_awal_model');
    $stokAwal = $this->Stok_awal_model->get_stok_awal_by_barang($b->id_barang);
    $hasStokAwal = !empty($stokAwal);
}
?>
<div class="col-lg-3 col-md-4 col-sm-6 mb-4 barang-item card-clickable"
    data-nama="<?php echo strtolower($b->nama_barang); ?>" data-sku="<?php echo strtolower($b->sku); ?>"
    data-kategori="<?php echo $b->id_kategori; ?>" data-status="<?php echo $b->aktif; ?>"
    data-stok="<?php echo $stok; ?>" data-id="<?php echo $b->id_barang; ?>"
    data-gambar="<?php echo $b->gambar ? base_url('uploads/barang/' . $b->gambar) : ''; ?>"
    data-namabarang="<?php echo $b->nama_barang; ?>" data-skuvalue="<?php echo $b->sku; ?>"
    data-kategoriname="<?php echo $b->nama_kategori; ?>"
    data-perusahaan="<?php echo isset($b->nama_perusahaan) ? $b->nama_perusahaan : '-'; ?>"
    data-deskripsi="<?php echo $b->deskripsi ?: '-'; ?>" data-hasstokawal="<?php echo $hasStokAwal ? '1' : '0'; ?>"
    data-idperusahaan="<?php echo $b->id_perusahaan; ?>">
    <div class="card h-100 shadow-sm border-0">
        <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative"
            style="height: 180px; overflow: hidden;">
            <!-- Tambahkan nomor urut di sini -->
            <div class="position-absolute top-0 right-0 m-2">
                <span class="badge badge-primary nomor-urut">#</span>
            </div>
            <?php if ($b->gambar): ?>
                <img src="<?php echo base_url('uploads/barang/' . $b->gambar); ?>" class="img-fluid"
                    style="max-height: 100%; width: auto; object-fit: contain;"
                    data-src="<?php echo base_url('uploads/barang/' . $b->gambar); ?>">
            <?php else: ?>
                <div class="text-center text-muted p-4">
                    <i class="fas fa-image fa-3x"></i>
                    <p class="mt-2">No Image</p>
                </div>
            <?php endif; ?>
            <!-- Status Stok Awal Badge -->
            <?php if (!$hasStokAwal && ($this->session->userdata('id_role') == 1 || $this->session->userdata('id_role') == 5)): ?>
                <div class="position-absolute top-0 left-0 m-2">
                    <span class="badge badge-warning p-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <span>Belum Ada Stok</span>
                    </span>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="card-title font-weight-bold text-truncate"><?php echo $b->nama_barang; ?></h6>
                <span style="font-size: 1.2rem; font-weight: bold;"
                    class="<?php echo $stokClass; ?>"><?php echo $stok ?: 0; ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-start mb-2">
                <p class="card-text text-muted small mb-1">SKU: <?php echo $b->sku; ?></p>
                <span class="badge badge-<?php echo ($b->aktif == 1) ? 'success' : 'danger'; ?>">
                    <?php echo ($b->aktif == 1) ? 'Aktif' : 'Tidak Aktif'; ?>
                </span>
            </div>
            <p class="card-text text-muted small mb-2">
                <i class="fas fa-tag"></i> <?php echo $b->nama_kategori; ?>
            </p>
            <?php if ($this->session->userdata('id_role') == 5): ?>
                <p class="card-text text-muted small mb-2">
                    <i class="fas fa-building"></i>
                    <?php echo isset($b->nama_perusahaan) ? $b->nama_perusahaan : '-'; ?>
                </p>
            <?php endif; ?>
            <div class="mt-auto">
                <?php if ($this->session->userdata('id_role') == 5 || $this->session->userdata('id_role') == 1): ?>
                    <div class="d-grid gap-2 mb-1">
                        <div class="btn-group w-100" role="group">
                            <a href="<?php echo site_url('barang/edit/' . $b->id_barang); ?>"
                                class="btn btn-warning btn-sm flex-fill d-flex align-items-center justify-content-center mr-1">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <?php if ($b->aktif == 1): ?>
                                <a href="<?php echo site_url('barang/nonaktif/' . $b->id_barang); ?>"
                                    class="btn btn-danger btn-sm flex-fill d-flex align-items-center justify-content-center ml-1"
                                    onclick="return confirm('Apakah Anda yakin ingin menonaktifkan barang ini?')">
                                    <i class="fas fa-minus-square mr-1"></i> Nonaktif
                                </a>
                            <?php else: ?>
                                <a href="<?php echo site_url('barang/aktif/' . $b->id_barang); ?>"
                                    class="btn btn-success btn-sm flex-fill d-flex align-items-center justify-content-center ml-1"
                                    onclick="return confirm('Apakah Anda yakin ingin mengaktifkan barang ini?')">
                                    <i class="fas fa-check-square mr-1"></i> Aktif
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!$hasStokAwal): ?>
                        <button type="button" class="btn btn-primary btn-sm w-100 input-stok-btn"
                            data-id="<?php echo $b->id_barang; ?>" data-nama="<?php echo $b->nama_barang; ?>"
                            data-idperusahaan="<?php echo $b->id_perusahaan; ?>">
                            <i class="fas fa-boxes mr-1"></i> Input Stok Awal
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
                <button type="button" class="btn btn-info btn-sm w-100 detail-btn"
                    data-id="<?php echo $b->id_barang; ?>">
                    <i class="fas fa-info-circle mr-1"></i> Detail
                </button>
            </div>
        </div>
    </div>
</div>