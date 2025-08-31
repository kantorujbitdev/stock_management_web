<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Stok Awal</h3>
        <div class="card-tools">
            <a href="<?php echo site_url('stok_awal/add'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Stok Awal
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
                    <th>Barang</th>
                    <th>SKU</th>
                    <th>Gudang</th>
                    <th>Perusahaan</th>
                    <th>Qty Awal</th>
                    <th>Keterangan</th>
                    <th>Dibuat Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($stok_awal as $s): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $s->nama_barang; ?></td>
                    <td><?php echo $s->sku; ?></td>
                    <td><?php echo $s->nama_gudang; ?></td>
                    <td><?php echo $s->nama_perusahaan; ?></td>
                    <td><?php echo $s->qty_awal; ?></td>
                    <td><?php echo $s->keterangan; ?></td>
                    <td><?php echo $s->created_by_name; ?></td>
                    <td>
                        <a href="<?php echo site_url('stok_awal/edit/' . $s->id_stok_awal); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?php echo site_url('stok_awal/delete/' . $s->id_stok_awal); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>