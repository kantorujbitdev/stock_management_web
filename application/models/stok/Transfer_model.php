<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all transfer
    public function get_all_transfer() {
        $this->db->select('transfer_stok.*, barang.nama_barang, barang.sku, 
                          gudang_asal.nama_gudang as gudang_asal, 
                          gudang_tujuan.nama_gudang as gudang_tujuan,
                          user.nama as created_by');
        $this->db->from('transfer_stok');
        $this->db->join('barang', 'barang.id_barang = transfer_stok.id_barang');
        $this->db->join('gudang as gudang_asal', 'gudang_asal.id_gudang = transfer_stok.id_gudang_asal');
        $this->db->join('gudang as gudang_tujuan', 'gudang_tujuan.id_gudang = transfer_stok.id_gudang_tujuan');
        $this->db->join('user', 'user.id_user = transfer_stok.id_user');
        $this->db->order_by('transfer_stok.tanggal', 'DESC');
        return $this->db->get()->result();
    }

    // Get transfer by perusahaan
    public function get_transfer_by_perusahaan($id_perusahaan) {
        $this->db->select('transfer_stok.*, barang.nama_barang, barang.sku, 
                          gudang_asal.nama_gudang as gudang_asal, 
                          gudang_tujuan.nama_gudang as gudang_tujuan,
                          user.nama as created_by');
        $this->db->from('transfer_stok');
        $this->db->join('barang', 'barang.id_barang = transfer_stok.id_barang');
        $this->db->join('gudang as gudang_asal', 'gudang_asal.id_gudang = transfer_stok.id_gudang_asal');
        $this->db->join('gudang as gudang_tujuan', 'gudang_tujuan.id_gudang = transfer_stok.id_gudang_tujuan');
        $this->db->join('user', 'user.id_user = transfer_stok.id_user');
        $this->db->where('gudang_asal.id_perusahaan', $id_perusahaan);
        $this->db->or_where('gudang_tujuan.id_perusahaan', $id_perusahaan);
        $this->db->order_by('transfer_stok.tanggal', 'DESC');
        return $this->db->get()->result();
    }

    // Get transfer by id
    public function get_transfer_by_id($id) {
        $this->db->select('transfer_stok.*, barang.nama_barang, barang.sku, 
                          gudang_asal.nama_gudang as gudang_asal, 
                          gudang_tujuan.nama_gudang as gudang_tujuan,
                          user.nama as created_by');
        $this->db->from('transfer_stok');
        $this->db->join('barang', 'barang.id_barang = transfer_stok.id_barang');
        $this->db->join('gudang as gudang_asal', 'gudang_asal.id_gudang = transfer_stok.id_gudang_asal');
        $this->db->join('gudang as gudang_tujuan', 'gudang_tujuan.id_gudang = transfer_stok.id_gudang_tujuan');
        $this->db->join('user', 'user.id_user = transfer_stok.id_user');
        $this->db->where('transfer_stok.id_transfer', $id);
        return $this->db->get()->row();
    }

    // Get next number for transfer
    public function get_next_number($id_perusahaan) {
        $this->db->select('COUNT(*) as total');
        $this->db->from('transfer_stok');
        $this->db->join('gudang as gudang_asal', 'gudang_asal.id_gudang = transfer_stok.id_gudang_asal');
        $this->db->where('gudang_asal.id_perusahaan', $id_perusahaan);
        $this->db->where('DATE(transfer_stok.tanggal)', date('Y-m-d'));
        $query = $this->db->get();
        $result = $query->row();
        
        return str_pad($result->total + 1, 3, '0', STR_PAD_LEFT);
    }

    // Insert transfer
    public function insert_transfer($data) {
        return $this->db->insert('transfer_stok', $data);
    }

    // Update transfer
    public function update_transfer($id, $data) {
        $this->db->where('id_transfer', $id);
        return $this->db->update('transfer_stok', $data);
    }

    // Delete transfer
    public function delete_transfer($id) {
        return $this->db->delete('transfer_stok', array('id_transfer' => $id));
    }
}