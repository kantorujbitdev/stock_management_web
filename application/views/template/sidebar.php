<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo site_url('dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-warehouse"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Manajemen Stok</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Dashboard -->
    <?php if ($this->hak_akses->cek_akses('dashboard')): ?>
        <li class="nav-item <?php echo $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>">
            <a class="nav-link" href="<?php echo site_url('dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>
    <?php endif; ?>
    <hr class="sidebar-divider">
    <!-- Master Data -->
    <?php if (
        $this->hak_akses->cek_akses('kategori') ||
        $this->hak_akses->cek_akses('barang') ||
        $this->hak_akses->cek_akses('supplier') ||
        $this->hak_akses->cek_akses('pelanggan') ||
        $this->hak_akses->cek_akses('perusahaan') ||
        $this->hak_akses->cek_akses('gudang')
    ): ?>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster">
                <i class="fas fa-database"></i>
                <span>Master Data</span>
            </a>
            <div id="collapseMaster" class="collapse">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php if ($this->hak_akses->cek_akses('kategori')): ?>
                        <a class="collapse-item" href="<?php echo site_url('master/kategori') ?>">Kategori</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('barang')): ?>
                        <a class="collapse-item" href="<?php echo site_url('master/barang') ?>">Barang</a>
                    <?php endif; ?>
                    <?php if ($this->session->userdata('id_role') == 5 && $this->hak_akses->cek_akses('perusahaan')): ?>
                        <a class="collapse-item" href="<?php echo site_url('perusahaan/perusahaan') ?>">Perusahaan</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('gudang')): ?>
                        <a class="collapse-item" href="<?php echo site_url('perusahaan/gudang') ?>">Gudang</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('supplier')): ?>
                        <a class="collapse-item" href="<?php echo site_url('master/supplier') ?>">Supplier</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('pelanggan')): ?>
                        <a class="collapse-item" href="<?php echo site_url('master/pelanggan') ?>">Pelanggan</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>
    <!-- Manajemen Stok -->
    <?php if (
        $this->hak_akses->cek_akses('stok_awal') ||
        $this->hak_akses->cek_akses('penerimaan') ||
        $this->hak_akses->cek_akses('transfer') ||
        $this->hak_akses->cek_akses('penyesuaian') ||
        $this->hak_akses->cek_akses('riwayat')
    ): ?>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStok">
                <i class="fas fa-boxes"></i>
                <span>Manajemen Stok</span>
            </a>
            <div id="collapseStok" class="collapse">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php if ($this->hak_akses->cek_akses('stok_awal')): ?>
                        <a class="collapse-item" href="<?php echo site_url('stok_awal') ?>">Stok Awal</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('penerimaan')): ?>
                        <a class="collapse-item" href="<?php echo site_url('penerimaan') ?>">Penerimaan Barang</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('transfer')): ?>
                        <a class="collapse-item" href="<?php echo site_url('transfer') ?>">Transfer Stok</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('penyesuaian')): ?>
                        <a class="collapse-item" href="<?php echo site_url('penyesuaian') ?>">Penyesuaian Stok</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('riwayat')): ?>
                        <a class="collapse-item" href="<?php echo site_url('riwayat') ?>">Riwayat Stok</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>
    <!-- Penjualan -->
    <?php if ($this->hak_akses->cek_akses('penjualan') || $this->hak_akses->cek_akses('retur')): ?>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePenjualan">
                <i class="fas fa-shopping-cart"></i>
                <span>Penjualan</span>
            </a>
            <div id="collapsePenjualan" class="collapse">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php if ($this->hak_akses->cek_akses('penjualan')): ?>
                        <a class="collapse-item" href="<?php echo site_url('penjualan') ?>">Penjualan</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('retur')): ?>
                        <a class="collapse-item" href="<?php echo site_url('retur') ?>">Retur Penjualan</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>
    <!-- Laporan -->
    <?php if (
        $this->hak_akses->cek_akses('laporan_stok') ||
        $this->hak_akses->cek_akses('laporan_penjualan') ||
        $this->hak_akses->cek_akses('laporan_retur') ||
        $this->hak_akses->cek_akses('laporan_transfer')
    ): ?>
        <li class="nav-item">
            <a class="nav-link collapsed <?php echo ($this->uri->segment(1) == 'laporan_stok' || $this->uri->segment(1) == 'laporan_penjualan' || $this->uri->segment(1) == 'laporan_retur' || $this->uri->segment(1) == 'laporan_transfer') ? '' : 'collapsed' ?>" 
               href="#" data-toggle="collapse" data-target="#collapseLaporan" 
               <?php echo ($this->uri->segment(1) == 'laporan_stok' || $this->uri->segment(1) == 'laporan_penjualan' || $this->uri->segment(1) == 'laporan_retur' || $this->uri->segment(1) == 'laporan_transfer') ? 'aria-expanded="true"' : '' ?>>
                <i class="fas fa-chart-line"></i>
                <span>Laporan</span>
            </a>
            <div id="collapseLaporan" class="collapse <?php echo ($this->uri->segment(1) == 'laporan_stok' || $this->uri->segment(1) == 'laporan_penjualan' || $this->uri->segment(1) == 'laporan_retur' || $this->uri->segment(1) == 'laporan_transfer') ? 'show' : '' ?>">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php if ($this->hak_akses->cek_akses('laporan_stok')): ?>
                        <a class="collapse-item <?php echo $this->uri->segment(1) == 'laporan_stok' ? 'active' : '' ?>" href="<?php echo site_url('laporan_stok') ?>">Laporan Stok</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('laporan_penjualan')): ?>
                        <a class="collapse-item <?php echo $this->uri->segment(1) == 'laporan_penjualan' ? 'active' : '' ?>" href="<?php echo site_url('laporan_penjualan') ?>">Laporan Penjualan</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('laporan_retur')): ?>
                        <a class="collapse-item <?php echo $this->uri->segment(1) == 'laporan_retur' ? 'active' : '' ?>" href="<?php echo site_url('laporan_retur') ?>">Laporan Retur</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('laporan_transfer')): ?>
                        <a class="collapse-item <?php echo $this->uri->segment(1) == 'laporan_transfer' ? 'active' : '' ?>" href="<?php echo site_url('laporan_transfer') ?>">Laporan Transfer</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>
    <!-- Manajemen User -->
    <?php if ($this->hak_akses->cek_akses('user') || $this->hak_akses->cek_akses('hak_akses')): ?>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser">
                <i class="fas fa-users"></i>
                <span>Manajemen User</span>
            </a>
            <div id="collapseUser" class="collapse">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php if ($this->hak_akses->cek_akses('user')): ?>
                        <a class="collapse-item" href="<?php echo site_url('auth/user') ?>">Manajemen User</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('hak_akses')): ?>
                        <a class="collapse-item" href="<?php echo site_url('auth/user/hak_akses') ?>">Hak Akses</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>
    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->