<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('penjualan/Retur_model');
        $this->load->model('penjualan/Detail_retur_model');
        $this->load->model('penjualan/Penjualan_model');
        $this->load->model('perusahaan/Perusahaan_model');
        $this->load->model('perusahaan/Gudang_model');
        $this->load->model('master/Barang_model');
        $this->load->model('stok/Stok_gudang_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('retur');
    }

    public function index() {
        $data['title'] = 'Data Retur Penjualan';
        
        // Jika Super Admin, tampilkan semua retur
        if ($this->session->userdata('id_role') == 5) {
            $data['retur'] = $this->Retur_model->get_all_retur();
        } else {
            // Jika Admin Pusat atau Admin Return, tampilkan retur milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['retur'] = $this->Retur_model->get_retur_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'penjualan/retur_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Retur Penjualan';
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat atau Admin Return, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['content'] = 'penjualan/retur_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('id_penjualan', 'Penjualan', 'required');
        $this->form_validation->set_rules('tanggal_retur', 'Tanggal Retur', 'required');
        $this->form_validation->set_rules('alasan_retur', 'Alasan Retur', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_penjualan = $this->input->post('id_penjualan');
                
                // Get penjualan to check perusahaan
                $penjualan = $this->Penjualan_model->get_penjualan_by_id($id_penjualan);
                $this->load->model('auth/User_model');
                $user = $this->User_model->get_user_by_id($penjualan->id_user);
                
                if ($user->id_perusahaan != $id_perusahaan_user) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                    redirect('retur');
                }
            }
            
            // Generate no retur
            $id_perusahaan = $this->input->post('id_perusahaan');
            $no_retur = 'RET-' . date('Ymd') . '-' . $this->Retur_model->get_next_number($id_perusahaan);
            
            $data = [
                'no_retur' => $no_retur,
                'id_penjualan' => $this->input->post('id_penjualan'),
                'id_user' => $this->session->userdata('id_user'),
                'tanggal_retur' => $this->input->post('tanggal_retur'),
                'alasan_retur' => $this->input->post('alasan_retur'),
                'status' => 'diterima'
            ];

            $insert = $this->Retur_model->insert_retur($data);
            
            if ($insert) {
                $id_retur = $this->db->insert_id();
                redirect('retur/detail/' . $id_retur);
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan retur penjualan');
                redirect('retur/add');
            }
        }
    }

    public function detail($id) {
        // Cek apakah user punya akses ke retur ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $retur = $this->Retur_model->get_retur_by_id($id);
            
            // Get user to check perusahaan
            $this->load->model('auth/User_model');
            $user = $this->User_model->get_user_by_id($retur->id_user);
            
            if ($user->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke retur ini');
                redirect('retur');
            }
        }
        
        $data['title'] = 'Detail Retur Penjualan';
        $data['retur'] = $this->Retur_model->get_retur_by_id($id);
        $data['detail'] = $this->Detail_retur_model->get_detail_by_retur($id);
        
        // Get barang from penjualan
        $this->load->model('penjualan/Detail_penjualan_model');
        $data['barang_penjualan'] = $this->Detail_penjualan_model->get_detail_by_penjualan($data['retur']->id_penjualan);
        
        // Get gudang by perusahaan
        $this->load->model('auth/User_model');
        $user = $this->User_model->get_user_by_id($data['retur']->id_user);
        $data['gudang'] = $this->Gudang_model->get_gudang_by_perusahaan($user->id_perusahaan);
        
        $data['content'] = 'penjualan/retur_detail';
        $this->load->view('template/template', $data);
    }

    public function add_barang() {
        $id_retur = $this->input->post('id_retur');
        $id_barang = $this->input->post('id_barang');
        $id_gudang = $this->input->post('id_gudang');
        $jumlah_retur = $this->input->post('jumlah_retur');
        $alasan_barang = $this->input->post('alasan_barang');
        
        // Cek apakah barang sudah ada di detail
        $check = $this->Detail_retur_model->get_detail_by_barang_retur($id_retur, $id_barang);
        
        if ($check) {
            // Update existing detail
            $data = [
                'jumlah_retur' => $check->jumlah_retur + $jumlah_retur,
                'alasan_barang' => $alasan_barang
            ];
            
            $this->Detail_retur_model->update_detail($check->id_detail_retur, $data);
        } else {
            // Insert new detail
            $data = [
                'id_retur' => $id_retur,
                'id_barang' => $id_barang,
                'id_gudang' => $id_gudang,
                'jumlah_retur' => $jumlah_retur,
                'alasan_barang' => $alasan_barang
            ];
            
            $this->Detail_retur_model->insert_detail($data);
        }
        
        redirect('retur/detail/' . $id_retur);
    }

    public function delete_barang($id) {
        $id_retur = $this->input->get('id_retur');
        
        $this->Detail_retur_model->delete_detail($id);
        
        redirect('retur/detail/' . $id_retur);
    }

    public function proses($id) {
        // Cek apakah user punya akses ke retur ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $retur = $this->Retur_model->get_retur_by_id($id);
            
            // Get user to check perusahaan
            $this->load->model('auth/User_model');
            $user = $this->User_model->get_user_by_id($retur->id_user);
            
            if ($user->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke retur ini');
                redirect('retur');
            }
        }
        
        $retur = $this->Retur_model->get_retur_by_id($id);
        $detail = $this->Detail_retur_model->get_detail_by_retur($id);
        
        if (empty($detail)) {
            $this->session->set_flashdata('error', 'Tidak ada barang yang diretur');
            redirect('retur/detail/' . $id);
        }
        
        // Update status retur
        $this->Retur_model->update_retur($id, ['status' => 'selesai']);
        
        // Update stok gudang dan log stok
        foreach ($detail as $d) {
            $this->update_stok_gudang($d->id_barang, $d->id_gudang, $d->jumlah_retur, 'retur', $id);
        }
        
        $this->session->set_flashdata('success', 'Retur penjualan berhasil diproses');
        redirect('retur');
    }

    public function tolak($id) {
        // Cek apakah user punya akses ke retur ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $retur = $this->Retur_model->get_retur_by_id($id);
            
            // Get user to check perusahaan
            $this->load->model('auth/User_model');
            $user = $this->User_model->get_user_by_id($retur->id_user);
            
            if ($user->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke retur ini');
                redirect('retur');
            }
        }
        
        // Update status retur
        $this->Retur_model->update_retur($id, ['status' => 'ditolak']);
        
        $this->session->set_flashdata('success', 'Retur penjualan berhasil ditolak');
        redirect('retur');
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
        
        if ($stok_gudang) {
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
            'jenis' => 'retur',
            'jumlah' => $qty,
            'keterangan' => 'Retur Penjualan',
            'id_referensi' => $id_referensi,
            'tipe_referensi' => 'retur'
        ];
        
        $this->Log_stok_model->insert_log($data_log);
    }
    
    public function get_penjualan_by_perusahaan() {
        $id_perusahaan = $this->input->post('id_perusahaan');
        
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
        $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        $this->db->where('penjualan.status', 'selesai');
        $this->db->order_by('penjualan.tanggal_penjualan', 'DESC');
        $penjualan = $this->db->get()->result();
        
        echo '<option value="">-- Pilih Penjualan --</option>';
        foreach ($penjualan as $row) {
            echo '<option value="'.$row->id_penjualan.'">'.$row->no_invoice.' - '.$row->nama_pelanggan.' ('.date('d-m-Y', strtotime($row->tanggal_penjualan)).')</option>';
        }
    }
    
    public function get_barang_by_penjualan() {
        $id_penjualan = $this->input->post('id_penjualan');
        
        $this->db->select('detail_penjualan.*, barang.nama_barang, barang.sku, gudang.nama_gudang, gudang.id_gudang');
        $this->db->from('detail_penjualan');
        $this->db->join('barang', 'barang.id_barang = detail_penjualan.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = detail_penjualan.id_gudang');
        $this->db->where('detail_penjualan.id_penjualan', $id_penjualan);
        $barang = $this->db->get()->result();
        
        echo '<option value="">-- Pilih Barang --</option>';
        foreach ($barang as $row) {
            echo '<option value="'.$row->id_barang.'" data-gudang="'.$row->id_gudang.'" data-jumlah="'.$row->jumlah.'">'.$row->nama_barang.' - '.$row->sku.' (Jual: '.$row->jumlah.')</option>';
        }
    }
}