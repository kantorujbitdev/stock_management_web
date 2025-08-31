<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Fungsi untuk menampilkan pesan alert
function alert($type, $message) {
    $ci = get_instance();
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                ' . $message . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
}

// Fungsi untuk format tanggal
function format_date($date) {
    return date('d-m-Y', strtotime($date));
}

// Fungsi untuk format datetime
function format_datetime($datetime) {
    return date('d-m-Y H:i:s', strtotime($datetime));
}

// Fungsi untuk format angka
function format_number($number) {
    return number_format($number, 0, ',', '.');
}

// Fungsi untuk cek aktif menu
function is_active($menu) {
    $ci = get_instance();
    $controller = $ci->router->fetch_class();
    $method = $ci->router->fetch_method();
    
    if ($controller == $menu) {
        return 'active';
    }
    
    $segments = $ci->uri->segment_array();
    if (isset($segments[1]) && $segments[1] == $menu) {
        return 'active';
    }
    
    return '';
}