<div class="container-fluid px-1">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-warehouse mr-2"></i>Laporan Stok
        </h1>
        <div class="d-none d-sm-inline-block">
            <?php $this->load->view('laporan/stok/laporan_stok_export_pdf_excel'); ?>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-1">
        <?php $this->load->view('laporan/stok/table_summary', ['summary' => $summary]); ?>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center" id="filterHeader"
            style="cursor:pointer;">
            <h6 class="mb-0 text-primary font-weight-bold">
                <i class="fas fa-filter mr-2"></i>Filter Data
            </h6>
            <button type="button" class="btn btn-sm btn-outline-primary no-hover" id="toggleFilter" disabled>
                <i class="fas fa-chevron-down" id="toggleIcon"></i>
            </button>
        </div>
        <?php $this->load->view('laporan/stok/filter', [
            'perusahaan' => $perusahaan,
            'gudang' => $gudang,
            'kategori' => $kategori,
            'filter' => $filter
        ]); ?>
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
            <?php $this->load->view('laporan/stok/table_view', [
                'stok' => $stok,
                'summary' => $summary
            ]); ?>
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

    <!-- Load JavaScript -->
    <?php $this->load->view('laporan/stok/laporan_stok_script'); ?>
</div>