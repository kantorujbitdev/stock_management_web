<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('hak_akses');
        $this->load->model('stok/Log_stok_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('riwayat');
    }

    public function index() {
        $data['title'] = 'Riwayat Stok';
        
        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $id_gudang = $this->input->get('id_gudang');
        $id_barang = $this->input->get('id_barang');
        $jenis = $this->input->get('jenis');
        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        
        // Jika Super Admin, tampilkan semua riwayat
        if ($this->session->userdata('id_role') == 5) {
            $data['riwayat'] = $this->Log_stok_model->get_filtered_riwayat($id_perusahaan, $id_gudang, $id_barang, $jenis, $tanggal_awal, $tanggal_akhir);
            $data['perusahaan'] = $this->Log_stok_model->get_perusahaan_list();
        } else {
            // Jika Admin Pusat, tampilkan riwayat milik perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['riwayat'] = $this->Log_stok_model->get_filtered_riwayat($id_perusahaan_user, $id_gudang, $id_barang, $jenis, $tanggal_awal, $tanggal_akhir);
            
            // Get perusahaan data for filter
            $this->load->model('perusahaan/Perusahaan_model');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan_user));
        }
        
        // Get gudang list based on selected perusahaan
        if ($id_perusahaan) {
            $data['gudang'] = $this->Log_stok_model->get_gudang_by_perusahaan($id_perusahaan);
        } else {
            $data['gudang'] = [];
        }
        
        // Get barang list based on selected gudang
        if ($id_gudang) {
            $data['barang'] = $this->Log_stok_model->get_barang_by_gudang($id_gudang);
        } else {
            $data['barang'] = [];
        }
        
        // Set filter values for view
        $data['filter'] = [
            'id_perusahaan' => $id_perusahaan,
            'id_gudang' => $id_gudang,
            'id_barang' => $id_barang,
            'jenis' => $jenis,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ];
        
        $data['content'] = 'stok/riwayat_list';
        $this->load->view('template/template', $data);
    }
}