<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Barang_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get barang with stock status
    public function get_barang_with_stok_status($filter = [])
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan, 
                    COALESCE(sa.qty_awal, 0) as qty_awal,
                    COALESCE(sa.id_stok_awal, 0) as has_stok_awal,
                    COALESCE(sa.keterangan, "") as keterangan,
                    COALESCE(sa.created_at) as tanggal_update,
                    g.nama_gudang,
                    u.nama as created_by_name');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->join('stok_awal sa', 'b.id_barang = sa.id_barang', 'left');
        $this->db->join('gudang g', 'sa.id_gudang = g.id_gudang', 'left');
        $this->db->join('user u', 'sa.created_by = u.id_user', 'left');

        // Apply filter
        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('b.id_perusahaan', $filter['id_perusahaan']);
        }
        if (!empty($filter['id_kategori'])) {
            $this->db->where('b.id_kategori', $filter['id_kategori']);
        }

        // Filter by stock status
        if (!empty($filter['stock_status'])) {
            switch ($filter['stock_status']) {
                case 'empty':
                    $this->db->where('sa.id_stok_awal IS NULL');
                    break;
                case 'has_stock':
                    $this->db->where('sa.id_stok_awal IS NOT NULL');
                    break;
            }
        }
        if ($this->session->userdata('id_role') != 1 && $this->session->userdata('id_role') != 5) {
            $this->db->where('b.aktif', 1);
        }
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    // Get gudang by perusahaan
    public function get_gudang_by_perusahaan($id_perusahaan)
    {
        $this->db->where('id_perusahaan', $id_perusahaan);
        $this->db->where('status_aktif', 1);
        $this->db->order_by('nama_gudang', 'ASC');
        return $this->db->get('gudang')->result();
    }

    // Check if stok awal exists for barang
    public function check_stok_awal_exists($id_barang)
    {
        $this->db->where('id_barang', $id_barang);
        $query = $this->db->get('stok_awal');
        return $query->num_rows() > 0;
    }

    // Get all barang dengan join untuk efisiensi query
    public function get_all_barang()
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->order_by('b.id_barang', 'DESC');
        return $this->db->get()->result();
    }

    // Get barang by id dengan join
    public function get_barang_by_id($id)
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->where('b.id_barang', $id);
        return $this->db->get()->row();
    }

    // Get barang by perusahaan dengan join
    public function get_barang_by_perusahaan($id_perusahaan)
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->where('b.id_perusahaan', $id_perusahaan);
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    // Get barang with stock - perbaikan query untuk efisiensi
    public function get_barang_with_stock($id_perusahaan)
    {
        $this->db->select('b.*, k.nama_kategori, COALESCE(SUM(sg.jumlah), 0) as stok_tersedia');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('stok_gudang sg', 'b.id_barang = sg.id_barang AND sg.jumlah > 0', 'left');
        $this->db->where('b.id_perusahaan', $id_perusahaan);
        $this->db->where('b.aktif', 1);
        $this->db->group_by('b.id_barang');
        $this->db->having('stok_tersedia >', 0);
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    // Check SKU unique - tambahkan parameter untuk fleksibilitas
    public function check_sku_unique($sku, $id_perusahaan, $id_barang = NULL)
    {
        $this->db->where('sku', $sku);
        $this->db->where('id_perusahaan', $id_perusahaan);

        if ($id_barang) {
            $this->db->where('id_barang !=', $id_barang);
        }

        return $this->db->count_all_results('barang') > 0;
    }

    // Insert barang - return boolean untuk konsistensi
    public function insert_barang($data)
    {
        $this->db->insert('barang', $data);
        return ($this->db->affected_rows() > 0);
    }

    // Update barang - return boolean untuk konsistensi
    public function update_barang($id, $data)
    {
        $this->db->where('id_barang', $id);
        $this->db->update('barang', $data);
        return ($this->db->affected_rows() > 0);
    }

    // Update status barang - return boolean untuk konsistensi
    public function update_status($id, $status)
    {
        $this->db->where('id_barang', $id);
        $this->db->update('barang', ['aktif' => $status]);
        return ($this->db->affected_rows() > 0);
    }

    // Get stok barang - perbaikan untuk handle null
    public function get_stok_barang($id_barang)
    {
        $this->db->select('COALESCE(SUM(sg.jumlah), 0) as stok');
        $this->db->from('stok_gudang sg');
        $this->db->where('sg.id_barang', $id_barang);
        $result = $this->db->get()->row();
        return $result ? $result->stok : 0;
    }

    // Get barang by kategori - tambahkan method baru untuk fleksibilitas
    public function get_barang_by_kategori($id_kategori)
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->where('b.id_kategori', $id_kategori);
        $this->db->where('b.aktif', 1);
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }

    // Search barang - tambahkan method untuk pencarian
    public function search_barang($keyword, $id_perusahaan = null)
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->like('b.nama_barang', $keyword);
        $this->db->or_like('b.sku', $keyword);

        if ($id_perusahaan) {
            $this->db->where('b.id_perusahaan', $id_perusahaan);
        }

        $this->db->where('b.aktif', 1);
        $this->db->order_by('b.nama_barang', 'ASC');
        return $this->db->get()->result();
    }
}