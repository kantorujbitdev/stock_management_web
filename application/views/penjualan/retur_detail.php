<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Retur Penjualan</h3>
        <div class="card-tools">
            <?php if ($retur->status == 'diterima'): ?>
                <a href="<?php echo site_url('retur/proses/' . $retur->id_retur); ?>" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin memproses retur ini?')">
                    <i class="fas fa-check"></i> Proses
                </a>
                <a href="<?php echo site_url('retur/tolak/' . $retur->id_retur); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menolak retur ini?')">
                    <i class="fas fa-times"></i> Tolak
                </a>
            <?php endif; ?>
            <a href="<?php echo site_url('retur'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <tr>
                        <th>No Retur</th>
                        <td><?php echo $retur->no_retur; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?php echo date('d-m-Y', strtotime($retur->tanggal_retur)); ?></td>
                    </tr>
                    <tr>
                        <th>No Invoice</th>
                        <td><?php echo $retur->no_invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Pelanggan</th>
                        <td><?php echo $retur->nama_pelanggan; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($retur->status == 'diterima'): ?>
                                <span class="badge badge-primary">Diterima</span>
                            <?php elseif ($retur->status == 'diproses'): ?>
                                <span class="badge badge-warning">Diproses</span>
                            <?php elseif ($retur->status == 'selesai'): ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php elseif ($retur->status == 'ditolak'): ?>
                                <span class="badge badge-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Alasan Retur</th>
                        <td><?php echo $retur->alasan_retur; ?></td>
                    </tr>
                    <tr>
                        <th>Dibuat Oleh</th>
                        <td><?php echo $retur->created_by; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <hr>
        
        <h4>Daftar Barang Diretur</h4>
        
        <?php if ($retur->status == 'diterima'): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Barang Retur</h3>
            </div>
            <div class="card-body">
                <?php echo form_open('retur/add_barang'); ?>
                
                <input type="hidden" name="id_retur" value="<?php echo $retur->id_retur; ?>">
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_barang">Barang</label>
                            <select name="id_barang" class="form-control" id="id_barang" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach ($barang_penjualan as $b): ?>
                                    <option value="<?php echo $b->id_barang; ?>" data-gudang="<?php echo $b->id_gudang; ?>" data-jumlah="<?php echo $b->jumlah; ?>">
                                        <?php echo $b->nama_barang; ?> - <?php echo $b->sku; ?> (Jual: <?php echo $b->jumlah; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_gudang">Gudang</label>
                            <select name="id_gudang" class="form-control" id="id_gudang" required>
                                <option value="">-- Pilih Gudang --</option>
                                <?php foreach ($gudang as $g): ?>
                                    <option value="<?php echo $g->id_gudang; ?>"><?php echo $g->nama_gudang; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="jumlah_retur">Jumlah Retur</label>
                            <input type="number" name="jumlah_retur" class="form-control" id="jumlah_retur" required min="1">
                            <small class="form-text text-muted">Maks: <span id="jumlah_maks">0</span></small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="alasan_barang">Alasan Barang</label>
                            <input type="text" name="alasan_barang" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-1">
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
        
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>SKU</th>
                    <th>Gudang</th>
                    <th>Jumlah Retur</th>
                    <th>Alasan Barang</th>
                    <?php if ($retur->status == 'diterima'): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($detail as $d): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $d->nama_barang; ?></td>
                    <td><?php echo $d->sku; ?></td>
                    <td><?php echo $d->nama_gudang; ?></td>
                    <td><?php echo $d->jumlah_retur; ?></td>
                    <td><?php echo $d->alasan_barang; ?></td>
                    <?php if ($retur->status == 'diterima'): ?>
                    <td>
                        <a href="<?php echo site_url('retur/delete_barang/' . $d->id_detail_retur . '?id_retur=' . $retur->id_retur); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#id_barang').change(function() {
        var selected = $(this).find('option:selected');
        var id_gudang = selected.data('gudang');
        var jumlah_maks = selected.data('jumlah');
        
        $('#id_gudang').val(id_gudang);
        $('#jumlah_maks').text(jumlah_maks);
        $('#jumlah_retur').attr('max', jumlah_maks);
    });
});
</script>