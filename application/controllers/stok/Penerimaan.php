<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('stok/Penerimaan_model');
        $this->load->model('stok/Detail_penerimaan_model');
        $this->load->model('perusahaan/Perusahaan_model');
        $this->load->model('perusahaan/Gudang_model');
        $this->load->model('master/Barang_model');
        $this->load->model('master/Supplier_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('penerimaan');
    }

    public function index() {
        $data['title'] = 'Data Penerimaan Barang';
        
        // Jika Super Admin, tampilkan semua penerimaan
        if ($this->session->userdata('id_role') == 5) {
            $data['penerimaan'] = $this->Penerimaan_model->get_all_penerimaan();
        } else {
            // Jika Admin Pusat, tampilkan penerimaan milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['penerimaan'] = $this->Penerimaan_model->get_penerimaan_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'stok/penerimaan_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Penerimaan Barang';
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['supplier'] = $this->Supplier_model->get_all_supplier();
        
        $data['content'] = 'stok/penerimaan_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('id_supplier', 'Supplier', 'required');
        $this->form_validation->set_rules('id_gudang', 'Gudang', 'required');
        $this->form_validation->set_rules('tanggal_penerimaan', 'Tanggal Penerimaan', 'required');
        $this->form_validation->set_rules('no_faktur', 'No Faktur', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_perusahaan_input = $this->input->post('id_perusahaan');
                
                if ($id_perusahaan_user != $id_perusahaan_input) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                    redirect('penerimaan');
                }
            }
            
            // Generate no penerimaan
            $id_perusahaan = $this->input->post('id_perusahaan');
            $no_penerimaan = 'PCT-' . date('Ymd') . '-' . $this->Penerimaan_model->get_next_number($id_perusahaan);
            
            $data = [
                'id_supplier' => $this->input->post('id_supplier'),
                'id_gudang' => $this->input->post('id_gudang'),
                'id_user' => $this->session->userdata('id_user'),
                'tanggal_penerimaan' => $this->input->post('tanggal_penerimaan'),
                'no_faktur' => $this->input->post('no_faktur'),
                'no_penerimaan' => $no_penerimaan,
                'status' => 'draft',
                'keterangan' => $this->input->post('keterangan')
            ];

            $insert = $this->Penerimaan_model->insert_penerimaan($data);
            
            if ($insert) {
                $id_penerimaan = $this->db->insert_id();
                redirect('penerimaan/detail/' . $id_penerimaan);
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan penerimaan barang');
                redirect('penerimaan/add');
            }
        }
    }

    public function detail($id) {
        // Cek apakah user punya akses ke penerimaan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $penerimaan = $this->Penerimaan_model->get_penerimaan_by_id($id);
            
            // Get gudang to check perusahaan
            $this->load->model('perusahaan/Gudang_model');
            $gudang = $this->Gudang_model->get_gudang_by_id($penerimaan->id_gudang);
            
            if ($gudang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penerimaan ini');
                redirect('penerimaan');
            }
        }
        
        $data['title'] = 'Detail Penerimaan Barang';
        $data['penerimaan'] = $this->Penerimaan_model->get_penerimaan_by_id($id);
        $data['detail'] = $this->Detail_penerimaan_model->get_detail_by_penerimaan($id);
        
        // Get barang by perusahaan
        $this->load->model('perusahaan/Gudang_model');
        $gudang = $this->Gudang_model->get_gudang_by_id($data['penerimaan']->id_gudang);
        $data['barang'] = $this->Barang_model->get_barang_by_perusahaan($gudang->id_perusahaan);
        
        $data['content'] = 'stok/penerimaan_detail';
        $this->load->view('template/template', $data);
    }

    public function add_barang() {
        $id_penerimaan = $this->input->post('id_penerimaan');
        $id_barang = $this->input->post('id_barang');
        $jumlah_diterima = $this->input->post('jumlah_diterima');
        $harga_beli = $this->input->post('harga_beli');
        
        // Cek apakah barang sudah ada di detail
        $check = $this->Detail_penerimaan_model->get_detail_by_barang_penerimaan($id_penerimaan, $id_barang);
        
        if ($check) {
            // Update existing detail
            $data = [
                'jumlah_diterima' => $check->jumlah_diterima + $jumlah_diterima,
                'harga_beli' => $harga_beli
            ];
            
            $this->Detail_penerimaan_model->update_detail($check->id_detail, $data);
        } else {
            // Insert new detail
            $data = [
                'id_penerimaan' => $id_penerimaan,
                'id_barang' => $id_barang,
                'jumlah_diterima' => $jumlah_diterima,
                'harga_beli' => $harga_beli
            ];
            
            $this->Detail_penerimaan_model->insert_detail($data);
        }
        
        redirect('penerimaan/detail/' . $id_penerimaan);
    }

    public function delete_barang($id) {
        $id_penerimaan = $this->input->get('id_penerimaan');
        
        $this->Detail_penerimaan_model->delete_detail($id);
        
        redirect('penerimaan/detail/' . $id_penerimaan);
    }

    public function proses($id) {
        // Cek apakah user punya akses ke penerimaan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $penerimaan = $this->Penerimaan_model->get_penerimaan_by_id($id);
            
            // Get gudang to check perusahaan
            $this->load->model('perusahaan/Gudang_model');
            $gudang = $this->Gudang_model->get_gudang_by_id($penerimaan->id_gudang);
            
            if ($gudang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penerimaan ini');
                redirect('penerimaan');
            }
        }
        
        $penerimaan = $this->Penerimaan_model->get_penerimaan_by_id($id);
        $detail = $this->Detail_penerimaan_model->get_detail_by_penerimaan($id);
        
        if (empty($detail)) {
            $this->session->set_flashdata('error', 'Tidak ada barang yang diterima');
            redirect('penerimaan/detail/' . $id);
        }
        
        // Update status penerimaan
        $this->Penerimaan_model->update_penerimaan($id, ['status' => 'diterima']);
        
        // Update stok gudang dan log stok
        foreach ($detail as $d) {
            $this->update_stok_gudang($d->id_barang, $penerimaan->id_gudang, $d->jumlah_diterima, 'penerimaan', $id);
        }
        
        $this->session->set_flashdata('success', 'Penerimaan barang berhasil diproses');
        redirect('penerimaan');
    }

    public function batal($id) {
        // Cek apakah user punya akses ke penerimaan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $penerimaan = $this->Penerimaan_model->get_penerimaan_by_id($id);
            
            // Get gudang to check perusahaan
            $this->load->model('perusahaan/Gudang_model');
            $gudang = $this->Gudang_model->get_gudang_by_id($penerimaan->id_gudang);
            
            if ($gudang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penerimaan ini');
                redirect('penerimaan');
            }
        }
        
        // Update status penerimaan
        $this->Penerimaan_model->update_penerimaan($id, ['status' => 'dibatalkan']);
        
        $this->session->set_flashdata('success', 'Penerimaan barang berhasil dibatalkan');
        redirect('penerimaan');
    }
    
    // Helper function untuk update stok gudang
    private function update_stok_gudang($id_barang, $id_gudang, $qty, $jenis, $id_referensi) {
        $this->load->model('stok/Stok_gudang_model');
        
        // Get perusahaan from gudang
        $this->load->model('perusahaan/Gudang_model');
        $gudang = $this->Gudang_model->get_gudang_by_id($id_gudang);
        $id_perusahaan = $gudang->id_perusahaan;
        
        // Cek apakah stok gudang sudah ada
        $stok_gudang = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);
        
        if ($stok_gudang) {
            // Update stok gudang yang sudah ada
            $jumlah = $stok_gudang->jumlah + $qty;
            $this->Stok_gudang_model->update_stok($stok_gudang->id_stok, ['jumlah' => $jumlah]);
        } else {
            // Insert stok gudang baru
            $data_stok = [
                'id_perusahaan' => $id_perusahaan,
                'id_gudang' => $id_gudang,
                'id_barang' => $id_barang,
                'jumlah' => $qty
            ];
            
            $this->Stok_gudang_model->insert_stok($data_stok);
        }
        
        // Insert log stok
        $this->load->model('stok/Log_stok_model');
        
        $data_log = [
            'id_barang' => $id_barang,
            'id_user' => $this->session->userdata('id_user'),
            'id_perusahaan' => $id_perusahaan,
            'id_gudang' => $id_gudang,
            'jenis' => 'masuk',
            'jumlah' => $qty,
            'keterangan' => 'Penerimaan Barang',
            'id_referensi' => $id_referensi,
            'tipe_referensi' => 'penerimaan'
        ];
        
        $this->Log_stok_model->insert_log($data_log);
    }
    
    public function get_gudang_by_perusahaan() {
        $id_perusahaan = $this->input->post('id_perusahaan');
        $gudang = $this->Gudang_model->get_gudang_by_perusahaan($id_perusahaan);
        
        echo '<option value="">-- Pilih Gudang --</option>';
        foreach ($gudang as $row) {
            echo '<option value="'.$row->id_gudang.'">'.$row->nama_gudang.'</option>';
        }
    }
}