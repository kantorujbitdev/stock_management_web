<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <a href="<?php echo site_url('retur'); ?>" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h5 class="mb-0 ml-3">
            <i class="fas fa-tags"></i>
            Pilih Penjualan untuk Retur
        </h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>Panduan:</strong> Pilih penjualan yang akan diretur. Hanya penjualan dengan status "Selesai" yang
            ditampilkan.
            Penjualan yang sudah semua barangnya diretur tidak akan muncul dalam daftar ini.
        </div>

        <?php if (empty($penjualan)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Tidak ada penjualan yang bisa diretur. Semua penjualan dengan status "Selesai" sudah memiliki retur atau
                semua barang sudah diretur.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Barang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($penjualan as $p): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $p->no_invoice; ?></td>
                                <td><?php echo date('d-m-Y H:i', strtotime($p->tanggal_penjualan)); ?></td>
                                <td><?php echo $p->nama_pelanggan; ?></td>
                                <td><?php echo $p->daftar_barang; ?></td>
                                <td>
                                    <a href="<?php echo site_url('retur/add/' . $p->id_penjualan); ?>"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Retur
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>