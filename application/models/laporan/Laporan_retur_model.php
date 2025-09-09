<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Laporan_retur_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_filtered_retur($id_perusahaan = null, $tanggal_awal = null, $tanggal_akhir = null, $status = null)
    {
        $this->db->select('retur_penjualan.*, 
                       penjualan.no_invoice, 
                       penjualan.id_perusahaan, 
                       pelanggan.nama_pelanggan, 
                       user_created.nama as created_by,
                       approval_log.id_user as approval_user_id,
                       user_approval.nama as approval_by,
                       approval_log.tanggal as approval_date,
                       approval_log.status as approval_status');
        $this->db->from('retur_penjualan');
        $this->db->join('penjualan', 'penjualan.id_penjualan = retur_penjualan.id_penjualan', 'left');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left');
        $this->db->join('user user_created', 'user_created.id_user = retur_penjualan.id_user', 'left');

        // Subquery untuk mendapatkan log approval terakhir
        $this->db->join('(SELECT 
                        lr1.id_retur, 
                        lr1.id_user, 
                        lr1.tanggal, 
                        lr1.status
                      FROM log_status_retur lr1
                      INNER JOIN (
                        SELECT id_retur, MAX(tanggal) as max_tanggal
                        FROM log_status_retur
                        WHERE status IN ("diterima", "ditolak")
                        GROUP BY id_retur
                      ) lr2 ON lr1.id_retur = lr2.id_retur AND lr1.tanggal = lr2.max_tanggal
                     ) approval_log', 'approval_log.id_retur = retur_penjualan.id_retur', 'left');

        $this->db->join('user user_approval', 'user_approval.id_user = approval_log.id_user', 'left');

        if ($id_perusahaan) {
            $this->db->where('penjualan.id_perusahaan', $id_perusahaan);
        }

        if ($tanggal_awal) {
            $this->db->where('DATE(retur_penjualan.tanggal_retur) >=', $tanggal_awal);
        }

        if ($tanggal_akhir) {
            $this->db->where('DATE(retur_penjualan.tanggal_retur) <=', $tanggal_akhir);
        }

        if ($status) {
            $this->db->where('retur_penjualan.status', $status);
        }

        $this->db->order_by('retur_penjualan.tanggal_retur', 'DESC');

        $result = $this->db->get();

        if ($result === FALSE) {
            $error = $this->db->error();
            log_message('error', 'Database Error: ' . $error['message']);
            log_message('error', 'Query: ' . $this->db->last_query());
            return array();
        }

        return $result->result();
    }

    // Get perusahaan list
    public function get_perusahaan_list()
    {
        return $this->db->get('perusahaan')->result();
    }
}