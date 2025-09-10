<div class="card">
    <div class="card-header">
        <h5 class="card-title">Detail Penerimaan Barang</h3>
            <div class="card-tools">
                <?php if ($penerimaan->status == 'draft'): ?>
                    <a href="<?php echo site_url('penerimaan/proses/' . $penerimaan->id_penerimaan); ?>"
                        class="btn btn-success btn-sm"
                        onclick="return confirm('Apakah Anda yakin ingin memproses penerimaan ini?')">
                        <i class="fas fa-check"></i> Proses
                    </a>
                    <a href="<?php echo site_url('penerimaan/batal/' . $penerimaan->id_penerimaan); ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Apakah Anda yakin ingin membatalkan penerimaan ini?')">
                        <i class="fas fa-times"></i> Batalkan
                    </a>
                <?php endif; ?>
                <?php echo back_button('penerimaan'); ?>
            </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <tr>
                        <th>No Penerimaan</th>
                        <td><?php echo $penerimaan->no_penerimaan; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?php echo date('d-m-Y', strtotime($penerimaan->tanggal_penerimaan)); ?></td>
                    </tr>
                    <tr>
                        <th>Supplier</th>
                        <td><?php echo $penerimaan->nama_supplier; ?></td>
                    </tr>
                    <tr>
                        <th>No Faktur</th>
                        <td><?php echo $penerimaan->no_faktur; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <tr>
                        <th>Gudang</th>
                        <td><?php echo $penerimaan->nama_gudang; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($penerimaan->status == 'draft'): ?>
                                <span class="badge badge-secondary">Draft</span>
                            <?php elseif ($penerimaan->status == 'diterima'): ?>
                                <span class="badge badge-success">Diterima</span>
                            <?php elseif ($penerimaan->status == 'dibatalkan'): ?>
                                <span class="badge badge-danger">Dibatalkan</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Oleh</th>
                        <td><?php echo $penerimaan->created_by; ?></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td><?php echo $penerimaan->keterangan; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <h4>Daftar Barang</h4>

        <?php if ($penerimaan->status == 'draft'): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Tambah Barang</h3>
                </div>
                <div class="card-body">
                    <?php echo form_open('penerimaan/add_barang'); ?>

                    <input type="hidden" name="id_penerimaan" value="<?php echo $penerimaan->id_penerimaan; ?>">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="id_barang">Barang</label>
                                <select name="id_barang" class="form-control" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <?php foreach ($barang as $b): ?>
                                        <option value="<?php echo $b->id_barang; ?>"><?php echo $b->nama_barang; ?> -
                                            <?php echo $b->sku; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="jumlah_diterima">Jumlah Diterima</label>
                                <input type="number" name="jumlah_diterima" class="form-control" required min="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="harga_beli">Harga Beli</label>
                                <input type="number" name="harga_beli" class="form-control" required min="0">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary form-control">Tambah</button>
                            </div>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>SKU</th>
                    <th>Jumlah Diterima</th>
                    <th>Harga Beli</th>
                    <th>Total</th>
                    <?php if ($penerimaan->status == 'draft'): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                $total = 0;
                foreach ($detail as $d): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $d->nama_barang; ?></td>
                        <td><?php echo $d->sku; ?></td>
                        <td><?php echo $d->jumlah_diterima; ?></td>
                        <td><?php echo number_format($d->harga_beli, 2, ',', '.'); ?></td>
                        <td><?php echo number_format($d->jumlah_diterima * $d->harga_beli, 2, ',', '.'); ?></td>
                        <?php if ($penerimaan->status == 'draft'): ?>
                            <td>
                                <a href="<?php echo site_url('penerimaan/delete_barang/' . $d->id_detail . '?id_penerimaan=' . $penerimaan->id_penerimaan); ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php $total += ($d->jumlah_diterima * $d->harga_beli); ?>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" class="text-right"><strong>Total</strong></td>
                    <td><strong><?php echo number_format($total, 2, ',', '.'); ?></strong></td>
                    <?php if ($penerimaan->status == 'draft'): ?>
                        <td></td>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>