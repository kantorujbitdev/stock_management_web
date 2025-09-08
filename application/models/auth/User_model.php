<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_user($username)
    {
        $this->db->select('u.*, r.nama_role');
        $this->db->from('user u');
        $this->db->join('role_user r', 'u.id_role = r.id_role');
        $this->db->where('u.username', $username);
        return $this->db->get()->row();
    }

    public function get_all_users()
    {
        $myUser = $this->session->userdata('username');
        $this->db->select('u.*, r.nama_role, p.nama_perusahaan, g.nama_gudang');
        $this->db->from('user u');
        $this->db->join('role_user r', 'u.id_role = r.id_role', 'left');
        $this->db->join('perusahaan p', 'u.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->join('gudang g', 'u.id_gudang = g.id_gudang', 'left');
        if ($this->session->userdata('id_role') == 1) {
            $this->db->where('u.id_perusahaan', $this->session->userdata('id_perusahaan'));
        }
        // $this->db->where('username !=', $myUser);
        $this->db->order_by('u.id_user', 'DESC');
        return $this->db->get()->result();
    }

    public function get_user_by_id($id)
    {
        $this->db->select('u.*, r.nama_role, p.nama_perusahaan, g.nama_gudang');
        $this->db->from('user u');
        $this->db->join('role_user r', 'u.id_role = r.id_role', 'left');
        $this->db->join('perusahaan p', 'u.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->join('gudang g', 'u.id_gudang = g.id_gudang', 'left');
        $this->db->where('u.id_user', $id);
        return $this->db->get()->row();
    }

    public function insert_user($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    public function update_user($id, $data)
    {
        $this->db->where('id_user', $id);
        return $this->db->update('user', $data);
    }

    public function aktif_user($id)
    {
        // Soft delete: update status menjadi tidak aktif
        $this->db->where('id_user', $id);
        return $this->db->update('user', ['aktif' => 1]);
    }

    public function update_last_login($id)
    {
        $this->db->where('id_user', $id);
        return $this->db->update('user', ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function get_roles()
    {
        return $this->db->get('role_user')->result();
    }

    public function get_perusahaan()
    {
        $this->db->where('status_aktif', 1);
        return $this->db->get('perusahaan')->result();
    }

    public function get_gudang_by_perusahaan($id_perusahaan)
    {
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('status_aktif', 1);
        return $this->db->get('gudang')->result();
    }
}