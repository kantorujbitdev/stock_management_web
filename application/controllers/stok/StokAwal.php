<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StokAwal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('stok/Stok_awal_model');
        $this->load->model('perusahaan/Perusahaan_model');
        $this->load->model('perusahaan/Gudang_model');
        $this->load->model('master/Barang_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('stok_awal');
    }

    public function index() {
        $data['title'] = 'Data Stok Awal';
        
        // Jika Super Admin, tampilkan semua stok awal
        if ($this->session->userdata('id_role') == 5) {
            $data['stok_awal'] = $this->Stok_awal_model->get_all_stok_awal();
        } else {
            // Jika Admin Pusat, tampilkan stok awal milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['stok_awal'] = $this->Stok_awal_model->get_stok_awal_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'stok/stok_awal_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Stok Awal';
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['content'] = 'stok/stok_awal_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_gudang', 'Gudang', 'required');
        $this->form_validation->set_rules('id_barang', 'Barang', 'required');
        $this->form_validation->set_rules('qty_awal', 'Qty Awal', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_perusahaan_input = $this->input->post('id_perusahaan');
                
                if ($id_perusahaan_user != $id_perusahaan_input) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                    redirect('stok_awal');
                }
            }
            
            // Cek apakah stok awal untuk barang dan gudang ini sudah ada
            $id_barang = $this->input->post('id_barang');
            $id_gudang = $this->input->post('id_gudang');
            
            if ($this->Stok_awal_model->check_stok_awal_exists($id_barang, $id_gudang)) {
                $this->session->set_flashdata('error', 'Stok awal untuk barang ini di gudang ini sudah ada');
                redirect('stok_awal/add');
            }
            
            $data = [
                'id_barang' => $id_barang,
                'id_gudang' => $id_gudang,
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'qty_awal' => $this->input->post('qty_awal'),
                'keterangan' => $this->input->post('keterangan'),
                'created_by' => $this->session->userdata('id_user')
            ];

            $insert = $this->Stok_awal_model->insert_stok_awal($data);
            
            if ($insert) {
                // Update stok gudang
                $this->update_stok_gudang($id_barang, $id_gudang, $this->input->post('qty_awal'), 'stok_awal');
                
                $this->session->set_flashdata('success', 'Stok awal berhasil ditambahkan');
                redirect('stok_awal');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan stok awal');
                redirect('stok_awal/add');
            }
        }
    }

    public function edit($id) {
        // Cek apakah user punya akses ke stok awal ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $stok_awal = $this->Stok_awal_model->get_stok_awal_by_id($id);
            
            if ($stok_awal->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke stok awal ini');
                redirect('stok_awal');
            }
        }
        
        $data['title'] = 'Edit Stok Awal';
        $data['stok_awal'] = $this->Stok_awal_model->get_stok_awal_by_id($id);
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['gudang'] = $this->Gudang_model->get_gudang_by_perusahaan($data['stok_awal']->id_perusahaan);
        $data['barang'] = $this->Barang_model->get_barang_by_perusahaan($data['stok_awal']->id_perusahaan);
        
        $data['content'] = 'stok/stok_awal_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process() {
        $id = $this->input->post('id_stok_awal');
        
        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $id_perusahaan_input = $this->input->post('id_perusahaan');
            
            if ($id_perusahaan_user != $id_perusahaan_input) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                redirect('stok_awal');
            }
        }
        
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_gudang', 'Gudang', 'required');
        $this->form_validation->set_rules('id_barang', 'Barang', 'required');
        $this->form_validation->set_rules('qty_awal', 'Qty Awal', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $id_barang = $this->input->post('id_barang');
            $id_gudang = $this->input->post('id_gudang');
            $qty_awal = $this->input->post('qty_awal');
            
            // Cek apakah stok awal untuk barang dan gudang ini sudah ada (kecuali untuk record yang sama)
            $stok_awal = $this->Stok_awal_model->get_stok_awal_by_id($id);
            
            if ($stok_awal->id_barang != $id_barang || $stok_awal->id_gudang != $id_gudang) {
                if ($this->Stok_awal_model->check_stok_awal_exists($id_barang, $id_gudang)) {
                    $this->session->set_flashdata('error', 'Stok awal untuk barang ini di gudang ini sudah ada');
                    redirect('stok_awal/edit/' . $id);
                }
            }
            
            $data = [
                'id_barang' => $id_barang,
                'id_gudang' => $id_gudang,
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'qty_awal' => $qty_awal,
                'keterangan' => $this->input->post('keterangan')
            ];

            $update = $this->Stok_awal_model->update_stok_awal($id, $data);
            
            if ($update) {
                // Update stok gudang
                $this->update_stok_gudang($id_barang, $id_gudang, $qty_awal, 'stok_awal', $stok_awal->qty_awal);
                
                $this->session->set_flashdata('success', 'Stok awal berhasil diupdate');
                redirect('stok_awal');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate stok awal');
                redirect('stok_awal/edit/' . $id);
            }
        }
    }

    public function delete($id) {
        // Cek apakah user punya akses ke stok awal ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $stok_awal = $this->Stok_awal_model->get_stok_awal_by_id($id);
            
            if ($stok_awal->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke stok awal ini');
                redirect('stok_awal');
            }
        }
        
        $stok_awal = $this->Stok_awal_model->get_stok_awal_by_id($id);
        
        if ($this->Stok_awal_model->delete_stok_awal($id)) {
            // Update stok gudang
            $this->update_stok_gudang($stok_awal->id_barang, $stok_awal->id_gudang, 0, 'stok_awal', $stok_awal->qty_awal);
            
            $this->session->set_flashdata('success', 'Stok awal berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus stok awal');
        }
        redirect('stok_awal');
    }
    
    // Helper function untuk update stok gudang
    private function update_stok_gudang($id_barang, $id_gudang, $qty, $jenis, $qty_lama = 0) {
        $this->load->model('stok/Stok_gudang_model');
        
        // Cek apakah stok gudang sudah ada
        $stok_gudang = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);
        
        if ($stok_gudang) {
            // Update stok gudang yang sudah ada
            if ($jenis == 'stok_awal') {
                $jumlah = $stok_gudang->jumlah - $qty_lama + $qty;
            } else {
                $jumlah = $stok_gudang->jumlah + $qty;
            }
            
            $this->Stok_gudang_model->update_stok($stok_gudang->id_stok, ['jumlah' => $jumlah]);
        } else {
            // Insert stok gudang baru
            $data_stok = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
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
            'id_perusahaan' => $this->input->post('id_perusahaan'),
            'id_gudang' => $id_gudang,
            'jenis' => 'masuk',
            'jumlah' => $qty,
            'keterangan' => 'Stok Awal: ' . $this->input->post('keterangan'),
            'id_referensi' => isset($id) ? $id : null,
            'tipe_referensi' => 'stok_awal'
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
    
    public function get_barang_by_perusahaan() {
        $id_perusahaan = $this->input->post('id_perusahaan');
        $barang = $this->Barang_model->get_barang_by_perusahaan($id_perusahaan);
        
        echo '<option value="">-- Pilih Barang --</option>';
        foreach ($barang as $row) {
            echo '<option value="'.$row->id_barang.'">'.$row->nama_barang.' - '.$row->sku.'</option>';
        }
    }
}