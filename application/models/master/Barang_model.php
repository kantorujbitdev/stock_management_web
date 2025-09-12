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
    public function get_barang_with_stok_status($filter)
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan, sg.id_stok, sg.jumlah as stok');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');
        $this->db->join('stok_gudang sg', 'b.id_barang = sg.id_barang', 'left');

        // Apply filters
        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('b.id_perusahaan', $filter['id_perusahaan']);
        }

        if (!empty($filter['id_kategori'])) {
            $this->db->where('b.id_kategori', $filter['id_kategori']);
        }

        if (isset($filter['status']) && $filter['status'] !== '') {
            $this->db->where('b.aktif', $filter['status']);
        }

        if (!empty($filter['search'])) {
            $this->db->group_start();
            $this->db->like('b.nama_barang', $filter['search']);
            $this->db->or_like('b.sku', $filter['search']);
            $this->db->group_end();
        }

        if (!empty($filter['stock_status'])) {
            if ($filter['stock_status'] == 'empty') {
                $this->db->where('sg.id_stok IS NULL');
            } elseif ($filter['stock_status'] == 'has_stock') {
                $this->db->where('sg.id_stok IS NOT NULL');
            }
        }

        // Apply sorting
        if (!empty($filter['sort_by'])) {
            switch ($filter['sort_by']) {
                case 'nama_barang':
                    $this->db->order_by('b.nama_barang', 'ASC');
                    break;
                case 'sku':
                    $this->db->order_by('b.sku', 'ASC');
                    break;
                case 'stok':
                    $this->db->order_by('sg.jumlah', 'DESC');
                    break;
            }
        } else {
            $this->db->order_by('b.nama_barang', 'ASC');
        }

        // Apply pagination - PASTIKAN INI ADA
        if (isset($filter['limit']) && isset($filter['offset'])) {
            $this->db->limit($filter['limit'], $filter['offset']);
        }

        $query = $this->db->get();
        return $query->result();
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

    public function get_all_barang($filter)
    {
        $this->db->select('b.*, k.nama_kategori, p.nama_perusahaan');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');

        // Filter conditions
        if (!empty($filter['search'])) {
            $this->db->group_start();
            $this->db->like('b.nama_barang', $filter['search']);
            $this->db->or_like('b.sku', $filter['search']);
            $this->db->group_end();
        }

        if (!empty($filter['id_kategori'])) {
            $this->db->where('b.id_kategori', $filter['id_kategori']);
        }

        if ($filter['status'] !== '') {
            $this->db->where('b.aktif', $filter['status']);
        }

        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('b.id_perusahaan', $filter['id_perusahaan']);
        }

        // Sort
        if (!empty($filter['sort_by'])) {
            switch ($filter['sort_by']) {
                case 'nama_barang':
                    $this->db->order_by('b.nama_barang', 'ASC');
                    break;
                case 'sku':
                    $this->db->order_by('b.sku', 'ASC');
                    break;
                case 'stok':
                    // This will require a subquery or join to stok_gudang table
                    $this->db->order_by('stok_total', 'DESC');
                    break;
            }
        } else {
            $this->db->order_by('b.nama_barang', 'ASC');
        }

        // Limit and offset for pagination
        if (isset($filter['limit']) && isset($filter['offset'])) {
            $this->db->limit($filter['limit'], $filter['offset']);
        }

        $query = $this->db->get();
        return $query->result();
    }
    // Pastikan fungsi count_barang_with_stok_status ada dan benar
    public function count_barang_with_stok_status($filter = [])
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'b.id_kategori = k.id_kategori', 'left');
        $this->db->join('perusahaan p', 'b.id_perusahaan = p.id_perusahaan', 'left');

        // Filter by perusahaan
        if (isset($filter['id_perusahaan']) && !empty($filter['id_perusahaan'])) {
            $this->db->where('b.id_perusahaan', $filter['id_perusahaan']);
        }

        // Filter by kategori
        if (isset($filter['id_kategori']) && !empty($filter['id_kategori'])) {
            $this->db->where('b.id_kategori', $filter['id_kategori']);
        }

        // Filter by status
        if (isset($filter['status']) && $filter['status'] !== '') {
            $this->db->where('b.aktif', $filter['status']);
        }

        // Filter by search
        if (isset($filter['search']) && !empty($filter['search'])) {
            $this->db->group_start();
            $this->db->like('b.nama_barang', $filter['search']);
            $this->db->or_like('b.sku', $filter['search']);
            $this->db->group_end();
        }

        // Filter by stock status
        if (isset($filter['stock_status']) && !empty($filter['stock_status'])) {
            if ($filter['stock_status'] == 'empty') {
                $this->db->where('(SELECT COUNT(*) FROM stok_awal sa WHERE sa.id_barang = b.id_barang) = 0');
            } elseif ($filter['stock_status'] == 'has_stock') {
                $this->db->where('(SELECT COUNT(*) FROM stok_awal sa WHERE sa.id_barang = b.id_barang) > 0');
            }
        }

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->total;
        }

        return 0;
    }
    public function count_all_barang($filter)
    {
        $this->db->from('barang b');

        // Apply same filters as above but without select, join, order, limit
        if (!empty($filter['search'])) {
            $this->db->group_start();
            $this->db->like('b.nama_barang', $filter['search']);
            $this->db->or_like('b.sku', $filter['search']);
            $this->db->group_end();
        }

        if (!empty($filter['id_kategori'])) {
            $this->db->where('b.id_kategori', $filter['id_kategori']);
        }

        if ($filter['status'] !== '') {
            $this->db->where('b.aktif', $filter['status']);
        }

        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('b.id_perusahaan', $filter['id_perusahaan']);
        }

        return $this->db->count_all_results();
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