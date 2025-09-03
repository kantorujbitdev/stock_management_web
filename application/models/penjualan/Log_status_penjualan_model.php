<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_status_penjualan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert_log($data)
    {
        $this->db->insert('log_status_penjualan', $data);
        return $this->db->insert_id();
    }

    public function get_log_by_penjualan($id_penjualan)
    {
        $this->db->select('lsp.*, u.nama as nama_user');
        $this->db->from('log_status_penjualan lsp');
        $this->db->join('user u', 'lsp.id_user = u.id_user', 'left');
        $this->db->where('lsp.id_penjualan', $id_penjualan);
        $this->db->order_by('lsp.tanggal', 'ASC'); // Urut dari yang terlama
        return $this->db->get()->result();
    }
}