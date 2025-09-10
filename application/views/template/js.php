<!-- Core plugin JavaScript-->
<script src="<?php echo base_url('application/views/template/assets/js/jquery.easing.min.js'); ?>"></script>

<!-- SB Admin 2 -->
<script src="<?php echo base_url('application/views/template/assets/js/sb-admin-2.min.js'); ?>"></script>

<!-- DataTables JS -->
<script src="<?php echo base_url('application/views/template/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('application/views/template/assets/js/dataTables.bootstrap4.min.js'); ?>"></script>

<!-- Page level custom scripts -->
<script>
    // Fungsi untuk mengeksekusi script setelah jQuery dan Bootstrap siap
    function runAfterDependencies(callback) {
        if (window.jQuery && typeof jQuery.fn.modal === 'function') {
            callback();
        } else {
            setTimeout(function () {
                runAfterDependencies(callback);
            }, 100);
        }
    }

    // Inisialisasi DataTables
    runAfterDependencies(function () {
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#dataTable').DataTable({
                responsive: true,
                paging: true,
                ordering: false,
                info: true,
                scrollX: true,
                autoWidth: false,
                dom: '<"row mb-3"<"col-md-6 d-flex align-items-center"l><"col-md-6 d-flex justify-content-end"f>>' +
                    'rt' +
                    '<"row mt-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
            });
        }
    });
</script>