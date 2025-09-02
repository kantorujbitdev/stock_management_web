<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Gudang</h6>
            </div>
            <div class="col text-right">
                <a href="<?php echo site_url('gudang/add') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus">
                    </i>
                    Tambah Gudang
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Gudang</th>
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
                    foreach ($gudang as $row): ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $row->nama_gudang ?></td>
                            <?php if ($this->session->userdata('id_role') == 5): ?>
                                <td><?php echo isset($p->nama_perusahaan) ? $p->nama_perusahaan : '-'; ?></td>
                            <?php endif; ?>
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
                                <a href="<?php echo site_url('gudang/edit/' . $row->id_gudang) ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>Edit</a>

                                <?php if ($row->status_aktif == '1'): ?>
                                    <a href="<?php echo site_url('gudang/nonaktif/' . $row->id_gudang) ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menonaktifkan gudang ini?')">
                                        <i class="fas fa-minus-square"></i> Nonaktifkan</a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('gudang/aktif/' . $row->id_gudang) ?>"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali gudang ini?')">
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