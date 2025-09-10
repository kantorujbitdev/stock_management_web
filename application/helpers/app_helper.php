<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('app_version')) {
    function app_version()
    {
        return '1.2.4';
    }
}
// Fungsi untuk menampilkan pesan alert
// function alert($type, $message)
// {
//     $ci = get_instance();
//     return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
//                 ' . $message . '
//                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
//                     <span aria-hidden="true">&times;</span>
//                 </button>
//             </div>';
// }

// // Fungsi untuk format tanggal
// function format_date($date)
// {
//     return date('d-m-Y', strtotime($date));
// }

// // Fungsi untuk format datetime
// function format_datetime($datetime)
// {
//     return date('d-m-Y H:i:s', strtotime($datetime));
// }

// // Fungsi untuk format angka
// function format_number($number)
// {
//     return number_format($number, 0, ',', '.');
// }

// // Fungsi untuk cek aktif menu
// function is_active($menu)
// {
//     $ci = get_instance();
//     $controller = $ci->router->fetch_class();
//     $method = $ci->router->fetch_method();

//     if ($controller == $menu) {
//         return 'active';
//     }

//     $segments = $ci->uri->segment_array();
//     if (isset($segments[1]) && $segments[1] == $menu) {
//         return 'active';
//     }

//     return '';
// }

if (!function_exists('back_button')) {
    function back_button($fallback = 'dashboard', $label = 'Kembali')
    {
        $ci = get_instance();
        $url = site_url($fallback);
        return '<a href="#" onclick="if(document.referrer){window.history.back();}else{window.location=\'' . $url . '\';} return false;" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> ' . $label . '
                </a>';
    }
}

if (!function_exists('responsive_title')) {
    function responsive_title($text, $icon_class = null)
    {
        $icon_html = $icon_class ? '<i class="' . $icon_class . ' ml-4 mr-2"></i>' : '';
        return '<h5 class="mb-0 title-responsive d-flex align-items-center">'
            . $icon_html . $text .
            '</h5>';
    }
}
