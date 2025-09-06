<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h5 class="m-0 font-weight-bold text-primary">Daftar Perusahaan</h6>
            </div>
            <?php if ($this->session->userdata('id_role') == 5): ?>
                <div class="col text-right">
                    <a href="<?php echo site_url('perusahaan/add') ?>" class="btn btn-primary btn-sm"><i
                            class="fas fa-plus"></i> Tambah Perusahaan</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perusahaan</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($perusahaan as $row): ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $row->nama_perusahaan ?></td>
                            <td><?php echo $row->alamat ?></td>
                            <td><?php echo $row->telepon ?></td>
                            <td>
                                <?php if ($row->status_aktif == 1): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('perusahaan/edit/' . $row->id_perusahaan) ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>Edit</a>


                                <!-- <?php if ($this->session->userdata('id_role') == 5): ?>
                                <a href="<?php echo site_url('perusahaan/delete/' . $row->id_perusahaan) ?>" 
                                class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan perusahaan ini?')">
                                <i class="fas fa-ban"></i> Nonaktifkan</a>
                            <?php endif; ?> -->

                                <?php if ($row->status_aktif == '1'): ?>
                                    <a href="<?php echo site_url('perusahaan/nonaktif/' . $row->id_perusahaan) ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menonaktifkan perusahaan ini?')">
                                        <i class="fas fa-minus-square"></i> Nonaktifkan</a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('perusahaan/aktif/' . $row->id_perusahaan) ?>"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali perusahaan ini?')">
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