<div class="card">
    <div class="card-header">
        <h5 class="card-title">Data Stok Awal</h3>
            <div class="card-tools">
                <a href="<?php echo site_url('stok_awal/add'); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Stok Awal
                </a>
                <a href="<?php echo site_url('stok_awal/export_excel'); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
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

        <?php if ($this->session->flashdata('warning')): ?>
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fas fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('warning'); ?>
            </div>
        <?php endif; ?>

        <!-- Filter Form -->
        <form method="get" action="<?php echo site_url('stok_awal'); ?>">
            <div class="row">
                <div class="col-md-3">
                    <select name="id_perusahaan" class="form-control" id="filter_perusahaan">
                        <option value="">-- Semua Perusahaan --</option>
                        <?php foreach ($perusahaan as $p): ?>
                            <option value="<?php echo $p->id_perusahaan; ?>" <?php echo ($filter['id_perusahaan'] == $p->id_perusahaan) ? 'selected' : ''; ?>>
                                <?php echo $p->nama_perusahaan; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="id_gudang" class="form-control" id="filter_gudang">
                        <option value="">-- Semua Gudang --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="id_barang" class="form-control" id="filter_barang">
                        <option value="">-- Semua Barang --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info btn-block">Filter</button>
                </div>
            </div>
        </form>
        <hr>

        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
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
                    <!-- <th>Aksi</th> -->
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($stok_awal as $s): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $s->nama_barang; ?></td>
                        <td><?php echo $s->sku; ?></td>
                        <td><?php echo $s->nama_gudang; ?></td>
                        <td><?php echo $s->nama_perusahaan; ?></td>
                        <td><?php echo $s->qty_awal; ?></td>
                        <td><?php echo $s->keterangan; ?></td>
                        <td><?php echo $s->created_by_name; ?></td>
                        <!-- <td>
                        <a href="<?php echo site_url('stok_awal/edit/' . $s->id_stok_awal); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?php echo site_url('stok_awal/delete/' . $s->id_stok_awal); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td> -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>