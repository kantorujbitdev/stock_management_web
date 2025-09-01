<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('master/Kategori_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('kategori');
    }

    public function index() {
        $data['title'] = 'Data Kategori';
        
        // Jika Super Admin, tampilkan semua kategori
        if ($this->session->userdata('id_role') == 5) {
            $data['kategori'] = $this->Kategori_model->get_all_kategori();
        } else {
            // Jika Admin Pusat, tampilkan kategori milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'master/kategori_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Kategori';
        
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
        
        $data['content'] = 'master/kategori_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_perusahaan_input = $this->input->post('id_perusahaan');
                
                if ($id_perusahaan_user != $id_perusahaan_input) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                    redirect('kategori');
                }
            }
            
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'nama_kategori' => $this->input->post('nama_kategori'),
                'deskripsi' => $this->input->post('deskripsi')
            ];

            $this->Kategori_model->insert_kategori($data);
            $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan');
            redirect('kategori');
        }
    }

    public function edit($id) {
        // Cek apakah user punya akses ke kategori ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $kategori = $this->Kategori_model->get_kategori_by_id($id);
            
            if ($kategori->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kategori ini');
                redirect('kategori');
            }
        }
        
        $data['title'] = 'Edit Kategori';
        $data['kategori'] = $this->Kategori_model->get_kategori_by_id($id);
        
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
        
        $data['content'] = 'master/kategori_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process() {
        $id = $this->input->post('id_kategori');
        
        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $id_perusahaan_input = $this->input->post('id_perusahaan');
            
            if ($id_perusahaan_user != $id_perusahaan_input) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                redirect('kategori');
            }
        }
        
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'nama_kategori' => $this->input->post('nama_kategori'),
                'deskripsi' => $this->input->post('deskripsi')
            ];

            $this->Kategori_model->update_kategori($id, $data);
            $this->session->set_flashdata('success', 'Kategori berhasil diupdate');
            redirect('kategori');
        }
    }

    public function nonaktif($id) {
        // Cek apakah user punya akses ke kategori ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $kategori = $this->Kategori_model->get_kategori_by_id($id);
            
            if ($kategori->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kategori ini');
                redirect('kategori');
            }
        }
        
        if ($this->Kategori_model->delete_kategori($id)) {
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kategori. Mungkin masih ada kategori yang terkait.');
        }
        redirect('kategori');
    }

    public function aktif($id) {
        // Cek apakah user punya akses ke kategori ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $kategori = $this->Kategori_model->get_kategori_by_id($id);
            
            if ($kategori->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke kategori ini');
                redirect('kategori');
            }
        }
        
        if ($this->Kategori_model->restore_kategori($id)) {
            $this->session->set_flashdata('success', 'Kategori berhasil diaktifkan kembali');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan kategori. Mungkin masih ada kategori yang terkait.');
        }
        redirect('kategori');
    }
}