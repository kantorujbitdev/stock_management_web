<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_transfer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get filtered transfer
    public function get_filtered_transfer($id_perusahaan = null, $tanggal_awal = null, $tanggal_akhir = null, $status = null) {
        $this->db->select('transfer_stok.*, barang.nama_barang, 
                          gudang_asal.nama_gudang as gudang_asal, 
                          gudang_tujuan.nama_gudang as gudang_tujuan,
                          user.nama as created_by');
        $this->db->from('transfer_stok');
        $this->db->join('barang', 'barang.id_barang = transfer_stok.id_barang');
        $this->db->join('gudang as gudang_asal', 'gudang_asal.id_gudang = transfer_stok.id_gudang_asal');
        $this->db->join('gudang as gudang_tujuan', 'gudang_tujuan.id_gudang = transfer_stok.id_gudang_tujuan');
        $this->db->join('user', 'user.id_user = transfer_stok.id_user');
        
        if ($id_perusahaan) {
            $this->db->where('gudang_asal.id_perusahaan', $id_perusahaan);
            $this->db->or_where('gudang_tujuan.id_perusahaan', $id_perusahaan);
        }
        
        if ($tanggal_awal) {
            $this->db->where('DATE(transfer_stok.tanggal) >=', $tanggal_awal);
        }
        
        if ($tanggal_akhir) {
            $this->db->where('DATE(transfer_stok.tanggal) <=', $tanggal_akhir);
        }
        
        if ($status) {
            $this->db->where('transfer_stok.status', $status);
        }
        
        $this->db->order_by('transfer_stok.tanggal', 'DESC');
        return $this->db->get()->result();
    }

    // Get perusahaan list
    public function get_perusahaan_list() {
        return $this->db->get('perusahaan')->result();
    }
}