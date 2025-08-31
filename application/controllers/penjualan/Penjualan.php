<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('penjualan/Penjualan_model');
        $this->load->model('penjualan/Detail_penjualan_model');
        $this->load->model('perusahaan/Perusahaan_model');
        $this->load->model('perusahaan/Gudang_model');
        $this->load->model('master/Barang_model');
        $this->load->model('master/Pelanggan_model');
        $this->load->model('stok/Stok_gudang_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('penjualan');
    }

    public function index() {
        $data['title'] = 'Data Penjualan';
        
        // Jika Super Admin, tampilkan semua penjualan
        if ($this->session->userdata('id_role') == 5) {
            $data['penjualan'] = $this->Penjualan_model->get_all_penjualan();
        } else {
            // Jika Admin Pusat atau Sales Online, tampilkan penjualan milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['penjualan'] = $this->Penjualan_model->get_penjualan_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'penjualan/penjualan_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Penjualan';
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat atau Sales Online, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['pelanggan'] = $this->Pelanggan_model->get_all_pelanggan();
        
        $data['content'] = 'penjualan/penjualan_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('id_pelanggan', 'Pelanggan', 'required');
        $this->form_validation->set_rules('tanggal_penjualan', 'Tanggal Penjualan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_perusahaan_input = $this->input->post('id_perusahaan');
                
                if ($id_perusahaan_user != $id_perusahaan_input) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                    redirect('penjualan');
                }
            }
            
            // Generate no invoice
            $id_perusahaan = $this->input->post('id_perusahaan');
            $no_invoice = 'INV-' . date('Ymd') . '-' . $this->Penjualan_model->get_next_number($id_perusahaan);
            
            $data = [
                'no_invoice' => $no_invoice,
                'id_user' => $this->session->userdata('id_user'),
                'id_pelanggan' => $this->input->post('id_pelanggan'),
                'tanggal_penjualan' => $this->input->post('tanggal_penjualan'),
                'total_harga' => 0,
                'keterangan' => $this->input->post('keterangan'),
                'status' => 'proses'
            ];

            $insert = $this->Penjualan_model->insert_penjualan($data);
            
            if ($insert) {
                $id_penjualan = $this->db->insert_id();
                redirect('penjualan/detail/' . $id_penjualan);
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan penjualan');
                redirect('penjualan/add');
            }
        }
    }

    public function detail($id) {
        // Cek apakah user punya akses ke penjualan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $penjualan = $this->Penjualan_model->get_penjualan_by_id($id);
            
            // Get user to check perusahaan
            $this->load->model('auth/User_model');
            $user = $this->User_model->get_user_by_id($penjualan->id_user);
            
            if ($user->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                redirect('penjualan');
            }
        }
        
        $data['title'] = 'Detail Penjualan';
        $data['penjualan'] = $this->Penjualan_model->get_penjualan_by_id($id);
        $data['detail'] = $this->Detail_penjualan_model->get_detail_by_penjualan($id);
        
        // Get barang by perusahaan
        $this->load->model('auth/User_model');
        $user = $this->User_model->get_user_by_id($data['penjualan']->id_user);
        $data['barang'] = $this->Barang_model->get_barang_by_perusahaan($user->id_perusahaan);
        
        // Get gudang by perusahaan
        $data['gudang'] = $this->Gudang_model->get_gudang_by_perusahaan($user->id_perusahaan);
        
        $data['content'] = 'penjualan/penjualan_detail';
        $this->load->view('template/template', $data);
    }

    public function add_barang() {
        $id_penjualan = $this->input->post('id_penjualan');
        $id_barang = $this->input->post('id_barang');
        $id_gudang = $this->input->post('id_gudang');
        $jumlah = $this->input->post('jumlah');
        $harga_satuan = $this->input->post('harga_satuan');
        
        // Cek apakah barang sudah ada di detail
        $check = $this->Detail_penjualan_model->get_detail_by_barang_penjualan($id_penjualan, $id_barang);
        
        if ($check) {
            // Update existing detail
            $data = [
                'jumlah' => $check->jumlah + $jumlah,
                'harga_satuan' => $harga_satuan
            ];
            
            $this->Detail_penjualan_model->update_detail($check->id_detail, $data);
        } else {
            // Insert new detail
            $data = [
                'id_penjualan' => $id_penjualan,
                'id_barang' => $id_barang,
                'id_gudang' => $id_gudang,
                'jumlah' => $jumlah,
                'harga_satuan' => $harga_satuan
            ];
            
            $this->Detail_penjualan_model->insert_detail($data);
        }
        
        // Update total harga penjualan
        $this->update_total_harga($id_penjualan);
        
        redirect('penjualan/detail/' . $id_penjualan);
    }

    public function delete_barang($id) {
        $id_penjualan = $this->input->get('id_penjualan');
        
        $this->Detail_penjualan_model->delete_detail($id);
        
        // Update total harga penjualan
        $this->update_total_harga($id_penjualan);
        
        redirect('penjualan/detail/' . $id_penjualan);
    }

    public function proses($id) {
        // Cek apakah user punya akses ke penjualan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $penjualan = $this->Penjualan_model->get_penjualan_by_id($id);
            
            // Get user to check perusahaan
            $this->load->model('auth/User_model');
            $user = $this->User_model->get_user_by_id($penjualan->id_user);
            
            if ($user->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                redirect('penjualan');
            }
        }
        
        $penjualan = $this->Penjualan_model->get_penjualan_by_id($id);
        $detail = $this->Detail_penjualan_model->get_detail_by_penjualan($id);
        
        if (empty($detail)) {
            $this->session->set_flashdata('error', 'Tidak ada barang yang dijual');
            redirect('penjualan/detail/' . $id);
        }
        
        // Cek stok untuk setiap barang
        foreach ($detail as $d) {
            $stok = $this->Stok_gudang_model->get_stok_by_barang_gudang($d->id_barang, $d->id_gudang);
            
            if (!$stok || $stok->jumlah < $d->jumlah) {
                $this->session->set_flashdata('error', 'Stok barang ' . $d->nama_barang . ' tidak mencukupi');
                redirect('penjualan/detail/' . $id);
            }
        }
        
        // Update status penjualan
        $this->Penjualan_model->update_penjualan($id, ['status' => 'packing']);
        
        $this->session->set_flashdata('success', 'Penjualan berhasil diproses');
        redirect('penjualan');
    }

    public function kirim($id) {
        // Cek apakah user punya akses ke penjualan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $penjualan = $this->Penjualan_model->get_penjualan_by_id($id);
            
            // Get user to check perusahaan
            $this->load->model('auth/User_model');
            $user = $this->User_model->get_user_by_id($penjualan->id_user);
            
            if ($user->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                redirect('penjualan');
            }
        }
        
        $penjualan = $this->Penjualan_model->get_penjualan_by_id($id);
        
        if ($penjualan->status != 'packing') {
            $this->session->set_flashdata('error', 'Penjualan harus dalam status packing terlebih dahulu');
            redirect('penjualan');
        }
        
        // Update status penjualan
        $this->Penjualan_model->update_penjualan($id, ['status' => 'dikirim']);
        
        $this->session->set_flashdata('success', 'Penjualan berhasil dikirim');
        redirect('penjualan');
    }

    public function selesai($id) {
        // Cek apakah user punya akses ke penjualan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $penjualan = $this->Penjualan_model->get_penjualan_by_id($id);
            
            // Get user to check perusahaan
            $this->load->model('auth/User_model');
            $user = $this->User_model->get_user_by_id($penjualan->id_user);
            
            if ($user->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                redirect('penjualan');
            }
        }
        
        $penjualan = $this->Penjualan_model->get_penjualan_by_id($id);
        $detail = $this->Detail_penjualan_model->get_detail_by_penjualan($id);
        
        if ($penjualan->status != 'dikirim') {
            $this->session->set_flashdata('error', 'Penjualan harus dalam status dikirim terlebih dahulu');
            redirect('penjualan');
        }
        
        // Update status penjualan
        $this->Penjualan_model->update_penjualan($id, ['status' => 'selesai']);
        
        // Update stok gudang dan log stok
        foreach ($detail as $d) {
            $this->update_stok_gudang($d->id_barang, $d->id_gudang, $d->jumlah, 'penjualan', $id);
        }
        
        $this->session->set_flashdata('success', 'Penjualan berhasil diselesaikan');
        redirect('penjualan');
    }

    public function batal($id) {
        // Cek apakah user punya akses ke penjualan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $penjualan = $this->Penjualan_model->get_penjualan_by_id($id);
            
            // Get user to check perusahaan
            $this->load->model('auth/User_model');
            $user = $this->User_model->get_user_by_id($penjualan->id_user);
            
            if ($user->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                redirect('penjualan');
            }
        }
        
        // Update status penjualan
        $this->Penjualan_model->update_penjualan($id, ['status' => 'batal']);
        
        $this->session->set_flashdata('success', 'Penjualan berhasil dibatalkan');
        redirect('penjualan');
    }
    
    // Helper function untuk update total harga
    private function update_total_harga($id_penjualan) {
        $detail = $this->Detail_penjualan_model->get_detail_by_penjualan($id_penjualan);
        $total = 0;
        
        foreach ($detail as $d) {
            $total += ($d->jumlah * $d->harga_satuan);
        }
        
        $this->Penjualan_model->update_penjualan($id_penjualan, ['total_harga' => $total]);
    }
    
    // Helper function untuk update stok gudang
    private function update_stok_gudang($id_barang, $id_gudang, $qty, $jenis, $id_referensi) {
        $this->load->model('stok/Stok_gudang_model');
        
        // Get perusahaan from gudang
        $this->load->model('perusahaan/Gudang_model');
        $gudang = $this->Gudang_model->get_gudang_by_id($id_gudang);
        $id_perusahaan = $gudang->id_perusahaan;
        
        // Update stok gudang
        $stok_gudang = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);
        $jumlah = $stok_gudang->jumlah - $qty;
        $this->Stok_gudang_model->update_stok($stok_gudang->id_stok, ['jumlah' => $jumlah]);
        
        // Insert log stok
        $this->load->model('stok/Log_stok_model');
        
        $data_log = [
            'id_barang' => $id_barang,
            'id_user' => $this->session->userdata('id_user'),
            'id_perusahaan' => $id_perusahaan,
            'id_gudang' => $id_gudang,
            'jenis' => 'keluar',
            'jumlah' => $qty,
            'keterangan' => 'Penjualan',
            'id_referensi' => $id_referensi,
            'tipe_referensi' => 'penjualan'
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
    
    public function get_barang_by_gudang() {
        $id_gudang = $this->input->post('id_gudang');
        
        $this->db->select('stok_gudang.*, barang.nama_barang, barang.sku');
        $this->db->from('stok_gudang');
        $this->db->join('barang', 'barang.id_barang = stok_gudang.id_barang');
        $this->db->where('stok_gudang.id_gudang', $id_gudang);
        $this->db->where('stok_gudang.jumlah >', 0);
        $barang = $this->db->get()->result();
        
        echo '<option value="">-- Pilih Barang --</option>';
        foreach ($barang as $row) {
            echo '<option value="'.$row->id_barang.'" data-stok="'.$row->jumlah.'">'.$row->nama_barang.' - '.$row->sku.' (Stok: '.$row->jumlah.')</option>';
        }
    }
    
    public function get_stok_barang() {
        $id_barang = $this->input->post('id_barang');
        $id_gudang = $this->input->post('id_gudang');
        
        $stok = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);
        
        if ($stok) {
            echo $stok->jumlah;
        } else {
            echo 0;
        }
    }
}