<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="<?php echo site_url('dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-warehouse"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Manajemen Stok</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard (selalu tampil karena sudah dicek di controller) -->
    <li class="nav-item <?php echo is_active('dashboard') ?>">
        <a class="nav-link" href="<?php echo site_url('dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Master Data (hanya tampil jika user punya akses ke salah satu submenu) -->
    <?php if ($this->hak_akses->cek_akses('kategori') || $this->hak_akses->cek_akses('barang') || $this->hak_akses->cek_akses('supplier') || $this->hak_akses->cek_akses('pelanggan')): ?>
        <!-- Master Data Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="true"
                aria-controls="collapseMaster">
                <i class="fas fa-database"></i>
                <span>Master Data</span>
            </a>
            <div id="collapseMaster" class="collapse" aria-labelledby="headingMaster" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- Menu Kategori -->
                    <?php if ($this->hak_akses->cek_akses('kategori')): ?>
                        <a class="collapse-item" href="<?php echo site_url('master/kategori') ?>">Kategori</a>
                    <?php endif; ?>

                    <!-- Menu Barang -->
                    <?php if ($this->hak_akses->cek_akses('barang')): ?>
                        <a class="collapse-item" href="<?php echo site_url('master/barang') ?>">Barang</a>
                    <?php endif; ?>

                    <!-- Menu Perusahaan (hanya Super Admin) -->
                    <?php if ($this->session->userdata('id_role') == 5): ?>
                        <a class="collapse-item" href="<?php echo site_url('perusahaan/perusahaan') ?>">Perusahaan</a>
                    <?php endif; ?>

                    <!-- Menu Gudang (Super Admin dan Admin Pusat) -->
                    <?php if ($this->session->userdata('id_role') == 5 || $this->session->userdata('id_role') == 1): ?>
                        <a class="collapse-item" href="<?php echo site_url('perusahaan/gudang') ?>">Gudang</a>
                    <?php endif; ?>

                    <!-- Menu Supplier -->
                    <?php if ($this->hak_akses->cek_akses('supplier')): ?>
                        <a class="collapse-item" href="<?php echo site_url('master/supplier') ?>">Supplier</a>
                    <?php endif; ?>

                    <!-- Menu Pelanggan -->
                    <?php if ($this->hak_akses->cek_akses('pelanggan')): ?>
                        <a class="collapse-item" href="<?php echo site_url('master/pelanggan') ?>">Pelanggan</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Nav Item - Manajemen Stok (hanya tampil jika user punya akses ke salah satu submenu) -->
    <?php if ($this->hak_akses->cek_akses('stok_awal') || $this->hak_akses->cek_akses('penerimaan') || $this->hak_akses->cek_akses('transfer') || $this->hak_akses->cek_akses('penyesuaian') || $this->hak_akses->cek_akses('riwayat')): ?>
        <!-- Manajemen Stok Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStok" aria-expanded="true"
                aria-controls="collapseStok">
                <i class="fas fa-boxes"></i>
                <span>Manajemen Stok</span>
            </a>
            <div id="collapseStok" class="collapse" aria-labelledby="headingStok" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- Menu Stok Awal -->
                    <?php if ($this->hak_akses->cek_akses('stok_awal')): ?>
                        <a class="collapse-item" href="<?php echo site_url('stok_awal') ?>">Stok Awal</a>
                    <?php endif; ?>

                    <!-- Menu Penerimaan Barang -->
                    <?php if ($this->hak_akses->cek_akses('penerimaan')): ?>
                        <a class="collapse-item" href="<?php echo site_url('penerimaan') ?>">Penerimaan Barang</a>
                    <?php endif; ?>

                    <!-- Menu Transfer Stok -->
                    <?php if ($this->hak_akses->cek_akses('transfer')): ?>
                        <a class="collapse-item" href="<?php echo site_url('transfer') ?>">Transfer Stok</a>
                    <?php endif; ?>

                    <!-- Menu Penyesuaian Stok (hanya Super Admin) -->
                    <?php if ($this->session->userdata('id_role') == 5 && $this->hak_akses->cek_akses('penyesuaian')): ?>
                        <a class="collapse-item" href="<?php echo site_url('penyesuaian') ?>">Penyesuaian Stok</a>
                    <?php endif; ?>

                    <!-- Menu Riwayat Stok -->
                    <?php if ($this->hak_akses->cek_akses('riwayat')): ?>
                        <a class="collapse-item" href="<?php echo site_url('riwayat') ?>">Riwayat Stok</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Nav Item - Penjualan (hanya tampil jika user punya akses ke salah satu submenu) -->
    <?php if ($this->hak_akses->cek_akses('penjualan') || $this->hak_akses->cek_akses('retur')): ?>
        <!-- Penjualan Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePenjualan"
                aria-expanded="true" aria-controls="collapsePenjualan">
                <i class="fas fa-shopping-cart"></i>
                <span>Penjualan</span>
            </a>
            <div id="collapsePenjualan" class="collapse" aria-labelledby="headingPenjualan" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- Menu Penjualan -->
                    <?php if ($this->hak_akses->cek_akses('penjualan')): ?>
                        <a class="collapse-item" href="<?php echo site_url('penjualan') ?>">Penjualan</a>
                    <?php endif; ?>

                    <!-- Menu Retur Penjualan -->
                    <?php if ($this->hak_akses->cek_akses('retur')): ?>
                        <a class="collapse-item" href="<?php echo site_url('retur') ?>">Retur Penjualan</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Nav Item - Laporan (hanya tampil jika user punya akses ke salah satu submenu) -->
    <?php if ($this->hak_akses->cek_akses('laporan_stok') || $this->hak_akses->cek_akses('laporan_penjualan') || $this->hak_akses->cek_akses('laporan_retur') || $this->hak_akses->cek_akses('laporan_transfer')): ?>
        <!-- Laporan Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
                aria-expanded="true" aria-controls="collapseLaporan">
                <i class="fas fa-chart-line"></i>
                <span>Laporan</span>
            </a>
            <div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <!-- Menu Laporan Stok -->
                    <?php if ($this->hak_akses->cek_akses('laporan_stok')): ?>
                        <a class="collapse-item" href="<?php echo site_url('laporan_stok') ?>">Laporan Stok</a>
                    <?php endif; ?>

                    <!-- Menu Laporan Penjualan -->
                    <?php if ($this->hak_akses->cek_akses('laporan_penjualan')): ?>
                        <a class="collapse-item" href="<?php echo site_url('laporan_penjualan') ?>">Laporan Penjualan</a>
                    <?php endif; ?>

                    <!-- Menu Laporan Retur -->
                    <?php if ($this->hak_akses->cek_akses('laporan_retur')): ?>
                        <a class="collapse-item" href="<?php echo site_url('laporan_retur') ?>">Laporan Retur</a>
                    <?php endif; ?>

                    <!-- Menu Laporan Transfer -->
                    <?php if ($this->hak_akses->cek_akses('laporan_transfer')): ?>
                        <a class="collapse-item" href="<?php echo site_url('laporan_transfer') ?>">Laporan Transfer</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Nav Item - Manajemen User (hanya tampil jika user punya akses ke salah satu submenu) -->
    <?php if ($this->hak_akses->cek_akses('user') || $this->hak_akses->cek_akses('hak_akses')): ?>
        <li class="nav-item <?php echo is_active('auth') ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser" aria-expanded="true"
                aria-controls="collapseUser">
                <i class="fas fa-users"></i>
                <span>Manajemen User</span>
            </a>
            <div id="collapseUser" class="collapse <?php echo is_active('auth') ? 'show' : '' ?>"
                aria-labelledby="headingUser" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php if ($this->hak_akses->cek_akses('user')): ?>
                        <a class="collapse-item <?php echo is_active('user') ?>"
                            href="<?php echo site_url('auth/user') ?>">Manajemen User</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('hak_akses')): ?>
                        <a class="collapse-item <?php echo is_active('hak_akses') ?>"
                            href="<?php echo site_url('auth/user/hak_akses') ?>">Hak Akses</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->