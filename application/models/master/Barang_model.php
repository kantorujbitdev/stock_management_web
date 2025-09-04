<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all barang
    public function get_all_barang()
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->order_by('b.id_barang', 'DESC');
        return $this->db->get()->result();
    }

    // Get barang by id
    public function get_barang_by_id($id)
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->where('b.id_barang', $id);
        return $this->db->get()->row();
    }

    // Get barang by perusahaan
    public function get_barang_by_perusahaan($id_perusahaan)
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->where('b.id_perusahaan', $id_perusahaan);
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }
    // public function get_barang_with_stock($id_perusahaan)
    // {
    //     $this->db->select('b.id_barang, b.nama_barang, b.sku');
    //     $this->db->from('barang b');
    //     $this->db->join('stok_gudang sg', 'b.id_barang = sg.id_barang', 'inner');
    //     $this->db->where('b.id_perusahaan', $id_perusahaan);
    //     $this->db->where('b.aktif', 1);
    //     $this->db->where('sg.jumlah >', 0);
    //     $this->db->group_by('b.id_barang');
    //     $this->db->order_by('b.nama_barang', 'ASC');
    //     return $this->db->get()->result();
    // }
    public function get_barang_with_stock($id_perusahaan)
    {
        $this->db->select('b.*, k.nama_kategori, COALESCE(sg.jumlah, 0) as stok_tersedia');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('stok_gudang sg', 'b.id_barang = sg.id_barang', 'left');
        $this->db->where('b.id_perusahaan', $id_perusahaan);
        $this->db->where('b.aktif', 1);
        $this->db->where('sg.jumlah >', 0);
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    // Check SKU uniqueness
    public function check_sku($sku, $id_perusahaan, $id_barang = null)
    {
        $this->db->where('sku', $sku);
        $this->db->where('id_perusahaan', $id_perusahaan);

        if ($id_barang) {
            $this->db->where('id_barang !=', $id_barang);
        }

        $query = $this->db->get('barang');
        return $query->num_rows() > 0;
    }

    // Insert barang
    public function insert_barang($data)
    {
        $this->db->insert('barang', $data);
        return $this->db->insert_id();
    }

    // Update barang
    public function update_barang($id, $data)
    {
        $this->db->where('id_barang', $id);
        return $this->db->update('barang', $data);
    }

    // Update status barang
    public function update_status($id, $status)
    {
        $this->db->where('id_barang', $id);
        return $this->db->update('barang', ['aktif' => $status]);
    }

    // Get stok barang
    public function get_stok_barang($id_barang)
    {
        $this->db->select('SUM(sg.jumlah) as stok');
        $this->db->from('stok_gudang sg');
        $this->db->where('sg.id_barang', $id_barang);
        return $this->db->get()->row()->stok;
    }
}