<!-- Top Menu -->
<nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary topbar mb-4 static-top shadow">
    <a class="navbar-brand text-white" href="<?php echo site_url('dashboard') ?>">
        <i class="fas fa-warehouse mr-2 ml-2"></i>
        <span class="company-name"><?= $this->session->userdata('nama_perusahaan') ?: 'Stok App' ?></span>
    </a>
    <!-- Mobile Toggle Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Menu Items -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <?php
            $role = $this->session->userdata('id_role');
            $uri1 = $this->uri->segment(1);
            $uri2 = $this->uri->segment(2);
            $uri3 = $this->uri->segment(3);
            $uriSegments = [$uri1, $uri2, $uri3];
            $hasSeg = function ($names) use ($uriSegments) {
                foreach ((array) $names as $n) {
                    if (in_array($n, $uriSegments, true))
                        return true;
                }
                return false;
            };
            ?>

            <!-- Dashboard -->
            <?php if ($this->hak_akses->cek_akses('dashboard')): ?>
                <li class="nav-item <?php echo $hasSeg(['dashboard']) ? 'active' : '' ?>">
                    <a class="nav-link text-white" href="<?php echo site_url('dashboard') ?>">
                        <i class="fas fa-fw fa-tachometer-alt mr-1"></i>
                        <span class="d-none d-sm-inline">Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Setup Dropdown -->
            <?php if ($this->hak_akses->cek_akses('setup')): ?>
                <li
                    class="nav-item dropdown <?php echo $hasSeg(['barang', 'gudang', 'sales', 'packing', 'pelanggan', 'supplier']) ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="setupDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cogs mr-1"></i>
                        <span class="d-none d-sm-inline">Setup</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="setupDropdown">
                        <?php if ($this->hak_akses->cek_akses('barang')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['barang']) ? 'active' : '' ?>"
                                href="<?php echo site_url('barang') ?>">Barang</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('gudang')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['gudang']) ? 'active' : '' ?>"
                                href="<?php echo site_url('gudang') ?>">Gudang</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('sales')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['sales']) ? 'active' : '' ?>"
                                href="<?php echo site_url('sales') ?>">Sales</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('packing')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['packing']) ? 'active' : '' ?>"
                                href="<?php echo site_url('packing') ?>">Packing</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('pelanggan')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['pelanggan']) ? 'active' : '' ?>"
                                href="<?php echo site_url('pelanggan') ?>">Pelanggan</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('supplier')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['supplier']) ? 'active' : '' ?>"
                                href="<?php echo site_url('supplier') ?>">Supplier</a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Aktifitas Dropdown -->
            <?php if ($this->hak_akses->cek_akses('aktifitas')): ?>
                <li
                    class="nav-item dropdown <?php echo $hasSeg(['pemindahan_barang', 'penerimaan_barang', 'retur_penjualan', 'retur_pembelian']) ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="aktifitasDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-tasks mr-1"></i>
                        <span class="d-none d-sm-inline">Aktifitas</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="aktifitasDropdown">
                        <?php if ($this->hak_akses->cek_akses('pemindahan_barang')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['pemindahan_barang']) ? 'active' : '' ?>"
                                href="<?php echo site_url('pemindahan_barang') ?>">Pemindahan Barang</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('penerimaan_barang')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['penerimaan_barang']) ? 'active' : '' ?>"
                                href="<?php echo site_url('penerimaan_barang') ?>">Penerimaan Barang</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('retur_penjualan')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['retur_penjualan']) ? 'active' : '' ?>"
                                href="<?php echo site_url('retur_penjualan') ?>">Retur Penjualan</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('retur_pembelian')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['retur_pembelian']) ? 'active' : '' ?>"
                                href="<?php echo site_url('retur_pembelian') ?>">Retur Pembelian</a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Daftar Dropdown -->
            <?php if ($this->hak_akses->cek_akses('daftar')): ?>
                <li
                    class="nav-item dropdown <?php echo $hasSeg(['list_pemindahan', 'list_penerimaan', 'list_retur_jual', 'list_retur_beli']) ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="daftarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-list mr-1"></i>
                        <span class="d-none d-sm-inline">Daftar</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="daftarDropdown">
                        <?php if ($this->hak_akses->cek_akses('list_pemindahan')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['list_pemindahan']) ? 'active' : '' ?>"
                                href="<?php echo site_url('list_pemindahan') ?>">Pemindahan Barang</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('list_penerimaan')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['list_penerimaan']) ? 'active' : '' ?>"
                                href="<?php echo site_url('list_penerimaan') ?>">Penerimaan Barang</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('list_retur_jual')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['list_retur_jual']) ? 'active' : '' ?>"
                                href="<?php echo site_url('list_retur_jual') ?>">Retur Penjualan</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('list_retur_beli')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['list_retur_beli']) ? 'active' : '' ?>"
                                href="<?php echo site_url('list_retur_beli') ?>">Retur Pembelian</a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Laporan Dropdown -->
            <?php if ($this->hak_akses->cek_akses('laporan')): ?>
                <li
                    class="nav-item dropdown <?php echo $hasSeg(['laporan_sales', 'laporan_packing', 'laporan_mutasi', 'laporan_summary']) ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="laporanDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-chart-line mr-1"></i>
                        <span class="d-none d-sm-inline">Laporan</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="laporanDropdown">
                        <?php if ($this->hak_akses->cek_akses('laporan_sales')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_sales']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_sales') ?>">Sales</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('laporan_packing')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_packing']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_packing') ?>">Packing</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('laporan_mutasi')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_mutasi']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_mutasi') ?>">Mutasi Rincian Mutasi Barang</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('laporan_summary')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_summary']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_summary') ?>">Summary (MASUK -> KELUAR -> AKHIR)</a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>
        </ul>

        <!-- User Information -->
        <ul class="navbar-nav">
            <li class="nav-item dropdown no-arrow">
                <?php
                $role_id = $this->session->userdata('id_role');
                $nama_role = $this->session->userdata('nama_role');
                $nama = $this->session->userdata('nama');
                switch ($role_id) {
                    case 2:
                        $profile_img = 'undraw_profile_sales.svg';
                        break;
                    case 3:
                        $profile_img = 'undraw_profile_packing.svg';
                        break;
                    case 4:
                        $profile_img = 'undraw_profile_retur.svg';
                        break;
                    case 1:
                    case 5:
                    default:
                        $profile_img = 'undraw_profile.svg';
                        break;
                }
                ?>
                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-white small">
                        <?php echo $nama ?> (<?php echo $nama_role ?>)
                    </span>
                    <span class="mr-2 d-inline d-lg-none text-white small">
                        <?php echo $nama ?>
                    </span>
                    <img class="img-profile rounded-circle"
                        src="<?php echo base_url('application/views/template/assets/img/profile/' . $profile_img) ?>">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo site_url('auth/logout') ?>" data-toggle="modal"
                        data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>