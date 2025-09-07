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