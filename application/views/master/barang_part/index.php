<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col-md-6">
                <?php echo responsive_title_blue('Daftar Barang') ?>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo site_url('barang/add') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Barang
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

        <!-- Load Filter -->
        <?php $this->load->view('master/barang_part/barang_filter', ['kategori' => $kategori, 'filter' => $filter, 'perusahaan' => $perusahaan]); ?>

        <!-- Grid Barang -->
        <div class="row" id="barangGrid">
            <?php foreach ($barang as $b): ?>
                <?php $this->load->view('master/barang_part/barang_card', ['b' => $b]); ?>
            <?php endforeach; ?>
        </div>
        <!-- Info Items -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="text-muted">
                    <span id="showing-count"><?php echo count($barang); ?></span> dari
                    <span id="total-count"><?php echo $total_items; ?></span> barang
                </span>
            </div>
            <div>
                <span class="text-muted">Halaman <span id="current-page"><?php echo $current_page; ?></span></span>
            </div>
        </div>
        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="text-center py-4" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat barang lainnya...</p>
        </div>

        <!-- No Results Message -->
        <?php if (empty($barang)): ?>
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak ada barang yang ditemukan</h5>
                <p class="text-muted">Coba gunakan filter lain atau reset filter</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Load Modals -->
<?php $this->load->view('master/barang_part/barang_detail_modal'); ?>
<?php $this->load->view('master/barang_part/barang_stok_modal'); ?>
<?php $this->load->view('master/barang_part/barang_image_modal'); ?>

<!-- Load Script -->
<?php $this->load->view('master/barang_part/barang_style'); ?>
<?php $this->load->view('master/barang_part/barang_script'); ?>