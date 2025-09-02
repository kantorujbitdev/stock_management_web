<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Penyesuaian Stok</h3>
        <div class="card-tools">
            <a href="<?php echo site_url('penyesuaian/add'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Penyesuaian
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
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Gudang</th>
                    <th>Perusahaan</th>
                    <th>Stok Awal</th>
                    <th>Stok Baru</th>
                    <th>Selisih</th>
                    <th>Alasan</th>
                    <th>Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($penyesuaian as $p): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d-m-Y H:i', strtotime($p->created_at)); ?></td>
                        <td><?php echo $p->nama_barang; ?></td>
                        <td><?php echo $p->nama_gudang; ?></td>
                        <td><?php echo $p->nama_perusahaan; ?></td>
                        <td><?php echo $p->jumlah_saat_ini; ?></td>
                        <td><?php echo $p->jumlah_baru; ?></td>
                        <td>
                            <?php if ($p->selisih > 0): ?>
                                <span class="text-success">+<?php echo $p->selisih; ?></span>
                            <?php else: ?>
                                <span class="text-danger"><?php echo $p->selisih; ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $p->alasan; ?></td>
                        <td><?php echo $p->created_by; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>