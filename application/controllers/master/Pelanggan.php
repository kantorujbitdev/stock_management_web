<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('master/Pelanggan_model');
        $this->load->model('perusahaan/Perusahaan_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('pelanggan');
    }

    public function index() {
        $data['title'] = 'Data Pelanggan';
        
        // Jika Super Admin, tampilkan semua pelanggan
        if ($this->session->userdata('id_role') == 5) {
            $data['pelanggan'] = $this->Pelanggan_model->get_all_pelanggan();
        } else {
            // Jika Admin Pusat, tampilkan pelanggan milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['pelanggan'] = $this->Pelanggan_model->get_pelanggan_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'master/pelanggan_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Pelanggan';
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['content'] = 'master/pelanggan_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('nama_pelanggan', 'Nama Pelanggan', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_perusahaan_input = $this->input->post('id_perusahaan');
                
                if ($id_perusahaan_user != $id_perusahaan_input) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                    redirect('pelanggan');
                }
            }
            
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'nama_pelanggan' => $this->input->post('nama_pelanggan'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon')
            ];
            
            $this->Pelanggan_model->insert_pelanggan($data);
            $this->session->set_flashdata('success', 'Pelanggan berhasil ditambahkan');
            redirect('pelanggan');
        }
    }

    public function edit($id) {
        // Cek apakah user punya akses ke pelanggan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $pelanggan = $this->Pelanggan_model->get_pelanggan_by_id($id);
            
            if ($pelanggan->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke pelanggan ini');
                redirect('pelanggan');
            }
        }
        
        $data['title'] = 'Edit Pelanggan';
        $data['pelanggan'] = $this->Pelanggan_model->get_pelanggan_by_id($id);
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['content'] = 'master/pelanggan_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process() {
        $id = $this->input->post('id_pelanggan');
        
        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $id_perusahaan_input = $this->input->post('id_perusahaan');
            
            if ($id_perusahaan_user != $id_perusahaan_input) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                redirect('pelanggan');
            }
        }
        
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('nama_pelanggan', 'Nama Pelanggan', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'nama_pelanggan' => $this->input->post('nama_pelanggan'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon')
            ];
            
            $this->Pelanggan_model->update_pelanggan($id, $data);
            $this->session->set_flashdata('success', 'Pelanggan berhasil diupdate');
            redirect('pelanggan');
        }
    }

    public function nonaktif($id) {
        // Cek apakah user punya akses ke pelanggan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $pelanggan = $this->Pelanggan_model->get_pelanggan_by_id($id);
            
            if ($pelanggan->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke pelanggan ini');
                redirect('pelanggan');
            }
        }
        
        if ($this->Pelanggan_model->delete_pelanggan($id)) {
            $this->session->set_flashdata('success', 'Pelanggan berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pelanggan');
        }
        redirect('pelanggan');
    }

    public function aktif($id) {
        // Cek apakah user punya akses ke pelanggan ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $pelanggan = $this->Pelanggan_model->get_pelanggan_by_id($id);
            
            if ($pelanggan->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke pelanggan ini');
                redirect('pelanggan');
            }
        }
        
        if ($this->Pelanggan_model->restore_pelanggan($id)) {
            $this->session->set_flashdata('success', 'Pelanggan berhasil diaktifkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan pelanggan');
        }
        redirect('pelanggan');
    }

    public function get_pelanggan_by_perusahaan() {
        $id_perusahaan = $this->input->post('id_perusahaan');
        $pelanggan = $this->Pelanggan_model->get_pelanggan_by_perusahaan($id_perusahaan);
        
        echo '<option value="">-- Pilih Pelanggan --</option>';
        foreach ($pelanggan as $row) {
            echo '<option value="'.$row->id_pelanggan.'">'.$row->nama_pelanggan.'</option>';
        }
    }
}