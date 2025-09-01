<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all barang
    public function get_all_barang() {
        $this->db->select('barang.*, kategori.nama_kategori, perusahaan.nama_perusahaan');
        $this->db->from('barang');
        $this->db->join('kategori', 'kategori.id_kategori = barang.id_kategori');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = barang.id_perusahaan');
        return $this->db->get()->result();
    }

    // Get barang by perusahaan
    public function get_barang_by_perusahaan($id_perusahaan) {
        $this->db->select('barang.*, kategori.nama_kategori, perusahaan.nama_perusahaan');
        $this->db->from('barang');
        $this->db->join('kategori', 'kategori.id_kategori = barang.id_kategori');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = barang.id_perusahaan');
        $this->db->where('barang.id_perusahaan', $id_perusahaan);
        return $this->db->get()->result();
    }

    // Get barang by id
    public function get_barang_by_id($id) {
        $this->db->select('barang.*, kategori.nama_kategori, perusahaan.nama_perusahaan');
        $this->db->from('barang');
        $this->db->join('kategori', 'kategori.id_kategori = barang.id_kategori');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = barang.id_perusahaan');
        $this->db->where('barang.id_barang', $id);
        return $this->db->get()->row();
    }

    // Check SKU unik per perusahaan
    public function check_sku($id_perusahaan, $sku, $id_barang = null) {
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('sku', $sku);
        
        if ($id_barang) {
            $this->db->where('id_barang !=', $id_barang);
        }
        
        $query = $this->db->get('barang');
        return $query->num_rows() > 0;
    }

    // Insert barang
    public function insert_barang($data) {
        return $this->db->insert('barang', $data);
    }

    // Update barang
    public function update_barang($id, $data) {
        $this->db->where('id_barang', $id);
        return $this->db->update('barang', $data);
    }

    // Delete barang
    public function delete_barang($id) {
        // Cek apakah ada transaksi yang terkait dengan barang ini
        $this->db->where('id_barang', $id);
        $check_stok = $this->db->get('stok_gudang')->num_rows();
        $check_penerimaan = $this->db->get('detail_penerimaan')->num_rows();
        $check_penjualan = $this->db->get('detail_penjualan')->num_rows();
        
        if ($check_stok > 0 || $check_penerimaan > 0 || $check_penjualan > 0) {
            return false; // Tidak bisa dihapus karena ada transaksi terkait
        }
        
        // Hapus gambar jika ada
        $barang = $this->get_barang_by_id($id);
        if ($barang->gambar && file_exists('./uploads/barang/' . $barang->gambar)) {
            unlink('./uploads/barang/' . $barang->gambar);
        }
        
        return $this->db->delete('barang', array('id_barang' => $id));
    }

    public function get_kategori_by_perusahaan($id_perusahaan)
    {
        return $this->db->get_where('kategori', ['id_perusahaan' => $id_perusahaan])->result();
    }

}