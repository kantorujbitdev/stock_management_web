<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
            </div>
            <div class="col text-right">
                <a href="<?php echo site_url('kategori/add') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>
            </div>
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
                        <th>Nama Kategori</th>
                            <?php if ($this->session->userdata('id_role') == 5): ?>
                                <th>Perusahaan</th>
                            <?php endif; ?>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($kategori as $k): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $k->nama_kategori; ?></td>
                            <?php if ($this->session->userdata('id_role') == 5): ?>
                            <td><?php echo isset($k->nama_perusahaan) ? $k->nama_perusahaan : '-'; ?></td>
                            <?php endif; ?>
                        <td><?php echo $k->deskripsi; ?></td>
                        <td>
                            <?php if ($k->status_aktif == 1): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="<?php echo site_url('kategori/edit/' . $k->id_kategori); ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>Edit </a>

                                <?php if ($k->status_aktif == '1'): ?>
                                    <a href="<?php echo site_url('kategori/nonaktif/'.$k->id_kategori) ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Apakah Anda yakin ingin menonaktifkan kategori ini?')">
                                    <i class="fas fa-minus-square"></i> Nonaktifkan</a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('kategori/aktif/'.$k->id_kategori) ?>" class="btn btn-sm btn-success" 
                                    onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali kategori ini?')">
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