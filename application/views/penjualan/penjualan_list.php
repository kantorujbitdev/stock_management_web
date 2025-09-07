<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h5 class="m-0 font-weight-bold text-primary">Daftar penjualan</h5>
            </div>
            <div class="col text-right">
                <a href="<?php echo site_url('penjualan/add') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus">
                    </i>
                    Tambah Penjualan
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        <!-- Filter Form -->
        <form method="get" action="<?php echo site_url('penjualan'); ?>">
            <div class="row">
                <div class="col-md-2">
                    <select name="id_perusahaan" class="form-control">
                        <option value="">-- Perusahaan --</option>
                        <?php foreach ($perusahaan as $p): ?>
                            <option value="<?php echo $p->id_perusahaan; ?>" <?php echo ($filter['id_perusahaan'] == $p->id_perusahaan) ? 'selected' : ''; ?>>
                                <?php echo $p->nama_perusahaan; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="id_pelanggan" class="form-control">
                        <option value="">-- Pelanggan --</option>
                        <?php foreach ($pelanggan as $p): ?>
                            <option value="<?php echo $p->id_pelanggan; ?>" <?php echo ($filter['id_pelanggan'] == $p->id_pelanggan) ? 'selected' : ''; ?>>
                                <?php echo $p->nama_pelanggan; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">-- Status --</option>
                        <option value="proses" <?php echo ($filter['status'] == 'proses') ? 'selected' : ''; ?>>Proses
                        </option>
                        <option value="packing" <?php echo ($filter['status'] == 'packing') ? 'selected' : ''; ?>>Packing
                        </option>
                        <option value="dikirim" <?php echo ($filter['status'] == 'dikirim') ? 'selected' : ''; ?>>Dikirim
                        </option>
                        <option value="selesai" <?php echo ($filter['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai
                        </option>
                        <option value="batal" <?php echo ($filter['status'] == 'batal') ? 'selected' : ''; ?>>Batal
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="<?php echo $filter['date_from']; ?>"
                        placeholder="Dari Tanggal">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="<?php echo $filter['date_to']; ?>"
                        placeholder="Sampai Tanggal">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-block">Filter</button>
                </div>
            </div>
        </form>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Invoice</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Barang</th>
                        <th>Status Penjualan</th>
                        <th>Status Retur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($penjualan as $p): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $p->no_invoice; ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($p->tanggal_penjualan)); ?></td>
                            <td><?php echo $p->nama_pelanggan; ?></td>
                            <td><?php echo $p->daftar_barang; ?></td>
                            <td>
                                <?php
                                $status_class = '';
                                switch ($p->status) {
                                    case 'proses':
                                        $status_class = 'warning';
                                        break;
                                    case 'packing':
                                        $status_class = 'info';
                                        break;
                                    case 'dikirim':
                                        $status_class = 'primary';
                                        break;
                                    case 'selesai':
                                        $status_class = 'success';
                                        break;
                                    case 'batal':
                                        $status_class = 'secondary';
                                        break;
                                }
                                ?>
                                <span class="badge badge-<?php echo $status_class; ?>">
                                    <?php echo ucfirst($p->status); ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                // Cek apakah penjualan ini memiliki retur
                                $this->db->where('id_penjualan', $p->id_penjualan);
                                $this->db->where('status !=', 'batal');
                                $has_retur = $this->db->count_all_results('retur_penjualan') > 0;

                                if ($has_retur) {
                                    echo '<span class="badge badge-warning">Ada Retur</span>';
                                } else {
                                    echo '<span class="badge badge-light">Tidak Ada</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('penjualan/view/' . $p->id_penjualan); ?>"
                                    class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <?php if ($p->status == 'selesai' && !$has_retur): ?>
                                    <a href="<?php echo site_url('retur/add/' . $p->id_penjualan); ?>"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-undo"></i> Retur
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