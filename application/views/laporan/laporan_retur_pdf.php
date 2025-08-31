<!DOCTYPE html>
<html>
<head>
    <title>Laporan Retur Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
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
    <h2 class="text-center">LAPORAN RETUR PENJUALAN</h2>
    <p class="text-center">Periode: <?php echo date('d-m-Y', strtotime($tanggal_awal)); ?> s/d <?php echo date('d-m-Y', strtotime($tanggal_akhir)); ?></p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Retur</th>
                <th>Tanggal</th>
                <th>No Invoice</th>
                <th>Pelanggan</th>
                <th>Alasan Retur</th>
                <th>Status</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($retur as $r): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $r->no_retur; ?></td>
                <td><?php echo date('d-m-Y', strtotime($r->tanggal_retur)); ?></td>
                <td><?php echo $r->no_invoice; ?></td>
                <td><?php echo $r->nama_pelanggan; ?></td>
                <td><?php echo $r->alasan_retur; ?></td>
                <td><?php echo ucfirst($r->status); ?></td>
                <td><?php echo $r->created_by; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>