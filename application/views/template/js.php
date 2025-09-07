<!-- jQuery dengan fallback ke CDN -->
<script src="<?php echo base_url('application/views/template/assets/js/jquery.min.js'); ?>"
    onerror="this.onerror=null;this.src='https://code.jquery.com/jquery-3.6.0.min.js';"></script>

<!-- Bootstrap JS dengan fallback ke CDN -->
<script src="<?php echo base_url('application/views/template/assets/js/bootstrap.bundle.min.js'); ?>"
    onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js';"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo base_url('application/views/template/assets/js/jquery.easing.min.js'); ?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo base_url('application/views/template/assets/js/sb-admin-2.min.js'); ?>"></script>

<!-- Page level plugins -->
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
                responsive: true
            });
        }
    });
</script>