<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <a href="<?php echo site_url('laporan_penjualan'); ?>" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h5 class="mb-0 ml-3">
            Detail Penjualan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">No Invoice</th>
                        <td><?php echo $penjualan->no_invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Penjualan</th>
                        <td><?php echo date('d-m-Y H:i:s', strtotime($penjualan->tanggal_penjualan)); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($penjualan->status == 'proses'): ?>
                                <span class="badge badge-secondary">Proses</span>
                            <?php elseif ($penjualan->status == 'packing'): ?>
                                <span class="badge badge-primary">Packing</span>
                            <?php elseif ($penjualan->status == 'dikirim'): ?>
                                <span class="badge badge-info">Dikirim</span>
                            <?php elseif ($penjualan->status == 'selesai'): ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php elseif ($penjualan->status == 'batal'): ?>
                                <span class="badge badge-danger">Batal</span>
                            <?php endif; ?>
                            <?php if ($last_status): ?>
                                <br><small class="text-muted">Tanggal:
                                    <?php echo date('d-m-Y H:i:s', strtotime($last_status->tanggal)); ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td><?php echo $penjualan->created_by; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Pelanggan</th>
                        <td><?php echo $penjualan->nama_pelanggan; ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td><?php echo $penjualan->alamat_pelanggan; ?></td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td><?php echo $penjualan->telepon_pelanggan; ?></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td><?php echo $penjualan->keterangan ? $penjualan->keterangan : '-'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Detail Barang</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>SKU</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Gudang</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($detail_barang as $item): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $item->sku; ?></td>
                            <td><?php echo $item->nama_barang; ?></td>
                            <td><?php echo $item->jumlah; ?></td>
                            <td>
                                <?php
                                // Ambil nama gudang
                                $this->db->select('nama_gudang');
                                $this->db->where('id_gudang', $item->id_gudang);
                                $gudang = $this->db->get('gudang')->row();
                                echo $gudang ? $gudang->nama_gudang : '-';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($detail_barang)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data barang</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Riwayat Status</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>User</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($log_status as $log): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($log->tanggal)); ?></td>
                            <td>
                                <?php if ($log->status == 'proses'): ?>
                                    <span class="badge badge-secondary">Proses</span>
                                <?php elseif ($log->status == 'packing'): ?>
                                    <span class="badge badge-primary">Packing</span>
                                <?php elseif ($log->status == 'dikirim'): ?>
                                    <span class="badge badge-info">Dikirim</span>
                                <?php elseif ($log->status == 'selesai'): ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php elseif ($log->status == 'batal'): ?>
                                    <span class="badge badge-danger">Batal</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $log->user_name; ?></td>
                            <td><?php echo $log->keterangan ? $log->keterangan : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($log_status)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada riwayat status</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <!-- Tombol kembali -->
            <div class="form-group">
                <a href="<?php echo site_url('penjualan'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>