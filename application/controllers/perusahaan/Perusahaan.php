<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perusahaan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('perusahaan/Perusahaan_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        $this->hak_akses->cek_akses('perusahaan');
    }

    public function index()
    {
        $data['title'] = 'Data Perusahaan';

        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_all_perusahaan();
        } else {
            // Jika bukan Super Admin, redirect ke halaman tidak berwenang
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }

        $data['content'] = 'perusahaan/perusahaan_list';
        $this->load->view('template/template', $data);
    }

    public function add()
    {
        // Hanya Super Admin yang bisa menambah perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }

        $data['title'] = 'Tambah Perusahaan';
        $data['content'] = 'perusahaan/perusahaan_form';
        $this->load->view('template/template', $data);
    }

    public function add_process()
    {
        // Hanya Super Admin yang bisa menambah perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }

        $this->form_validation->set_rules('nama_perusahaan', 'Nama Perusahaan', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = [
                'nama_perusahaan' => $this->input->post('nama_perusahaan'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon'),
                'status_aktif' => 1 // Default aktif
            ];

            $this->Perusahaan_model->insert_perusahaan($data);
            $this->session->set_flashdata('success', 'Perusahaan berhasil ditambahkan');
            redirect('perusahaan');
        }
    }

    public function edit($id)
    {
        // Hanya Super Admin yang bisa mengedit perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }

        $data['title'] = 'Edit Perusahaan';
        $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_by_id($id);

        if (!$data['perusahaan']) {
            show_404();
        }

        $data['content'] = 'perusahaan/perusahaan_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process()
    {
        // Hanya Super Admin yang bisa mengedit perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }

        $id = $this->input->post('id_perusahaan');

        $this->form_validation->set_rules('nama_perusahaan', 'Nama Perusahaan', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'nama_perusahaan' => $this->input->post('nama_perusahaan'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->Perusahaan_model->update_perusahaan($id, $data);
            $this->session->set_flashdata('success', 'Perusahaan berhasil diupdate');
            redirect('perusahaan');
        }
    }

    public function nonaktif($id)
    {
        // Hanya Super Admin yang bisa menonaktifkan perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }

        if ($this->Perusahaan_model->update_status($id, 0)) {
            $this->session->set_flashdata('success', 'Perusahaan berhasil dinonaktifkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menonaktifkan perusahaan');
        }
        redirect('perusahaan');
    }

    public function aktif($id)
    {
        // Hanya Super Admin yang bisa mengaktifkan perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }

        if ($this->Perusahaan_model->update_status($id, 1)) {
            $this->session->set_flashdata('success', 'Perusahaan berhasil diaktifkan kembali');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan perusahaan');
        }
        redirect('perusahaan');
    }
}