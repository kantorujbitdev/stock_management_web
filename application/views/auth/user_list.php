<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h5 class="m-0 font-weight-bold text-primary">Daftar User</h6>
            </div>
            <div class="col text-right">
                <a href="<?php echo site_url('auth/user/add') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i>
                    Tambah User
                </a>

                <?php if ($this->session->userdata('id_role') == 5): ?>
                    <a href="<?php echo site_url('auth/user/hak_akses') ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-key"></i>
                        Hak Akses
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Perusahaan</th>
                        <th>Gudang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $user->nama ?></td>
                            <td><?php echo $user->username ?></td>
                            <td><?php echo $user->nama_role ?></td>
                            <td><?php echo $user->nama_perusahaan ? $user->nama_perusahaan : '-' ?></td>
                            <td><?php echo $user->nama_gudang ? $user->nama_gudang : '-' ?></td>
                            <td>
                                <?php if ($user->aktif == 1): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('auth/user/edit/' . $user->id_user) ?>"
                                    class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>Edit</a>

                                <?php if ($user->aktif == '1'): ?>
                                    <a href="<?php echo site_url('auth/user/delete/' . $user->id_user) ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menonaktifkan user ini?')">
                                        <i class="fas fa-minus-square"></i> Nonaktifkan</a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('auth/user/aktif/' . $user->id_user) ?>"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali user ini?')">
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