<div class="table-responsive">
    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
        <thead class="bg-light">
            <tr>
                <th width="5%">No</th>
                <th width="8%">Kode Barang</th>
                <th width="15%">Nama Barang</th>
                <th width="10%">Kategori</th>
                <th width="10%">Perusahaan</th>
                <th width="10%">Gudang</th>
                <th width="8%" class="text-center">Stok Awal</th>
                <th width="8%" class="text-center">Pembelian</th>
                <th width="8%" class="text-center">Retur</th>
                <th width="8%" class="text-center">Penjualan</th>
                <th width="8%" class="text-center">Stok Akhir</th>
                <th width="10%">Status</th>
                <th width="10%">Update</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($stok as $s): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $s->sku; ?></td>
                    <td><?php echo $s->nama_barang; ?></td>
                    <td><?php echo $s->nama_kategori; ?></td>
                    <td><?php echo $s->nama_perusahaan; ?></td>
                    <td><?php echo $s->nama_gudang; ?></td>
                    <td class="text-center"><?php echo number_format($s->stok_awal, 0, ',', '.'); ?></td>
                    <td class="text-center"><?php echo number_format($s->pembelian_masuk, 0, ',', '.'); ?></td>
                    <td class="text-center">
                        <span class="text-success font-weight-bold">
                            <?php echo number_format($s->retur_masuk, 0, ',', '.'); ?>
                        </span>
                    </td>
                    <td class="text-center"><?php echo number_format($s->penjualan_keluar, 0, ',', '.'); ?></td>
                    <td class="text-center">
                        <span class="font-weight-bold <?php echo ($s->jumlah < 10) ? 'text-danger' : ''; ?>">
                            <?php echo number_format($s->jumlah, 0, ',', '.'); ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        $status_class = '';
                        $status_text = '';
                        $status_icon = '';

                        if ($s->jumlah == 0) {
                            $status_class = 'danger';
                            $status_text = 'Stok Habis';
                            $status_icon = 'fas fa-times-circle';
                        } elseif ($s->jumlah < 10) {
                            $status_class = 'warning';
                            $status_text = 'Stok Menipis';
                            $status_icon = 'fas fa-exclamation-triangle';
                        } elseif ($s->jumlah > 100) {
                            $status_class = 'info';
                            $status_text = 'Stok Berlebih';
                            $status_icon = 'fas fa-info-circle';
                        } else {
                            $status_class = 'success';
                            $status_text = 'Normal';
                            $status_icon = 'fas fa-check-circle';
                        }
                        ?>
                        <span class="badge badge-<?php echo $status_class; ?>">
                            <i class="<?php echo $status_icon; ?> mr-1"></i><?php echo $status_text; ?>
                        </span>
                    </td>
                    <td><?php echo date('d-m-Y H:i', strtotime($s->updated_at)); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="bg-light font-weight-bold">
            <tr>
                <td colspan="6" class="text-right">TOTAL</td>
                <td class="text-center"><?php echo number_format($summary['total_stok_awal'], 0, ',', '.'); ?></td>
                <td class="text-center"><?php echo number_format($summary['total_pembelian_masuk'], 0, ',', '.'); ?>
                </td>
                <td class="text-center"><?php echo number_format($summary['total_retur_masuk'], 0, ',', '.'); ?></td>
                <td class="text-center"><?php echo number_format($summary['total_penjualan_keluar'], 0, ',', '.'); ?>
                </td>
                <td class="text-center"><?php echo number_format($summary['total_stok_akhir'], 0, ',', '.'); ?></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>