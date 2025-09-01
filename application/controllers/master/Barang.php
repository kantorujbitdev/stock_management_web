<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('master/Barang_model');
        $this->load->model('master/Kategori_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('barang');
    }

    public function index() {
        $data['title'] = 'Data Barang';
        
        // Jika Super Admin, tampilkan semua barang
        if ($this->session->userdata('id_role') == 5) {
            $data['barang'] = $this->Barang_model->get_all_barang();
        } else {
            // Jika Admin Pusat, tampilkan barang milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['barang'] = $this->Barang_model->get_barang_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'master/barang_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Barang';
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $this->load->model('perusahaan/Perusahaan_model');
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $this->load->model('perusahaan/Perusahaan_model');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['content'] = 'master/barang_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('sku', 'SKU', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_perusahaan_input = $this->input->post('id_perusahaan');
                
                if ($id_perusahaan_user != $id_perusahaan_input) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                    redirect('barang');
                }
            }
            
            // Cek SKU unik per perusahaan
            $id_perusahaan = $this->input->post('id_perusahaan');
            $sku = $this->input->post('sku');
            
            if ($this->Barang_model->check_sku($id_perusahaan, $sku)) {
                $this->session->set_flashdata('error', 'SKU sudah ada untuk perusahaan ini');
                redirect('barang/add');
            }
            
            // Handle upload gambar
            $gambar = '';
            if (!empty($_FILES['gambar']['name'])) {
                $config['upload_path'] = './uploads/barang/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = time() . '_' . $_FILES['gambar']['name'];
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $gambar = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('barang/add');
                }
            }
            
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'id_kategori' => $this->input->post('id_kategori'),
                'nama_barang' => $this->input->post('nama_barang'),
                'sku' => $sku,
                'deskripsi' => $this->input->post('deskripsi'),
                'gambar' => $gambar
            ];

            $this->Barang_model->insert_barang($data);
            $this->session->set_flashdata('success', 'Barang berhasil ditambahkan');
            redirect('barang');
        }
    }

    public function edit($id) {
        // Cek apakah user punya akses ke barang ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $barang = $this->Barang_model->get_barang_by_id($id);
            
            if ($barang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
                redirect('barang');
            }
        }
        
        $data['title'] = 'Edit Barang';
        $data['barang'] = $this->Barang_model->get_barang_by_id($id);
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $this->load->model('perusahaan/Perusahaan_model');
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $this->load->model('perusahaan/Perusahaan_model');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($data['barang']->id_perusahaan);
        
        $data['content'] = 'master/barang_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process() {
        $id = $this->input->post('id_barang');
        
        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $id_perusahaan_input = $this->input->post('id_perusahaan');
            
            if ($id_perusahaan_user != $id_perusahaan_input) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                redirect('barang');
            }
        }
        
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('sku', 'SKU', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            // Cek SKU unik per perusahaan (kecuali untuk barang yang sama)
            $id_perusahaan = $this->input->post('id_perusahaan');
            $sku = $this->input->post('sku');
            $barang = $this->Barang_model->get_barang_by_id($id);
            
            if ($barang->sku != $sku && $this->Barang_model->check_sku($id_perusahaan, $sku)) {
                $this->session->set_flashdata('error', 'SKU sudah ada untuk perusahaan ini');
                redirect('barang/edit/' . $id);
            }
            
            // Handle upload gambar
            $gambar = $barang->gambar;
            if (!empty($_FILES['gambar']['name'])) {
                $config['upload_path'] = './uploads/barang/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = time() . '_' . $_FILES['gambar']['name'];
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('gambar')) {
                    // Hapus gambar lama jika ada
                    if ($barang->gambar && file_exists('./uploads/barang/' . $barang->gambar)) {
                        unlink('./uploads/barang/' . $barang->gambar);
                    }
                    
                    $upload_data = $this->upload->data();
                    $gambar = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('barang/edit/' . $id);
                }
            }
            
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'id_kategori' => $this->input->post('id_kategori'),
                'nama_barang' => $this->input->post('nama_barang'),
                'sku' => $sku,
                'deskripsi' => $this->input->post('deskripsi'),
                'gambar' => $gambar
            ];

            $this->Barang_model->update_barang($id, $data);
            $this->session->set_flashdata('success', 'Barang berhasil diupdate');
            redirect('barang');
        }
    }

    public function delete($id) {
        // Cek apakah user punya akses ke barang ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $barang = $this->Barang_model->get_barang_by_id($id);
            
            if ($barang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
                redirect('barang');
            }
        }
        
        if ($this->Barang_model->delete_barang($id)) {
            $this->session->set_flashdata('success', 'Barang berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus barang. Mungkin masih ada transaksi yang terkait.');
        }
        redirect('barang');
    }
    
    
    public function get_kategori_by_perusahaan()
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        if (empty($id_perusahaan)) {
            $id_perusahaan = $this->input->get('id_perusahaan'); // fallback GET
        }

        // Kalau bukan Super Admin, defaultnya ambil dari session
        if ($this->session->userdata('id_role') != 5) {
            if (!empty($this->session->userdata('id_perusahaan'))) {
                $id_perusahaan = $this->session->userdata('id_perusahaan');
            }
            // kalau session kosong, biarin pake POST/GET biar ga null
        }

        log_message('error', 'POST id_perusahaan: ' . $this->input->post('id_perusahaan'));
        log_message('error', 'GET id_perusahaan: ' . $this->input->get('id_perusahaan'));
        log_message('error', 'SESSION id_perusahaan: ' . $this->session->userdata('id_perusahaan'));


        $kategori = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);

        $options = "<option value=''>-- Pilih Kategori --</option>";
        foreach ($kategori as $k) {
            $options .= "<option value='{$k->id_kategori}'>{$k->nama_kategori}</option>";
        }

        echo $options;
    }


}