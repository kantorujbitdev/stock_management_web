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
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('pelanggan');
    }

    public function index() {
        $data['title'] = 'Data Pelanggan';
        $data['pelanggan'] = $this->Pelanggan_model->get_all_pelanggan();
        
        $data['content'] = 'master/pelanggan_list';
        $this->load->view('template/template', $data);
    }

    public function add() {
        $data['title'] = 'Tambah Pelanggan';
        $data['content'] = 'master/pelanggan_form';
        $this->load->view('template/template', $data);
    }

    public function add_process() {
        $this->form_validation->set_rules('nama_pelanggan', 'Nama Pelanggan', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = [
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
        $data['title'] = 'Edit Pelanggan';
        $data['pelanggan'] = $this->Pelanggan_model->get_pelanggan_by_id($id);
        
        if (empty($data['pelanggan'])) {
            show_404();
        }
        
        $data['content'] = 'master/pelanggan_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process() {
        $id = $this->input->post('id_pelanggan');
        
        $this->form_validation->set_rules('nama_pelanggan', 'Nama Pelanggan', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('telepon', 'Telepon', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'nama_pelanggan' => $this->input->post('nama_pelanggan'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon')
            ];

            $this->Pelanggan_model->update_pelanggan($id, $data);
            $this->session->set_flashdata('success', 'Pelanggan berhasil diupdate');
            redirect('pelanggan');
        }
    }

    public function delete($id) {
        if ($this->Pelanggan_model->delete_pelanggan($id)) {
            $this->session->set_flashdata('success', 'Pelanggan berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pelanggan. Mungkin masih ada penjualan yang terkait.');
        }
        redirect('pelanggan');
    }
}