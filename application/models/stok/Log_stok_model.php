<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_stok_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get filtered riwayat
    public function get_filtered_riwayat($id_perusahaan = null, $id_gudang = null, $id_barang = null, $jenis = null, $tanggal_awal = null, $tanggal_akhir = null) {
        $this->db->select('log_stok.*, barang.nama_barang, barang.sku, gudang.nama_gudang, perusahaan.nama_perusahaan, user.nama as created_by');
        $this->db->from('log_stok');
        $this->db->join('barang', 'barang.id_barang = log_stok.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = log_stok.id_gudang');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = log_stok.id_perusahaan');
        $this->db->join('user', 'user.id_user = log_stok.id_user', 'left');
        
        if ($id_perusahaan) {
            $this->db->where('log_stok.id_perusahaan', $id_perusahaan);
        }
        
        if ($id_gudang) {
            $this->db->where('log_stok.id_gudang', $id_gudang);
        }
        
        if ($id_barang) {
            $this->db->where('log_stok.id_barang', $id_barang);
        }
        
        if ($jenis) {
            $this->db->where('log_stok.jenis', $jenis);
        }
        
        if ($tanggal_awal) {
            $this->db->where('DATE(log_stok.tanggal) >=', $tanggal_awal);
        }
        
        if ($tanggal_akhir) {
            $this->db->where('DATE(log_stok.tanggal) <=', $tanggal_akhir);
        }
        
        $this->db->order_by('log_stok.tanggal', 'DESC');
        return $this->db->get()->result();
    }

    // Get perusahaan list
    public function get_perusahaan_list() {
        return $this->db->get('perusahaan')->result();
    }

    // Get gudang by perusahaan
    public function get_gudang_by_perusahaan($id_perusahaan) {
        $this->db->where('id_perusahaan', $id_perusahaan);
        return $this->db->get('gudang')->result();
    }

    // Get barang by gudang
    public function get_barang_by_gudang($id_gudang) {
        $this->db->select('barang.*');
        $this->db->from('barang');
        $this->db->join('stok_gudang', 'stok_gudang.id_barang = barang.id_barang');
        $this->db->where('stok_gudang.id_gudang', $id_gudang);
        $this->db->group_by('barang.id_barang');
        return $this->db->get()->result();
    }

    // Insert log
    public function insert_log($data) {
        return $this->db->insert('log_stok', $data);
    }
}