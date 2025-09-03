<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all pelanggan
    public function get_all_pelanggan()
    {
        $this->db->select('p.*, per.nama_perusahaan');
        $this->db->from('pelanggan p');
        $this->db->join('perusahaan per', 'p.id_perusahaan = per.id_perusahaan', 'left');
        $this->db->order_by('p.id_pelanggan', 'DESC');
        return $this->db->get()->result();
    }

    // Get pelanggan by id
    public function get_pelanggan_by_id($id)
    {
        $this->db->select('p.*, per.nama_perusahaan');
        $this->db->from('pelanggan p');
        $this->db->join('perusahaan per', 'p.id_perusahaan = per.id_perusahaan', 'left');
        $this->db->where('p.id_pelanggan', $id);
        return $this->db->get()->row();
    }

    // // Get pelanggan by perusahaan
    // public function get_pelanggan_by_perusahaan($id_perusahaan)
    // {
    //     $this->db->where('id_perusahaan', $id_perusahaan);
    //     $this->db->order_by('nama_pelanggan', 'ASC');
    //     return $this->db->get('pelanggan')->result();
    // }

    // Insert pelanggan
    public function insert_pelanggan($data)
    {
        $this->db->insert('pelanggan', $data);
        return $this->db->insert_id();
    }

    // Update pelanggan
    public function update_pelanggan($id, $data)
    {
        $this->db->where('id_pelanggan', $id);
        return $this->db->update('pelanggan', $data);
    }

    public function get_pelanggan_aktif()
    {
        $this->db->select('*');
        $this->db->from('pelanggan');
        $this->db->where('status_aktif', 1);
        $this->db->order_by('nama_pelanggan', 'ASC');
        return $this->db->get()->result();
    }

    public function get_pelanggan_by_perusahaan($id_perusahaan)
    {
        $this->db->select('*');
        $this->db->from('pelanggan');
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('status_aktif', 1);
        $this->db->order_by('nama_pelanggan', 'ASC');
        return $this->db->get()->result();
    }
    // Soft delete pelanggan (ubah status aktif)
    public function delete_pelanggan($id)
    {
        $this->db->where('id_pelanggan', $id);
        return $this->db->update('pelanggan', ['status_aktif' => 0]);
    }

    // Restore pelanggan (ubah status aktif)
    public function restore_pelanggan($id)
    {
        $this->db->where('id_pelanggan', $id);
        return $this->db->update('pelanggan', ['status_aktif' => 1]);
    }

}