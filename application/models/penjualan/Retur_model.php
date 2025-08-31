<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all retur
    public function get_all_retur() {
        $this->db->select('retur_penjualan.*, penjualan.no_invoice, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('retur_penjualan');
        $this->db->join('penjualan', 'penjualan.id_penjualan = retur_penjualan.id_penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = retur_penjualan.id_user');
        $this->db->order_by('retur_penjualan.tanggal_retur', 'DESC');
        return $this->db->get()->result();
    }

    // Get retur by perusahaan
    public function get_retur_by_perusahaan($id_perusahaan) {
        $this->db->select('retur_penjualan.*, penjualan.no_invoice, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('retur_penjualan');
        $this->db->join('penjualan', 'penjualan.id_penjualan = retur_penjualan.id_penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = retur_penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
        $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        $this->db->order_by('retur_penjualan.tanggal_retur', 'DESC');
        return $this->db->get()->result();
    }

    // Get retur by id
    public function get_retur_by_id($id) {
        $this->db->select('retur_penjualan.*, penjualan.no_invoice, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('retur_penjualan');
        $this->db->join('penjualan', 'penjualan.id_penjualan = retur_penjualan.id_penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = retur_penjualan.id_user');
        $this->db->where('retur_penjualan.id_retur', $id);
        return $this->db->get()->row();
    }

    // Get next number for retur
    public function get_next_number($id_perusahaan) {
        $this->db->select('COUNT(*) as total');
        $this->db->from('retur_penjualan');
        $this->db->join('user', 'user.id_user = retur_penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
        $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        $this->db->where('DATE(retur_penjualan.created_at)', date('Y-m-d'));
        $query = $this->db->get();
        $result = $query->row();
        
        return str_pad($result->total + 1, 3, '0', STR_PAD_LEFT);
    }

    // Insert retur
    public function insert_retur($data) {
        return $this->db->insert('retur_penjualan', $data);
    }

    // Update retur
    public function update_retur($id, $data) {
        $this->db->where('id_retur', $id);
        return $this->db->update('retur_penjualan', $data);
    }

    // Delete retur
    public function delete_retur($id) {
        return $this->db->delete('retur_penjualan', array('id_retur' => $id));
    }
}