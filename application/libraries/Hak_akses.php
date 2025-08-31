<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hak_akses {
    protected $ci;
    
    public function __construct() {
        $this->ci = &get_instance();
        // Load model hanya jika diperlukan, tidak di constructor
    }
    
    // Cek hak akses berdasarkan role dan fitur
    public function cek_akses($fitur) {
        $id_role = $this->ci->session->userdata('id_role');
        
        // Super Admin memiliki akses penuh
        if ($id_role == 5) {
            return TRUE;
        }
        
        $this->ci->load->model('auth/Hak_akses_model');
        $akses = $this->ci->Hak_akses_model->get_akses($id_role, $fitur);
        
        if ($akses && $akses->akses == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    // Mendapatkan menu berdasarkan role
    public function get_menu() {
        $id_role = $this->ci->session->userdata('id_role');
        
        // Menu untuk Super Admin
        if ($id_role == 5) {
            return [
                'dashboard' => 'Dashboard',
                'perusahaan' => 'Perusahaan',
                'master' => 'Master Data',
                'stok' => 'Manajemen Stok',
                'penjualan' => 'Penjualan',
                'laporan' => 'Laporan',
                'pengaturan' => 'Pengaturan',
                'auth' => 'Manajemen User'
            ];
        }
        
        // Menu untuk Admin Pusat
        if ($id_role == 1) {
            return [
                'dashboard' => 'Dashboard',
                'perusahaan' => 'Perusahaan',
                'master' => 'Master Data',
                'stok' => 'Manajemen Stok',
                'laporan' => 'Laporan'
            ];
        }
        
        // Menu untuk Sales Online
        if ($id_role == 2) {
            return [
                'dashboard' => 'Dashboard',
                'penjualan' => 'Penjualan'
            ];
        }
        
        // Menu untuk Admin Packing
        if ($id_role == 3) {
            return [
                'dashboard' => 'Dashboard',
                'penjualan' => 'Penjualan'
            ];
        }
        
        // Menu untuk Admin Return
        if ($id_role == 4) {
            return [
                'dashboard' => 'Dashboard',
                'retur' => 'Retur Penjualan'
            ];
        }
        
        return [];
    }
}