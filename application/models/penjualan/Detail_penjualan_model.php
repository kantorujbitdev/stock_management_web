<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail_penjualan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get detail by penjualan
    public function get_detail_by_penjualan($id_penjualan) {
        $this->db->select('detail_penjualan.*, barang.nama_barang, barang.sku, gudang.nama_gudang');
        $this->db->from('detail_penjualan');
        $this->db->join('barang', 'barang.id_barang = detail_penjualan.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = detail_penjualan.id_gudang');
        $this->db->where('detail_penjualan.id_penjualan', $id_penjualan);
        return $this->db->get()->result();
    }

    // Get detail by barang and penjualan
    public function get_detail_by_barang_penjualan($id_penjualan, $id_barang) {
        $this->db->where('id_penjualan', $id_penjualan);
        $this->db->where('id_barang', $id_barang);
        return $this->db->get('detail_penjualan')->row();
    }

    // Insert detail
    public function insert_detail($data) {
        return $this->db->insert('detail_penjualan', $data);
    }

    // Update detail
    public function update_detail($id, $data) {
        $this->db->where('id_detail', $id);
        return $this->db->update('detail_penjualan', $data);
    }

    // Delete detail
    public function delete_detail($id) {
        return $this->db->delete('detail_penjualan', array('id_detail' => $id));
    }
}