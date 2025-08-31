<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all penjualan
    public function get_all_penjualan() {
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->order_by('penjualan.tanggal_penjualan', 'DESC');
        return $this->db->get()->result();
    }

    // Get penjualan by perusahaan
    public function get_penjualan_by_perusahaan($id_perusahaan) {
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
        $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        $this->db->order_by('penjualan.tanggal_penjualan', 'DESC');
        return $this->db->get()->result();
    }

    // Get penjualan by id
    public function get_penjualan_by_id($id) {
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->where('penjualan.id_penjualan', $id);
        return $this->db->get()->row();
    }

    // Get next number for penjualan
    public function get_next_number($id_perusahaan) {
        $this->db->select('COUNT(*) as total');
        $this->db->from('penjualan');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
        $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        $this->db->where('DATE(penjualan.created_at)', date('Y-m-d'));
        $query = $this->db->get();
        $result = $query->row();
        
        return str_pad($result->total + 1, 3, '0', STR_PAD_LEFT);
    }

    // Insert penjualan
    public function insert_penjualan($data) {
        return $this->db->insert('penjualan', $data);
    }

    // Update penjualan
    public function update_penjualan($id, $data) {
        $this->db->where('id_penjualan', $id);
        return $this->db->update('penjualan', $data);
    }

    // Delete penjualan
    public function delete_penjualan($id) {
        return $this->db->delete('penjualan', array('id_penjualan' => $id));
    }
}