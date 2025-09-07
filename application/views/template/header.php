<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon"
        href="<?php echo base_url(); ?>application/views/template/assets/img/logo_warehouse.png" />
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?><?= $this->session->userdata('nama_perusahaan'); ?></title>
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url('application/views/template/assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('application/views/template/assets/css/all.min.css'); ?>" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="<?php echo base_url('application/views/template/assets/css/dataTables.bootstrap4.min.css'); ?>"
        rel="stylesheet">
    <!-- SB Admin 2 CSS -->
    <link href="<?php echo base_url('application/views/template/assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <!-- Load jQuery terlebih dahulu -->
    <script src="<?php echo base_url('application/views/template/assets/js/jquery.min.js'); ?>"></script>
    <!-- Load Bootstrap JS -->
    <script src="<?php echo base_url('application/views/template/assets/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url('application/views/template/assets/js/jquery.easing.min.js'); ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url('application/views/template/assets/js/sb-admin-2.min.js'); ?>"></script>

    <!-- Page level plugins -->
    <script src="<?php echo base_url('application/views/template/assets/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('application/views/template/assets/js/dataTables.bootstrap4.min.js'); ?>"></script>
</head>