<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Laporan_stok_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get filtered stok
    public function get_filtered_stok($filter = [])
    {
        $this->db->select('sg.*, b.nama_barang, b.sku, k.nama_kategori, 
                           p.nama_perusahaan, g.nama_gudang,
                           COALESCE(sa.qty_awal, 0) as stok_awal');
        $this->db->from('stok_gudang sg');
        $this->db->join('barang b', 'b.id_barang = sg.id_barang', 'left');
        $this->db->join('kategori k', 'k.id_kategori = b.id_kategori', 'left');
        $this->db->join('perusahaan p', 'p.id_perusahaan = sg.id_perusahaan', 'left');
        $this->db->join('gudang g', 'g.id_gudang = sg.id_gudang', 'left');
        $this->db->join('(SELECT id_barang, id_gudang, SUM(qty_awal) as qty_awal 
                     FROM stok_awal 
                     GROUP BY id_barang, id_gudang) sa',
            'sa.id_barang = sg.id_barang AND sa.id_gudang = sg.id_gudang',
            'left'
        );

        // Apply filter
        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('sg.id_perusahaan', $filter['id_perusahaan']);
        }
        if (!empty($filter['id_gudang'])) {
            $this->db->where('sg.id_gudang', $filter['id_gudang']);
        }
        if (!empty($filter['id_kategori'])) {
            $this->db->where('b.id_kategori', $filter['id_kategori']);
        }

        // Filter by stock status
        if (!empty($filter['stock_status'])) {
            switch ($filter['stock_status']) {
                case 'low':
                    $this->db->where('sg.jumlah >', 0);
                    $this->db->where('sg.jumlah <', 10);
                    break;
                case 'over':
                    $this->db->where('sg.jumlah >', 100);
                    break;
                case 'empty':
                    $this->db->where('sg.jumlah', 0);
                    break;
                case 'normal':
                    $this->db->where('sg.jumlah >=', 10);
                    $this->db->where('sg.jumlah <=', 100);
                    break;
            }
        }

        $this->db->order_by('p.nama_perusahaan, g.nama_gudang, b.nama_barang');

        $query = $this->db->get();
        $result = $query->result();

        // Calculate movement for each item
        foreach ($result as &$item) {
            // Get total masuk (pembelian, penerimaan, retur)
            $this->db->select('SUM(jumlah) as total');
            $this->db->where('id_barang', $item->id_barang);
            $this->db->where('id_gudang', $item->id_gudang);
            $this->db->where('jenis', 'masuk');
            $masuk = $this->db->get('log_stok')->row();
            $item->total_masuk = $masuk ? $masuk->total : 0;

            // Get total keluar (penjualan, retur keluar, adjustmen)
            $this->db->select('SUM(jumlah) as total');
            $this->db->where('id_barang', $item->id_barang);
            $this->db->where('id_gudang', $item->id_gudang);
            $this->db->where('jenis', 'keluar');
            $keluar = $this->db->get('log_stok')->row();
            $item->total_keluar = $keluar ? $keluar->total : 0;

            // Get retur masuk (khusus dari retur penjualan)
            $this->db->select('SUM(jumlah) as total');
            $this->db->where('id_barang', $item->id_barang);
            $this->db->where('id_gudang', $item->id_gudang);
            $this->db->where('jenis', 'masuk');
            $this->db->where('tipe_referensi', 'retur');
            $retur_masuk = $this->db->get('log_stok')->row();
            $item->retur_masuk = $retur_masuk ? $retur_masuk->total : 0;

            // Get retur keluar (jika ada)
            $this->db->select('SUM(jumlah) as total');
            $this->db->where('id_barang', $item->id_barang);
            $this->db->where('id_gudang', $item->id_gudang);
            $this->db->where('jenis', 'keluar');
            $this->db->where('tipe_referensi', 'retur');
            $retur_keluar = $this->db->get('log_stok')->row();
            $item->retur_keluar = $retur_keluar ? $retur_keluar->total : 0;

            // Calculate pembelian masuk (non-retur)
            $item->pembelian_masuk = $item->total_masuk - $item->retur_masuk;

            // Calculate penjualan keluar (non-retur)
            $item->penjualan_keluar = $item->total_keluar - $item->retur_keluar;
        }

        return $result;
    }

    // Get retur summary
    public function get_retur_summary($filter = [])
    {
        $this->db->select('b.id_barang, b.nama_barang, b.sku, k.nama_kategori, 
                           p.nama_perusahaan, g.nama_gudang,
                           SUM(CASE WHEN ls.jenis = "masuk" AND ls.tipe_referensi = "retur" THEN ls.jumlah ELSE 0 END) as total_retur_masuk,
                           SUM(CASE WHEN ls.jenis = "keluar" AND ls.tipe_referensi = "retur" THEN ls.jumlah ELSE 0 END) as total_retur_keluar');
        $this->db->from('log_stok ls');
        $this->db->join('barang b', 'b.id_barang = ls.id_barang');
        $this->db->join('kategori k', 'k.id_kategori = b.id_kategori');
        $this->db->join('stok_gudang sg', 'sg.id_barang = b.id_barang AND sg.id_gudang = ls.id_gudang');
        $this->db->join('perusahaan p', 'p.id_perusahaan = sg.id_perusahaan');
        $this->db->join('gudang g', 'g.id_gudang = sg.id_gudang');
        $this->db->where('ls.tipe_referensi', 'retur');

        // Apply filter
        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('sg.id_perusahaan', $filter['id_perusahaan']);
        }
        if (!empty($filter['id_gudang'])) {
            $this->db->where('sg.id_gudang', $filter['id_gudang']);
        }
        if (!empty($filter['id_kategori'])) {
            $this->db->where('b.id_kategori', $filter['id_kategori']);
        }

        $this->db->group_by('b.id_barang, b.nama_barang, b.sku, k.nama_kategori, p.nama_perusahaan, g.nama_gudang');
        $this->db->order_by('p.nama_perusahaan, g.nama_gudang, b.nama_barang');

        return $this->db->get()->result();
    }

    // Get perusahaan list
    public function get_perusahaan_list()
    {
        return $this->db->get('perusahaan')->result();
    }

    // Get gudang by perusahaan
    public function get_gudang_by_perusahaan($id_perusahaan)
    {
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('status_aktif', 1);
        $gudang = $this->db->get('gudang')->result();

        $options = '';
        foreach ($gudang as $row) {
            $options .= '<option value="' . $row->id_gudang . '">' . $row->nama_gudang . '</option>';
        }

        return $options;
    }

    // Get kategori by perusahaan
    public function get_kategori_by_perusahaan($id_perusahaan)
    {
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('status_aktif', 1);
        $kategori = $this->db->get('kategori')->result();

        $options = '';
        foreach ($kategori as $row) {
            $options .= '<option value="' . $row->id_kategori . '">' . $row->nama_kategori . '</option>';
        }

        return $options;
    }
}