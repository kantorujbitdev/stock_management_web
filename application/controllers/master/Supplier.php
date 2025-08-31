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
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('supplier');
    }

    public function index() {
        $data['title'] = 'Data Supplier';
        $data['supplier'] = $this->Supplier_model->get_all_supplier();
        
        $data['content'] = 'master/supplier_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Supplier';
        $data['content'] = 'master/supplier_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = [
                'nama_supplier' => $this->input->post('nama_supplier'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon'),
                'status_aktif' => 1
            ];

            $this->Supplier_model->insert_supplier($data);
            $this->session->set_flashdata('success', 'Supplier berhasil ditambahkan');
            redirect('supplier');
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit Supplier';
        $data['supplier'] = $this->Supplier_model->get_supplier_by_id($id);
        
        if (empty($data['supplier'])) {
            show_404();
        }
        
        $data['content'] = 'master/supplier_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process() {
        $id = $this->input->post('id_supplier');
        
        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'nama_supplier' => $this->input->post('nama_supplier'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon')
            ];

            $this->Supplier_model->update_supplier($id, $data);
            $this->session->set_flashdata('success', 'Supplier berhasil diupdate');
            redirect('supplier');
        }
    }

    public function delete($id) {
        if ($this->Supplier_model->delete_supplier($id)) {
            $this->session->set_flashdata('success', 'Supplier berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus supplier. Mungkin masih ada penerimaan barang yang terkait.');
        }
        redirect('supplier');
    }
}