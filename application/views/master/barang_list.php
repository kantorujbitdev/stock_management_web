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

        <!-- Filter dan Pencarian -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" id="searchBarang" class="form-control" placeholder="Cari barang...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <select id="filterKategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    <?php
                    $this->load->model('master/Kategori_model');
                    if ($this->session->userdata('id_role') == 5) {
                        $kategori = $this->Kategori_model->get_all_kategori();
                    } else {
                        $id_perusahaan = $this->session->userdata('id_perusahaan');
                        $kategori = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
                    }
                    foreach ($kategori as $k): ?>
                        <option value="<?php echo $k->id_kategori; ?>"><?php echo $k->nama_kategori; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="sortBy" class="form-control">
                    <option value="nama_barang">Nama</option>
                    <option value="sku">SKU</option>
                    <option value="stok">Stok</option>
                </select>
            </div>
        </div>

        <!-- Grid Barang -->
        <div class="row" id="barangGrid">
            <?php
            $this->load->model('master/Barang_model');
            foreach ($barang as $b):
                $stok = $this->Barang_model->get_stok_barang($b->id_barang);
                $stokClass = ($stok > 10) ? 'success' : (($stok > 0) ? 'warning' : 'danger');
                $stokIcon = ($stok > 10) ? 'fa-check-circle' : (($stok > 0) ? 'fa-exclamation-circle' : 'fa-times-circle');
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 barang-item card-clickable"
                    data-nama="<?php echo strtolower($b->nama_barang); ?>" data-sku="<?php echo strtolower($b->sku); ?>"
                    data-kategori="<?php echo $b->id_kategori; ?>" data-status="<?php echo $b->aktif; ?>"
                    data-stok="<?php echo $stok; ?>" data-id="<?php echo $b->id_barang; ?>"
                    data-gambar="<?php echo $b->gambar ? base_url('uploads/barang/' . $b->gambar) : ''; ?>"
                    data-namabarang="<?php echo $b->nama_barang; ?>" data-skuvalue="<?php echo $b->sku; ?>"
                    data-kategoriname="<?php echo $b->nama_kategori; ?>"
                    data-perusahaan="<?php echo isset($b->nama_perusahaan) ? $b->nama_perusahaan : '-'; ?>"
                    data-deskripsi="<?php echo $b->deskripsi ?: '-'; ?>">
                    <div class="card h-100 shadow-sm">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative"
                            style="height: 180px; overflow: hidden;">
                            <?php if ($b->gambar): ?>
                                <img src="<?php echo base_url('uploads/barang/' . $b->gambar); ?>" class="img-fluid"
                                    style="max-height: 100%; width: auto; object-fit: contain;"
                                    data-src="<?php echo base_url('uploads/barang/' . $b->gambar); ?>">
                            <?php else: ?>
                                <div class="text-center text-muted p-4">
                                    <i class="fas fa-image fa-3x"></i>
                                    <p class="mt-2">No Image</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="card-title font-weight-bold text-truncate"><?php echo $b->nama_barang; ?></h6>
                                <span style="font-size: 1.2rem; font-weight: bold;"><?php echo $stok ?: 0; ?></span>

                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <p class="card-text text-muted small mb-1">SKU: <?php echo $b->sku; ?></p>
                                <span class="badge badge-<?php echo ($b->aktif == 1) ? 'success' : 'danger'; ?>">
                                    <?php echo ($b->aktif == 1) ? 'Aktif' : 'Tidak Aktif'; ?>
                                </span>
                            </div>
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-tag"></i> <?php echo $b->nama_kategori; ?>
                            </p>
                            <?php if ($this->session->userdata('id_role') == 5): ?>
                                <p class="card-text text-muted small mb-2">
                                    <i class="fas fa-building"></i>
                                    <?php echo isset($b->nama_perusahaan) ? $b->nama_perusahaan : '-'; ?>
                                </p>
                            <?php endif; ?>

                            <div class="mt-auto">
                                <?php if ($this->session->userdata('id_role') == 5 || $this->session->userdata('id_role') == 1): ?>
                                    <div class="d-grid gap-2 mb-1">
                                        <div class="btn-group w-100" role="group">
                                            <a href="<?php echo site_url('barang/edit/' . $b->id_barang); ?>"
                                                class="btn btn-warning btn-sm flex-fill d-flex align-items-center justify-content-center mr-1">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                            <?php if ($b->aktif == 1): ?>
                                                <a href="<?php echo site_url('barang/nonaktif/' . $b->id_barang); ?>"
                                                    class="btn btn-danger btn-sm flex-fill d-flex align-items-center justify-content-center ml-1"
                                                    onclick="return confirm('Apakah Anda yakin ingin menonaktifkan barang ini?')">
                                                    <i class="fas fa-minus-square mr-1"></i> Nonaktif
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo site_url('barang/aktif/' . $b->id_barang); ?>"
                                                    class="btn btn-success btn-sm flex-fill d-flex align-items-center justify-content-center ml-1"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengaktifkan barang ini?')">
                                                    <i class="fas fa-check-square mr-1"></i> Aktif
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <button type="button" class="btn btn-info btn-sm w-100 detail-btn"
                                    data-id="<?php echo $b->id_barang; ?>">
                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal Detail Barang Global -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 text-center">
                        <div id="modalGambarContainer">
                            <!-- Gambar akan dimuat di sini -->
                        </div>
                    </div>
                    <div class="col-md-7">
                        <table class="table table-borderless">
                            <tr>
                                <td width="30%"><strong>Nama Barang</strong></td>
                                <td width="5%">:</td>
                                <td id="modalNamaBarang"></td>
                            </tr>
                            <tr>
                                <td><strong>SKU</strong></td>
                                <td>:</td>
                                <td id="modalSKU"></td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td>:</td>
                                <td id="modalKategori"></td>
                            </tr>
                            <tr id="modalPerusahaanRow" style="display: none;">
                                <td><strong>Perusahaan</strong></td>
                                <td>:</td>
                                <td id="modalPerusahaan"></td>
                            </tr>
                            <tr>
                                <td><strong>Stok Tersedia</strong></td>
                                <td>:</td>
                                <td>
                                    <span id="modalStok" style="font-size: 1.5rem; font-weight: bold;"></span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>:</td>
                                <td>
                                    <span id="modalStatus"></span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Deskripsi</strong></td>
                                <td>:</td>
                                <td id="modalDeskripsi"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a id="modalEditLink" href="#" class="btn btn-warning" style="display: none;">
                    <i class="fas fa-edit"></i> Edit Barang
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Fullscreen untuk Gambar -->
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
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .barang-item {
        transition: all 0.3s ease;
    }

    .barang-item:hover {
        transform: translateY(-5px);
    }

    .card-clickable {
        cursor: pointer;
    }

    /* Badge stok yang lebih menarik */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        font-weight: 600;
        border-radius: 0.375rem;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 576px) {
        .card-img-top {
            height: 150px !important;
        }

        .card-title {
            font-size: 0.9rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.775rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    }

    /* Responsif untuk layar sedang */
    @media (min-width: 577px) and (max-width: 768px) {
        .card-img-top {
            height: 160px !important;
        }
    }

    /* Responsif untuk layar besar */
    @media (min-width: 769px) {
        .card-img-top {
            height: 180px !important;
        }
    }
</style>

<!-- JS Custom untuk halaman ini -->
<script>
    // Filter functionality
    document.getElementById('searchBarang').addEventListener('keyup', filterBarang);
    document.getElementById('filterKategori').addEventListener('change', filterBarang);
    document.getElementById('filterStatus').addEventListener('change', filterBarang);
    document.getElementById('sortBy').addEventListener('change', sortBarang);

    function filterBarang() {
        const searchValue = document.getElementById('searchBarang').value.toLowerCase();
        const kategoriValue = document.getElementById('filterKategori').value;
        const statusValue = document.getElementById('filterStatus').value;
        const items = document.querySelectorAll('.barang-item');

        items.forEach(item => {
            const nama = item.getAttribute('data-nama');
            const sku = item.getAttribute('data-sku');
            const kategori = item.getAttribute('data-kategori');
            const status = item.getAttribute('data-status');

            const matchSearch = nama.includes(searchValue) || sku.includes(searchValue);
            const matchKategori = kategoriValue === '' || kategori === kategoriValue;
            const matchStatus = statusValue === '' || status === statusValue;

            if (matchSearch && matchKategori && matchStatus) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function sortBarang() {
        const sortBy = document.getElementById('sortBy').value;
        const grid = document.getElementById('barangGrid');
        const items = Array.from(grid.querySelectorAll('.barang-item'));

        items.sort((a, b) => {
            let aValue, bValue;

            switch (sortBy) {
                case 'nama_barang':
                    aValue = a.getAttribute('data-nama');
                    bValue = b.getAttribute('data-nama');
                    return aValue.localeCompare(bValue);
                case 'sku':
                    aValue = a.getAttribute('data-sku');
                    bValue = b.getAttribute('data-sku');
                    return aValue.localeCompare(bValue);
                case 'stok':
                    aValue = parseInt(a.getAttribute('data-stok'));
                    bValue = parseInt(b.getAttribute('data-stok'));
                    return bValue - aValue; // Descending for stock
                default:
                    return 0;
            }
        });

        // Re-append sorted items
        items.forEach(item => grid.appendChild(item));
    }

    // Detail modal functionality
    document.addEventListener('DOMContentLoaded', function () {
        // Add click event to all detail buttons
        const detailButtons = document.querySelectorAll('.detail-btn');
        detailButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent card click event
                showDetailModal(this.closest('.barang-item'));
            });
        });

        // Add click event to all cards
        const cardItems = document.querySelectorAll('.card-clickable');
        cardItems.forEach(card => {
            card.addEventListener('click', function () {
                showDetailModal(this);
            });
        });
    });

    function showDetailModal(barangItem) {
        const id = barangItem.getAttribute('data-id');
        const gambar = barangItem.getAttribute('data-gambar');
        const namaBarang = barangItem.getAttribute('data-namabarang');
        const sku = barangItem.getAttribute('data-skuvalue');
        const kategori = barangItem.getAttribute('data-kategoriname');
        const perusahaan = barangItem.getAttribute('data-perusahaan');
        const deskripsi = barangItem.getAttribute('data-deskripsi');
        const stok = parseInt(barangItem.getAttribute('data-stok'));
        const status = barangItem.getAttribute('data-status');

        // Set modal content
        if (gambar) {
            document.getElementById('modalGambarContainer').innerHTML =
                `<img src="${gambar}" class="img-fluid img-clickable" data-src="${gambar}">`;
        } else {
            document.getElementById('modalGambarContainer').innerHTML =
                `<div class="text-center text-muted p-4">
                    <i class="fas fa-image fa-5x"></i>
                    <p class="mt-2">No Image</p>
                </div>`;
        }

        document.getElementById('modalNamaBarang').textContent = namaBarang;
        document.getElementById('modalSKU').textContent = sku;
        document.getElementById('modalKategori').textContent = kategori;
        document.getElementById('modalPerusahaan').textContent = perusahaan;
        document.getElementById('modalDeskripsi').textContent = deskripsi;

        // Set stok with color
        const stokElement = document.getElementById('modalStok');
        stokElement.textContent = stok;
        stokElement.className = '';
        if (stok > 10) {
            stokElement.className = 'text-success';
            stokElement.style.fontSize = '1.5rem';
            stokElement.style.fontWeight = 'bold';
        } else if (stok > 0) {
            stokElement.className = 'text-warning';
            stokElement.style.fontSize = '1.5rem';
            stokElement.style.fontWeight = 'bold';
        } else {
            stokElement.className = 'text-danger';
            stokElement.style.fontSize = '1.5rem';
            stokElement.style.fontWeight = 'bold';
        }

        // Set status
        const statusElement = document.getElementById('modalStatus');
        if (status === '1') {
            statusElement.textContent = 'Aktif';
            statusElement.className = 'text-success font-weight-bold';
        } else {
            statusElement.textContent = 'Tidak Aktif';
            statusElement.className = 'text-danger font-weight-bold';
        }

        // Show/hide perusahaan row based on user role
        const perusahaanRow = document.getElementById('modalPerusahaanRow');
        if (document.body.getAttribute('data-role') === '5') {
            perusahaanRow.style.display = '';
        } else {
            perusahaanRow.style.display = 'none';
        }

        // Set edit link
        document.getElementById('modalEditLink').href = `barang/edit/${id}`;
        if (document.body.getAttribute('data-role') === '5' || document.body.getAttribute('data-role') === '1') {
            document.getElementById('modalEditLink').style.display = '';
        } else {
            document.getElementById('modalEditLink').style.display = 'none';
        }

        // Show modal
        $('#detailModal').modal('show');
    }

    // Fungsi untuk mengeksekusi script setelah jQuery dan Bootstrap siap
    function runImageFullscreenScript() {
        if (window.jQuery && typeof jQuery.fn.modal === 'function') {
            console.log("Initializing image fullscreen script");
            // Klik gambar untuk fullscreen
            jQuery(document).on('click', '.img-clickable', function (e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent card click event
                console.log('Gambar diklik');
                var src = jQuery(this).data('src');
                console.log('SRC: ' + src);
                jQuery('#gambarPreview').attr('src', src);
                jQuery('#gambarModal').modal('show');
            });
            // Zoom pakai scroll
            jQuery('#gambarPreview').on('wheel', function (e) {
                e.preventDefault();
                var scale = jQuery(this).data('scale') || 1;
                scale += (e.originalEvent.deltaY < 0 ? 0.1 : -0.1);
                if (scale < 0.5) scale = 0.5;
                if (scale > 3) scale = 3;
                jQuery(this).css('transform', 'translate(0,0) scale(' + scale + ')');
                jQuery(this).data('scale', scale);
            });
            // Drag gambar
            var isDragging = false, startX, startY, translateX = 0, translateY = 0;
            jQuery('#gambarPreview').on('mousedown', function (e) {
                isDragging = true;
                startX = e.pageX - translateX;
                startY = e.pageY - translateY;
                jQuery(this).css('cursor', 'grabbing');
            });
            jQuery(document).on('mouseup', function () {
                isDragging = false;
                jQuery('#gambarPreview').css('cursor', 'grab');
            });
            jQuery(document).on('mousemove', function (e) {
                if (!isDragging) return;
                translateX = e.pageX - startX;
                translateY = e.pageY - startY;
                jQuery('#gambarPreview').css('transform',
                    'translate(' + translateX + 'px,' + translateY + 'px) scale(' + (jQuery('#gambarPreview').data('scale') || 1) + ')');
            });
            // Reset saat modal ditutup
            jQuery('#gambarModal').on('hidden.bs.modal', function () {
                jQuery('#gambarPreview').css('transform', 'scale(1)').data('scale', 1);
                translateX = 0; translateY = 0;
            });
            console.log("Image fullscreen script initialized");
        } else {
            console.log("jQuery or Bootstrap not ready yet, retrying...");
            setTimeout(runImageFullscreenScript, 200);
        }
    }

    // Jalankan script setelah dependencies siap
    document.addEventListener('DOMContentLoaded', function () {
        runImageFullscreenScript();
    });
</script>