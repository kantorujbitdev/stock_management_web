<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_awal_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all stok awal
    public function get_all_stok_awal() {
        $this->db->select('stok_awal.*, barang.nama_barang, barang.sku, gudang.nama_gudang, perusahaan.nama_perusahaan, user.nama as created_by_name');
        $this->db->from('stok_awal');
        $this->db->join('barang', 'barang.id_barang = stok_awal.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = stok_awal.id_gudang');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = stok_awal.id_perusahaan');
        $this->db->join('user', 'user.id_user = stok_awal.created_by');
        return $this->db->get()->result();
    }

    // Get stok awal by perusahaan
    public function get_stok_awal_by_perusahaan($id_perusahaan) {
        $this->db->select('stok_awal.*, barang.nama_barang, barang.sku, gudang.nama_gudang, perusahaan.nama_perusahaan, user.nama as created_by_name');
        $this->db->from('stok_awal');
        $this->db->join('barang', 'barang.id_barang = stok_awal.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = stok_awal.id_gudang');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = stok_awal.id_perusahaan');
        $this->db->join('user', 'user.id_user = stok_awal.created_by');
        $this->db->where('stok_awal.id_perusahaan', $id_perusahaan);
        return $this->db->get()->result();
    }

    // Get stok awal by id
    public function get_stok_awal_by_id($id) {
        $this->db->select('stok_awal.*, barang.nama_barang, barang.sku, gudang.nama_gudang, perusahaan.nama_perusahaan, user.nama as created_by_name');
        $this->db->from('stok_awal');
        $this->db->join('barang', 'barang.id_barang = stok_awal.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = stok_awal.id_gudang');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = stok_awal.id_perusahaan');
        $this->db->join('user', 'user.id_user = stok_awal.created_by');
        $this->db->where('stok_awal.id_stok_awal', $id);
        return $this->db->get()->row();
    }

    // Check if stok awal exists for barang and gudang
    public function check_stok_awal_exists($id_barang, $id_gudang, $id_stok_awal = null) {
        $this->db->where('id_barang', $id_barang);
        $this->db->where('id_gudang', $id_gudang);
        
        if ($id_stok_awal) {
            $this->db->where('id_stok_awal !=', $id_stok_awal);
        }
        
        $query = $this->db->get('stok_awal');
        return $query->num_rows() > 0;
    }

    // Insert stok awal
    public function insert_stok_awal($data) {
        return $this->db->insert('stok_awal', $data);
    }

    // Update stok awal
    public function update_stok_awal($id, $data) {
        $this->db->where('id_stok_awal', $id);
        return $this->db->update('stok_awal', $data);
    }

    // Delete stok awal
    public function delete_stok_awal($id) {
        return $this->db->delete('stok_awal', array('id_stok_awal' => $id));
    }
}