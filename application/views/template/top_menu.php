<?php
// Definisikan variabel yang diperlukan
$role = $this->session->userdata('id_role');
$uri1 = $this->uri->segment(1);
$uri2 = $this->uri->segment(2);
$uri3 = $this->uri->segment(3);
$uriSegments = [$uri1, $uri2, $uri3];

// Fungsi untuk mengecek apakah URI mengandung segmen tertentu
$hasSeg = function ($names) use ($uriSegments) {
    foreach ((array) $names as $n) {
        if (in_array($n, $uriSegments, true))
            return true;
    }
    return false;
};
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white topbar static-top shadow"
    style="margin-bottom: 0; border-bottom: 1px solid #e3e6f0;">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <!-- Dashboard -->
            <?php if ($this->hak_akses->cek_akses('dashboard')): ?>
                <li class="nav-item <?php echo $hasSeg(['dashboard']) ? 'active' : '' ?>">
                    <a class="nav-link" href="<?php echo site_url('dashboard') ?>">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Master Data (menggantikan Setup) -->
            <?php
            $master_children = ['kategori', 'barang', 'supplier', 'pelanggan', 'gudang'];
            if ($role == 5) {
                $master_children[] = 'perusahaan';
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
                    <a class="nav-link dropdown-toggle" href="#" id="masterDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-database"></i>
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

            <!-- Penjualan (menggantikan Sales) -->
            <?php if ($this->hak_akses->cek_akses('penjualan')): ?>
                <li class="nav-item <?php echo $hasSeg(['penjualan']) ? 'active' : '' ?>">
                    <a class="nav-link" href="<?php echo site_url('penjualan') ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Penjualan</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Packing (khusus untuk Admin Packing) -->
            <?php if ($role == 3 && $this->hak_akses->cek_akses('penjualan')): ?>
                <li class="nav-item <?php echo $hasSeg(['packing']) ? 'active' : '' ?>">
                    <a class="nav-link" href="<?php echo site_url('packing') ?>">
                        <i class="fas fa-box-open"></i>
                        <span>Packing</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Retur (menggantikan Retur Penjualan) -->
            <?php if ($this->hak_akses->cek_akses('retur')): ?>
                <li class="nav-item <?php echo $hasSeg(['retur']) ? 'active' : '' ?>">
                    <a class="nav-link" href="<?php echo site_url('retur') ?>">
                        <i class="fas fa-undo"></i>
                        <span>Retur Penjualan</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Dropdown: Manajemen Stok (menggantikan Aktifitas) -->
            <?php
            $stok_children = ['transfer', 'penyesuaian', 'riwayat'];
            $stok_active = $hasSeg($stok_children);
            $show_stok = false;
            foreach ($stok_children as $sc) {
                if ($this->hak_akses->cek_akses($sc)) {
                    $show_stok = true;
                    break;
                }
            }
            ?>
            <?php if ($show_stok): ?>
                <li class="nav-item dropdown <?php echo $stok_active ? 'active' : '' ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="stokDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-warehouse"></i>
                        <span>Manajemen Stok</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="stokDropdown">
                        <?php foreach ($stok_children as $sc): ?>
                            <?php if ($this->hak_akses->cek_akses($sc)): ?>
                                <a class="dropdown-item <?php echo $hasSeg([$sc]) ? 'active' : '' ?>"
                                    href="<?php echo site_url($sc) ?>">
                                    <?= ucwords(str_replace('_', ' ', $sc)) ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Dropdown: Laporan -->
            <?php
            $laporan_children = ['laporan_stok', 'laporan_penjualan', 'laporan_retur', 'laporan_transfer'];
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
                    <a class="nav-link dropdown-toggle" href="#" id="laporanDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="laporanDropdown">
                        <?php if ($this->hak_akses->cek_akses('laporan_stok')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_stok']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_stok') ?>">
                                Stok Terkini
                            </a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('laporan_penjualan')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_penjualan']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_penjualan') ?>">
                                Penjualan
                            </a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('laporan_retur')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_retur']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_retur') ?>">
                                Retur Penjualan
                            </a>
                        <?php endif; ?>
                        <?php if ($this->hak_akses->cek_akses('laporan_transfer')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['laporan_transfer']) ? 'active' : '' ?>"
                                href="<?php echo site_url('laporan_transfer') ?>">
                                Transfer Barang
                            </a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>

            <!-- User Management (khusus untuk Super Admin dan Admin Pusat) -->
            <?php
            $user_children = ['user'];
            if ($role == 5) {
                $user_children[] = 'hak_akses';
            }
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
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-users-cog"></i>
                        <span>Manajemen User</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                        <?php if ($this->hak_akses->cek_akses('user')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['user']) ? 'active' : '' ?>"
                                href="<?php echo site_url('user') ?>">
                                User Pengguna
                            </a>
                        <?php endif; ?>
                        <?php if ($role == 5 && $this->hak_akses->cek_akses('hak_akses')): ?>
                            <a class="dropdown-item <?php echo $hasSeg(['hak_akses']) ? 'active' : '' ?>"
                                href="<?php echo site_url('user/hak_akses') ?>">
                                Hak Akses
                            </a>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>