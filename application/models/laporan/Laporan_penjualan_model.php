<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_penjualan_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get filtered penjualan
    public function get_filtered_penjualan($id_perusahaan = null, $tanggal_awal = null, $tanggal_akhir = null, $status = null)
    {
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan, user.nama as created_by');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->join('user_perusahaan', 'user_perusahaan.id_user = user.id_user');

        if ($id_perusahaan) {
            $this->db->where('user_perusahaan.id_perusahaan', $id_perusahaan);
        }

        if ($tanggal_awal) {
            $this->db->where('DATE(penjualan.tanggal_penjualan) >=', $tanggal_awal);
        }

        if ($tanggal_akhir) {
            $this->db->where('DATE(penjualan.tanggal_penjualan) <=', $tanggal_akhir);
        }

        if ($status) {
            $this->db->where('penjualan.status', $status);
        }

        $this->db->order_by('penjualan.tanggal_penjualan', 'DESC');
        return $this->db->get()->result();
    }

    // Get perusahaan list
    public function get_perusahaan_list()
    {
        return $this->db->get('perusahaan')->result();
    }
}