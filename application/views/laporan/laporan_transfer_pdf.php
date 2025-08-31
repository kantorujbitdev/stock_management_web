<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transfer Stok</title>
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
    <h2 class="text-center">LAPORAN TRANSFER STOK</h2>
    <p class="text-center">Periode: <?php echo date('d-m-Y', strtotime($tanggal_awal)); ?> s/d <?php echo date('d-m-Y', strtotime($tanggal_akhir)); ?></p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Transfer</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Gudang Asal</th>
                <th>Gudang Tujuan</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($transfer as $t): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $t->no_transfer; ?></td>
                <td><?php echo date('d-m-Y H:i', strtotime($t->tanggal)); ?></td>
                <td><?php echo $t->nama_barang; ?></td>
                <td><?php echo $t->gudang_asal; ?></td>
                <td><?php echo $t->gudang_tujuan; ?></td>
                <td><?php echo $t->jumlah; ?></td>
                <td><?php echo ucfirst($t->status); ?></td>
                <td><?php echo $t->created_by; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>