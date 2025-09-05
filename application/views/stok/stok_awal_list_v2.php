<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Barang - Stok Awal</h6>
            </div>
            <div class="col text-right">
                <a href="<?php echo site_url('stok_awal/export_excel'); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>
        <div class="card-tools">
            <div class="btn-group">
                <a href="<?php echo site_url('stok_awal?stock_status=all'); ?>"
                    class="btn btn-sm <?php echo ($filter['stock_status'] == 'all' || empty($filter['stock_status'])) ? 'btn-primary' : 'btn-default'; ?>">
                    <i class="fas fa-list"></i> Semua
                </a>
                <a href="<?php echo site_url('stok_awal?stock_status=empty'); ?>"
                    class="btn btn-sm <?php echo ($filter['stock_status'] == 'empty') ? 'btn-danger' : 'btn-default'; ?>">
                    <i class="fas fa-box-open"></i> Belum Ada Stok
                </a>
                <a href="<?php echo site_url('stok_awal?stock_status=has_stock'); ?>"
                    class="btn btn-sm <?php echo ($filter['stock_status'] == 'has_stock') ? 'btn-success' : 'btn-default'; ?>">
                    <i class="fas fa-box"></i> Sudah Ada Stok
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

        <!-- Filter Form -->
        <form method="get" action="<?php echo site_url('stok_awal'); ?>">
            <div class="row">
                <div class="col-md-3">
                    <select name="id_perusahaan" class="form-control" id="filter_perusahaan">
                        <option value="">-- Semua Perusahaan --</option>
                        <?php foreach ($perusahaan as $p): ?>
                            <option value="<?php echo $p->id_perusahaan; ?>" <?php echo ($filter['id_perusahaan'] == $p->id_perusahaan) ? 'selected' : ''; ?>>
                                <?php echo $p->nama_perusahaan; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="id_gudang" class="form-control" id="filter_gudang">
                        <option value="">-- Semua Gudang --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="id_barang" class="form-control" id="filter_barang">
                        <option value="">-- Semua Barang --</option>
                    </select>
                </div>
                <div class="col-md-3">
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
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Perusahaan</th>
                        <th>Status Stok</th>
                        <th>Gudang</th>
                        <th>Qty Awal</th>
                        <th>Keterangan</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($barang as $item): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $item->sku; ?></strong></td>
                            <td><strong><?php echo $item->nama_barang; ?></strong></td>
                            <td><?php echo $item->nama_kategori; ?></td>
                            <td><?php echo $item->nama_perusahaan; ?></td>
                            <td>
                                <?php if ($item->has_stok_awal > 0): ?>
                                    <span class="badge badge-success">Sudah Ada Stok</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Belum Ada Stok</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $item->nama_gudang ?: '-'; ?></td>
                            <td>
                                <?php if ($item->qty_awal > 0): ?>
                                    <span class="font-weight-bold"><?php echo $item->qty_awal; ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td><?php echo $item->keterangan ?: '-'; ?></td>
                            <td><?php echo $item->created_by_name ?: '-'; ?></td>
                            <td>
                                <?php if ($item->has_stok_awal == 0): ?>
                                    <a href="<?php echo site_url('stok_awal/input_stok/' . $item->id_barang); ?>"
                                        class="btn btn-primary btn-sm" title="Input Stok Awal">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript untuk filter -->
<script>
    $(document).ready(function () {
        // Filter functionality
        $('#filter_perusahaan').change(function () {
            var id_perusahaan = $(this).val();

            if (id_perusahaan != '') {
                // Get gudang by perusahaan
                $.ajax({
                    url: "<?php echo site_url('stok_awal/get_gudang_by_perusahaan') ?>",
                    method: "GET",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#filter_gudang').html('<option value="">-- Semua Gudang --</option>' + data);
                    }
                });

                // Get barang by perusahaan
                $.ajax({
                    url: "<?php echo site_url('stok_awal/get_barang_by_perusahaan') ?>",
                    method: "GET",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#filter_barang').html('<option value="">-- Semua Barang --</option>' + data);
                    }
                });
            } else {
                $('#filter_gudang').html('<option value="">-- Semua Gudang --</option>');
                $('#filter_barang').html('<option value="">-- Semua Barang --</option>');
            }
        });

</script>