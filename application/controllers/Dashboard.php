<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('hak_akses');
        $this->load->model('Dashboard_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index() {
        $data['title'] = 'Dashboard';
        
        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['total_perusahaan'] = $this->Dashboard_model->get_total_perusahaan();
            $data['total_gudang'] = $this->Dashboard_model->get_total_gudang();
            $data['total_barang'] = $this->Dashboard_model->get_total_barang();
            $data['total_stok'] = $this->Dashboard_model->get_total_stok();
            $data['total_penjualan'] = $this->Dashboard_model->get_total_penjualan();
            $data['total_retur'] = $this->Dashboard_model->get_total_retur();
            $data['grafik_penjualan'] = $this->Dashboard_model->get_grafik_penjualan_bulan();
            $data['stok_menipis'] = $this->Dashboard_model->get_stok_menipis();
            $data['penjualan_terakhir'] = $this->Dashboard_model->get_penjualan_terakhir();
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['total_gudang'] = $this->Dashboard_model->get_total_gudang_by_perusahaan($id_perusahaan);
            $data['total_barang'] = $this->Dashboard_model->get_total_barang_by_perusahaan($id_perusahaan);
            $data['total_stok'] = $this->Dashboard_model->get_total_stok_by_perusahaan($id_perusahaan);
            $data['total_penjualan'] = $this->Dashboard_model->get_total_penjualan_by_perusahaan($id_perusahaan);
            $data['total_retur'] = $this->Dashboard_model->get_total_retur_by_perusahaan($id_perusahaan);
            $data['grafik_penjualan'] = $this->Dashboard_model->get_grafik_penjualan_bulan_by_perusahaan($id_perusahaan);
            $data['stok_menipis'] = $this->Dashboard_model->get_stok_menipis_by_perusahaan($id_perusahaan);
            $data['penjualan_terakhir'] = $this->Dashboard_model->get_penjualan_terakhir_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'dashboard';
        $this->load->view('template/template', $data);
    }
}