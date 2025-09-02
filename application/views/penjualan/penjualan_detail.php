<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Penjualan</h3>
        <div class="card-tools">
            <?php if ($penjualan->status == 'proses'): ?>
                <a href="<?php echo site_url('penjualan/proses/' . $penjualan->id_penjualan); ?>" class="btn btn-warning btn-sm" onclick="return confirm('Apakah Anda yakin ingin memproses penjualan ini?')">
                    <i class="fas fa-box"></i> Proses
                </a>
            <?php endif; ?>
            <?php if ($penjualan->status == 'packing'): ?>
                <a href="<?php echo site_url('penjualan/kirim/' . $penjualan->id_penjualan); ?>" class="btn btn-primary btn-sm" onclick="return confirm('Apakah Anda yakin ingin mengirim penjualan ini?')">
                    <i class="fas fa-truck"></i> Kirim
                </a>
            <?php endif; ?>
            <?php if ($penjualan->status == 'dikirim'): ?>
                <a href="<?php echo site_url('penjualan/selesai/' . $penjualan->id_penjualan); ?>" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan penjualan ini?')">
                    <i class="fas fa-check"></i> Selesai
                </a>
            <?php endif; ?>
            <?php if ($penjualan->status != 'selesai' && $penjualan->status != 'batal'): ?>
                <a href="<?php echo site_url('penjualan/batal/' . $penjualan->id_penjualan); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan penjualan ini?')">
                    <i class="fas fa-times"></i> Batalkan
                </a>
            <?php endif; ?>
            <a href="<?php echo site_url('penjualan'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <tr>
                        <th>No Invoice</th>
                        <td><?php echo $penjualan->no_invoice; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?php echo date('d-m-Y', strtotime($penjualan->tanggal_penjualan)); ?></td>
                    </tr>
                    <tr>
                        <th>Pelanggan</th>
                        <td><?php echo $penjualan->nama_pelanggan; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($penjualan->status == 'proses'): ?>
                                <span class="badge badge-secondary">Proses</span>
                            <?php elseif ($penjualan->status == 'packing'): ?>
                                <span class="badge badge-primary">Packing</span>
                            <?php elseif ($penjualan->status == 'dikirim'): ?>
                                <span class="badge badge-info">Dikirim</span>
                            <?php elseif ($penjualan->status == 'selesai'): ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php elseif ($penjualan->status == 'batal'): ?>
                                <span class="badge badge-danger">Batal</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Harga</th>
                        <td><?php echo number_format($penjualan->total_harga, 2, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Dibuat Oleh</th>
                        <td><?php echo $penjualan->created_by; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <hr>
        
        <h4>Daftar Barang</h4>
        
        <?php if ($penjualan->status == 'proses'): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Barang</h3>
            </div>
            <div class="card-body">
                <?php echo form_open('penjualan/add_barang'); ?>
                
                <input type="hidden" name="id_penjualan" value="<?php echo $penjualan->id_penjualan; ?>">
                
                <div class="row">
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_barang">Barang</label>
                            <select name="id_barang" class="form-control" id="id_barang" required>
                                <option value="">-- Pilih Barang --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" id="jumlah" required min="1">
                            <small class="form-text text-muted">Stok tersedia: <span id="stok_tersedia">0</span></small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan</label>
                            <input type="number" name="harga_satuan" class="form-control" required min="0">
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
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                    <?php if ($penjualan->status == 'proses'): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; $total = 0; foreach ($detail as $d): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $d->nama_barang; ?></td>
                    <td><?php echo $d->sku; ?></td>
                    <td><?php echo $d->nama_gudang; ?></td>
                    <td><?php echo $d->jumlah; ?></td>
                    <td><?php echo number_format($d->harga_satuan, 2, ',', '.'); ?></td>
                    <td><?php echo number_format($d->jumlah * $d->harga_satuan, 2, ',', '.'); ?></td>
                    <?php if ($penjualan->status == 'proses'): ?>
                    <td>
                        <a href="<?php echo site_url('penjualan/delete_barang/' . $d->id_detail . '?id_penjualan=' . $penjualan->id_penjualan); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php $total += ($d->jumlah * $d->harga_satuan); ?>
                <?php endforeach; ?>
                <tr>
                    <td colspan="6" class="text-right"><strong>Total</strong></td>
                    <td><strong><?php echo number_format($total, 2, ',', '.'); ?></strong></td>
                    <?php if ($penjualan->status == 'proses'): ?>
                    <td></td>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#id_gudang').change(function() {
        var id_gudang = $(this).val();
        
        if (id_gudang != '') {
            $.ajax({
                url: "<?php echo site_url('penjualan/get_barang_by_gudang') ?>",
                method: "POST",
                data: {id_gudang: id_gudang},
                success: function(data) {
                    $('#id_barang').html(data);
                    $('#stok_tersedia').text('0');
                }
            });
        } else {
            $('#id_barang').html('<option value="">-- Pilih Barang --</option>');
            $('#stok_tersedia').text('0');
        }
    });
    
    $('#id_barang').change(function() {
        var id_barang = $(this).val();
        var id_gudang = $('#id_gudang').val();
        
        if (id_barang != '' && id_gudang != '') {
            $.ajax({
                url: "<?php echo site_url('penjualan/get_stok_barang') ?>",
                method: "POST",
                data: {id_barang: id_barang, id_gudang: id_gudang},
                success: function(data) {
                    $('#stok_tersedia').text(data);
                    $('#jumlah').attr('max', data);
                }
            });
        } else {
            $('#stok_tersedia').text('0');
            $('#jumlah').removeAttr('max');
        }
    });
});
</script>