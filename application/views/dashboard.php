<div class="row">
    <!-- Total Perusahaan (hanya Super Admin) -->
    <?php if ($this->session->userdata('id_role') == 5): ?>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo $total_perusahaan; ?></h3>
                <p>Total Perusahaan</p>
            </div>
            <div class="icon">
                <i class="ion ion-briefcase"></i>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Total Gudang -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?php echo $total_gudang; ?></h3>
                <p>Total Gudang</p>
            </div>
            <div class="icon">
                <i class="ion ion-home"></i>
            </div>
        </div>
    </div>

    <!-- Total Barang -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?php echo $total_barang; ?></h3>
                <p>Total Barang</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
        </div>
    </div>

    <!-- Total Stok -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?php echo $total_stok; ?></h3>
                <p>Total Stok</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Total Penjualan -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3><?php echo $total_penjualan; ?></h3>
                <p>Total Penjualan</p>
            </div>
            <div class="icon">
                <i class="ion ion-cart"></i>
            </div>
        </div>
    </div>

    <!-- Total Retur -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3><?php echo $total_retur; ?></h3>
                <p>Total Retur</p>
            </div>
            <div class="icon">
                <i class="ion ion-android-exit"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Grafik Penjualan -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Grafik Penjualan Tahun <?php echo date('Y'); ?></h5>
            </div>
            <div class="card-body">
                <canvas id="grafikPenjualan" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Stok Menipis -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Stok Menipis</h5>
            </div>
            <div class="card-body">
                <?php if (empty($stok_menipis)): ?>
                    <p class="text-center">Tidak ada stok yang menipis</p>
                <?php else: ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stok_menipis as $s): ?>
                            <tr>
                                <td>
                                    <?php echo $s->nama_barang; ?>
                                    <?php if ($this->session->userdata('id_role') == 5): ?>
                                        <br><small><?php echo $s->nama_perusahaan; ?> - <?php echo $s->nama_gudang; ?></small>
                                    <?php else: ?>
                                        <br><small><?php echo $s->nama_gudang; ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-danger"><?php echo $s->jumlah; ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Penjualan Terakhir -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Penjualan Terakhir</h5>
            </div>
            <div class="card-body">
                <?php if (empty($penjualan_terakhir)): ?>
                    <p class="text-center">Belum ada data penjualan</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No Invoice</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($penjualan_terakhir as $p): ?>
                            <tr>
                                <td><?php echo $p->no_invoice; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($p->tanggal_penjualan)); ?></td>
                                <td><?php echo $p->nama_pelanggan; ?></td>
                                <td><?php echo number_format($p->total_harga, 2, ',', '.'); ?></td>
                                <td>
                                    <?php if ($p->status == 'proses'): ?>
                                        <span class="badge badge-secondary">Proses</span>
                                    <?php elseif ($p->status == 'packing'): ?>
                                        <span class="badge badge-primary">Packing</span>
                                    <?php elseif ($p->status == 'dikirim'): ?>
                                        <span class="badge badge-info">Dikirim</span>
                                    <?php elseif ($p->status == 'selesai'): ?>
                                        <span class="badge badge-success">Selesai</span>
                                    <?php elseif ($p->status == 'batal'): ?>
                                        <span class="badge badge-danger">Batal</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $p->created_by; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Grafik Penjualan
    var ctx = document.getElementById('grafikPenjualan').getContext('2d');
    var grafikPenjualan = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Jumlah Penjualan',
                data: [
                    <?php foreach ($grafik_penjualan as $g): ?>
                    <?php echo $g['total']; ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>