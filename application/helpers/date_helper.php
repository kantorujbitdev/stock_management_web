<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('format_tanggal')) {
    function format_tanggal($tanggal)
    {
        if (empty($tanggal)) {
            return '-';
        }

        // Convert string to timestamp
        $timestamp = strtotime($tanggal);

        // Format tanggal: dd-mm-yyyy HH:mm
        return date('d-m-Y H:i:s', $timestamp);
    }
}

if (!function_exists('format_tanggal_indo')) {
    function format_tanggal_indo($tanggal)
    {
        if (empty($tanggal)) {
            return '-';
        }

        $timestamp = strtotime($tanggal);

        // Array nama hari
        $nama_hari = array(
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        );

        // Array nama bulan
        $nama_bulan = array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );

        // Get hari, tanggal, bulan, tahun, jam, menit
        $hari = $nama_hari[date('w', $timestamp)];
        $tanggal = date('j', $timestamp);
        $bulan = $nama_bulan[date('n', $timestamp)];
        $tahun = date('Y', $timestamp);
        $jam = date('H:i', $timestamp);

        return $hari . ', ' . $tanggal . ' ' . $bulan . ' ' . $tahun . ' ' . $jam;
    }
}

if (!function_exists('selisih_waktu')) {
    function selisih_waktu($tanggal)
    {
        if (empty($tanggal)) {
            return '-';
        }

        $timestamp = strtotime($tanggal);
        $selisih = time() - $timestamp;

        if ($selisih < 60) {
            return 'Baru saja';
        } elseif ($selisih < 3600) {
            $menit = floor($selisih / 60);
            return $menit . ' menit yang lalu';
        } elseif ($selisih < 86400) {
            $jam = floor($selisih / 3600);
            return $jam . ' jam yang lalu';
        } elseif ($selisih < 604800) {
            $hari = floor($selisih / 86400);
            return $hari . ' hari yang lalu';
        } else {
            return format_tanggal($tanggal);
        }
    }
}