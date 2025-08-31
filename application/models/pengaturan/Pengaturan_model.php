<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all pengaturan
    public function get_all_pengaturan() {
        return $this->db->get('pengaturan_sistem')->result();
    }

    // Get pengaturan by key
    public function get_pengaturan_by_key($key) {
        return $this->db->get_where('pengaturan_sistem', ['key' => $key])->row();
    }

    // Insert pengaturan
    public function insert_pengaturan($data) {
        return $this->db->insert('pengaturan_sistem', $data);
    }

    // Update pengaturan
    public function update_pengaturan($id, $data) {
        $this->db->where('id_pengaturan', $id);
        return $this->db->update('pengaturan_sistem', $data);
    }

    // Delete pengaturan
    public function delete_pengaturan($id) {
        return $this->db->delete('pengaturan_sistem', ['id_pengaturan' => $id]);
    }
}