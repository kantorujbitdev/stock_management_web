<div class="card">
    <div class="card-header">
        <h5 class="card-title">Pengaturan Sistem</h3>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fas fa-check"></i> <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fas fa-ban"></i> <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo site_url('pengaturan/sistem/update'); ?>">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Pengaturan</th>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pengaturan as $p): ?>
                            <tr>
                                <td>
                                    <?php echo $p->key; ?>
                                    <input type="hidden" name="pengaturan" value="<?php echo $p->key; ?>">
                                </td>
                                <td>
                                    <input type="text" name="value" class="form-control" value="<?php echo $p->value; ?>"
                                        required>
                                </td>
                                <td><?php echo $p->keterangan; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Simpan Semua</button>
            </div>
        </form>
    </div>
</div>