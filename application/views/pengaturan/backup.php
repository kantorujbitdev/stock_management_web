<div class="card">
    <div class="card-header">
        <h3 class="card-title">Backup Database</h3>
        <div class="card-tools">
            <a href="<?php echo site_url('pengaturan/backup/create'); ?>" class="btn btn-primary btn-sm"
                onclick="return confirm('Apakah Anda yakin ingin membuat backup database sekarang?')">
                <i class="fas fa-save"></i> Backup Sekarang
            </a>
        </div>
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

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama File</th>
                        <th>Ukuran</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($backup_files)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada file backup</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($backup_files as $file): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $file; ?></td>
                                <td><?php echo $this->get_file_size(FCPATH . 'backup/' . $file); ?></td>
                                <td><?php echo date('d-m-Y H:i:s', filemtime(FCPATH . 'backup/' . $file)); ?></td>
                                <td>
                                    <a href="<?php echo site_url('pengaturan/backup/download/' . $file); ?>"
                                        class="btn btn-success btn-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <a href="<?php echo site_url('pengaturan/backup/delete/' . $file); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus file backup ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Helper function to get file size
function get_file_size($file)
{
    $size = filesize($file);

    if ($size >= 1073741824) {
        return number_format($size / 1073741824, 2) . ' GB';
    } elseif ($size >= 1048576) {
        return number_format($size / 1048576, 2) . ' MB';
    } elseif ($size >= 1024) {
        return number_format($size / 1024, 2) . ' KB';
    } elseif ($size > 1) {
        return $size . ' bytes';
    } else {
        return '0 bytes';
    }
}
?>