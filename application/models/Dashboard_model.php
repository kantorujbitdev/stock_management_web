<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get total barang dengan filter perusahaan
    public function get_total_barang()
    {
        // Filter berdasarkan perusahaan jika bukan superadmin
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $this->db->where('id_perusahaan', $id_perusahaan);
        }
        $this->db->where('aktif', 1);
        return $this->db->count_all_results('barang');
    }

    // Get total stok dengan filter perusahaan
    public function get_total_stok()
    {
        $this->db->select('SUM(jumlah) as total');
        // Filter berdasarkan perusahaan jika bukan superadmin
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $this->db->where('id_perusahaan', $id_perusahaan);
        }
        $result = $this->db->get('stok_gudang')->row();
        return $result ? $result->total : 0;
    }

    // Get penjualan hari ini dengan filter perusahaan
    public function get_penjualan_hari_ini()
    {
        $today = date('Y-m-d');
        $this->db->where('DATE(tanggal_penjualan)', $today);
        // Filter berdasarkan perusahaan jika bukan superadmin
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $this->db->where('id_perusahaan', $id_perusahaan);
        }
        return $this->db->count_all_results('penjualan');
    }

    // Get stok menipis dengan filter perusahaan
    public function get_stok_menipis($threshold = 10)
    {
        $this->db->where('jumlah <', $threshold);
        // Filter berdasarkan perusahaan jika bukan superadmin
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $this->db->where('id_perusahaan', $id_perusahaan);
        }
        return $this->db->count_all_results('stok_gudang');
    }

    // Get data grafik penjualan (7 hari terakhir)
    public function get_grafik_penjualan()
    {
        $data = [];
        // Ambil 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $this->db->where('DATE(tanggal_penjualan)', $date);
            // Filter berdasarkan perusahaan jika bukan superadmin
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan = $this->session->userdata('id_perusahaan');
                $this->db->where('id_perusahaan', $id_perusahaan);
            }
            $count = $this->db->count_all_results('penjualan');

            $data[] = [
                'date' => $date,
                'count' => $count,
                'label' => date('d M', strtotime($date))
            ];
        }
        return $data;
    }

    // Get data penjualan per kategori
    public function get_penjualan_per_kategori()
    {
        $this->db->select('k.nama_kategori, COUNT(dp.id_detail) as total');
        $this->db->from('detail_penjualan dp');
        $this->db->join('barang b', 'dp.id_barang = b.id_barang');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori');
        $this->db->join('penjualan p', 'dp.id_penjualan = p.id_penjualan');

        // Filter untuk 30 hari terakhir
        $this->db->where('p.tanggal_penjualan >=', date('Y-m-d H:i:s', strtotime('-30 days')));

        // Filter berdasarkan perusahaan jika bukan superadmin
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $this->db->where('b.id_perusahaan', $id_perusahaan);
        }

        $this->db->group_by('k.id_kategori');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(5); // Ambil 5 kategori teratas

        return $this->db->get()->result();
    }
}