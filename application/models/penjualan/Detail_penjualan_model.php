<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detail_penjualan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_detail_by_penjualan($id_penjualan)
    {
        $this->db->select('dp.*, b.nama_barang, b.sku, g.nama_gudang');
        $this->db->from('detail_penjualan dp');
        $this->db->join('barang b', 'dp.id_barang = b.id_barang', 'left');
        $this->db->join('gudang g', 'dp.id_gudang = g.id_gudang', 'left');
        $this->db->where('dp.id_penjualan', $id_penjualan);
        $this->db->order_by('dp.id_detail', 'ASC');
        return $this->db->get()->result();
    }

    public function insert_detail_penjualan($data)
    {
        return $this->db->insert('detail_penjualan', $data);
    }

    public function update_detail_penjualan($id, $data)
    {
        $this->db->where('id_detail', $id);
        return $this->db->update('detail_penjualan', $data);
    }

    public function delete_detail_penjualan($id)
    {
        $this->db->where('id_detail', $id);
        return $this->db->delete('detail_penjualan');
    }

    public function delete_by_penjualan($id_penjualan)
    {
        $this->db->where('id_penjualan', $id_penjualan);
        return $this->db->delete('detail_penjualan');
    }
}