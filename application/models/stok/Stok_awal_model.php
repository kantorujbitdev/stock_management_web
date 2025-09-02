<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_awal_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all stok awal with filter
    public function get_all_stok_awal($filter = []) {
        $this->db->select('sa.*, b.nama_barang, b.sku, g.nama_gudang, p.nama_perusahaan, u.nama as created_by_name');
        $this->db->from('stok_awal sa');
        $this->db->join('barang b', 'b.id_barang = sa.id_barang', 'left');
        $this->db->join('gudang g', 'g.id_gudang = sa.id_gudang', 'left');
        $this->db->join('perusahaan p', 'p.id_perusahaan = sa.id_perusahaan', 'left');
        $this->db->join('user u', 'u.id_user = sa.created_by', 'left');
        
        // Apply filter
        if (!empty($filter['id_perusahaan'])) {
            $this->db->where('sa.id_perusahaan', $filter['id_perusahaan']);
        }
        if (!empty($filter['id_gudang'])) {
            $this->db->where('sa.id_gudang', $filter['id_gudang']);
        }
        if (!empty($filter['id_barang'])) {
            $this->db->where('sa.id_barang', $filter['id_barang']);
        }
        
        $this->db->order_by('sa.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    // Get stok awal by perusahaan with filter
    public function get_stok_awal_by_perusahaan($id_perusahaan, $filter = []) {
        $this->db->select('sa.*, b.nama_barang, b.sku, g.nama_gudang, p.nama_perusahaan, u.nama as created_by_name');
        $this->db->from('stok_awal sa');
        $this->db->join('barang b', 'b.id_barang = sa.id_barang', 'left');
        $this->db->join('gudang g', 'g.id_gudang = sa.id_gudang', 'left');
        $this->db->join('perusahaan p', 'p.id_perusahaan = sa.id_perusahaan', 'left');
        $this->db->join('user u', 'u.id_user = sa.created_by', 'left');
        $this->db->where('sa.id_perusahaan', $id_perusahaan);
        
        // Apply filter
        if (!empty($filter['id_gudang'])) {
            $this->db->where('sa.id_gudang', $filter['id_gudang']);
        }
        if (!empty($filter['id_barang'])) {
            $this->db->where('sa.id_barang', $filter['id_barang']);
        }
        
        $this->db->order_by('sa.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    // Get stok awal by id
    public function get_stok_awal_by_id($id) {
        $this->db->select('sa.*, b.nama_barang, b.sku, g.nama_gudang, p.nama_perusahaan, u.nama as created_by_name');
        $this->db->from('stok_awal sa');
        $this->db->join('barang b', 'b.id_barang = sa.id_barang', 'left');
        $this->db->join('gudang g', 'g.id_gudang = sa.id_gudang', 'left');
        $this->db->join('perusahaan p', 'p.id_perusahaan = sa.id_perusahaan', 'left');
        $this->db->join('user u', 'u.id_user = sa.created_by', 'left');
        $this->db->where('sa.id_stok_awal', $id);
        return $this->db->get()->row();
    }
    
    // // Check if stok awal exists for barang and gudang
    // public function check_stok_awal_exists($id_barang, $id_gudang, $id_stok_awal = null) {
    //     $this->db->where('id_barang', $id_barang);
    //     $this->db->where('id_gudang', $id_gudang);
        
    //     if ($id_stok_awal) {
    //         $this->db->where('id_stok_awal !=', $id_stok_awal);
    //     }
        
    //     $query = $this->db->get('stok_awal');
    //     return $query->num_rows() > 0;
    // }
    
    // Insert stok awal with transaction
    public function insert_stok_awal($data) {
        $this->db->trans_start();
        $this->db->insert('stok_awal', $data);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Gagal insert stok awal: ' . $this->db->error()['message']);
            return false;
        }
        return $insert_id;
    }
    
    // Update stok awal with transaction
    public function update_stok_awal($id, $data) {
        $this->db->trans_start();
        $this->db->where('id_stok_awal', $id);
        $this->db->update('stok_awal', $data);
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Gagal update stok awal: ' . $this->db->error()['message']);
            return false;
        }
        return true;
    }
    
    // Delete stok awal with transaction
    public function delete_stok_awal($id) {
        $this->db->trans_start();
        $this->db->delete('stok_awal', array('id_stok_awal' => $id));
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Gagal delete stok awal: ' . $this->db->error()['message']);
            return false;
        }
        return true;
    }
    public function get_barang_with_stock_status($filter = []) {
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
    if (!empty($filter['id_gudang'])) {
        $this->db->where('sa.id_gudang', $filter['id_gudang']);
    }
    if (!empty($filter['id_barang'])) {
        $this->db->where('b.id_barang', $filter['id_barang']);
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
    
    $this->db->where('b.aktif', 1);
    $this->db->order_by('p.nama_perusahaan, b.nama_barang');
    
    return $this->db->get()->result();
}
    // Cek stok awal exists
    public function check_stok_awal_exists($id_barang, $id_gudang) {
        $this->db->where('id_barang', $id_barang);
        $this->db->where('id_gudang', $id_gudang);
        $query = $this->db->get('stok_awal');
        return $query->num_rows() > 0;
    }

    // // Insert stok awal
    // public function insert_stok_awal($data) {
    //     return $this->db->insert('stok_awal', $data);
    // }
}