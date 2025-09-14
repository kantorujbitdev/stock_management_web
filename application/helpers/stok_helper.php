<?php
defined('BASEPATH') or exit('No direct script access allowed');

// if (!function_exists('check_stock_availability')) {
//     function check_stock_availability($id_barang, $id_gudang, $jumlah)
//     {
//         $CI = &get_instance();
//         $CI->load->model('stok/Stok_gudang_model');

//         $stock = $CI->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);

//         if (!$stock) {
//             return [
//                 'available' => false,
//                 'message' => 'Stok tidak ditemukan'
//             ];
//         }

//         if ($stock->jumlah < $jumlah) {
//             return [
//                 'available' => false,
//                 'message' => 'Stok tidak mencukupi. Tersedia: ' . $stock->jumlah
//             ];
//         }

//         return [
//             'available' => true,
//             'stock' => $stock->jumlah
//         ];
//     }
// }

// if (!function_exists('format_currency')) {
//     function format_currency($amount)
//     {
//         return 'Rp ' . number_format($amount, 0, ',', '.');
//     }
// }