<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all supplier
    public function get_all_supplier() {
        return $this->db->get('supplier')->result();
    }

    // Get supplier by id
    public function get_supplier_by_id($id) {
        return $this->db->get_where('supplier', ['id_supplier' => $id])->row();
    }

    // Insert supplier
    public function insert_supplier($data) {
        return $this->db->insert('supplier', $data);
    }

    // Update supplier
    public function update_supplier($id, $data) {
        $this->db->where('id_supplier', $id);
        return $this->db->update('supplier', $data);
    }

    // Delete supplier
    public function delete_supplier($id) {
        // Cek apakah ada penerimaan barang yang terkait dengan supplier ini
        $this->db->where('id_supplier', $id);
        $check = $this->db->get('penerimaan_barang')->num_rows();
        
        if ($check > 0) {
            return false; // Tidak bisa dihapus karena ada penerimaan terkait
        }
        
        return $this->db->delete('supplier', ['id_supplier' => $id]);
    }
}