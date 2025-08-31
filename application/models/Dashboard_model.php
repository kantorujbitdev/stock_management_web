<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get total perusahaan (Super Admin)
    public function get_total_perusahaan() {
        return $this->db->count_all_results('perusahaan');
    }

    // Get total gudang
    public function get_total_gudang() {
        return $this->db->count_all_results('gudang');
    }

    // Get total gudang by perusahaan
    public function get_total_gudang_by_perusahaan($id_perusahaan) {
        $this->db->where('id_perusahaan', $id_perusahaan);
        return $this->db->count_all_results('gudang');
    }

    // Get total barang
    public function get_total_barang() {
        return $this->db->count_all_results('barang');
    }

    // Get total barang by perusahaan
    public function get_total_barang_by_perusahaan($id_perusahaan) {
        $this->db->where('id_perusahaan', $id_perusahaan);
        return $this->db->count_all_results('barang');
    }

    // Get total stok
    public function get_total_stok() {
        $this->db->select_sum('jumlah');
        return $this->db->get('stok_gudang')->row()->jumlah;
    }

    // Get total stok by perusahaan
    public function get_total_stok_by_perusahaan($id_perusahaan) {
        $this->db->select_sum('jumlah');
        $this->db->where('id_perusahaan', $id_perusahaan);
        return $this->db->get('stok_gudang')->row()->jumlah;
    }

    // Get total penjualan
    public function get_total_penjualan() {
        return $this->db->count_all_results('penjualan');
    }

    // Get total penjualan by perusahaan
    public function get_total_penjualan_by_perusahaan($id_perusahaan) {
        $this->db->from('penjualan');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
        $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        return $this->db->count_all_results();
    }

    // Get total retur
    public function get_total_retur() {
        return $this->db->count_all_results('retur_penjualan');
    }

    // Get total retur by perusahaan
    public function get_total_retur_by_perusahaan($id_perusahaan) {
        $this->db->from('retur_penjualan');
        $this->db->join('user', 'user.id_user = retur_penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
        $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        return $this->db->count_all_results();
    }

    // Get grafik penjualan per bulan (Super Admin)
    public function get_grafik_penjualan_bulan() {
        $data = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $this->db->select('COUNT(*) as total');
            $this->db->from('penjualan');
            $this->db->where('MONTH(tanggal_penjualan)', $i);
            $this->db->where('YEAR(tanggal_penjualan)', date('Y'));
            $result = $this->db->get()->row();
            
            $data[] = [
                'bulan' => $i,
                'total' => $result->total
            ];
        }
        
        return $data;
    }

    // Get grafik penjualan per bulan by perusahaan
    public function get_grafik_penjualan_bulan_by_perusahaan($id_perusahaan) {
        $data = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $this->db->select('COUNT(*) as total');
            $this->db->from('penjualan');
            $this->db->join('user', 'user.id_user = penjualan.id_user');
            $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
            $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
            $this->db->where('MONTH(tanggal_penjualan)', $i);
            $this->db->where('YEAR(tanggal_penjualan)', date('Y'));
            $result = $this->db->get()->row();
            
            $data[] = [
                'bulan' => $i,
                'total' => $result->total
            ];
        }
        
        return $data;
    }

    // Get stok menipis (Super Admin)
    public function get_stok_menipis() {
        $this->db->select('stok_gudang.*, barang.nama_barang, barang.sku, gudang.nama_gudang, perusahaan.nama_perusahaan');
        $this->db->from('stok_gudang');
        $this->db->join('barang', 'barang.id_barang = stok_gudang.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = stok_gudang.id_gudang');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = stok_gudang.id_perusahaan');
        $this->db->where('stok_gudang.jumlah <', 10);
        $this->db->order_by('stok_gudang.jumlah', 'ASC');
        $this->db->limit(10);
        return $this->db->get()->result();
    }

    // Get stok menipis by perusahaan
    public function get_stok_menipis_by_perusahaan($id_perusahaan) {
        $this->db->select('stok_gudang.*, barang.nama_barang, barang.sku, gudang.nama_gudang');
        $this->db->from('stok_gudang');
        $this->db->join('barang', 'barang.id_barang = stok_gudang.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = stok_gudang.id_gudang');
        $this->db->where('stok_gudang.id_perusahaan', $id_perusahaan);
        $this->db->where('stok_gudang.jumlah <', 10);
        $this->db->order_by('stok_gudang.jumlah', 'ASC');
        $this->db->limit(10);
        return $this->db->get()->result();
    }

    // Get penjualan terakhir (Super Admin)
    public function get_penjualan_terakhir() {
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->order_by('penjualan.tanggal_penjualan', 'DESC');
        $this->db->limit(10);
        return $this->db->get()->result();
    }

    // Get penjualan terakhir by perusahaan
    public function get_penjualan_terakhir_by_perusahaan($id_perusahaan) {
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');
        $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        $this->db->order_by('penjualan.tanggal_penjualan', 'DESC');
        $this->db->limit(10);
        return $this->db->get()->result();
    }
}