<!DOCTYPE html>
<html>

<head>
    <title>Laporan Stok</title>
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
    <h2 class="text-center">LAPORAN STOK</h2>
    <p class="text-center">Tanggal: <?php echo date('d-m-Y'); ?></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Perusahaan</th>
                <th>Gudang</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($stok as $s): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $s->sku; ?></td>
                    <td><?php echo $s->nama_barang; ?></td>
                    <td><?php echo $s->nama_kategori; ?></td>
                    <td><?php echo $s->nama_perusahaan; ?></td>
                    <td><?php echo $s->nama_gudang; ?></td>
                    <td><?php echo $s->jumlah; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>