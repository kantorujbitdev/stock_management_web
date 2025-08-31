<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hak_akses_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get akses by role and fitur
    public function get_akses($id_role, $fitur) {
        $this->db->where('id_role', $id_role);
        $this->db->where('nama_fitur', $fitur);
        return $this->db->get('hak_akses_fitur')->row();
    }

    // Get all hak akses
    public function get_all_hak_akses() {
        $this->db->select('h.*, r.nama_role');
        $this->db->from('hak_akses_fitur h');
        $this->db->join('role_user r', 'h.id_role = r.id_role');
        $this->db->order_by('h.id_role', 'ASC');
        return $this->db->get()->result();
    }

    // Get hak akses by role
    public function get_hak_akses_by_role($id_role) {
        $this->db->where('id_role', $id_role);
        return $this->db->get('hak_akses_fitur')->result();
    }

    // Insert hak akses
    public function insert_hak_akses($data) {
        return $this->db->insert('hak_akses_fitur', $data);
    }

    // Update hak akses
    public function update_hak_akses($id, $data) {
        $this->db->where('id_hak_akses', $id);
        return $this->db->update('hak_akses_fitur', $data);
    }

    // Delete hak akses by role
    public function delete_hak_akses_by_role($id_role) {
        $this->db->where('id_role', $id_role);
        return $this->db->delete('hak_akses_fitur');
    }
    

    // Get all fitur
    public function get_all_fitur() {
        return [
            'dashboard' => 'Dashboard',
            'perusahaan' => 'Perusahaan',
            'gudang' => 'Gudang',
            'kategori' => 'Kategori Barang',
            'barang' => 'Barang',
            'supplier' => 'Supplier',
            'pelanggan' => 'Pelanggan',
            'stok_awal' => 'Stok Awal',
            'penerimaan' => 'Penerimaan Barang',
            'transfer' => 'Transfer Stok',
            'penyesuaian' => 'Penyesuaian Stok',
            'riwayat' => 'Riwayat Stok',
            'penjualan' => 'Penjualan',
            'retur' => 'Retur Penjualan',
            'laporan_stok' => 'Laporan Stok',
            'laporan_penjualan' => 'Laporan Penjualan',
            'laporan_retur' => 'Laporan Retur',
            'laporan_transfer' => 'Laporan Transfer',
            'pengaturan_sistem' => 'Pengaturan Sistem',
            'backup' => 'Backup Database',
            'user' => 'Manajemen User',
            'hak_akses' => 'Hak Akses'
        ];
    }
}