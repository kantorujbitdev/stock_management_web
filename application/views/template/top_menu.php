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
                        <span>Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Penjualan -->
            <?php if ($this->hak_akses->cek_akses('penjualan')): ?>
                <li class="nav-item <?php echo $hasSeg(['penjualan']) ? 'active' : '' ?>">
                    <a class="nav-link text-white" href="<?php echo site_url('penjualan') ?>">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        <span>Penjualan</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Retur -->
            <?php if ($this->hak_akses->cek_akses('retur')): ?>
                <li class="nav-item <?php echo $hasSeg(['retur']) ? 'active' : '' ?>">
                    <a class="nav-link text-white" href="<?php echo site_url('retur') ?>">
                        <i class="fas fa-undo mr-1"></i>
                        <span>Retur</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Master Data Dropdown -->
            <?php
            if ($role != 5) {
                $master_children = ['kategori', 'barang', 'supplier', 'pelanggan', 'gudang'];
            } else {
                $master_children = ['kategori', 'barang', 'supplier', 'pelanggan', 'perusahaan', 'gudang'];
            }
            $master_active = $hasSeg($master_children);
            $show_master = false;
            foreach ($master_children as $m) {
                if ($this->hak_akses->cek_akses($m)) {
                    $show_master = true;
                    break;
                }
            }
            ?>
            <?php if ($show_master): ?>
                <li class="nav-item dropdown <?php echo $master_active ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="masterDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-database mr-1"></i>
                        <span>Master Data</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="masterDropdown">
                        <?php foreach ($master_children as $m): ?>
                            <?php if ($this->hak_akses->cek_akses($m)): ?>
                                <a class="dropdown-item <?php echo $hasSeg([$m]) ? 'active' : '' ?>"
                                    href="<?php echo site_url($m) ?>">
                                    <?= ucfirst($m) ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Manajemen Stok Dropdown -->
            <?php
            $manajement_stok_children = ['transfer', 'penyesuaian', 'riwayat'];
            $manajement_stok_active = $hasSeg($manajement_stok_children);
            $show_manajement_stok = false;
            foreach ($manajement_stok_children as $msc) {
                if ($this->hak_akses->cek_akses($msc)) {
                    $show_manajement_stok = true;
                    break;
                }
            }
            ?>
            <?php if ($show_manajement_stok): ?>
                <li class="nav-item dropdown <?php echo $manajement_stok_active ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="stokDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-boxes mr-1"></i>
                        <span>Manajemen Stok</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="stokDropdown">
                        <?php foreach ($manajement_stok_children as $msc): ?>
                            <?php if ($this->hak_akses->cek_akses($msc)): ?>
                                <a class="dropdown-item <?php echo $hasSeg([$msc]) ? 'active' : '' ?>"
                                    href="<?php echo site_url($msc) ?>">
                                    <?= ucwords(str_replace('_', ' ', $msc)) ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Laporan Dropdown -->
            <?php
            $laporan_children = ['laporan_penjualan', 'laporan_stok', 'laporan_retur'];
            $laporan_active = $hasSeg($laporan_children);
            $show_laporan = false;
            foreach ($laporan_children as $l) {
                if ($this->hak_akses->cek_akses($l)) {
                    $show_laporan = true;
                    break;
                }
            }
            ?>
            <?php if ($show_laporan): ?>
                <li class="nav-item dropdown <?php echo $laporan_active ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="laporanDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-chart-line mr-1"></i>
                        <span>Laporan</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="laporanDropdown">
                        <?php if ($this->hak_akses->cek_akses('laporan_penjualan')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_penjualan']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_penjualan') ?>">Penjualan</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('laporan_stok')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_stok']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_stok') ?>">Stok Terkini</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('laporan_retur')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_retur']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_retur') ?>">Retur</a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Manajemen User Dropdown -->
            <?php
            $user_children = ['user', 'hak_akses'];
            $user_active = $hasSeg($user_children);
            $show_user = false;
            foreach ($user_children as $u) {
                if ($this->hak_akses->cek_akses($u)) {
                    $show_user = true;
                    break;
                }
            }
            ?>
            <?php if ($show_user): ?>
                <li class="nav-item dropdown <?php echo $user_active ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-users-cog mr-1"></i>
                        <span>Manajemen User</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                        <?php if ($this->hak_akses->cek_akses('user')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['user']) ? 'active' : '' ?>"
                                href="<?php echo site_url('user') ?>">User Pengguna</a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('hak_akses')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['hak_akses']) ? 'active' : '' ?>"
                                href="<?php echo site_url('user/hak_akses') ?>">Hak Akses</a>
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
                    <img class="img-profile rounded-circle mr-1"
                        src="<?php echo base_url('application/views/template/assets/img/profile/' . $profile_img) ?>">
                    <span class="mr-2 d-none d-lg-inline text-white small">
                        Hai, <?php echo $nama ?> (<?php echo $nama_role ?>)
                    </span>
                    <span class="mr-2 d-inline d-lg-none text-white small">
                        <?php echo $nama ?>
                    </span>

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