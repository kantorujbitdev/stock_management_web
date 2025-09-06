<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h5 class="m-0 font-weight-bold text-primary">Daftar Retur Penjualan</h6>
            </div>
            <?php if ($this->session->userdata('id_role') == 5): ?>
                <div class="col text-right">
                    <a href="<?php echo site_url('retur/add') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Retur Penjualan
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
                        <th>No Retur</th>
                        <th>Tanggal</th>
                        <th>No Invoice</th>
                        <th>Pelanggan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($retur as $r): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $r->no_retur; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($r->tanggal_retur)); ?></td>
                            <td><?php echo $r->no_invoice; ?></td>
                            <td><?php echo $r->nama_pelanggan; ?></td>
                            <td>
                                <?php if ($r->status == 'diterima'): ?>
                                    <span class="badge badge-primary">Diterima</span>
                                <?php elseif ($r->status == 'diproses'): ?>
                                    <span class="badge badge-warning">Diproses</span>
                                <?php elseif ($r->status == 'selesai'): ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php elseif ($r->status == 'ditolak'): ?>
                                    <span class="badge badge-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('retur/detail/' . $r->id_retur); ?>"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($r->status == 'diterima'): ?>
                                    <a href="<?php echo site_url('retur/proses/' . $r->id_retur); ?>"
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin memproses retur ini?')">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="<?php echo site_url('retur/tolak/' . $r->id_retur); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menolak retur ini?')">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>