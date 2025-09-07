<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pengaturan Hak Akses</h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-primary">Hak Akses per Role</h6>
        </div>
        <div class="card-body">
            <?php echo form_open('auth/user/simpan_hak_akses'); ?>
            <div class="form-group">
                <label for="id_role">Pilih Role</label>
                <select class="form-control" id="id_role" name="id_role" required>
                    <option value="">-- Pilih Role --</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role->id_role ?>"><?php echo $role->nama_role ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="table-responsive">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Daftar Fitur</h6>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="checkAll">
                                <i class="fas fa-check-square"></i> Check All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="uncheckAll">
                                <i class="fas fa-square"></i> Uncheck All
                            </button>
                        </div>
                </div>

                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50%">Fitur</th>
                            <th width="50%" class="text-center">Akses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fitur as $key => $value): ?>
                            <tr>
                                <td><?php echo $value ?></td>
                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input fitur-checkbox"
                                            id="fitur_<?php echo $key ?>" name="fitur[<?php echo $key ?>]" value="1">
                                        <label class="custom-control-label" for="fitur_<?php echo $key ?>"></label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?php echo site_url('auth/user'); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Check All functionality
        $('#checkAll').click(function () {
            $('.fitur-checkbox').prop('checked', true);
        });

        // Uncheck All functionality
        $('#uncheckAll').click(function () {
            $('.fitur-checkbox').prop('checked', false);
        });

        $('#id_role').change(function () {
            var id_role = $(this).val();

            if (id_role != '') {
                $.ajax({
                    url: "<?php echo site_url('auth/user/get_hak_akses') ?>",
                    method: "POST",
                    data: { id_role: id_role },
                    dataType: "json",
                    success: function (data) {
                        // Uncheck all first
                        $('.fitur-checkbox').prop('checked', false);

                        // Check based on data
                        $.each(data, function (key, value) {
                            $('#fitur_' + value.nama_fitur).prop('checked', true);
                        });
                    }
                });
            } else {
                $('.fitur-checkbox').prop('checked', false);
            }
        });
    });
</script>