<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penerimaan_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all penerimaan
    public function get_all_penerimaan()
    {
        $this->db->select('penerimaan_barang.*, supplier.nama_supplier, gudang.nama_gudang, user.nama as created_by');
        $this->db->from('penerimaan_barang');
        $this->db->join('supplier', 'supplier.id_supplier = penerimaan_barang.id_supplier');
        $this->db->join('gudang', 'gudang.id_gudang = penerimaan_barang.id_gudang');
        $this->db->join('user', 'user.id_user = penerimaan_barang.id_user');
        $this->db->order_by('penerimaan_barang.tanggal_penerimaan', 'DESC');
        return $this->db->get()->result();
    }

    // Get penerimaan by perusahaan
    public function get_penerimaan_by_perusahaan($id_perusahaan)
    {
        $this->db->select('penerimaan_barang.*, supplier.nama_supplier, gudang.nama_gudang, user.nama as created_by');
        $this->db->from('penerimaan_barang');
        $this->db->join('supplier', 'supplier.id_supplier = penerimaan_barang.id_supplier');
        $this->db->join('gudang', 'gudang.id_gudang = penerimaan_barang.id_gudang');
        $this->db->join('user', 'user.id_user = penerimaan_barang.id_user');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = gudang.id_perusahaan');
        $this->db->where('gudang.id_perusahaan', $id_perusahaan);
        $this->db->order_by('penerimaan_barang.tanggal_penerimaan', 'DESC');
        return $this->db->get()->result();
    }

    // Get penerimaan by id
    public function get_penerimaan_by_id($id)
    {
        $this->db->select('penerimaan_barang.*, supplier.nama_supplier, gudang.nama_gudang, user.nama as created_by');
        $this->db->from('penerimaan_barang');
        $this->db->join('supplier', 'supplier.id_supplier = penerimaan_barang.id_supplier');
        $this->db->join('gudang', 'gudang.id_gudang = penerimaan_barang.id_gudang');
        $this->db->join('user', 'user.id_user = penerimaan_barang.id_user');
        $this->db->where('penerimaan_barang.id_penerimaan', $id);
        return $this->db->get()->row();
    }

    // Get next number for penerimaan
    public function get_next_number($id_perusahaan)
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('penerimaan_barang');
        $this->db->join('gudang', 'gudang.id_gudang = penerimaan_barang.id_gudang');
        $this->db->where('gudang.id_perusahaan', $id_perusahaan);
        $this->db->where('DATE(penerimaan_barang.created_at)', date('Y-m-d'));
        $query = $this->db->get();
        $result = $query->row();

        return str_pad($result->total + 1, 3, '0', STR_PAD_LEFT);
    }

    // Insert penerimaan
    public function insert_penerimaan($data)
    {
        return $this->db->insert('penerimaan_barang', $data);
    }

    // Update penerimaan
    public function update_penerimaan($id, $data)
    {
        $this->db->where('id_penerimaan', $id);
        return $this->db->update('penerimaan_barang', $data);
    }

    // Delete penerimaan
    public function delete_penerimaan($id)
    {
        return $this->db->delete('penerimaan_barang', array('id_penerimaan' => $id));
    }
}