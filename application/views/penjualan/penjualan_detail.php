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
                        <td><?php echo date('d-m-Y H:i:s', strtotime($penjualan->tanggal_penjualan)); ?></td>
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
                </table>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="form-group">
            <?php if ($penjualan->status == 'proses'): ?>
                <?php if ($this->session->userdata('id_role') == 3 || $this->session->userdata('id_role') == 1 || $this->session->userdata('id_role') == 5): // Admin Packing ?>
                    <a href="<?php echo site_url('penjualan/update_status/' . $penjualan->id_penjualan . '/packing'); ?>"
                        class="btn btn-primary" onclick="return confirm('Ubah status menjadi packing?')">
                        <i class="fas fa-box"></i> Proses Packing
                    </a>
                <?php endif; ?>
                <?php if ($this->session->userdata('id_role') == 2 || $this->session->userdata('id_role') == 1 || $this->session->userdata('id_role') == 5): // Sales Online ?>
                    <a href="<?php echo site_url('penjualan/update_status/' . $penjualan->id_penjualan . '/batal'); ?>"
                        class="btn btn-danger" onclick="return confirm('Batalkan penjualan?')">
                        <i class="fas fa-times"></i> Batalkan Penjualan
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($penjualan->status == 'packing'): ?>
                <?php if ($this->session->userdata('id_role') == 3 || $this->session->userdata('id_role') == 1 || $this->session->userdata('id_role') == 5): // Admin Packing ?>
                    <a href="<?php echo site_url('penjualan/update_status/' . $penjualan->id_penjualan . '/dikirim'); ?>"
                        class="btn btn-primary" onclick="return confirm('Ubah status menjadi dikirim?')">
                        <i class="fas fa-truck"></i> Kirim
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($penjualan->status == 'dikirim'): ?>
                <?php if ($this->session->userdata('id_role') == 3 || $this->session->userdata('id_role') == 1 || $this->session->userdata('id_role') == 5): // Admin Packing ?>
                    <a href="<?php echo site_url('penjualan/update_status/' . $penjualan->id_penjualan . '/selesai'); ?>"
                        class="btn btn-success" onclick="return confirm('Ubah status menjadi selesai?')">
                        <i class="fas fa-check"></i> Selesai
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <hr>
    <div class="card-body">
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
                                <th width="5%">No</th>
                                <th width="35%">Barang</th>
                                <th width="20%">SKU</th>
                                <th width="20%">Gudang</th>
                                <th width="20%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_items = 0; // Total jenis barang (baris)
                            $total_pcs = 0;   // Total quantity semua barang
                            
                            foreach ($detail as $d):
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $d->nama_barang; ?></td>
                                    <td><?php echo $d->sku; ?></td>
                                    <td><?php echo $d->nama_gudang; ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-info"><?php echo $d->jumlah; ?> pcs</span>
                                    </td>
                                </tr>
                                <?php
                                $total_items++; // Tambah 1 untuk setiap baris
                                $total_pcs += $d->jumlah; // Tambah jumlah pcs
                            endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Total Item</td>
                                <td class="text-center font-weight-bold">
                                    <span class="badge badge-primary"><?php echo $total_items; ?> item</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">Total Quantity</td>
                                <td class="text-center font-weight-bold">
                                    <span class="badge badge-success"><?php echo $total_pcs; ?> pcs</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- Riwayat Status -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Riwayat Status</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Yang Melakukan</th>
                                <th width="25%">Tanggal</th>
                                <th width="20%">Status</th>
                                <th width="25%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $riwayat_status = $this->Log_status_penjualan_model->get_log_by_penjualan($penjualan->id_penjualan);
                            $no = 1;

                            // Definisikan icon untuk setiap status
                            $status_icons = [
                                'proses' => 'plus',
                                'packing' => 'box',
                                'dikirim' => 'truck',
                                'selesai' => 'check-circle',
                                'batal' => 'times-circle'
                            ];

                            // Tampilkan semua riwayat status dari log
                            foreach ($riwayat_status as $log):
                                $status_class = '';
                                switch ($log->status) {
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

                                // Khusus untuk status "proses", tampilkan sebagai "Dibuat"
                                $status_text = ($log->status == 'proses') ? 'Dibuat' : ucfirst($log->status);
                                $icon = ($log->status == 'proses') ? 'plus' : $status_icons[$log->status];
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $log->nama_user; ?></td>
                                    <td><?php echo date('d-m-Y H:i:s', strtotime($log->tanggal)); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $status_class; ?>">
                                            <i class="fas fa-<?php echo $icon; ?>"></i>
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $log->keterangan; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Tombol kembali -->
        <div class="form-group">
            <a href="<?php echo site_url('penjualan'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>