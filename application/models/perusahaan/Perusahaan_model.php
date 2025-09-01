<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perusahaan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all perusahaan
    public function get_all_perusahaan() {
        $this->db->select('*');
        $this->db->from('perusahaan');
        $this->db->order_by('created_at','DESC');
        return $this->db->get()->result();
    }

    // Get perusahaan by id
    public function get_perusahaan_by_id($id) {
        $this->db->where('id_perusahaan', $id);
        return $this->db->get('perusahaan')->row();
    }

    // Get perusahaan aktif
    public function get_perusahaan_aktif() {
        $this->db->where('status_aktif', 1);
        $this->db->order_by('created_at','DESC');
        return $this->db->get('perusahaan')->result();
    }

    // Insert perusahaan
    public function insert_perusahaan($data) {
        $this->db->insert('perusahaan', $data);
        return $this->db->insert_id();
    }

    // Update perusahaan
    public function update_perusahaan($id, $data) {
        $this->db->where('id_perusahaan', $id);
        return $this->db->update('perusahaan', $data);
    }

    public function delete_perusahaan($id) {
        // Soft delete: update status menjadi tidak aktif
        $this->db->where('id_perusahaan', $id);
        return $this->db->update('perusahaan', ['status_aktif' => 0]);
    }

    public function aktifkan_perusahaan($id) {
        // Soft delete: update status menjadi tidak aktif
        $this->db->where('id_perusahaan', $id);
        return $this->db->update('perusahaan', ['status_aktif' => 1]);
    }

    
    // Get gudang by perusahaan
    public function get_gudang_by_perusahaan($id_perusahaan) {
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('status_aktif', 1);
        $this->db->order_by('created_at','DESC');
        return $this->db->get('gudang')->result();
    }

    // Get user by perusahaan
    public function get_user_by_perusahaan($id_perusahaan) {
        $this->db->select('u.*, r.nama_role, g.nama_gudang');
        $this->db->from('user u');
        $this->db->join('role_user r', 'u.id_role = r.id_role', 'left');
        $this->db->join('gudang g', 'u.id_gudang = g.id_gudang', 'left');
        $this->db->where('u.id_perusahaan', $id_perusahaan);
        $this->db->where('u.aktif', 1);
        $this->db->order_by('u.nama', 'ASC');
        return $this->db->get()->result();
    }

    // Get total barang by perusahaan
    public function get_total_barang_by_perusahaan($id_perusahaan) {
        $this->db->select('COUNT(*) as total');
        $this->db->from('barang');
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('aktif', 1);
        return $this->db->get()->row()->total;
    }

    // Get total stok by perusahaan
    public function get_total_stok_by_perusahaan($id_perusahaan) {
        $this->db->select('SUM(jumlah) as total');
        $this->db->from('stok_gudang sg');
        $this->db->join('gudang g', 'sg.id_gudang = g.id_gudang');
        $this->db->where('g.id_perusahaan', $id_perusahaan);
        return $this->db->get()->row()->total;
    }

    // Check if perusahaan has related data
    public function has_related_data($id_perusahaan) {
        // Check gudang
        $this->db->where('id_perusahaan', $id_perusahaan);
        $gudang = $this->db->get('gudang')->result();
        
        // Check user
        $this->db->where('id_perusahaan', $id_perusahaan);
        $user = $this->db->get('user')->result();
        
        // Check barang
        $this->db->where('id_perusahaan', $id_perusahaan);
        $barang = $this->db->get('barang')->result();
        
        return (count($gudang) > 0 || count($user) > 0 || count($barang) > 0);
    }

    // Get perusahaan statistics
    public function get_perusahaan_statistics($id_perusahaan) {
        $stats = array();
        
        // Total gudang
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('status_aktif', 1);
        $stats['total_gudang'] = $this->db->count_all_results('gudang');
        
        // Total user
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('aktif', 1);
        $stats['total_user'] = $this->db->count_all_results('user');
        
        // Total barang
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('aktif', 1);
        $stats['total_barang'] = $this->db->count_all_results('barang');
        
        // Total stok
        $this->db->select('SUM(jumlah) as total_stok');
        $this->db->from('stok_gudang sg');
        $this->db->join('gudang g', 'sg.id_gudang = g.id_gudang');
        $this->db->where('g.id_perusahaan', $id_perusahaan);
        $result = $this->db->get()->row();
        $stats['total_stok'] = $result->total_stok ? $result->total_stok : 0;
        
        return $stats;
    }

    // Update status perusahaan
    public function update_status($id, $status) {
        $this->db->where('id_perusahaan', $id);
        $data = [
            'status_aktif' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->update('perusahaan', $data);
    }
}