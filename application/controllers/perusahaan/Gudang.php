<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gudang extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('perusahaan/Gudang_model');
        $this->load->model('perusahaan/Perusahaan_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('gudang');
    }

    public function index() {
        $data['title'] = 'Data Gudang';
        
        // Jika Super Admin, tampilkan semua gudang
        if ($this->session->userdata('id_role') == 5) {
            $data['gudang'] = $this->Gudang_model->get_all_gudang();
        } else {
            // Jika Admin Pusat, tampilkan gudang milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['gudang'] = $this->Gudang_model->get_gudang_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'perusahaan/gudang_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Gudang';
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['content'] = 'perusahaan/gudang_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('nama_gudang', 'Nama Gudang', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_perusahaan_input = $this->input->post('id_perusahaan');
                
                if ($id_perusahaan_user != $id_perusahaan_input) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                    redirect('gudang');
                }
            }
            
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'nama_gudang' => $this->input->post('nama_gudang'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon'),
                'created_by' => $this->session->userdata('id_user'),
                'status_aktif' => 1
            ];

            $this->Gudang_model->insert_gudang($data);
            $this->session->set_flashdata('success', 'Gudang berhasil ditambahkan');
            redirect('gudang');
        }
    }

    public function edit($id) {
        // Cek apakah user punya akses ke gudang ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $gudang = $this->Gudang_model->get_gudang_by_id($id);
            
            if ($gudang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke gudang ini');
                redirect('gudang');
            }
        }
        
        $data['title'] = 'Edit Gudang';
        $data['gudang'] = $this->Gudang_model->get_gudang_by_id($id);
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['content'] = 'perusahaan/gudang_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process() {
        $id = $this->input->post('id_gudang');
        
        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $id_perusahaan_input = $this->input->post('id_perusahaan');
            
            if ($id_perusahaan_user != $id_perusahaan_input) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                redirect('gudang');
            }
        }
        
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('nama_gudang', 'Nama Gudang', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'nama_gudang' => $this->input->post('nama_gudang'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon')
            ];

            $this->Gudang_model->update_gudang($id, $data);
            $this->session->set_flashdata('success', 'Gudang berhasil diupdate');
            redirect('gudang');
        }
    }

    public function nonaktif($id) {
        // Cek apakah user punya akses ke gudang ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $gudang = $this->Gudang_model->get_gudang_by_id($id);
            
            if ($gudang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke gudang ini');
                redirect('gudang');
            }
        }
        
        if ($this->Gudang_model->delete_gudang($id)) {
            $this->session->set_flashdata('success', 'Gudang berhasil dinonaktifkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menonaktifkan gudang');
        }
        redirect('gudang');
    }

    public function aktif($id) {
        // Cek apakah user punya akses ke gudang ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $gudang = $this->Gudang_model->get_gudang_by_id($id);
            
            if ($gudang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke gudang ini');
                redirect('gudang');
            }
        }
        
        if ($this->Gudang_model->update_status($id)) {
            $this->session->set_flashdata('success', 'Gudang berhasil diaktifkan kembali');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan gudang');
        }
        redirect('gudang');
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