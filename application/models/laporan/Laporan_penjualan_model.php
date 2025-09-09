<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Laporan_penjualan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_filtered_penjualan($id_perusahaan = null, $tanggal_awal = null, $tanggal_akhir = null, $status = null)
    {
        $this->db->select('penjualan.*, pelanggan.nama_pelanggan, user.nama as created_by, perusahaan.nama_perusahaan,
                       COUNT(detail_penjualan.id_detail) as jumlah_item,
                       GROUP_CONCAT(CONCAT(barang.nama_barang, " (", detail_penjualan.jumlah, ") ") SEPARATOR "<br>") as daftar_barang');
        $this->db->from('penjualan');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left');
        $this->db->join('user', 'user.id_user = penjualan.id_user', 'left');
        $this->db->join('detail_penjualan', 'detail_penjualan.id_penjualan = penjualan.id_penjualan', 'left');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = penjualan.id_perusahaan', 'left'); // Tambahkan ini
        $this->db->join('barang', 'barang.id_barang = detail_penjualan.id_barang', 'left');

        // Perbaikan: Filter berdasarkan id_perusahaan di tabel penjualan
        if ($id_perusahaan) {
            $this->db->where('penjualan.id_perusahaan', $id_perusahaan);
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

        $this->db->group_by('penjualan.id_penjualan');
        $this->db->order_by('penjualan.tanggal_penjualan', 'DESC');

        // Eksekusi query
        $result = $this->db->get();

        // Debug: Tampilkan query yang dihasilkan
        log_message('debug', 'Penjualan query: ' . $this->db->last_query());

        // Cek jika query gagal
        if ($result === FALSE) {
            $error = $this->db->error();
            log_message('error', 'Database Error: ' . $error['message']);
            log_message('error', 'Query: ' . $this->db->last_query());
            return array(); // Return array kosong untuk menghindari error
        }

        return $result->result();
    }

    // Get perusahaan list
    public function get_perusahaan_list()
    {
        return $this->db->get('perusahaan')->result();
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

}