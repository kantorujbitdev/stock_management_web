<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <?php echo back_button('retur'); ?>

        <h5 class="mb-0 ml-3">
            <i class="fas fa-tags"></i>
            Detail Retur Penjualan
        </h5>
    </div>
    <div class="card-body">
        <!-- Informasi Retur -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informasi Retur</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nomor Retur</label>
                            <p class="form-control-plaintext font-weight-bold"><?php echo $retur->no_retur; ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tanggal Retur</label>
                            <p class="form-control-plaintext">
                                <?php echo date('d-m-Y H:i:s', strtotime($retur->tanggal_retur)); ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Status</label>
                            <p class="form-control-plaintext">
                                <?php
                                $status_class = '';
                                switch ($retur->status) {
                                    case 'diproses':
                                        $status_class = 'warning';
                                        break;
                                    case 'diterima':
                                        $status_class = 'success';
                                        break;
                                    case 'ditolak':
                                        $status_class = 'danger';
                                        break;
                                    case 'batal':
                                        $status_class = 'secondary';
                                        break;
                                    case 'selesai':
                                        $status_class = 'primary';
                                        break;
                                }
                                ?>
                                <span
                                    class="badge badge-<?php echo $status_class; ?>"><?php echo ucfirst($retur->status); ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>No. Invoice</label>
                            <p class="form-control-plaintext"><?php echo $retur->no_invoice; ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pelanggan</label>
                            <p class="form-control-plaintext"><?php echo $retur->nama_pelanggan; ?></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Dibuat Oleh</label>
                            <p class="form-control-plaintext"><?php echo $retur->nama_user; ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Alasan Retur</label>
                            <p class="form-control-plaintext"><?php echo $retur->alasan_retur; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Barang -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Detail Barang</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Barang</th>
                                <th>Jumlah Retur</th>
                                <th>Alasan Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($detail as $d): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $d->nama_barang; ?></td>
                                    <td><?php echo $d->jumlah_retur; ?></td>
                                    <td>
                                        <?php
                                        $alasan_class = 'info';
                                        if ($d->alasan_barang == 'Barang Rusak') {
                                            $alasan_class = 'danger';
                                        } elseif ($d->alasan_barang == 'Tidak Lengkap') {
                                            $alasan_class = 'warning';
                                        } elseif ($d->alasan_barang == 'Tidak Sesuai Pesanan') {
                                            $alasan_class = 'secondary';
                                        }
                                        ?>
                                        <span
                                            class="badge badge-<?php echo $alasan_class; ?>"><?php echo $d->alasan_barang; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Aksi Status -->
        <?php if ($retur->status == 'diproses'): ?>
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ubah Status</h5>
                </div>
                <div class="card-body">
                    <?php if ($this->session->userdata('id_role') == 4 || $this->session->userdata('id_role') == 5 || $this->session->userdata('id_role') == 1): ?>
                        <a href="<?php echo site_url('retur/update_status/' . $retur->id_retur . '/diterima'); ?>"
                            class="btn btn-success"
                            onclick="return confirm('Apakah Anda yakin ingin menerima retur ini? Stok akan ditambah kembali.')">
                            <i class="fas fa-check"></i> Terima
                        </a>
                        <a href="<?php echo site_url('retur/update_status/' . $retur->id_retur . '/ditolak'); ?>"
                            class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak retur ini?')">
                            <i class="fas fa-times"></i> Tolak
                        </a>
                    <?php endif; ?>
                    <?php if ($this->session->userdata('id_role') == 2 || $this->session->userdata('id_role') == 5 || $this->session->userdata('id_role') == 1): ?>
                        <a href="<?php echo site_url('retur/update_status/' . $retur->id_retur . '/batal'); ?>"
                            class="btn btn-secondary"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan retur ini?')">
                            <i class="fas fa-ban"></i> Batal
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif ($retur->status == 'diterima'): ?>
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ubah Status</h5>
                </div>
                <div class="card-body">
                    <?php if ($this->session->userdata('id_role') == 4 || $this->session->userdata('id_role') == 5 || $this->session->userdata('id_role') == 1): ?>
                        <a href="<?php echo site_url('retur/update_status/' . $retur->id_retur . '/selesai'); ?>"
                            class="btn btn-primary"
                            onclick="return confirm('Apakah Anda yakin ingin menyelesaikan retur ini?')">
                            <i class="fas fa-check"></i> Selesai
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Riwayat Status -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Riwayat Status</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>User</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($riwayat_status) && !empty($riwayat_status)): ?>
                                <?php foreach ($riwayat_status as $log): ?>
                                    <tr>
                                        <td><?php echo date('d-m-Y H:i:s', strtotime($log->tanggal)); ?></td>
                                        <td>
                                            <?php if ($log->status == 'diterima'): ?>
                                                <span class="badge badge-success">Diterima</span>
                                            <?php elseif ($log->status == 'diproses'): ?>
                                                <span class="badge badge-warning">Diproses</span>
                                            <?php elseif ($log->status == 'selesai'): ?>
                                                <span class="badge badge-primary">Selesai</span>
                                            <?php elseif ($log->status == 'ditolak'): ?>
                                                <span class="badge badge-danger">Ditolak</span>
                                            <?php elseif ($log->status == 'batal'): ?>
                                                <span class="badge badge-secondary">Dibatalkan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $log->nama; ?></td>
                                        <td><?php echo $log->keterangan; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat status</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>