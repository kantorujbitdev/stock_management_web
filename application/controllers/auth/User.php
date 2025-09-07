<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('auth/User_model');
        $this->load->model('auth/Hak_akses_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        $this->hak_akses->cek_akses('user');
    }

    public function index()
    {
        $data['title'] = 'Manajemen User';
        $data['users'] = $this->User_model->get_all_users();
        $data['content'] = 'auth/user_list';
        $this->load->view('template/template', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah User';
        $data['roles'] = $this->User_model->get_roles();
        $data['perusahaan'] = $this->User_model->get_perusahaan();
        $data['content'] = 'auth/user_form';
        $this->load->view('template/template', $data);
    }

    public function add_process()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('id_role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
                'username' => $this->input->post('username'),
                'password_hash' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'id_role' => $this->input->post('id_role'),
                'id_perusahaan' => $this->input->post('id_perusahaan') ? $this->input->post('id_perusahaan') : NULL,
                'id_gudang' => $this->input->post('id_gudang') ? $this->input->post('id_gudang') : NULL,
                'created_by' => $this->session->userdata('id_user'),
                'aktif' => 1
            ];

            $this->User_model->insert_user($data);
            $this->session->set_flashdata('success', 'User berhasil ditambahkan');
            redirect('auth/user');
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit User';
        $data['user'] = $this->User_model->get_user_by_id($id);
        $data['roles'] = $this->User_model->get_roles();
        $data['perusahaan'] = $this->User_model->get_perusahaan();

        if ($data['user']->id_perusahaan) {
            $data['gudang'] = $this->User_model->get_gudang_by_perusahaan($data['user']->id_perusahaan);
        } else {
            $data['gudang'] = [];
        }

        $data['content'] = 'auth/user_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process()
    {
        $id = $this->input->post('id_user');

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|callback_check_username[' . $id . ']');
        $this->form_validation->set_rules('id_role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
                'username' => $this->input->post('username'),
                'id_role' => $this->input->post('id_role'),
                'id_perusahaan' => $this->input->post('id_perusahaan') ? $this->input->post('id_perusahaan') : NULL,
                'id_gudang' => $this->input->post('id_gudang') ? $this->input->post('id_gudang') : NULL
            ];

            // Jika password diisi
            if ($this->input->post('password')) {
                $data['password_hash'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }

            $this->User_model->update_user($id, $data);
            $this->session->set_flashdata('success', 'User berhasil diupdate');
            redirect('auth/user');
        }
    }

    public function check_username($username, $id)
    {
        $this->db->where('username', $username);
        $this->db->where('id_user !=', $id);
        $user = $this->db->get('user')->row();

        if ($user) {
            $this->form_validation->set_message('check_username', 'Username sudah digunakan');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function aktif($id)
    {
        // Cegah nonaktifkan user sendiri
        if ($id == $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Tidak dapat menonaktifkan user sendiri');
            redirect('auth/user');
        }

        if ($this->User_model->aktif_user($id)) {
            $this->session->set_flashdata('success', 'User berhasil dinonaktifkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menonaktifkan user');
        }
        redirect('auth/user');
    }

    public function hak_akses()
    {
        $data['title'] = 'Pengaturan Hak Akses';
        $data['roles'] = $this->User_model->get_roles();
        $data['fitur'] = $this->Hak_akses_model->get_all_fitur();
        $data['content'] = 'auth/hak_akses';
        $this->load->view('template/template', $data);
    }

    public function simpan_hak_akses()
    {
        $id_role = $this->input->post('id_role');
        $fitur = $this->input->post('fitur');

        // Hapus hak akses lama
        $this->Hak_akses_model->delete_hak_akses_by_role($id_role);

        // Insert hak akses baru
        foreach ($fitur as $nama_fitur => $akses) {
            $data = [
                'id_role' => $id_role,
                'nama_fitur' => $nama_fitur,
                'akses' => $akses
            ];
            $this->Hak_akses_model->insert_hak_akses($data);
        }

        $this->session->set_flashdata('success', 'Hak akses berhasil disimpan');
        redirect('auth/user/hak_akses');
    }

    public function get_gudang()
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        $gudang = $this->User_model->get_gudang_by_perusahaan($id_perusahaan);

        echo '<option value="">-- Pilih Gudang --</option>';
        foreach ($gudang as $row) {
            echo '<option value="' . $row->id_gudang . '">' . $row->nama_gudang . '</option>';
        }
    }

    public function get_hak_akses()
    {
        $id_role = $this->input->post('id_role');
        $hak_akses = $this->Hak_akses_model->get_hak_akses_by_role($id_role);

        echo json_encode($hak_akses);
    }
}