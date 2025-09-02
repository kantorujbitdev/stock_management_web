<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penyesuaian_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all penyesuaian
    public function get_all_penyesuaian()
    {
        $this->db->select('penyesuaian_stok.*, barang.nama_barang, barang.sku, 
                          gudang.nama_gudang, perusahaan.nama_perusahaan,
                          user.nama as created_by');
        $this->db->from('penyesuaian_stok');
        $this->db->join('barang', 'barang.id_barang = penyesuaian_stok.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = penyesuaian_stok.id_gudang');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = penyesuaian_stok.id_perusahaan');
        $this->db->join('user', 'user.id_user = penyesuaian_stok.id_user');
        $this->db->order_by('penyesuaian_stok.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Get penyesuaian by id
    public function get_penyesuaian_by_id($id)
    {
        $this->db->select('penyesuaian_stok.*, barang.nama_barang, barang.sku, 
                          gudang.nama_gudang, perusahaan.nama_perusahaan,
                          user.nama as created_by');
        $this->db->from('penyesuaian_stok');
        $this->db->join('barang', 'barang.id_barang = penyesuaian_stok.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = penyesuaian_stok.id_gudang');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = penyesuaian_stok.id_perusahaan');
        $this->db->join('user', 'user.id_user = penyesuaian_stok.id_user');
        $this->db->where('penyesuaian_stok.id_penyesuaian', $id);
        return $this->db->get()->row();
    }

    // Insert penyesuaian
    public function insert_penyesuaian($data)
    {
        return $this->db->insert('penyesuaian_stok', $data);
    }

    // Update penyesuaian
    public function update_penyesuaian($id, $data)
    {
        $this->db->where('id_penyesuaian', $id);
        return $this->db->update('penyesuaian_stok', $data);
    }

    // Delete penyesuaian
    public function delete_penyesuaian($id)
    {
        return $this->db->delete('penyesuaian_stok', array('id_penyesuaian' => $id));
    }
}