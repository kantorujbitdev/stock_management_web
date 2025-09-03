<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Penjualan</h3>
        <div class="card-tools">
            <a href="<?php echo site_url('penjualan'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="<?php echo site_url('penjualan/cetak/' . $penjualan->id_penjualan); ?>"
                class="btn btn-primary btn-sm" target="_blank">
                <i class="fas fa-print"></i> Cetak
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Informasi Penjualan</h5>
                <table class="table table-sm">
                    <tr>
                        <td width="150">No. Invoice</td>
                        <td><strong><?php echo $penjualan->no_invoice; ?></strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td><?php echo date('d-m-Y H:i', strtotime($penjualan->tanggal_penjualan)); ?></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            <?php
                            $status_class = '';
                            switch ($penjualan->status) {
                                case 'proses':
                                    $status_class = 'warning';
                                    break;
                                case 'packing':
                                    $status_class = 'info';
                                    break;
                                case 'dikirim':
                                    $status_class = 'primary';
                                    break;
                                case 'selesai':
                                    $status_class = 'success';
                                    break;
                                case 'batal':
                                    $status_class = 'danger';
                                    break;
                            }
                            ?>
                            <span class="badge badge-<?php echo $status_class; ?>">
                                <?php echo ucfirst($penjualan->status); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Dibuat Oleh</td>
                        <td><?php echo $penjualan->nama_user; ?></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td><?php echo $penjualan->keterangan ?: '-'; ?></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <h5>Informasi Pelanggan</h5>
                <table class="table table-sm">
                    <tr>
                        <td width="150">Nama Pelanggan</td>
                        <td><strong><?php echo $penjualan->nama_pelanggan; ?></strong></td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td><?php echo $penjualan->alamat_pelanggan ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td><?php echo $penjualan->telepon_pelanggan ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <td>Perusahaan</td>
                        <td><?php echo $penjualan->nama_perusahaan; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <h5>Detail Items</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>SKU</th>
                        <th>Gudang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($detail as $d): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $d->nama_barang; ?></td>
                            <td><?php echo $d->sku; ?></td>
                            <td><?php echo $d->nama_gudang; ?></td>
                            <td><?php echo $d->jumlah; ?></td>
                            <td>Rp <?php echo number_format($d->harga_satuan, 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($d->subtotal, 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Subtotal</strong></td>
                        <td><strong>Rp <?php echo number_format($penjualan->subtotal, 0, ',', '.'); ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Diskon</strong></td>
                        <td><strong>Rp <?php echo number_format($penjualan->diskon, 0, ',', '.'); ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Pajak</strong></td>
                        <td><strong>Rp <?php echo number_format($penjualan->pajak, 0, ',', '.'); ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total Bayar</strong></td>
                        <td><strong>Rp <?php echo number_format($penjualan->total_harga, 0, ',', '.'); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>