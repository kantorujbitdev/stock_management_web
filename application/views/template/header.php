<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo isset($title) ? $title . ' - ' : ''; ?><?= $this->session->userdata('nama_perusahaan'); ?></title>
    <link rel="icon" type="image/x-icon"
        href="<?php echo base_url('application/views/template/assets/img/logo_warehouse.png'); ?>">

    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url('application/views/template/assets/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="<?php echo base_url('application/views/template/assets/css/all.min.css'); ?>" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?php echo base_url('application/views/template/assets/css/dataTables.bootstrap4.min.css'); ?>"
        rel="stylesheet">

    <!-- SB Admin 2 CSS -->
    <link href="<?php echo base_url('application/views/template/assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('application/views/template/assets/css/head.css'); ?>">

    <!-- jQuery (harus duluan sebelum Bootstrap JS) -->
    <script src="<?php echo base_url('application/views/template/assets/js/jquery.min.js'); ?>"></script>
    <!-- Bootstrap Bundle (sudah include Popper.js) -->
    <script src="<?php echo base_url('application/views/template/assets/js/bootstrap.bundle.min.js'); ?>"></script>
</head>