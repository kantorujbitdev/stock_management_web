<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Laporan_retur_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get filtered retur
    public function get_filtered_retur($id_perusahaan = null, $tanggal_awal = null, $tanggal_akhir = null, $status = null)
    {
        // Debug: Log parameter
        log_message('debug', 'get_filtered_retur called with: ' . json_encode(func_get_args()));

        $this->db->select('retur_penjualan.*, penjualan.no_invoice, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('retur_penjualan');
        $this->db->join('penjualan', 'penjualan.id_penjualan = retur_penjualan.id_penjualan', 'left');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left');
        $this->db->join('user', 'user.id_user = retur_penjualan.id_user', 'left');

        // Filter berdasarkan id_perusahaan di tabel penjualan
        if ($id_perusahaan) {
            $this->db->where('penjualan.id_perusahaan', $id_perusahaan);
            log_message('debug', 'Filtering by company: ' . $id_perusahaan);
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

        // Debug: Tampilkan query
        $sql = $this->db->get_compiled_select();
        log_message('debug', 'Retur SQL: ' . $sql);

        $query = $this->db->get();

        if ($query === FALSE) {
            $error = $this->db->error();
            log_message('error', 'Database error: ' . json_encode($error));
            return array();
        }

        $result = $query->result();
        log_message('debug', 'Retur result count: ' . count($result));

        return $result;
    }

    // Get perusahaan list
    public function get_perusahaan_list()
    {
        return $this->db->get('perusahaan')->result();
    }
}