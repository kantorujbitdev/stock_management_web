<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sistem extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('pengaturan/Pengaturan_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('pengaturan_sistem');
    }

    public function index() {
        $data['title'] = 'Pengaturan Sistem';
        $data['pengaturan'] = $this->Pengaturan_model->get_all_pengaturan();
        
        $data['content'] = 'pengaturan/sistem';
        $this->load->view('template/template', $data);
    }

    public function update() {
        $this->form_validation->set_rules('pengaturan', 'Pengaturan', 'required');
        $this->form_validation->set_rules('value', 'Nilai', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Data tidak valid');
            redirect('pengaturan/sistem');
        } else {
            $pengaturan = $this->input->post('pengaturan');
            $value = $this->input->post('value');
            
            // Cek apakah pengaturan sudah ada
            $check = $this->Pengaturan_model->get_pengaturan_by_key($pengaturan);
            
            if ($check) {
                // Update pengaturan
                $this->Pengaturan_model->update_pengaturan($check->id_pengaturan, ['value' => $value]);
            } else {
                // Insert pengaturan baru
                $data = [
                    'key' => $pengaturan,
                    'value' => $value,
                    'keterangan' => 'Ditambahkan melalui form pengaturan sistem'
                ];
                
                $this->Pengaturan_model->insert_pengaturan($data);
            }
            
            $this->session->set_flashdata('success', 'Pengaturan berhasil diperbarui');
            redirect('pengaturan/sistem');
        }
    }
}