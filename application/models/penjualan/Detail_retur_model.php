<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Detail_retur_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert_detail_retur($data)
    {
        $this->db->insert('detail_retur_penjualan', $data);
        return $this->db->insert_id();
    }

    public function get_detail_by_retur($id_retur)
    {
        $this->db->select('drp.*, b.nama_barang, b.sku');
        $this->db->from('detail_retur_penjualan drp');
        $this->db->join('barang b', 'drp.id_barang = b.id_barang', 'left');
        $this->db->where('drp.id_retur', $id_retur);

        return $this->db->get()->result();
    }

    public function get_detail_by_penjualan($id_penjualan)
    {
        $this->db->select('drp.*, rp.no_retur, rp.tanggal_retur, rp.status');
        $this->db->from('detail_retur_penjualan drp');
        $this->db->join('retur_penjualan rp', 'drp.id_retur = rp.id_retur', 'left');
        $this->db->where('rp.id_penjualan', $id_penjualan);

        return $this->db->get()->result();
    }
}