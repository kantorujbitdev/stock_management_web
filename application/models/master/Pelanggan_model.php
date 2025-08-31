<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelanggan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all pelanggan
    public function get_all_pelanggan() {
        return $this->db->get('pelanggan')->result();
    }

    // Get pelanggan by id
    public function get_pelanggan_by_id($id) {
        return $this->db->get_where('pelanggan', ['id_pelanggan' => $id])->row();
    }

    // Insert pelanggan
    public function insert_pelanggan($data) {
        return $this->db->insert('pelanggan', $data);
    }

    // Update pelanggan
    public function update_pelanggan($id, $data) {
        $this->db->where('id_pelanggan', $id);
        return $this->db->update('pelanggan', $data);
    }

    // Delete pelanggan
    public function delete_pelanggan($id) {
        // Cek apakah ada penjualan yang terkait dengan pelanggan ini
        $this->db->where('id_pelanggan', $id);
        $check = $this->db->get('penjualan')->num_rows();
        
        if ($check > 0) {
            return false; // Tidak bisa dihapus karena ada penjualan terkait
        }
        
        return $this->db->delete('pelanggan', ['id_pelanggan' => $id]);
    }
}