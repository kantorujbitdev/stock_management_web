<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detail_retur_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get detail by retur
    public function get_detail_by_retur($id_retur)
    {
        $this->db->select('detail_retur_penjualan.*, barang.nama_barang, barang.sku, gudang.nama_gudang');
        $this->db->from('detail_retur_penjualan');
        $this->db->join('barang', 'barang.id_barang = detail_retur_penjualan.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = detail_retur_penjualan.id_gudang');
        $this->db->where('detail_retur_penjualan.id_retur', $id_retur);
        return $this->db->get()->result();
    }

    // Get detail by barang and retur
    public function get_detail_by_barang_retur($id_retur, $id_barang)
    {
        $this->db->where('id_retur', $id_retur);
        $this->db->where('id_barang', $id_barang);
        return $this->db->get('detail_retur_penjualan')->row();
    }

    // Insert detail
    public function insert_detail($data)
    {
        return $this->db->insert('detail_retur_penjualan', $data);
    }

    // Update detail
    public function update_detail($id, $data)
    {
        $this->db->where('id_detail_retur', $id);
        return $this->db->update('detail_retur_penjualan', $data);
    }

    // Delete detail
    public function delete_detail($id)
    {
        return $this->db->delete('detail_retur_penjualan', array('id_detail_retur' => $id));
    }
}