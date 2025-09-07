<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h5 class="m-0 font-weight-bold text-primary">Daftar Penerimaan Barang</h6>
            </div>
            <div class="col text-right">
                <a href="<?php echo site_url('penerimaan/add') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Penerimaan
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
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Penerimaan</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Gudang</th>
                        <th>No Faktur</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($penerimaan as $p): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $p->no_penerimaan; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($p->tanggal_penerimaan)); ?></td>
                            <td><?php echo $p->nama_supplier; ?></td>
                            <td><?php echo $p->nama_gudang; ?></td>
                            <td><?php echo $p->no_faktur; ?></td>
                            <td>
                                <?php if ($p->status == 'draft'): ?>
                                    <span class="badge badge-secondary">Draft</span>
                                <?php elseif ($p->status == 'diterima'): ?>
                                    <span class="badge badge-success">Diterima</span>
                                <?php elseif ($p->status == 'dibatalkan'): ?>
                                    <span class="badge badge-danger">Dibatalkan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('penerimaan/detail/' . $p->id_penerimaan); ?>"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($p->status == 'draft'): ?>
                                    <a href="<?php echo site_url('penerimaan/proses/' . $p->id_penerimaan); ?>"
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin memproses penerimaan ini?')">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="<?php echo site_url('penerimaan/batal/' . $p->id_penerimaan); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan penerimaan ini?')">
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