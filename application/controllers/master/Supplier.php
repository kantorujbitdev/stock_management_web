<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('master/Supplier_model');
        $this->load->model('perusahaan/Perusahaan_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('supplier');
    }

    public function index() {
        $data['title'] = 'Data Supplier';
        
        // Jika Super Admin, tampilkan semua supplier
        if ($this->session->userdata('id_role') == 5) {
            $data['supplier'] = $this->Supplier_model->get_all_supplier();
        } else {
            // Jika Admin Pusat, tampilkan supplier milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['supplier'] = $this->Supplier_model->get_supplier_by_perusahaan($id_perusahaan);
        }
        
        $data['content'] = 'master/supplier_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Supplier';
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['content'] = 'master/supplier_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required');
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
                    redirect('supplier');
                }
            }
            
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'nama_supplier' => $this->input->post('nama_supplier'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon'),
                'status_aktif' => 1 // Default aktif
            ];
            
            $this->Supplier_model->insert_supplier($data);
            $this->session->set_flashdata('success', 'Supplier berhasil ditambahkan');
            redirect('supplier');
        }
    }

    public function edit($id) {
        // Cek apakah user punya akses ke supplier ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $supplier = $this->Supplier_model->get_supplier_by_id($id);
            
            if ($supplier->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke supplier ini');
                redirect('supplier');
            }
        }
        
        $data['title'] = 'Edit Supplier';
        $data['supplier'] = $this->Supplier_model->get_supplier_by_id($id);
        
        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }
        
        $data['content'] = 'master/supplier_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process() {
        $id = $this->input->post('id_supplier');
        
        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $id_perusahaan_input = $this->input->post('id_perusahaan');
            
            if ($id_perusahaan_user != $id_perusahaan_input) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                redirect('supplier');
            }
        }
        
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'nama_supplier' => $this->input->post('nama_supplier'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon'),
                'status_aktif' => $this->input->post('status_aktif')
            ];
            
            $this->Supplier_model->update_supplier($id, $data);
            $this->session->set_flashdata('success', 'Supplier berhasil diupdate');
            redirect('supplier');
        }
    }

    public function nonaktif($id) {
        // Cek apakah user punya akses ke supplier ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $supplier = $this->Supplier_model->get_supplier_by_id($id);
            
            if ($supplier->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke supplier ini');
                redirect('supplier');
            }
        }
        
        if ($this->Supplier_model->update_status($id, 0)) {
            $this->session->set_flashdata('success', 'Supplier berhasil dinonaktifkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menonaktifkan supplier');
        }
        redirect('supplier');
    }

    public function aktif($id) {
        // Cek apakah user punya akses ke supplier ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $supplier = $this->Supplier_model->get_supplier_by_id($id);
            
            if ($supplier->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke supplier ini');
                redirect('supplier');
            }
        }
        
        if ($this->Supplier_model->update_status($id, 1)) {
            $this->session->set_flashdata('success', 'Supplier berhasil diaktifkan kembali');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan supplier');
        }
        redirect('supplier');
    }

    public function get_supplier_by_perusahaan() {
        $id_perusahaan = $this->input->post('id_perusahaan');
        $supplier = $this->Supplier_model->get_supplier_by_perusahaan($id_perusahaan);
        
        echo '<option value="">-- Pilih Supplier --</option>';
        foreach ($supplier as $row) {
            echo '<option value="'.$row->id_supplier.'">'.$row->nama_supplier.'</option>';
        }
    }
}