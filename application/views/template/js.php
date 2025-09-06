<!-- Bootstrap core JavaScript-->
<script src="<?php echo base_url('application/views/template/assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('application/views/template/assets/js/bootstrap.bundle.min.js'); ?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo base_url('application/views/template/assets/js/jquery.easing.min.js'); ?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo base_url('application/views/template/assets/js/sb-admin-2.min.js'); ?>"></script>

<!-- Page level plugins -->
<script src="<?php echo base_url('application/views/template/assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('application/views/template/assets/js/dataTables.bootstrap4.min.js'); ?>"></script>

<!-- Page level custom scripts -->
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable({
            responsive: true
        });
    });
</script>