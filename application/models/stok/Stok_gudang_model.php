<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_gudang_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_stok_by_barang_gudang($id_barang, $id_gudang)
    {
        $this->db->where('id_barang', $id_barang);
        $this->db->where('id_gudang', $id_gudang);
        $query = $this->db->get('stok_gudang');

        // Debug: Log last query
        log_message('debug', 'Stock query: ' . $this->db->last_query());

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }

    public function update_stock($id_stok, $data)
    {
        $this->db->where('id_stok', $id_stok);
        $this->db->update('stok_gudang', $data);

        // Debug: Log last query
        log_message('debug', 'Update stock query: ' . $this->db->last_query());

        return $this->db->affected_rows() > 0;
    }
    // Get all stok
    public function get_all_stok()
    {
        $this->db->select('stok_gudang.*, barang.nama_barang, barang.sku, gudang.nama_gudang, perusahaan.nama_perusahaan');
        $this->db->from('stok_gudang');
        $this->db->join('barang', 'barang.id_barang = stok_gudang.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = stok_gudang.id_gudang');
        $this->db->join('perusahaan', 'perusahaan.id_perusahaan = stok_gudang.id_perusahaan');
        return $this->db->get()->result();
    }

    // Get stok by perusahaan
    public function get_stok_by_perusahaan($id_perusahaan)
    {
        $this->db->select('stok_gudang.*, barang.nama_barang, barang.sku, gudang.nama_gudang');
        $this->db->from('stok_gudang');
        $this->db->join('barang', 'barang.id_barang = stok_gudang.id_barang');
        $this->db->join('gudang', 'gudang.id_gudang = stok_gudang.id_gudang');
        $this->db->where('stok_gudang.id_perusahaan', $id_perusahaan);
        return $this->db->get()->result();
    }

    // Get stok by gudang
    public function get_stok_by_gudang($id_gudang)
    {
        $this->db->select('stok_gudang.*, barang.nama_barang, barang.sku');
        $this->db->from('stok_gudang');
        $this->db->join('barang', 'barang.id_barang = stok_gudang.id_barang');
        $this->db->where('stok_gudang.id_gudang', $id_gudang);
        return $this->db->get()->result();
    }

    // Insert stok
    public function insert_stok($data)
    {
        // Debug: Log data yang akan diinsert
        log_message('debug', 'Inserting stock data: ' . json_encode($data));

        $this->db->insert('stok_gudang', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            // Debug: Log error
            $error = $this->db->error();
            log_message('error', 'Insert stok_gudang failed: ' . $error['message']);
            return false;
        }
    }

    public function update_stok($id, $data)
    {
        // Debug: Log data yang akan diupdate
        log_message('debug', 'Updating stock data: ' . json_encode([
            'id' => $id,
            'data' => $data
        ]));

        $this->db->where('id_stok', $id);
        $this->db->update('stok_gudang', $data);

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            // Debug: Log error
            $error = $this->db->error();
            log_message('error', 'Update stok_gudang failed: ' . $error['message']);
            return false;
        }
    }
    // Delete stok
    public function delete_stok($id)
    {
        return $this->db->delete('stok_gudang', array('id_stok' => $id));
    }
}