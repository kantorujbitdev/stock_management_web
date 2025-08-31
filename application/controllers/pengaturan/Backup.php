<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('hak_akses');
        $this->load->dbutil');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('backup');
    }

    public function index() {
        $data['title'] = 'Backup Database';
        $data['backup_files'] = $this->get_backup_files();
        
        $data['content'] = 'pengaturan/backup';
        $this->load->view('template/template', $data);
    }

    public function create() {
        // Load the DB utility class
        $this->load->dbutil();
        
        // Backup your entire database and assign it to a variable
        $backup = $this->dbutil->backup();
        
        // Load the file helper and write the file to your server
        $this->load->helper('file');
        $file_name = 'backup_' . date('Y-m-d_H-i-s') . '.gz';
        write_file(FCPATH . 'backup/' . $file_name, $backup);
        
        $this->session->set_flashdata('success', 'Backup berhasil dibuat');
        redirect('pengaturan/backup');
    }

    public function download($file_name) {
        $this->load->helper('download');
        force_download(FCPATH . 'backup/' . $file_name, NULL);
    }

    public function delete($file_name) {
        if (unlink(FCPATH . 'backup/' . $file_name)) {
            $this->session->set_flashdata('success', 'Backup berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Backup gagal dihapus');
        }
        redirect('pengaturan/backup');
    }
    
    private function get_backup_files() {
        $this->load->helper('directory');
        $map = directory_map(FCPATH . 'backup/', 1);
        
        $files = [];
        foreach ($map as $file) {
            if (is_array($file)) {
                foreach ($file as $f) {
                    $files[] = $f;
                }
            } else {
                $files[] = $file;
            }
        }
        
        // Sort files by date (descending)
        rsort($files);
        
        return $files;
    }
}