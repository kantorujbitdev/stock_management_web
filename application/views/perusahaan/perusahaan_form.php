<!-- Tambahkan di bagian bawah form -->
<div class="form-group">
    <label>Gudang</label>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Gudang</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (isset($perusahaan)) {
                    $gudang = $this->Perusahaan_model->get_gudang_by_perusahaan($perusahaan->id_perusahaan);
                    if ($gudang) {
                        foreach ($gudang as $g) {
                            echo '<tr>';
                            echo '<td>'.$g->nama_gudang.'</td>';
                            echo '<td>'.$g->alamat.'</td>';
                            echo '<td>'.$g->telepon.'</td>';
                            echo '<td>'.($g->status_aktif == 1 ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>').'</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center">Belum ada gudang</td></tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <a href="<?php echo site_url('gudang/add') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Gudang</a>
</div>