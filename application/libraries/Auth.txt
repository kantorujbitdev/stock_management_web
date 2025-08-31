<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {
    protected $ci;
    
    public function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->model('auth/User_model');
    }
    
    // Fungsi login
    public function login($username, $password) {
        $user = $this->ci->User_model->get_user($username);
        
        if ($user && password_verify($password, $user->password_hash)) {
            if ($user->aktif == 1) {
                $session_data = [
                    'id_user' => $user->id_user,
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'id_role' => $user->id_role,
                    'id_perusahaan' => $user->id_perusahaan,
                    'id_gudang' => $user->id_gudang,
                    'logged_in' => TRUE
                ];
                $this->ci->session->set_userdata($session_data);
                
                // Update last login
                $this->ci->User_model->update_last_login($user->id_user);
                
                return TRUE;
            } else {
                $this->ci->session->set_flashdata('error', 'Akun Anda tidak aktif');
                return FALSE;
            }
        } else {
            $this->ci->session->set_flashdata('error', 'Username atau password salah');
            return FALSE;
        }
    }
    
    // Cek login
    public function is_logged_in() {
        return $this->ci->session->userdata('logged_in');
    }
    
    // Logout
    public function logout() {
        $this->ci->session->sess_destroy();
        redirect('auth');
    }
    
    // Cek hak akses
    public function check_access($fitur) {
        if (!$this->is_logged_in()) {
            redirect('auth');
        }
        
        $id_role = $this->ci->session->userdata('id_role');
        
        // Super Admin memiliki akses penuh
        if ($id_role == 5) {
            return TRUE;
        }
        
        $this->ci->load->model('auth/Hak_akses_model');
        $akses = $this->ci->Hak_akses_model->get_akses($id_role, $fitur);
        
        if ($akses && $akses->akses == 1) {
            return TRUE;
        } else {
            $this->ci->session->set_flashdata('error', 'Anda tidak memiliki akses ke fitur ini');
            redirect('dashboard');
        }
    }
}