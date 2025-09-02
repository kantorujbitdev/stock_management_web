<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gudang_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all gudang
    public function get_all_gudang()
    {
        $this->db->select('g.*, p.nama_perusahaan, u.nama as created_by_name');
        $this->db->from('gudang g');
        $this->db->join('perusahaan p', 'g.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->join('user u', 'g.created_by = u.id_user', 'left');
        $this->db->order_by('g.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Get gudang by id
    public function get_gudang_by_id($id)
    {
        $this->db->select('g.*, p.nama_perusahaan');
        $this->db->from('gudang g');
        $this->db->join('perusahaan p', 'g.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->where('g.created_at', $id);
        return $this->db->get()->row();
    }

    // Get gudang by perusahaan
    public function get_gudang_by_perusahaan($id_perusahaan)
    {
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('status_aktif', 1);
        $this->db->order_by('nama_gudang', 'ASC');
        return $this->db->get('gudang')->result();
    }

    // Insert gudang
    public function insert_gudang($data)
    {
        $this->db->insert('gudang', $data);
        return $this->db->insert_id();
    }

    // Update gudang
    public function update_gudang($id, $data)
    {
        $this->db->where('id_gudang', $id);
        return $this->db->update('gudang', $data);
    }

    // Delete gudang    
    public function delete_gudang($id)
    {
        // Soft delete: update status menjadi tidak aktif
        $this->db->where('id_gudang', $id);
        return $this->db->update('gudang', ['status_aktif' => 0]);
    }

    // Update status gudang
    public function update_status($id)
    {
        // Soft delete: update status menjadi tidak aktif
        $this->db->where('id_gudang', $id);
        return $this->db->update('gudang', ['status_aktif' => 1]);
    }
}