<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo isset($user) ? 'Edit User' : 'Tambah User' ?></h1>

    <?php echo form_open(isset($user) ? 'auth/user/edit_process' : 'auth/user/add_process'); ?>
    <?php if (isset($user)): ?>
        <input type="hidden" name="id_user" value="<?php echo $user->id_user ?>">
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                            value="<?php echo isset($user) ? $user->nama : set_value('nama') ?>" required>
                        <?php echo form_error('nama', '<small class="text-danger">', '</small>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo isset($user) ? $user->username : set_value('username') ?>" required>
                        <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Password
                            <?php echo isset($user) ? '(Kosongkan jika tidak ingin mengubah)' : '' ?></label>
                        <input type="password" class="form-control" id="password" name="password" <?php echo !isset($user) ? 'required' : '' ?>>
                        <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_role">Role</label>
                        <select class="form-control" id="id_role" name="id_role" required>
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role->id_role ?>" <?php echo isset($user) && $user->id_role == $role->id_role ? 'selected' : '' ?>><?php echo $role->nama_role ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo form_error('id_role', '<small class="text-danger">', '</small>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="id_perusahaan">Perusahaan</label>
                        <select class="form-control" id="id_perusahaan" name="id_perusahaan">
                            <option value="">-- Pilih Perusahaan --</option>
                            <?php foreach ($perusahaan as $p): ?>
                                <option value="<?php echo $p->id_perusahaan ?>" <?php echo isset($user) && $user->id_perusahaan == $p->id_perusahaan ? 'selected' : '' ?>>
                                    <?php echo $p->nama_perusahaan ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="id_gudang">Gudang</label>
                        <select class="form-control" id="id_gudang" name="id_gudang">
                            <option value="">-- Pilih Gudang --</option>
                            <?php if (isset($gudang)): ?>
                                <?php foreach ($gudang as $g): ?>
                                    <option value="<?php echo $g->id_gudang ?>" <?php echo isset($user) && $user->id_gudang == $g->id_gudang ? 'selected' : '' ?>><?php echo $g->nama_gudang ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?php echo site_url('auth/user') ?>" class="btn btn-secondary">Batal</a>
        </div>
    </div>

    <?php echo form_close(); ?>
</div>

<script>
    $(document).ready(function () {
        $('#id_perusahaan').change(function () {
            var id_perusahaan = $(this).val();

            $.ajax({
                url: "<?php echo site_url('auth/user/get_gudang') ?>",
                method: "POST",
                data: { id_perusahaan: id_perusahaan },
                success: function (data) {
                    $('#id_gudang').html(data);
                }
            });
        });
    });
</script>