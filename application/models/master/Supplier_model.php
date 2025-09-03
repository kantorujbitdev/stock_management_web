<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all supplier
    public function get_all_supplier()
    {
        $this->db->select('s.*, p.nama_perusahaan');
        $this->db->from('supplier s');
        $this->db->join('perusahaan p', 's.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->order_by('s.id_supplier', 'DESC');
        return $this->db->get()->result();
    }

    // Get supplier by id
    public function get_supplier_by_id($id)
    {
        $this->db->select('s.*, p.nama_perusahaan');
        $this->db->from('supplier s');
        $this->db->join('perusahaan p', 's.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->where('s.id_supplier', $id);
        return $this->db->get()->row();
    }

    // Get supplier by perusahaan
    public function get_supplier_by_perusahaan($id_perusahaan)
    {
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('status_aktif', 1);
        $this->db->order_by('nama_supplier', 'ASC');
        return $this->db->get('supplier')->result();
    }

    // Insert supplier
    public function insert_supplier($data)
    {
        $this->db->insert('supplier', $data);
        return $this->db->insert_id();
    }

    // Update supplier
    public function update_supplier($id, $data)
    {
        $this->db->where('id_supplier', $id);
        return $this->db->update('supplier', $data);
    }

    // Update status supplier
    public function update_status($id, $status)
    {
        $this->db->where('id_supplier', $id);
        return $this->db->update('supplier', ['status_aktif' => $status]);
    }
}