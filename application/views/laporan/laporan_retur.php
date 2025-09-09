<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <h5 class="card-title">Laporan Retur Penjualan</h3>
            <div class="col text-right">
                <a href="<?php echo site_url('laporan_retur/export_pdf?' . $_SERVER['QUERY_STRING']); ?>"
                    class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="<?php echo site_url('laporan_retur/export_excel?' . $_SERVER['QUERY_STRING']); ?>"
                    class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('laporan_retur'); ?>">
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
                            <option value="diterima" <?php echo ($filter['status'] == 'diterima') ? 'selected' : ''; ?>>
                                Diterima</option>
                            <option value="diproses" <?php echo ($filter['status'] == 'diproses') ? 'selected' : ''; ?>>
                                Diproses</option>
                            <option value="selesai" <?php echo ($filter['status'] == 'selesai') ? 'selected' : ''; ?>>
                                Selesai</option>
                            <option value="ditolak" <?php echo ($filter['status'] == 'ditolak') ? 'selected' : ''; ?>>
                                Ditolak</option>
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
        <h5 class="card-title">Data Retur Penjualan</h5>
    </div>
    <div class="card-body">
        <?php if (isset($retur) && count($retur) > 0): ?>
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Retur</th>
                        <th>Tanggal</th>
                        <th>No Invoice</th>
                        <th>Pelanggan</th>
                        <th>Alasan Retur</th>
                        <th>Status</th>
                        <th>User</th>
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
                            <td><?php echo $r->alasan_retur; ?></td>
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
                            <td><?php echo $r->created_by; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                Tidak ada data retur penjualan dengan filter yang dipilih.
            </div>
        <?php endif; ?>
    </div>
</div>