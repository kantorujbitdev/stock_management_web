<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <?php echo responsive_title_blue('Daftar Pelanggan') ?>
            </div>
            <?php if ($this->session->userdata('id_role') == 1 || $this->session->userdata('id_role') == 5): ?>
                <div class="col text-right">
                    <a href="<?php echo site_url('pelanggan/add') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Pelanggan
                    </a>
                </div>
            <?php endif; ?>
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
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <?php if ($this->session->userdata('id_role') == 5): ?>
                            <th>Perusahaan</th>
                        <?php endif; ?>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($pelanggan as $p): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $p->nama_pelanggan; ?></td>
                            <?php if ($this->session->userdata('id_role') == 5): ?>
                                <td><?php echo isset($p->nama_perusahaan) ? $p->nama_perusahaan : '-'; ?></td>
                            <?php endif; ?>
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
                                <a href="<?php echo site_url('pelanggan/edit/' . $p->id_pelanggan); ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>Edit </a>
                                </a>

                                <?php if ($p->status_aktif == 1): ?>
                                    <a href="<?php echo site_url('pelanggan/nonaktif/' . $p->id_pelanggan); ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menonaktifkan pelanggan ini?')">
                                        <i class="fas fa-minus-square"></i> Nonaktifkan</a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('pelanggan/aktif/' . $p->id_pelanggan); ?>"
                                        class="btn btn-sm btn-success"
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