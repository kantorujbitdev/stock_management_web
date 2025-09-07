<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h5 class="m-0 font-weight-bold text-primary">Data Retur Penjualan</h6>
        <a href="<?php echo site_url('retur/add'); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Retur
        </a>
    </div>
    <div class="card-body">
        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <a class="d-block text-dark" data-toggle="collapse" href="#filterCollapse" role="button">
                    <i class="fas fa-filter"></i> Filter Data
                </a>
            </div>
            <div class="collapse <?php echo !empty($filter) && array_filter($filter) ? 'show' : ''; ?>" id="filterCollapse">
                <div class="card-body">
                    <?php echo form_open('retur', ['method' => 'GET']); ?>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_perusahaan">Perusahaan</label>
                                <select name="id_perusahaan" class="form-control" id="id_perusahaan">
                                    <option value="">-- Semua Perusahaan --</option>
                                    <?php foreach ($perusahaan as $p): ?>
                                        <option value="<?php echo $p->id_perusahaan; ?>" 
                                            <?php echo isset($filter['id_perusahaan']) && $filter['id_perusahaan'] == $p->id_perusahaan ? 'selected' : ''; ?>>
                                            <?php echo $p->nama_perusahaan; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="id_pelanggan">Pelanggan</label>
                                <select name="id_pelanggan" class="form-control" id="id_pelanggan">
                                    <option value="">-- Semua Pelanggan --</option>
                                    <?php foreach ($pelanggan as $p): ?>
                                        <option value="<?php echo $p->id_pelanggan; ?>" 
                                            <?php echo isset($filter['id_pelanggan']) && $filter['id_pelanggan'] == $p->id_pelanggan ? 'selected' : ''; ?>>
                                            <?php echo $p->nama_pelanggan; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="">-- Semua Status --</option>
                                    <option value="diproses" <?php echo isset($filter['status']) && $filter['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                                    <option value="diterima" <?php echo isset($filter['status']) && $filter['status'] == 'diterima' ? 'selected' : ''; ?>>Diterima</option>
                                    <option value="ditolak" <?php echo isset($filter['status']) && $filter['status'] == 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                                    <option value="batal" <?php echo isset($filter['status']) && $filter['status'] == 'batal' ? 'selected' : ''; ?>>Batal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary btn-sm mr-2">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="<?php echo site_url('retur'); ?>" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_from">Tanggal Dari</label>
                                <input type="date" name="date_from" class="form-control" id="date_from" 
                                    value="<?php echo isset($filter['date_from']) ? $filter['date_from'] : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_to">Tanggal Sampai</label>
                                <input type="date" name="date_to" class="form-control" id="date_to" 
                                    value="<?php echo isset($filter['date_to']) ? $filter['date_to'] : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Retur</th>
                        <th>Tanggal</th>
                        <th>Invoice Penjualan</th>
                        <th>Pelanggan</th>
                        <th>Barang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($retur as $r): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $r->no_retur; ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($r->tanggal_retur)); ?></td>
                            <td><?php echo $r->no_invoice; ?></td>
                            <td><?php echo $r->nama_pelanggan; ?></td>
                            <td><?php echo $r->daftar_barang; ?></td>
                            <td>
                                <?php
                                $status_class = '';
                                switch ($r->status) {
                                    case 'diproses':
                                        $status_class = 'warning';
                                        break;
                                    case 'diterima':
                                        $status_class = 'success';
                                        break;
                                    case 'ditolak':
                                        $status_class = 'danger';
                                        break;
                                    case 'batal':
                                        $status_class = 'secondary';
                                        break;
                                }
                                ?>
                                <span class="badge badge-<?php echo $status_class; ?>"><?php echo ucfirst($r->status); ?></span>
                            </td>
                            <td>
                                <a href="<?php echo site_url('retur/view/' . $r->id_retur); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>