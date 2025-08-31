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
        $this->db->where('kategori.status_aktif', 1);
        return $this->db->get()->result();
    }

    // Get kategori by perusahaan
    public function get_kategori_by_perusahaan($id_perusahaan) {
        $this->db->select('kategori.*, perusahaan.nama_perusahaan');
        $this->db->from('kategori');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = kategori.id_perusahaan');
        $this->db->where('kategori.id_perusahaan', $id_perusahaan);
        $this->db->where('kategori.status_aktif', 1);
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

    // Check if kategori name exists for specific company
    public function check_kategori_exists($nama_kategori, $id_perusahaan, $id_kategori = null) {
        $this->db->where('nama_kategori', $nama_kategori);
        $this->db->where('id_perusahaan', $id_perusahaan);
        
        if ($id_kategori) {
            $this->db->where('id_kategori !=', $id_kategori);
        }
        
        $query = $this->db->get('kategori');
        return $query->num_rows() > 0;
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

    // Soft delete kategori (ubah status aktif)
    public function delete_kategori($id) {
        $this->db->where('id_kategori', $id);
        return $this->db->update('kategori', ['status_aktif' => 0]);
    }

    // Restore kategori (ubah status aktif)
    public function restore_kategori($id) {
        $this->db->where('id_kategori', $id);
        return $this->db->update('kategori', ['status_aktif' => 1]);
    }

    // Get all kategori including inactive (for Super Admin)
    public function get_all_kategori_with_inactive() {
        $this->db->select('kategori.*, perusahaan.nama_perusahaan');
        $this->db->from('kategori');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = kategori.id_perusahaan');
        return $this->db->get()->result();
    }

    // Get perusahaan list for dropdown
    public function get_perusahaan_list() {
        return $this->db->get('perusahaan')->result();
    }
}