<!-- Core plugin JavaScript-->
<script src="<?php echo base_url('application/views/template/assets/js/jquery.easing.min.js'); ?>"></script>

<!-- SB Admin 2 -->
<script src="<?php echo base_url('application/views/template/assets/js/sb-admin-2.min.js'); ?>"></script>

<!-- DataTables JS -->
<script src="<?php echo base_url('application/views/template/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('application/views/template/assets/js/dataTables.bootstrap4.min.js'); ?>"></script>
<!-- Tambahkan sebelum </script> terakhir -->
<script>
    // Enhanced notification function
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '250px';
        notification.style.boxShadow = '0 0.5rem 1rem rgba(0, 0, 0, 0.15)';
        notification.style.borderRadius = '0.5rem';
        notification.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 150);
        }, 3000);
    }

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

    // Mobile menu enhancements
    $(document).ready(function () {
        // Add animation to menu items on mobile
        if ($(window).width() < 768) {
            $('.navbar-nav .nav-item').each(function (index) {
                $(this).css({
                    'animation': `fadeInLeft 0.3s ${index * 0.1}s forwards`,
                    'opacity': '0'
                });
            });
        }
    });
</script>

<style>
    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>