<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('auth/User_model');
    }

    public function index()
    {
        // Cek session dengan cara yang lebih aman
        $logged_in = $this->session->userdata('logged_in');

        if ($logged_in === TRUE) {
            redirect('dashboard');
        }

        // Tampilkan form login
        $this->load->view('auth/login');
    }

    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User_model->get_user($username);
            $role = $this->db->get_where('role_user', ['id_role' => $user->id_role])->row();

            // Ambil nama perusahaan kalau ada (kecuali super user)
            $nama_perusahaan = null;
            if ($user->id_role != 5 && $user->id_perusahaan !== null) {
                $perusahaan = $this->db->get_where('perusahaan', ['id_perusahaan' => $user->id_perusahaan])->row();
                $nama_perusahaan = $perusahaan ? $perusahaan->nama_perusahaan : null;
            }

            // 👉 Kalau nama_perusahaan kosong atau NULL → isi "Super User"
            if (empty($nama_perusahaan)) {
                $nama_perusahaan = "Super Admin";
            }

            if ($user && password_verify($password, $user->password_hash)) {
                if ($user->aktif == 1) {
                    $session_data = [
                        'id_user' => $user->id_user,
                        'nama' => $user->nama,
                        'username' => $user->username,
                        'id_role' => $user->id_role,
                        'nama_role' => $role ? $role->nama_role : null,
                        'id_perusahaan' => $user->id_perusahaan,
                        'nama_perusahaan' => $nama_perusahaan,
                        'id_gudang' => $user->id_gudang,
                        'logged_in' => TRUE
                    ];
                    $this->session->set_userdata($session_data);

                    $this->User_model->update_last_login($user->id_user);

                    if ($this->session->userdata('id_role') == 2) {
                        redirect('penjualan/add');
                    } else if ($this->session->userdata('id_role') == 3) {
                        redirect('penjualan');
                    } else if ($this->session->userdata('id_role') == 4) {
                        redirect('return');
                    } else {
                        redirect('dashboard');
                    }

                } else {
                    $this->session->set_flashdata('error', 'Akun Anda tidak aktif');
                    $this->index();
                }
            } else {
                $this->session->set_flashdata('error', 'Username atau password salah');
                $this->index();
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }
}