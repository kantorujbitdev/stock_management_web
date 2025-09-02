<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Backup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['auth', 'hak_akses']);
        $this->require_login();
        // pastikan hanya Super Admin
        if (!$this->hak_akses->is_super_admin()) {
            show_error('Forbidden', 403);
        }
    }

    public function create()
    {
        $this->load->dbutil();
        $prefs = array(
            'format' => 'sql',
            'add_drop' => TRUE,
            'add_insert' => TRUE,
            'newline' => "\n"
        );
        $backup = $this->dbutil->backup($prefs);
        $filename = 'db-backup-' . date('Ymd-His') . '.sql';
        $save_path = APPPATH . 'backups/' . $filename;

        // simpan file di application/backups
        if (!is_dir(APPPATH . 'backups/'))
            mkdir(APPPATH . 'backups/', 0750, true);
        if (file_put_contents($save_path, $backup) !== false) {
            $this->session->set_flashdata('success', 'Backup berhasil dibuat: ' . $filename);
        } else {
            $this->session->set_flashdata('error', 'Gagal membuat backup.');
        }
        redirect('pengaturan/backup');
    }

    public function download($file_name)
    {
        // only allow alphanumeric/dash/underscore in file name
        if (!preg_match('/^[0-9A-Za-z_\-\.]+$/', $file_name))
            show_error('Invalid filename', 400);
        $path = APPPATH . 'backups/' . $file_name;
        if (!file_exists($path))
            show_404();
        // hanya Super Admin bisa download (cek di constructor)
        $this->load->helper('download');
        force_download($path, NULL);
    }

    public function delete($file_name)
    {
        $path = APPPATH . 'backups/' . $file_name;
        if (file_exists($path) && unlink($path)) {
            $this->session->set_flashdata('success', 'Backup berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Backup gagal dihapus');
        }
        redirect('pengaturan/backup');
    }
}
