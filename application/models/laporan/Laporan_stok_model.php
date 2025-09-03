<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_stok_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get filtered stok
    public function get_filtered_stok($id_perusahaan = null, $id_gudang = null, $id_kategori = null)
    {
        $this->db->select('stok_gudang.*, barang.nama_barang, barang.sku, kategori.nama_kategori, gudang.nama_gudang, perusahaan.nama_perusahaan');
        $this->db->from('stok_gudang');
        $this->db->join('barang', 'barang.id_barang = stok_gudang.id_barang');
        $this->db->join('kategori', 'kategori.id_kategori = barang.id_kategori', 'left');
        $this->db->join('gudang', 'gudang.id_gudang = stok_gudang.id_gudang');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = stok_gudang.id_perusahaan');

        if ($id_perusahaan) {
            $this->db->where('stok_gudang.id_perusahaan', $id_perusahaan);
        }

        if ($id_gudang) {
            $this->db->where('stok_gudang.id_gudang', $id_gudang);
        }

        if ($id_kategori) {
            $this->db->where('barang.id_kategori', $id_kategori);
        }

        $this->db->order_by('perusahaan.nama_perusahaan, gudang.nama_gudang, barang.nama_barang');
        return $this->db->get()->result();
    }

    // Get perusahaan list
    public function get_perusahaan_list()
    {
        return $this->db->get('perusahaan')->result();
    }

    // Get gudang by perusahaan
    public function get_gudang_by_perusahaan($id_perusahaan)
    {
        $this->db->where('id_perusahaan', $id_perusahaan);
        return $this->db->get('gudang')->result();
    }

    // Get kategori by perusahaan
    public function get_kategori_by_perusahaan($id_perusahaan)
    {
        $this->db->where('id_perusahaan', $id_perusahaan);
        return $this->db->get('kategori')->result();
    }
}