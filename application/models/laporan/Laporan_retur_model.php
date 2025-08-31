<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_retur_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get filtered retur
    public function get_filtered_retur($id_perusahaan = null, $tanggal_awal = null, $tanggal_akhir = null, $status = null) {
        $this->db->select('retur_penjualan.*, penjualan.no_invoice, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('retur_penjualan');
        $this->db->join('penjualan', 'penjualan.id_penjualan = retur_penjualan.id_penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = retur_penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
        
        if ($id_perusahaan) {
            $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        }
        
        if ($tanggal_awal) {
            $this->db->where('DATE(retur_penjualan.tanggal_retur) >=', $tanggal_awal);
        }
        
        if ($tanggal_akhir) {
            $this->db->where('DATE(retur_penjualan.tanggal_retur) <=', $tanggal_akhir);
        }
        
        if ($status) {
            $this->db->where('retur_penjualan.status', $status);
        }
        
        $this->db->order_by('retur_penjualan.tanggal_retur', 'DESC');
        return $this->db->get()->result();
    }

    // Get perusahaan list
    public function get_perusahaan_list() {
        return $this->db->get('perusahaan')->result();
    }
}