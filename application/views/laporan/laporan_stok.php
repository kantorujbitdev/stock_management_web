<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <h5 class="card-title">Laporan Stok</h3>
            <div class="col text-right">
                <a href="<?php echo site_url('laporan_stok/export_pdf?' . $_SERVER['QUERY_STRING']); ?>"
                    class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="<?php echo site_url('laporan_stok/export_excel?' . $_SERVER['QUERY_STRING']); ?>"
                    class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('laporan_stok'); ?>">
            <div class="row">
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="id_kategori">Kategori</label>
                        <select name="id_kategori" class="form-control" id="id_kategori">
                            <option value="">-- Semua --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?php echo $k->id_kategori; ?>" <?php echo ($filter['id_kategori'] == $k->id_kategori) ? 'selected' : ''; ?>>
                                    <?php echo $k->nama_kategori; ?>
                                </option>
                            <?php endforeach; ?>
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
        <h5 class="card-title">Data Stok</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Perusahaan</th>
                    <th>Gudang</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($stok as $s): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $s->sku; ?></td>
                        <td><?php echo $s->nama_barang; ?></td>
                        <td><?php echo $s->nama_kategori; ?></td>
                        <td><?php echo $s->nama_perusahaan; ?></td>
                        <td><?php echo $s->nama_gudang; ?></td>
                        <td><?php echo $s->jumlah; ?></td>
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
                    url: "<?php echo site_url('laporan_stok/get_gudang_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#id_gudang').html('<option value="">-- Semua --</option>' + data);
                        $('#id_kategori').html('<option value="">-- Semua --</option>');
                    }
                });

                $.ajax({
                    url: "<?php echo site_url('laporan_stok/get_kategori_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#id_kategori').html('<option value="">-- Semua --</option>' + data);
                    }
                });
            } else {
                $('#id_gudang').html('<option value="">-- Semua --</option>');
                $('#id_kategori').html('<option value="">-- Semua --</option>');
            }
        });
    });
</script>