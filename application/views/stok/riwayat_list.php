<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h5 class="m-0 font-weight-bold text-primary">Riwayat Stok</h6>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form method="get" action="<?php echo site_url('riwayat'); ?>">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id_perusahaan">Perusahaan</label>
                        <select name="id_perusahaan" class="form-control" id="id_perusahaan">
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
                        <label for="id_gudang">Gudang</label>
                        <select name="id_gudang" class="form-control" id="id_gudang">
                            <option value="">-- Semua --</option>
                            <?php foreach ($gudang as $g): ?>
                                <option value="<?php echo $g->id_gudang; ?>" <?php echo ($filter['id_gudang'] == $g->id_gudang) ? 'selected' : ''; ?>>
                                    <?php echo $g->nama_gudang; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id_barang">Barang</label>
                        <select name="id_barang" class="form-control" id="id_barang">
                            <option value="">-- Semua --</option>
                            <?php foreach ($barang as $b): ?>
                                <option value="<?php echo $b->id_barang; ?>" <?php echo ($filter['id_barang'] == $b->id_barang) ? 'selected' : ''; ?>>
                                    <?php echo $b->nama_barang; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select name="jenis" class="form-control">
                            <option value="">-- Semua --</option>
                            <option value="masuk" <?php echo ($filter['jenis'] == 'masuk') ? 'selected' : ''; ?>>Masuk
                            </option>
                            <option value="keluar" <?php echo ($filter['jenis'] == 'keluar') ? 'selected' : ''; ?>>Keluar
                            </option>
                            <option value="retur" <?php echo ($filter['jenis'] == 'retur') ? 'selected' : ''; ?>>Retur
                            </option>
                            <option value="transfer_keluar" <?php echo ($filter['jenis'] == 'transfer_keluar') ? 'selected' : ''; ?>>Transfer Keluar</option>
                            <option value="transfer_masuk" <?php echo ($filter['jenis'] == 'transfer_masuk') ? 'selected' : ''; ?>>Transfer Masuk</option>
                            <option value="penyesuaian" <?php echo ($filter['jenis'] == 'penyesuaian') ? 'selected' : ''; ?>>Penyesuaian</option>
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
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?php echo site_url('riwayat'); ?>" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Data Riwayat Stok</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Barang</th>
                    <th>Gudang</th>
                    <th>Perusahaan</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($riwayat as $r): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d-m-Y H:i:s', strtotime($r->tanggal)); ?></td>
                        <td><?php echo $r->nama_barang; ?></td>
                        <td><?php echo $r->nama_gudang; ?></td>
                        <td><?php echo $r->nama_perusahaan; ?></td>
                        <td>
                            <?php if ($r->jenis == 'masuk'): ?>
                                <span class="badge badge-success">Masuk</span>
                            <?php elseif ($r->jenis == 'keluar'): ?>
                                <span class="badge badge-danger">Keluar</span>
                            <?php elseif ($r->jenis == 'retur'): ?>
                                <span class="badge badge-info">Retur</span>
                            <?php elseif ($r->jenis == 'transfer_keluar'): ?>
                                <span class="badge badge-warning">Transfer Keluar</span>
                            <?php elseif ($r->jenis == 'transfer_masuk'): ?>
                                <span class="badge badge-primary">Transfer Masuk</span>
                            <?php elseif ($r->jenis == 'penyesuaian'): ?>
                                <span class="badge badge-secondary">Penyesuaian</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $r->jumlah; ?></td>
                        <td><?php echo $r->keterangan; ?></td>
                        <td><?php echo $r->created_by; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#id_perusahaan').change(function () {
            var id_perusahaan = $(this).val();

            if (id_perusahaan != '') {
                $.ajax({
                    url: "<?php echo site_url('riwayat/get_gudang_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#id_gudang').html('<option value="">-- Semua --</option>' + data);
                        $('#id_barang').html('<option value="">-- Semua --</option>');
                    }
                });
            } else {
                $('#id_gudang').html('<option value="">-- Semua --</option>');
                $('#id_barang').html('<option value="">-- Semua --</option>');
            }
        });

        $('#id_gudang').change(function () {
            var id_gudang = $(this).val();

            if (id_gudang != '') {
                $.ajax({
                    url: "<?php echo site_url('riwayat/get_barang_by_gudang') ?>",
                    method: "POST",
                    data: { id_gudang: id_gudang },
                    success: function (data) {
                        $('#id_barang').html('<option value="">-- Semua --</option>' + data);
                    }
                });
            } else {
                $('#id_barang').html('<option value="">-- Semua --</option>');
            }
        });
    });
</script>