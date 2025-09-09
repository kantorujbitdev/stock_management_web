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
                <a href="<?php echo site_url('laporan_stok/retur_summary?' . $_SERVER['QUERY_STRING']); ?>"
                    class="btn btn-info btn-sm">
                    <i class="fas fa-undo"></i> Summary Retur
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
                        <label for="stock_status">Status Stok</label>
                        <select name="stock_status" class="form-control" id="stock_status">
                            <option value="">-- Semua --</option>
                            <option value="normal" <?php echo ($filter['stock_status'] == 'normal') ? 'selected' : ''; ?>>
                                Normal</option>
                            <option value="low" <?php echo ($filter['stock_status'] == 'low') ? 'selected' : ''; ?>>Stok
                                Menipis</option>
                            <option value="over" <?php echo ($filter['stock_status'] == 'over') ? 'selected' : ''; ?>>Stok
                                Berlebih</option>
                            <option value="empty" <?php echo ($filter['stock_status'] == 'empty') ? 'selected' : ''; ?>>
                                Stok Habis</option>
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
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Perusahaan</th>
                        <th>Gudang</th>
                        <th>Stok Awal</th>
                        <th>Pembelian</th>
                        <th>Retur Masuk</th>
                        <th>Penjualan</th>
                        <th>Retur Keluar</th>
                        <th>Stok Akhir</th>
                        <th>Status</th>
                        <th>Terakhir Update</th>
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
                            <td><?php echo $s->stok_awal; ?></td>
                            <td><?php echo $s->pembelian_masuk; ?></td>
                            <td>
                                <span class="text-success">
                                    <?php echo $s->retur_masuk; ?>
                                </span>
                            </td>
                            <td><?php echo $s->penjualan_keluar; ?></td>
                            <td>
                                <span class="text-danger">
                                    <?php echo $s->retur_keluar; ?>
                                </span>
                            </td>
                            <td>
                                <span class="font-weight-bold <?php echo ($s->jumlah < 10) ? 'text-danger' : ''; ?>">
                                    <?php echo $s->jumlah; ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $status_class = '';
                                $status_text = '';
                                if ($s->jumlah == 0) {
                                    $status_class = 'danger';
                                    $status_text = 'Stok Habis';
                                } elseif ($s->jumlah < 10) {
                                    $status_class = 'warning';
                                    $status_text = 'Stok Menipis';
                                } elseif ($s->jumlah > 100) {
                                    $status_class = 'info';
                                    $status_text = 'Stok Berlebih';
                                } else {
                                    $status_class = 'success';
                                    $status_text = 'Normal';
                                }
                                ?>
                                <span class="badge badge-<?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                            </td>
                            <td><?php echo date('d-m-Y H:i', strtotime($s->updated_at)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan="6">Total</td>
                        <td><?php echo array_sum(array_column($stok, 'stok_awal')); ?></td>
                        <td><?php echo array_sum(array_column($stok, 'pembelian_masuk')); ?></td>
                        <td><?php echo array_sum(array_column($stok, 'retur_masuk')); ?></td>
                        <td><?php echo array_sum(array_column($stok, 'penjualan_keluar')); ?></td>
                        <td><?php echo array_sum(array_column($stok, 'retur_keluar')); ?></td>
                        <td><?php echo array_sum(array_column($stok, 'jumlah')); ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk Summary Retur -->
<div class="modal fade" id="returSummaryModal" tabindex="-1" role="dialog" aria-labelledby="returSummaryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returSummaryModalLabel">Summary Retur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Kategori</th>
                                <th>Perusahaan</th>
                                <th>Gudang</th>
                                <th>Total Retur Masuk</th>
                                <th>Total Retur Keluar</th>
                                <th>Net Retur</th>
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
        $('#id_perusahaan').change(function () {
            var id_perusahaan = $(this).val();
            if (id_perusahaan != '') {
                $.ajax({
                    url: "<?php echo site_url('laporan_stok/get_gudang_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    success: function (data) {
                        $('#id_gudang').html('<option value="">-- Semua --</option>' + data);
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

        // Load retur summary
        function loadReturSummary() {
            $.ajax({
                url: "<?php echo site_url('laporan_stok/get_retur_summary') ?>",
                method: "GET",
                data: {
                    id_perusahaan: $('#id_perusahaan').val(),
                    id_gudang: $('#id_gudang').val(),
                    id_kategori: $('#id_kategori').val()
                },
                success: function (data) {
                    var html = '';
                    if (data.length > 0) {
                        $.each(data, function (index, item) {
                            html += '<tr>';
                            html += '<td>' + item.nama_barang + '</td>';
                            html += '<td>' + item.nama_kategori + '</td>';
                            html += '<td>' + item.nama_perusahaan + '</td>';
                            html += '<td>' + item.nama_gudang + '</td>';
                            html += '<td>' + item.total_retur_masuk + '</td>';
                            html += '<td>' + item.total_retur_keluar + '</td>';
                            html += '<td>' + (item.total_retur_masuk - item.total_retur_keluar) + '</td>';
                            html += '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="8" class="text-center">Tidak ada data retur</td></tr>';
                    }
                    $('#returSummaryBody').html(html);
                    $('#returSummaryModal').modal('show');
                }
            });
        }
    });
</script>