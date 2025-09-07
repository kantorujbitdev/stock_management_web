<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Retur_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_retur_with_detail($filter = [])
    {
        $this->db->select('rp.*, p.no_invoice, pl.nama_pelanggan, u.nama as nama_user, per.nama_perusahaan');
        $this->db->select('GROUP_CONCAT(b.nama_barang, " (", drp.jumlah_retur, " pcs)") AS daftar_barang');
        $this->db->from('retur_penjualan rp');
        $this->db->join('penjualan p', 'rp.id_penjualan = p.id_penjualan', 'left');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('user u', 'rp.id_user = u.id_user', 'left');
        $this->db->join('perusahaan per', 'pl.id_perusahaan = per.id_perusahaan', 'left');
        $this->db->join('detail_retur_penjualan drp', 'rp.id_retur = drp.id_retur', 'left');
        $this->db->join('barang b', 'drp.id_barang = b.id_barang', 'left');

        // Apply filters
        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('pl.id_perusahaan', $filter['id_perusahaan']);
        }
        if (!empty($filter['id_pelanggan'])) {
            $this->db->where('p.id_pelanggan', $filter['id_pelanggan']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('rp.status', $filter['status']);
        }
        if (!empty($filter['date_from'])) {
            $this->db->where('DATE(rp.tanggal_retur) >=', $filter['date_from']);
        }
        if (!empty($filter['date_to'])) {
            $this->db->where('DATE(rp.tanggal_retur) <=', $filter['date_to']);
        }

        $this->db->group_by('rp.id_retur');
        $this->db->order_by('rp.tanggal_retur', 'DESC');

        $query = $this->db->get();

        if ($query === false) {
            log_message('error', 'Query error: ' . $this->db->error()['message']);
            return array();
        }

        return $query->result();
    }

    public function get_retur_by_perusahaan_with_detail($id_perusahaan, $filter = [])
    {
        $this->db->select('rp.*, p.no_invoice, pl.nama_pelanggan, u.nama as nama_user');
        $this->db->select('GROUP_CONCAT(b.nama_barang, " (", drp.jumlah_retur, " pcs)") AS daftar_barang');
        $this->db->from('retur_penjualan rp');
        $this->db->join('penjualan p', 'rp.id_penjualan = p.id_penjualan', 'left');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('user u', 'rp.id_user = u.id_user', 'left');
        $this->db->join('detail_retur_penjualan drp', 'rp.id_retur = drp.id_retur', 'left');
        $this->db->join('barang b', 'drp.id_barang = b.id_barang', 'left');
        $this->db->where('pl.id_perusahaan', $id_perusahaan);

        // Apply filters
        if (!empty($filter['id_pelanggan'])) {
            $this->db->where('p.id_pelanggan', $filter['id_pelanggan']);
        }
        if (!empty($filter['status'])) {
            $this->db->where('rp.status', $filter['status']);
        }
        if (!empty($filter['date_from'])) {
            $this->db->where('DATE(rp.tanggal_retur) >=', $filter['date_from']);
        }
        if (!empty($filter['date_to'])) {
            $this->db->where('DATE(rp.tanggal_retur) <=', $filter['date_to']);
        }

        $this->db->group_by('rp.id_retur');
        $this->db->order_by('rp.tanggal_retur', 'DESC');

        $query = $this->db->get();

        if ($query === false) {
            log_message('error', 'Query error: ' . $this->db->error()['message']);
            return array();
        }

        return $query->result();
    }

    public function insert_retur($data)
    {
        $this->db->insert('retur_penjualan', $data);
        return $this->db->insert_id();
    }

    public function update_retur($id, $data)
    {
        $this->db->where('id_retur', $id);
        return $this->db->update('retur_penjualan', $data);
    }

    public function get_retur_by_id($id)
    {
        $this->db->select('rp.*, p.no_invoice, pl.nama_pelanggan, pl.alamat as alamat_pelanggan, 
                          pl.telepon as telepon_pelanggan, u.nama as nama_user, per.nama_perusahaan');
        $this->db->from('retur_penjualan rp');
        $this->db->join('penjualan p', 'rp.id_penjualan = p.id_penjualan', 'left');
        $this->db->join('pelanggan pl', 'p.id_pelanggan = pl.id_pelanggan', 'left');
        $this->db->join('user u', 'rp.id_user = u.id_user', 'left');
        $this->db->join('perusahaan per', 'pl.id_perusahaan = per.id_perusahaan', 'left');
        $this->db->where('rp.id_retur', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }

    public function get_last_retur($prefix)
    {
        $this->db->like('no_retur', $prefix, 'after');
        $this->db->order_by('no_retur', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('retur_penjualan');

        if ($query->num_rows() > 0) {
            return $query->row()->no_retur;
        } else {
            return null;
        }
    }
}