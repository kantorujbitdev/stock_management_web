<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Pelanggan</h3>
        <div class="card-tools">
            <a href="<?php echo site_url('pelanggan/add'); ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Pelanggan
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
                        <th width="5%">No</th>
                        <th>Nama Pelanggan</th>
                        <th>Perusahaan</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach ($pelanggan as $p): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $p->nama_pelanggan; ?></td>
                        <td><?php echo $p->nama_perusahaan; ?></td>
                        <td><?php echo $p->alamat; ?></td>
                        <td><?php echo $p->telepon; ?></td>
                        <td>
                            <?php if ($p->status_aktif == 1): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td>                            
                            <a href="<?php echo site_url('pelanggan/edit/'.$p->id_pelanggan); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>Edit </a>
                            </a>
                            
                            <?php if ($p->status_aktif == 1): ?>
                                <a href="<?php echo site_url('pelanggan/nonaktif/'.$p->id_pelanggan); ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menonaktifkan pelanggan ini?')">
                                <i class="fas fa-minus-square"></i> Nonaktifkan</a>
                            <?php else: ?>
                                <a href="<?php echo site_url('pelanggan/aktif/'.$p->id_pelanggan); ?>" class="btn btn-sm btn-success" 
                                    onclick="return confirm('Apakah Anda yakin ingin mengaktifkan pelanggan ini?')">
                                <i class="fas fa-check-square"></i> Aktifkan</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>