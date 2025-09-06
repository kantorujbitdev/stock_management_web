<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>

<body>
    <h3>Laporan Penjualan</h3>
    <p>Periode: <?php echo date('d-m-Y', strtotime($filter['tanggal_awal'])); ?> s/d
        <?php echo date('d-m-Y', strtotime($filter['tanggal_akhir'])); ?></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Invoice</th>
                <th>Pelanggan</th>
                <?php if ($this->session->userdata('id_role') == 5): // Super Admin ?>
                    <th>Perusahaan</th>
                <?php endif; ?>
                <th>Barang</th>
                <th>Jumlah Item</th>
                <th>Status</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($penjualan as $p): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $p->no_invoice; ?></td>
                    <td><?php echo $p->nama_pelanggan; ?></td>
                    <?php if ($this->session->userdata('id_role') == 5): // Super Admin ?>
                        <td><?php echo $p->nama_perusahaan; ?></td>
                    <?php endif; ?>
                    <td><?php echo $p->daftar_barang; ?></td>
                    <td class="text-right"><?php echo $p->jumlah_item; ?></td>
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
</body>

</html>