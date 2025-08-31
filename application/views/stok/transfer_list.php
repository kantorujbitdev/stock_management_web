<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Transfer Stok</h3>
        <div class="card-tools">
            <a href="<?php echo site_url('transfer/add'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Transfer
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
        
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Transfer</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Gudang Asal</th>
                    <th>Gudang Tujuan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($transfer as $t): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $t->no_transfer; ?></td>
                    <td><?php echo date('d-m-Y H:i', strtotime($t->tanggal)); ?></td>
                    <td><?php echo $t->nama_barang; ?></td>
                    <td><?php echo $t->gudang_asal; ?></td>
                    <td><?php echo $t->gudang_tujuan; ?></td>
                    <td><?php echo $t->jumlah; ?></td>
                    <td>
                        <?php if ($t->status == 'pending'): ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php elseif ($t->status == 'selesai'): ?>
                            <span class="badge badge-success">Selesai</span>
                        <?php elseif ($t->status == 'batal'): ?>
                            <span class="badge badge-danger">Dibatalkan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($t->status == 'pending'): ?>
                            <a href="<?php echo site_url('transfer/approve/' . $t->id_transfer); ?>" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin menyetujui transfer ini?')">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="<?php echo site_url('transfer/reject/' . $t->id_transfer); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan transfer ini?')">
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