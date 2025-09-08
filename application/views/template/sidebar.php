<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="<?php echo site_url('dashboard') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-warehouse"></i>
        </div>
        <div class="sidebar-brand-text mx-3" style="max-width:140px;">
            <?= $this->session->userdata('nama_perusahaan') ?: 'Stok App' ?>
        </div>
    </a>

    <hr class="sidebar-divider my-0">

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
            <a class="nav-link" href="<?php echo site_url('dashboard') ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
    <?php endif; ?>

    <!-- Penjualan -->
    <?php if ($this->hak_akses->cek_akses('penjualan')): ?>
        <li class="nav-item <?php echo $hasSeg(['penjualan']) ? 'active' : '' ?>">
            <a class="nav-link" href="<?php echo site_url('penjualan') ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Penjualan</span>
            </a>
        </li>
    <?php endif; ?>

    <!-- Retur -->
    <?php if ($this->hak_akses->cek_akses('retur')): ?>
        <li class="nav-item <?php echo $hasSeg(['retur']) ? 'active' : '' ?>">
            <a class="nav-link" href="<?php echo site_url('retur') ?>">
                <i class="fas fa-undo"></i>
                <span>Retur</span>
            </a>
        </li>
    <?php endif; ?>

    <hr class="sidebar-divider">

    <!-- MASTER DATA -->
    <?php
    $master_children = ['kategori', 'barang', 'supplier', 'pelanggan', 'perusahaan', 'gudang'];
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
        <li class="nav-item <?php echo $master_active ? 'active' : '' ?>">
            <a class="nav-link <?php echo $master_active ? '' : 'collapsed' ?>" href="#" data-toggle="collapse"
                data-target="#collapseMaster" aria-expanded="<?php echo $master_active ? 'true' : 'false' ?>"
                aria-controls="collapseMaster">
                <i class="fas fa-database"></i>
                <span>Master Data</span>
            </a>
            <div id="collapseMaster" class="collapse <?php echo $master_active ? 'show' : '' ?>"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php foreach ($master_children as $m): ?>
                        <?php if ($this->hak_akses->cek_akses($m)): ?>
                            <a class="collapse-item <?php echo $hasSeg([$m]) ? 'active' : '' ?>"
                                href="<?php echo site_url($m) ?>"><?= ucfirst($m) ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- <hr class="sidebar-divider"> -->

    <!-- Manajemen Stok -->
    <?php
    $manajement_stok_children = ['stok_awal', 'transfer', 'penyesuaian', 'riwayat'];
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
        <li class="nav-item <?php echo $manajement_stok_active ? 'active' : '' ?>">
            <a class="nav-link <?php echo $manajement_stok_active ? '' : 'collapsed' ?>" href="#" data-toggle="collapse"
                data-target="#collapseManajemenStok"
                aria-expanded="<?php echo $manajement_stok_active ? 'true' : 'false' ?>"
                aria-controls="collapseManajemenStok">
                <i class="fas fa-database"></i>
                <span>Manajemen Stok</span>
            </a>
            <div id="collapseManajemenStok" class="collapse <?php echo $manajement_stok_active ? 'show' : '' ?>"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php foreach ($manajement_stok_children as $msc): ?>
                        <?php if ($this->hak_akses->cek_akses($msc)): ?>
                            <a class="collapse-item <?php echo $hasSeg([$msc]) ? 'active' : '' ?>"
                                href="<?php echo site_url($msc) ?>">
                                <?= ucwords(str_replace('_', ' ', $msc)) ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Laporan -->
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
        <li class="nav-item <?php echo $laporan_active ? 'active' : '' ?>">
            <a class="nav-link <?php echo $laporan_active ? '' : 'collapsed' ?>" href="#" data-toggle="collapse"
                data-target="#collapseLaporan" aria-expanded="<?php echo $laporan_active ? 'true' : 'false' ?>"
                aria-controls="collapseLaporan">
                <i class="fas fa-chart-line"></i>
                <span>Laporan</span>
            </a>
            <div id="collapseLaporan" class="collapse <?php echo $laporan_active ? 'show' : '' ?>"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php if ($this->hak_akses->cek_akses('laporan_penjualan')): ?>
                        <a class="collapse-item <?php echo $hasSeg(['laporan_penjualan']) ? 'active' : '' ?>"
                            href="<?php echo site_url('laporan_penjualan') ?>">Penjualan</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('laporan_stok')): ?>
                        <a class="collapse-item <?php echo $hasSeg(['laporan_stok']) ? 'active' : '' ?>"
                            href="<?php echo site_url('laporan_stok') ?>">Stok</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('laporan_retur')): ?>
                        <a class="collapse-item <?php echo $hasSeg(['laporan_retur']) ? 'active' : '' ?>"
                            href="<?php echo site_url('laporan_retur') ?>">Retur</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- User & Hak Akses -->
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
        <li class="nav-item <?php echo $user_active ? 'active' : '' ?>">
            <a class="nav-link <?php echo $user_active ? '' : 'collapsed' ?>" href="#" data-toggle="collapse"
                data-target="#collapseUser" aria-expanded="<?php echo $user_active ? 'true' : 'false' ?>"
                aria-controls="collapseUser">
                <i class="fas fa-users-cog"></i>
                <span>Manajemen User</span>
            </a>
            <div id="collapseUser" class="collapse <?php echo $user_active ? 'show' : '' ?>"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php if ($this->hak_akses->cek_akses('user')): ?>
                        <a class="collapse-item <?php echo $hasSeg(['user']) ? 'active' : '' ?>"
                            href="<?php echo site_url('user') ?>">User Pengguna</a>
                    <?php endif; ?>
                    <?php if ($this->hak_akses->cek_akses('hak_akses')): ?>
                        <a class="collapse-item <?php echo $hasSeg(['hak_akses']) ? 'active' : '' ?>"
                            href="<?php echo site_url('user/hak_akses') ?>">Hak Akses</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- <hr class="sidebar-divider d-none d-md-block"> -->

    <!-- Sidebar Toggler -->
    <!-- <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div> -->

</ul>
<!-- End of Sidebar -->