<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get total barang
    public function get_total_barang()
    {
        return $this->db->count_all('barang');
    }

    // Get total stok
    public function get_total_stok()
    {
        $this->db->select('SUM(jumlah) as total');
        return $this->db->get('stok_gudang')->row()->total;
    }

    // Get penjualan hari ini
    public function get_penjualan_hari_ini()
    {
        $today = date('Y-m-d');
        $this->db->where('DATE(tanggal_penjualan)', $today);
        return $this->db->count_all_results('penjualan');
    }

    // Get stok menipis
    public function get_stok_menipis()
    {
        $this->db->where('jumlah <', 10);
        return $this->db->count_all_results('stok_gudang');
    }
}