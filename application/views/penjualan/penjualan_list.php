<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Penjualan</h3>
        <div class="card-tools">
            <a href="<?php echo site_url('penjualan/add'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Penjualan
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fas fa-check"></i> <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fas fa-ban"></i> <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($penjualan as $p): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $p->no_invoice; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($p->tanggal_penjualan)); ?></td>
                        <td><?php echo $p->nama_pelanggan; ?></td>
                        <td><?php echo number_format($p->total_harga, 2, ',', '.'); ?></td>
                        <td>
                            <?php if ($p->status == 'proses'): ?>
                                <span class="badge badge-secondary">Proses</span>
                            <?php elseif ($p->status == 'packing'): ?>
                                <span class="badge badge-primary">Packing</span>
                            <?php elseif ($p->status == 'dikirim'): ?>
                                <span class="badge badge-info">Dikirim</span>
                            <?php elseif ($p->status == 'selesai'): ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php elseif ($p->status == 'batal'): ?>
                                <span class="badge badge-danger">Batal</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo site_url('penjualan/detail/' . $p->id_penjualan); ?>" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if ($p->status == 'proses'): ?>
                                <a href="<?php echo site_url('penjualan/proses/' . $p->id_penjualan); ?>" class="btn btn-warning btn-sm" onclick="return confirm('Apakah Anda yakin ingin memproses penjualan ini?')">
                                    <i class="fas fa-box"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($p->status == 'packing'): ?>
                                <a href="<?php echo site_url('penjualan/kirim/' . $p->id_penjualan); ?>" class="btn btn-primary btn-sm" onclick="return confirm('Apakah Anda yakin ingin mengirim penjualan ini?')">
                                    <i class="fas fa-truck"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($p->status == 'dikirim'): ?>
                                <a href="<?php echo site_url('penjualan/selesai/' . $p->id_penjualan); ?>" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan penjualan ini?')">
                                    <i class="fas fa-check"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($p->status != 'selesai' && $p->status != 'batal'): ?>
                                <a href="<?php echo site_url('penjualan/batal/' . $p->id_penjualan); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan penjualan ini?')">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>