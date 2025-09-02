<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detail_penerimaan_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get detail by penerimaan
    public function get_detail_by_penerimaan($id_penerimaan)
    {
        $this->db->select('detail_penerimaan.*, barang.nama_barang, barang.sku');
        $this->db->from('detail_penerimaan');
        $this->db->join('barang', 'barang.id_barang = detail_penerimaan.id_barang');
        $this->db->where('detail_penerimaan.id_penerimaan', $id_penerimaan);
        return $this->db->get()->result();
    }

    // Get detail by barang and penerimaan
    public function get_detail_by_barang_penerimaan($id_penerimaan, $id_barang)
    {
        $this->db->where('id_penerimaan', $id_penerimaan);
        $this->db->where('id_barang', $id_barang);
        return $this->db->get('detail_penerimaan')->row();
    }

    // Insert detail
    public function insert_detail($data)
    {
        return $this->db->insert('detail_penerimaan', $data);
    }

    // Update detail
    public function update_detail($id, $data)
    {
        $this->db->where('id_detail', $id);
        return $this->db->update('detail_penerimaan', $data);
    }

    // Delete detail
    public function delete_detail($id)
    {
        return $this->db->delete('detail_penerimaan', array('id_detail' => $id));
    }
}