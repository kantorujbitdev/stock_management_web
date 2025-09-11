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
    </div>
</div>

<!-- Load Modals -->
<?php $this->load->view('master/barang_part/barang_detail_modal'); ?>
<?php $this->load->view('master/barang_part/barang_stok_modal'); ?>
<?php $this->load->view('master/barang_part/barang_image_modal'); ?>

<!-- Load Script -->
<?php $this->load->view('master/barang_part/barang_style'); ?>
<?php $this->load->view('master/barang_part/barang_script'); ?>