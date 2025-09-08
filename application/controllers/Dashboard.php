<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends CI_Controller
{
    public function __construct()
    {
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

    public function index()
    {
        // Cek hak akses dashboard
        $this->hak_akses->cek_akses('dashboard');
        $data['title'] = 'Dashboard';
        $data['total_barang'] = $this->Dashboard_model->get_total_barang();
        $data['total_stok'] = $this->Dashboard_model->get_total_stok();
        $data['penjualan_hari_ini'] = $this->Dashboard_model->get_penjualan_hari_ini();
        $data['stok_menipis'] = $this->Dashboard_model->get_stok_menipis();

        // Data untuk grafik
        $data['grafik_penjualan'] = $this->Dashboard_model->get_grafik_penjualan();
        $data['penjualan_per_kategori'] = $this->Dashboard_model->get_penjualan_per_kategori();

        // Jika Admin Pusat, load data perusahaan
        if ($this->session->userdata('id_role') == 5 || $this->session->userdata('id_role') == 1) {
            $this->load->model('perusahaan/Perusahaan_model');
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan);
            // Load gudang untuk perusahaan ini
            $this->load->model('perusahaan/Gudang_model');
            $data['gudang'] = $this->Gudang_model->get_gudang_by_perusahaan($id_perusahaan);
        }
        $data['content'] = 'dashboard';
        $this->load->view('template/template', $data);
    }
}