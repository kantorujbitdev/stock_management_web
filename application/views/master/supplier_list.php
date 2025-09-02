<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Supplier</h6>
            </div>
            <?php if ($this->session->userdata('id_role') == 5): ?>
                <div class="col text-right">
                    <a href="<?php echo site_url('supplier/add') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Supplier
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
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
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
                    foreach ($supplier as $s): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $s->nama_supplier; ?></td>
                            <?php if ($this->session->userdata('id_role') == 5): ?>
                                <td><?php echo isset($s->nama_perusahaan) ? $s->nama_perusahaan : '-'; ?></td>
                            <?php endif; ?>
                            <td><?php echo $s->alamat; ?></td>
                            <td><?php echo $s->telepon; ?></td>
                            <td>
                                <?php if ($s->status_aktif == 1): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('supplier/edit/' . $s->id_supplier); ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>Edit </a>
                                </a>

                                <?php if ($s->status_aktif == 1): ?>
                                    <a href="<?php echo site_url('supplier/nonaktif/' . $s->id_supplier); ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menonaktifkan supplier ini?')">
                                        <i class="fas fa-minus-square"></i> Nonaktifkan</a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('supplier/aktif/' . $s->id_supplier); ?>"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Apakah Anda yakin ingin mengaktifkan supplier ini?')">
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