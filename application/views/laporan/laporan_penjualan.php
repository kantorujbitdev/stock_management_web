<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Penjualan</h3>
        <div class="card-tools">
            <a href="<?php echo site_url('laporan_penjualan/export_pdf?' . $_SERVER['QUERY_STRING']); ?>"
                class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="<?php echo site_url('laporan_penjualan/export_excel?' . $_SERVER['QUERY_STRING']); ?>"
                class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('laporan_penjualan'); ?>">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="id_perusahaan">Perusahaan</label>
                        <select name="id_perusahaan" class="form-control">
                            <option value="">-- Semua --</option>
                            <?php foreach ($perusahaan as $p): ?>
                                <option value="<?php echo $p->id_perusahaan; ?>" 
                                <?php echo ($filter['id_perusahaan'] == $p->id_perusahaan) ? 'selected' : ''; ?>>
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
                            <option value="proses" <?php echo ($filter['status'] == 'proses') ? 'selected' : ''; ?>>Proses
                            </option>
                            <option value="packing" <?php echo ($filter['status'] == 'packing') ? 'selected' : ''; ?>>
                                Packing</option>
                            <option value="dikirim" <?php echo ($filter['status'] == 'dikirim') ? 'selected' : ''; ?>>
                                Dikirim</option>
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
        <h3 class="card-title">Data Penjualan</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Invoice</th>
                    <th>Pelanggan</th>
                    <?php if ($this->session->userdata('id_role') == 5): // Super Admin ?>
                    <th>Perusahaan</th>
                    <?php endif; ?>
                    <th>Barang</th>
                    <th>@</th>
                    <th>Status</th>
                    <th>User</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($penjualan as $p): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $p->no_invoice; ?></td>
                        <td><?php echo $p->nama_pelanggan; ?></td>
                        <?php if ($this->session->userdata('id_role') == 5): // Super Admin ?>
                        <td><?php echo $p->nama_perusahaan; ?></td>
                        <?php endif; ?>
                        <td>
                            <div style="max-width: 300px; overflow: auto;">
                                <?php echo $p->daftar_barang; ?>
                            </div>
                        </td>
                        <td><?php echo $p->jumlah_item; ?></td>
                        <td>
                            <?php if ($p->status == 'proses'): ?>
                                <span class="badge badge-secondary">Proses</span>
                            <?php elseif ($p->status == 'packing'): ?>
                                <span class="badge badge-primary">Packing</span>
                            <?php elseif ($p->status == 'dikirim'): ?>
                                <span class="badge badge-info">Dikirim</span>
                            <?php elseif ($p->status == 'selesai'): ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php elseif ($p->status == 'batal'): ?>
                                <span class="badge badge-danger">Batal</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $p->created_by; ?></td>
                        <td>
                            <a href="<?php echo site_url('laporan_penjualan/detail/' . $p->id_penjualan); ?>" 
                               class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>