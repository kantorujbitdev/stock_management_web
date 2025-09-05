<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_all_penjualan_with_detail($filter = [])
    {
        $this->db->select('p.*, pl.nama_pelanggan, pl.alamat, u.nama as nama_user, per.nama_perusahaan');
        $this->db->select('GROUP_CONCAT(b.nama_barang, " (", dp.jumlah, " pcs)") AS daftar_barang');
        $this->db->from('penjualan p');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('user u', 'p.id_user = u.id_user', 'left');
        $this->db->join('perusahaan per', 'pl.id_perusahaan = per.id_perusahaan', 'left');
        $this->db->join('detail_penjualan dp', 'p.id_penjualan = dp.id_penjualan', 'left');
        $this->db->join('barang b', 'dp.id_barang = b.id_barang', 'left');

        // Apply filters
        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('pl.id_perusahaan', $filter['id_perusahaan']);
        }
        if (!empty($filter['id_pelanggan'])) {
            $this->db->where('p.id_pelanggan', $filter['id_pelanggan']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('p.status', $filter['status']);
        }
        if (!empty($filter['date_from'])) {
            $this->db->where('DATE(p.tanggal_penjualan) >=', $filter['date_from']);
        }
        if (!empty($filter['date_to'])) {
            $this->db->where('DATE(p.tanggal_penjualan) <=', $filter['date_to']);
        }

        $this->db->group_by('p.id_penjualan');
        $this->db->order_by('p.tanggal_penjualan', 'DESC');

        $query = $this->db->get();

        // Cek apakah query berhasil
        if ($query === false) {
            // Log error
            log_message('error', 'Query error: ' . $this->db->error()['message']);
            return array();
        }

        return $query->result();
    }

    public function get_penjualan_by_perusahaan_with_detail($id_perusahaan, $filter = [])
    {
        $this->db->select('p.*, pl.nama_pelanggan, pl.alamat, u.nama as nama_user');
        $this->db->select('GROUP_CONCAT(b.nama_barang, " (", dp.jumlah, " pcs)") AS daftar_barang');
        $this->db->from('penjualan p');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('user u', 'p.id_user = u.id_user', 'left');
        $this->db->join('detail_penjualan dp', 'p.id_penjualan = dp.id_penjualan', 'left');
        $this->db->join('barang b', 'dp.id_barang = b.id_barang', 'left');
        $this->db->where('pl.id_perusahaan', $id_perusahaan);

        // Apply filters
        if (!empty($filter['id_pelanggan'])) {
            $this->db->where('p.id_pelanggan', $filter['id_pelanggan']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('p.status', $filter['status']);
        }
        if (!empty($filter['date_from'])) {
            $this->db->where('DATE(p.tanggal_penjualan) >=', $filter['date_from']);
        }
        if (!empty($filter['date_to'])) {
            $this->db->where('DATE(p.tanggal_penjualan) <=', $filter['date_to']);
        }

        $this->db->group_by('p.id_penjualan');
        $this->db->order_by('p.tanggal_penjualan', 'DESC');

        $query = $this->db->get();

        // Cek apakah query berhasil
        if ($query === false) {
            // Log error
            log_message('error', 'Query error: ' . $this->db->error()['message']);
            return array();
        }

        return $query->result();
    }
    public function get_all_penjualan($filter = [])
    {
        $this->db->select('p.*, pl.nama_pelanggan, u.nama as nama_user, per.nama_perusahaan');
        $this->db->from('penjualan p');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('user u', 'p.id_user = u.id_user', 'left');
        $this->db->join('perusahaan per', 'pl.id_perusahaan = per.id_perusahaan', 'left');

        // Apply filters
        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('pl.id_perusahaan', $filter['id_perusahaan']);
        }
        if (!empty($filter['id_pelanggan'])) {
            $this->db->where('p.id_pelanggan', $filter['id_pelanggan']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('p.status', $filter['status']);
        }
        if (!empty($filter['date_from'])) {
            $this->db->where('DATE(p.tanggal_penjualan) >=', $filter['date_from']);
        }
        if (!empty($filter['date_to'])) {
            $this->db->where('DATE(p.tanggal_penjualan) <=', $filter['date_to']);
        }

        $this->db->order_by('p.tanggal_penjualan', 'DESC');
        return $this->db->get()->result();
    }

    public function get_penjualan_by_perusahaan($id_perusahaan, $filter = [])
    {
        $this->db->select('p.*, pl.nama_pelanggan, u.nama as nama_user');
        $this->db->from('penjualan p');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('user u', 'p.id_user = u.id_user', 'left');
        $this->db->where('pl.id_perusahaan', $id_perusahaan);

        // Apply filters
        if (!empty($filter['id_pelanggan'])) {
            $this->db->where('p.id_pelanggan', $filter['id_pelanggan']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('p.status', $filter['status']);
        }
        if (!empty($filter['date_from'])) {
            $this->db->where('DATE(p.tanggal_penjualan) >=', $filter['date_from']);
        }
        if (!empty($filter['date_to'])) {
            $this->db->where('DATE(p.tanggal_penjualan) <=', $filter['date_to']);
        }

        $this->db->order_by('p.tanggal_penjualan', 'DESC');
        return $this->db->get()->result();
    }
    public function insert_penjualan($data)
    {
        $this->db->insert('penjualan', $data);

        // Debug: Log last query
        log_message('debug', 'Last query: ' . $this->db->last_query());

        return $this->db->insert_id();
    }

    public function update_penjualan($id, $data)
    {
        $this->db->where('id_penjualan', $id);
        return $this->db->update('penjualan', $data);
    }

    public function delete_penjualan($id)
    {
        $this->db->where('id_penjualan', $id);
        return $this->db->delete('penjualan');
    }


    public function get_last_invoice($prefix)
    {
        $this->db->like('no_invoice', $prefix, 'after');
        $this->db->order_by('no_invoice', 'DESC');
        $this->db->limit(1);
        return $this->db->get('penjualan')->row()->no_invoice;
    }
    public function get_penjualan_by_id($id)
    {
        $this->db->select('p.*, pl.nama_pelanggan, pl.alamat as alamat_pelanggan, pl.telepon as telepon_pelanggan, 
                      u.nama as nama_user, per.nama_perusahaan');
        $this->db->from('penjualan p');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('user u', 'p.id_user = u.id_user', 'left');
        $this->db->join('perusahaan per', 'pl.id_perusahaan = per.id_perusahaan', 'left');
        $this->db->where('p.id_penjualan', $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }

}