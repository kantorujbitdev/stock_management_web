<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Barang</h6>
            </div>
            <div class="col text-right">
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

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Barang</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <?php if ($this->session->userdata('id_role') == 5): ?>
                            <th>Perusahaan</th>
                        <?php endif; ?>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($barang as $b): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <!-- <td>
                                <?php if ($b->gambar): ?>
                                    <img src="<?php echo base_url('uploads/barang/' . $b->gambar); ?>" class="img-thumbnail"
                                        width="50">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td> -->
                            <td>
                                <?php if ($b->gambar): ?>
                                    <img src="<?php echo base_url('uploads/barang/' . $b->gambar); ?>"
                                        class="img-thumbnail img-clickable" width="50"
                                        data-src="<?php echo base_url('uploads/barang/' . $b->gambar); ?>">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $b->nama_barang; ?></td>
                            <td><?php echo $b->sku; ?></td>
                            <td><?php echo $b->nama_kategori; ?></td>
                            <?php if ($this->session->userdata('id_role') == 5): ?>
                                <td><?php echo isset($b->nama_perusahaan) ? $b->nama_perusahaan : '-'; ?></td>
                            <?php endif; ?>
                            <td>
                                <?php
                                $this->load->model('master/Barang_model');
                                $stok = $this->Barang_model->get_stok_barang($b->id_barang);
                                echo $stok ?: 0;
                                ?>
                            </td>
                            <td>
                                <?php if ($b->aktif == 1): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($b->aktif == 1): ?>
                                    <a href="<?php echo site_url('barang/nonaktif/' . $b->id_barang); ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menonaktifkan barang ini?')">
                                        <i class="fas fa-minus-square"></i> Nonaktifkan
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('barang/aktif/' . $b->id_barang); ?>"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Apakah Anda yakin ingin mengaktifkan barang ini?')">
                                        <i class="fas fa-check-square"></i> Aktifkan
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo site_url('barang/edit/' . $b->id_barang); ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal Fullscreen -->
<div class="modal fade" id="gambarModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content bg-transparent border-0 shadow-none">

            <!-- Tombol Tutup di pojok kanan atas -->
            <button type="button" class="close position-absolute" data-dismiss="modal" aria-label="Close"
                style="top: 15px; right: 20px; font-size: 2rem; color: white; z-index: 1051;">
                <span aria-hidden="true">&times;</span>
            </button>

            <div class="modal-body p-0 text-center">
                <img id="gambarPreview" src="" class="img-fluid rounded-lg shadow-lg"
                    style="max-height: 90vh; cursor: grab;" />
            </div>
        </div>
    </div>
</div>
<!-- CSS Custom -->
<style>
    .img-clickable {
        cursor: zoom-in;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .img-clickable:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
</style>


<!-- JS Custom -->
<script>
    $(document).on('click', '.img-clickable', function () {
        var src = $(this).data('src');
        $('#gambarPreview').attr('src', src);
        $('#gambarModal').modal('show');
    });

    // Zoom pakai scroll
    $('#gambarPreview').on('wheel', function (e) {
        e.preventDefault();
        var scale = $(this).data('scale') || 1;
        scale += (e.originalEvent.deltaY < 0 ? 0.1 : -0.1);
        if (scale < 0.5) scale = 0.5;
        if (scale > 3) scale = 3;
        $(this).css('transform', 'translate(0,0) scale(' + scale + ')');
        $(this).data('scale', scale);
    });

    // Drag gambar
    let isDragging = false, startX, startY, translateX = 0, translateY = 0;

    $('#gambarPreview').on('mousedown', function (e) {
        isDragging = true;
        startX = e.pageX - translateX;
        startY = e.pageY - translateY;
        $(this).css('cursor', 'grabbing');
    });

    $(document).on('mouseup', function () {
        isDragging = false;
        $('#gambarPreview').css('cursor', 'grab');
    });

    $(document).on('mousemove', function (e) {
        if (!isDragging) return;
        translateX = e.pageX - startX;
        translateY = e.pageY - startY;
        $('#gambarPreview').css('transform',
            'translate(' + translateX + 'px,' + translateY + 'px) scale(' + ($('#gambarPreview').data('scale') || 1) + ')');
    });

    // Reset saat modal ditutup
    $('#gambarModal').on('hidden.bs.modal', function () {
        $('#gambarPreview').css('transform', 'scale(1)').data('scale', 1);
        translateX = 0; translateY = 0;
    });
</script>