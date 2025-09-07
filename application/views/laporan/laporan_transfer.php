<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <h5 class="card-title">Laporan Transfer Stok</h3>
            <div class="col text-right">
                <a href="<?php echo site_url('laporan_transfer/export_pdf?' . $_SERVER['QUERY_STRING']); ?>"
                    class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="<?php echo site_url('laporan_transfer/export_excel?' . $_SERVER['QUERY_STRING']); ?>"
                    class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('laporan_transfer'); ?>">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="id_perusahaan">Perusahaan</label>
                        <select name="id_perusahaan" class="form-control">
                            <option value="">-- Semua --</option>
                            <?php foreach ($perusahaan as $p): ?>
                                <option value="<?php echo $p->id_perusahaan; ?>" <?php echo ($filter['id_perusahaan'] == $p->id_perusahaan) ? 'selected' : ''; ?>>
                                    <?php echo $p->nama_perusahaan; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" class="form-control"
                            value="<?php echo $filter['tanggal_awal']; ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control"
                            value="<?php echo $filter['tanggal_akhir']; ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="">-- Semua --</option>
                            <option value="pending" <?php echo ($filter['status'] == 'pending') ? 'selected' : ''; ?>>
                                Pending</option>
                            <option value="selesai" <?php echo ($filter['status'] == 'selesai') ? 'selected' : ''; ?>>
                                Selesai</option>
                            <option value="batal" <?php echo ($filter['status'] == 'batal') ? 'selected' : ''; ?>>Batal
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Data Transfer Stok</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Transfer</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Gudang Asal</th>
                        <th>Gudang Tujuan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($transfer as $t): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $t->no_transfer; ?></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($t->tanggal)); ?></td>
                            <td><?php echo $t->nama_barang; ?></td>
                            <td><?php echo $t->gudang_asal; ?></td>
                            <td><?php echo $t->gudang_tujuan; ?></td>
                            <td><?php echo $t->jumlah; ?></td>
                            <td>
                                <?php if ($t->status == 'pending'): ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php elseif ($t->status == 'selesai'): ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php elseif ($t->status == 'batal'): ?>
                                    <span class="badge badge-danger">Batal</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $t->created_by; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>