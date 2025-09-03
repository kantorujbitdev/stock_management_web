<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h2 class="text-center">LAPORAN PENJUALAN</h2>
    <p class="text-center">Periode: <?php echo date('d-m-Y', strtotime($tanggal_awal)); ?> s/d
        <?php echo date('d-m-Y', strtotime($tanggal_akhir)); ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Invoice</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            $total = 0;
            foreach ($penjualan as $p): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $p->no_invoice; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($p->tanggal_penjualan)); ?></td>
                    <td><?php echo $p->nama_pelanggan; ?></td>
                    <td class="text-right"><?php echo number_format($p->total_harga, 2, ',', '.'); ?></td>
                    <td><?php echo ucfirst($p->status); ?></td>
                    <td><?php echo $p->created_by; ?></td>
                </tr>
                <?php if ($p->status == 'selesai')
                    $total += $p->total_harga; ?>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" class="text-right"><strong>Total Penjualan Selesai</strong></td>
                <td class="text-right"><strong><?php echo number_format($total, 2, ',', '.'); ?></strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>

</html>