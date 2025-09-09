<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Log_status_retur_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Insert log status retur
    public function insert_log($data)
    {
        $this->db->insert('log_status_retur', $data);
        return $this->db->insert_id();
    }

    // Get log by retur
    public function get_log_by_retur($id_retur)
    {
        $this->db->select('log_status_retur.*, user.nama');
        $this->db->from('log_status_retur');
        $this->db->join('user', 'user.id_user = log_status_retur.id_user');
        $this->db->where('id_retur', $id_retur);
        $this->db->order_by('tanggal', 'DESC');
        return $this->db->get()->result();
    }

    // Get last status
    public function get_last_status($id_retur)
    {
        $this->db->where('id_retur', $id_retur);
        $this->db->order_by('tanggal', 'DESC');
        $this->db->limit(1);
        return $this->db->get('log_status_retur')->row();
    }
}