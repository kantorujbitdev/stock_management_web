<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Laporan_penjualan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get filtered penjualan dengan data lengkap
    public function get_filtered_penjualan($id_perusahaan = null, $tanggal_awal = null, $tanggal_akhir = null, $status = null)
    {
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan, user.nama as created_by, user.id_perusahaan, perusahaan.nama_perusahaan as nama_perusahaan');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = user.id_perusahaan');

        // Filter berdasarkan perusahaan
        if ($id_perusahaan) {
            $this->db->where('user.id_perusahaan', $id_perusahaan);
        }

        // Filter berdasarkan tanggal
        if ($tanggal_awal) {
            $this->db->where('DATE(penjualan.tanggal_penjualan) >=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $this->db->where('DATE(penjualan.tanggal_penjualan) <=', $tanggal_akhir);
        }

        // Filter berdasarkan status
        if ($status) {
            $this->db->where('penjualan.status', $status);
        }

        $this->db->order_by('penjualan.tanggal_penjualan', 'DESC');

        $result = $this->db->get()->result();

        // Tambahkan data tambahan untuk setiap penjualan
        foreach ($result as $row) {
            // Get tanggal status terakhir
            $last_status = $this->get_last_status_date($row->id_penjualan);
            $row->tanggal_status_terakhir = $last_status ? $last_status->tanggal : null;

            // Get jumlah item
            $row->jumlah_item = $this->get_jumlah_item($row->id_penjualan);

            // Get daftar barang
            $row->daftar_barang = $this->get_daftar_barang($row->id_penjualan);
        }

        return $result;
    }

    // Get daftar barang penjualan
    private function get_daftar_barang($id_penjualan)
    {
        $this->db->select('barang.nama_barang, detail_penjualan.jumlah');
        $this->db->from('detail_penjualan');
        $this->db->join('barang', 'barang.id_barang = detail_penjualan.id_barang');
        $this->db->where('detail_penjualan.id_penjualan', $id_penjualan);
        $query = $this->db->get();

        $barang_list = [];
        foreach ($query->result() as $item) {
            $barang_list[] = $item->nama_barang . ' (' . $item->jumlah . ')';
        }

        return implode(', ', $barang_list);
    }

    // Get jumlah item penjualan
    private function get_jumlah_item($id_penjualan)
    {
        $this->db->select('SUM(jumlah) as total');
        $this->db->from('detail_penjualan');
        $this->db->where('id_penjualan', $id_penjualan);
        $query = $this->db->get();
        $result = $query->row();

        return $result ? $result->total : 0;
    }

    // Get tanggal status terakhir
    public function get_last_status_date($id_penjualan)
    {
        $this->db->select('tanggal, status');
        $this->db->from('log_status_penjualan');
        $this->db->where('id_penjualan', $id_penjualan);
        $this->db->order_by('tanggal', 'DESC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }
    // Get detail penjualan
    public function get_detail_penjualan($id_penjualan)
    {
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan, pelanggan.alamat as alamat_pelanggan, 
                       pelanggan.telepon as telepon_pelanggan, user.nama as created_by');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left');
        $this->db->join('user', 'user.id_user = penjualan.id_user');
        $this->db->where('penjualan.id_penjualan', $id_penjualan);
        return $this->db->get()->row();
    }

    // Get detail barang penjualan
    public function get_detail_barang($id_penjualan)
    {
        $this->db->select('detail_penjualan.*, barang.nama_barang, barang.sku, gudang.nama_gudang');
        $this->db->from('detail_penjualan');
        $this->db->join('barang', 'barang.id_barang = detail_penjualan.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = detail_penjualan.id_gudang');
        $this->db->where('detail_penjualan.id_penjualan', $id_penjualan);
        return $this->db->get()->result();
    }

    // Get log status penjualan
    public function get_log_status($id_penjualan)
    {
        $this->db->select('log_status_penjualan.*, user.nama as user_name');
        $this->db->from('log_status_penjualan');
        $this->db->join('user', 'user.id_user = log_status_penjualan.id_user');
        $this->db->where('log_status_penjualan.id_penjualan', $id_penjualan);
        $this->db->order_by('log_status_penjualan.tanggal', 'ASC');
        return $this->db->get()->result();
    }
    // Get perusahaan list
    public function get_perusahaan_list()
    {
        return $this->db->get('perusahaan')->result();
    }
}