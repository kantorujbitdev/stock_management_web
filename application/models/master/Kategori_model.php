<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all kategori
    public function get_all_kategori() {
        $this->db->select('kategori.*, perusahaan.nama_perusahaan');
        $this->db->from('kategori');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = kategori.id_perusahaan');
        return $this->db->get()->result();
    }

    // Get kategori by perusahaan
    public function get_kategori_by_perusahaan($id_perusahaan) {
        $this->db->select('kategori.*, perusahaan.nama_perusahaan');
        $this->db->from('kategori');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = kategori.id_perusahaan');
        $this->db->where('kategori.id_perusahaan', $id_perusahaan);
        return $this->db->get()->result();
    }

    // Get kategori by id
    public function get_kategori_by_id($id) {
        $this->db->select('kategori.*, perusahaan.nama_perusahaan');
        $this->db->from('kategori');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = kategori.id_perusahaan');
        $this->db->where('kategori.id_kategori', $id);
        return $this->db->get()->row();
    }

    // Insert kategori
    public function insert_kategori($data) {
        return $this->db->insert('kategori', $data);
    }

    // Update kategori
    public function update_kategori($id, $data) {
        $this->db->where('id_kategori', $id);
        return $this->db->update('kategori', $data);
    }

    // Delete kategori
    public function delete_kategori($id) {
        // Cek apakah ada barang yang terkait dengan kategori ini
        $this->db->where('id_kategori', $id);
        $check = $this->db->get('barang')->num_rows();
        
        if ($check > 0) {
            return false; // Tidak bisa dihapus karena ada barang terkait
        }
        
        return $this->db->delete('kategori', array('id_kategori' => $id));
    }
}