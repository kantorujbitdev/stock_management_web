<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse mr-2"></i>Laporan Stok
        </h1>
        <div class="d-none d-sm-inline-block">
            <a href="<?php echo site_url('laporan_stok/export_pdf?' . $_SERVER['QUERY_STRING']); ?>"
                class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Export PDF
            </a>
            <a href="<?php echo site_url('laporan_stok/export_excel?' . $_SERVER['QUERY_STRING']); ?>"
                class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Item</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $summary['total_items']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Stok</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($summary['total_stok_akhir'], 0, ',', '.'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stok Menipis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $summary['stok_low_count']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stok Habis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $summary['stok_empty_count']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i>Filter Data
            </h6>
        </div>
        <div class="card-body">
            <form method="get" action="<?php echo site_url('laporan_stok'); ?>">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="id_perusahaan" class="form-label small text-muted">Perusahaan</label>
                        <select name="id_perusahaan" class="form-control form-control-sm" id="id_perusahaan">
                            <option value="">-- Semua Perusahaan --</option>
                            <?php foreach ($perusahaan as $p): ?>
                                <option value="<?php echo $p->id_perusahaan; ?>" <?php echo (isset($filter['id_perusahaan']) && $filter['id_perusahaan'] == $p->id_perusahaan) ? 'selected' : ''; ?>>
                                    <?php echo $p->nama_perusahaan; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="id_gudang" class="form-label small text-muted">Gudang</label>
                        <select name="id_gudang" class="form-control form-control-sm" id="id_gudang">
                            <option value="">-- Semua Gudang --</option>
                            <?php if (isset($gudang) && is_array($gudang)): ?>
                                <?php foreach ($gudang as $g): ?>
                                    <option value="<?php echo $g->id_gudang; ?>" <?php echo (isset($filter['id_gudang']) && $filter['id_gudang'] == $g->id_gudang) ? 'selected' : ''; ?>>
                                        <?php echo $g->nama_gudang; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="id_kategori" class="form-label small text-muted">Kategori</label>
                        <select name="id_kategori" class="form-control form-control-sm" id="id_kategori">
                            <option value="">-- Semua Kategori --</option>
                            <?php if (isset($kategori) && is_array($kategori)): ?>
                                <?php foreach ($kategori as $k): ?>
                                    <option value="<?php echo $k->id_kategori; ?>" <?php echo (isset($filter['id_kategori']) && $filter['id_kategori'] == $k->id_kategori) ? 'selected' : ''; ?>>
                                        <?php echo $k->nama_kategori; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="stock_status" class="form-label small text-muted">Status Stok</label>
                        <select name="stock_status" class="form-control form-control-sm" id="stock_status">
                            <option value="">-- Semua Status --</option>
                            <option value="normal" <?php echo (isset($filter['stock_status']) && $filter['stock_status'] == 'normal') ? 'selected' : ''; ?>>
                                Normal
                            </option>
                            <option value="low" <?php echo (isset($filter['stock_status']) && $filter['stock_status'] == 'low') ? 'selected' : ''; ?>>
                                Stok Menipis
                            </option>
                            <option value="over" <?php echo (isset($filter['stock_status']) && $filter['stock_status'] == 'over') ? 'selected' : ''; ?>>
                                Stok Berlebih
                            </option>
                            <option value="empty" <?php echo (isset($filter['stock_status']) && $filter['stock_status'] == 'empty') ? 'selected' : ''; ?>>
                                Stok Habis
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label small text-muted">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-sm form-control">
                            <i class="fas fa-search mr-1"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-2"></i>Data Stok
            </h6>
            <div>
                <a href="<?php echo site_url('laporan_stok/export_pdf?' . $_SERVER['QUERY_STRING']); ?>"
                    class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="<?php echo site_url('laporan_stok/export_excel?' . $_SERVER['QUERY_STRING']); ?>"
                    class="btn btn-success btn-sm ml-1">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Kode Barang</th>
                            <th width="15%">Nama Barang</th>
                            <th width="10%">Kategori</th>
                            <th width="12%">Perusahaan</th>
                            <th width="10%">Gudang</th>
                            <th width="8%" class="text-center">Stok Awal</th>
                            <th width="8%" class="text-center">Pembelian</th>
                            <th width="8%" class="text-center">Retur Masuk</th>
                            <th width="8%" class="text-center">Penjualan</th>
                            <th width="8%" class="text-center">Retur Keluar</th>
                            <th width="8%" class="text-center">Stok Akhir</th>
                            <th width="10%">Status</th>
                            <th width="12%">Terakhir Update</th>
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
                                <td class="text-center"><?php echo number_format($s->stok_awal, 0, ',', '.'); ?></td>
                                <td class="text-center"><?php echo number_format($s->pembelian_masuk, 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <span class="text-success font-weight-bold">
                                        <?php echo number_format($s->retur_masuk, 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td class="text-center"><?php echo number_format($s->penjualan_keluar, 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <span class="text-danger font-weight-bold">
                                        <?php echo number_format($s->retur_keluar, 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="font-weight-bold <?php echo ($s->jumlah < 10) ? 'text-danger' : ''; ?>">
                                        <?php echo number_format($s->jumlah, 0, ',', '.'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $status_class = '';
                                    $status_text = '';
                                    $status_icon = '';

                                    if ($s->jumlah == 0) {
                                        $status_class = 'danger';
                                        $status_text = 'Stok Habis';
                                        $status_icon = 'fas fa-times-circle';
                                    } elseif ($s->jumlah < 10) {
                                        $status_class = 'warning';
                                        $status_text = 'Stok Menipis';
                                        $status_icon = 'fas fa-exclamation-triangle';
                                    } elseif ($s->jumlah > 100) {
                                        $status_class = 'info';
                                        $status_text = 'Stok Berlebih';
                                        $status_icon = 'fas fa-info-circle';
                                    } else {
                                        $status_class = 'success';
                                        $status_text = 'Normal';
                                        $status_icon = 'fas fa-check-circle';
                                    }
                                    ?>
                                    <span class="badge badge-<?php echo $status_class; ?>">
                                        <i class="<?php echo $status_icon; ?> mr-1"></i><?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d-m-Y H:i', strtotime($s->updated_at)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <td colspan="6" class="text-right">TOTAL</td>
                            <td class="text-center">
                                <?php echo number_format($summary['total_stok_awal'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?php echo number_format($summary['total_pembelian_masuk'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?php echo number_format($summary['total_retur_masuk'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?php echo number_format($summary['total_penjualan_keluar'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?php echo number_format($summary['total_retur_keluar'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?php echo number_format($summary['total_stok_akhir'], 0, ',', '.'); ?>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Summary Retur -->
<div class="modal fade" id="returSummaryModal" tabindex="-1" role="dialog" aria-labelledby="returSummaryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="returSummaryModalLabel">
                    <i class="fas fa-undo mr-2"></i>Summary Retur
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Barang</th>
                                <th>Kategori</th>
                                <th>Perusahaan</th>
                                <th>Gudang</th>
                                <th class="text-center">Total Retur Masuk</th>
                                <th class="text-center">Total Retur Keluar</th>
                                <th class="text-center">Net Retur</th>
                            </tr>
                        </thead>
                        <tbody id="returSummaryBody">
                            <!-- Data akan diisi via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Load gudang and kategori based on perusahaan
        $('#id_perusahaan').change(function () {
            var id_perusahaan = $(this).val();

            // Reset gudang and kategori
            $('#id_gudang').html('<option value="">-- Semua Gudang --</option>');
            $('#id_kategori').html('<option value="">-- Semua Kategori --</option>');

            if (id_perusahaan != '') {
                // Load gudang
                $.ajax({
                    url: "<?php echo site_url('laporan_stok/get_gudang_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    dataType: "html",
                    success: function (data) {
                        if (data) {
                            $('#id_gudang').append(data);
                        }
                    }
                });

                // Load kategori
                $.ajax({
                    url: "<?php echo site_url('laporan_stok/get_kategori_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    dataType: "html",
                    success: function (data) {
                        if (data) {
                            $('#id_kategori').append(data);
                        }
                    }
                });
            }
        });

        // Load retur summary
        $('#loadReturSummary').click(function (e) {
            e.preventDefault();

            $.ajax({
                url: "<?php echo site_url('laporan_stok/get_retur_summary') ?>",
                method: "GET",
                data: {
                    id_perusahaan: $('#id_perusahaan').val(),
                    id_gudang: $('#id_gudang').val(),
                    id_kategori: $('#id_kategori').val()
                },
                dataType: "json",
                success: function (data) {
                    var html = '';

                    if (data.length > 0) {
                        $.each(data, function (index, item) {
                            var netRetur = item.total_retur_masuk - item.total_retur_keluar;
                            var netClass = netRetur > 0 ? 'text-success' : (netRetur < 0 ? 'text-danger' : '');

                            html += '<tr>';
                            html += '<td>' + item.nama_barang + '</td>';
                            html += '<td>' + item.nama_kategori + '</td>';
                            html += '<td>' + item.nama_perusahaan + '</td>';
                            html += '<td>' + item.nama_gudang + '</td>';
                            html += '<td class="text-center">' + item.total_retur_masuk + '</td>';
                            html += '<td class="text-center">' + item.total_retur_keluar + '</td>';
                            html += '<td class="text-center font-weight-bold ' + netClass + '">' + netRetur + '</td>';
                            html += '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="7" class="text-center">Tidak ada data retur</td></tr>';
                    }

                    $('#returSummaryBody').html(html);
                    $('#returSummaryModal').modal('show');
                }
            });
        });
    });
</script>