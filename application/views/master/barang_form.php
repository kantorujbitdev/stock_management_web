<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <?php echo back_button('barang'); ?>
        <h5 class="mb-0 ml-3">
            <i class="fas fa-tags"></i>
            <?php echo isset($barang) ? 'Edit Data Barang' : 'Tambah Data Barang'; ?>
        </h5>
    </div>
    <div class="card-body">
        <!-- Alert error/success -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $this->session->flashdata('error'); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
        <?php if (!empty($validation_errors)): ?>
            <div class="alert alert-warning alert-dismissible fade show">
                <strong>Perbaiki kesalahan berikut:</strong>
                <ul class="mb-0">
                    <?php foreach ($validation_errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
        <?php echo form_open_multipart(isset($barang) ? 'barang/edit_process' : 'barang/add_process'); ?>
        <?php if (isset($barang)): ?>
            <input type="hidden" name="id_barang" value="<?php echo $barang->id_barang; ?>">
            <input type="hidden" name="gambar_lama" value="<?php echo $barang->gambar; ?>">
        <?php endif; ?>
        <!-- ROW: Perusahaan & Kategori -->
        <div class="form-row">
            <!-- PERUSAHAAN -->
            <div class="form-group col-md-6">
                <label for="id_perusahaan">Perusahaan <span class="text-danger">*</span></label>
                <?php if ($this->session->userdata('id_role') == 5): ?>
                    <select name="id_perusahaan" id="id_perusahaan" class="form-control" required>
                        <option value="">-- Pilih Perusahaan --</option>
                        <?php foreach ($perusahaan as $p): ?>
                            <option value="<?php echo $p->id_perusahaan; ?>" <?php echo set_select(
                                   'id_perusahaan',
                                   $p->id_perusahaan,
                                   (isset($old_input['id_perusahaan']) && $old_input['id_perusahaan'] == $p->id_perusahaan) ||
                                   (isset($barang) && $barang->id_perusahaan == $p->id_perusahaan)
                               ); ?>>
                                <?php echo $p->nama_perusahaan; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input type="hidden" name="id_perusahaan"
                        value="<?php echo $this->session->userdata('id_perusahaan'); ?>">
                    <input type="text" class="form-control" value="<?php echo $perusahaan[0]->nama_perusahaan; ?>" readonly>
                <?php endif; ?>
            </div>
            <!-- KATEGORI -->
            <div class="form-group col-md-6">
                <label for="id_kategori">Kategori <span class="text-danger">*</span></label>
                <select name="id_kategori" id="id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?php echo $k->id_kategori; ?>" <?php echo set_select(
                               'id_kategori',
                               $k->id_kategori,
                               (isset($old_input['id_kategori']) && $old_input['id_kategori'] == $k->id_kategori) ||
                               (isset($barang) && $barang->id_kategori == $k->id_kategori)
                           ); ?>>
                            <?php echo $k->nama_kategori; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <!-- SKU -->
        <div class="form-group">
            <label for="sku">SKU <span class="text-danger">*</span></label>
            <input type="text" name="sku" id="sku" class="form-control" value="<?php echo set_value(
                'sku',
                isset($old_input['sku']) ? $old_input['sku'] :
                (isset($barang) ? $barang->sku : '')
            ); ?>" <?php echo isset($barang) ? 'readonly' : ''; ?> required>
            <?php if (isset($barang)): ?>
                <small class="form-text text-muted">SKU tidak dapat diubah setelah dibuat.</small>
            <?php endif; ?>
        </div>
        <!-- NAMA BARANG -->
        <div class="form-group">
            <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?php echo set_value(
                'nama_barang',
                isset($old_input['nama_barang']) ? $old_input['nama_barang'] :
                (isset($barang) ? $barang->nama_barang : '')
            ); ?>" required>
        </div>
        <!-- DESKRIPSI -->
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"><?php echo set_value(
                'deskripsi',
                isset($old_input['deskripsi']) ? $old_input['deskripsi'] :
                (isset($barang) ? $barang->deskripsi : '')
            ); ?></textarea>
        </div>
        <!-- GAMBAR -->
        <div class="form-group">
            <label for="gambar" class="font-weight-bold">Gambar</label>
            <div class="row align-items-center">
                <!-- Input File -->
                <div class="col-md-6 col-12 mb-2 mb-md-0">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="gambar" id="gambar" accept="image/*">
                        <label class="custom-file-label" for="gambar">Pilih file</label>
                    </div>
                    <small class="form-text text-muted">
                        Format: JPG, JPEG, PNG, GIF. Maksimal: 2MB
                    </small>
                </div>
                <!-- Preview -->
                <div class="col-md-6 col-12 text-center">
                    <?php if (isset($barang) && $barang->gambar): ?>
                        <img id="preview" src="<?php echo base_url('uploads/barang/' . $barang->gambar); ?>"
                            class="img-thumbnail shadow-sm" width="120" alt="Gambar Barang">
                    <?php else: ?>
                        <img id="preview"
                            src="<?php echo base_url('application/views/template/assets/img/no-image.png'); ?>"
                            class="img-thumbnail shadow-sm d-none" width="120" alt="Preview Gambar">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- ACTION BUTTONS -->
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Simpan
            </button>
            <a href="<?php echo site_url('barang'); ?>" class="btn btn-secondary">
                <i class="fa fa-times"></i> Batal
            </a>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    $(function () {
        // custom file input
        $('.custom-file-input').on('change', function () {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);

            // preview image
            let input = this;
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview').attr('src', e.target.result).removeClass('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });

        // load kategori by perusahaan (Super Admin only)
        $('#id_perusahaan').on('change', function () {
            let id = $(this).val();
            if (id) {
                $.get("<?php echo site_url('barang/get_kategori_by_perusahaan'); ?>",
                    { id_perusahaan: id }, function (res) {
                        let data = JSON.parse(res);
                        let options = '<option value="">-- Pilih Kategori --</option>';
                        $.each(data, function (i, item) {
                            options += `<option value="${item.id_kategori}">${item.nama_kategori}</option>`;
                        });
                        $('#id_kategori').html(options);
                    }
                );
            }
        });

        // trigger saat edit super admin
        <?php if (isset($barang) && $this->session->userdata('id_role') == 5): ?>
            $('#id_perusahaan').trigger('change');
            setTimeout(() => {
                $('#id_kategori').val('<?php echo $barang->id_kategori; ?>');
            }, 600);
        <?php endif; ?>
    });
</script>