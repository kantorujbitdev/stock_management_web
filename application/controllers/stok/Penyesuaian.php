<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penyesuaian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('stok/Penyesuaian_model');
        $this->load->model('perusahaan/Perusahaan_model');
        $this->load->model('perusahaan/Gudang_model');
        $this->load->model('master/Barang_model');
        $this->load->model('stok/Stok_gudang_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        $this->hak_akses->cek_akses('penyesuaian');

        // Hanya Super Admin yang bisa akses
        if ($this->session->userdata('id_role') != 5) {
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['title'] = 'Data Penyesuaian Stok';
        $data['penyesuaian'] = $this->Penyesuaian_model->get_all_penyesuaian();

        $data['content'] = 'stok/penyesuaian_list';
        $this->load->view('template/template', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah Penyesuaian Stok';
        $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();

        $data['content'] = 'stok/penyesuaian_form';
        $this->load->view('template/template', $data);
    }

    public function add_process()
    {
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_gudang', 'Gudang', 'required');
        $this->form_validation->set_rules('id_barang', 'Barang', 'required');
        $this->form_validation->set_rules('jumlah_baru', 'Jumlah Baru', 'required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('alasan', 'Alasan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            $id_barang = $this->input->post('id_barang');
            $id_gudang = $this->input->post('id_gudang');
            $jumlah_baru = $this->input->post('jumlah_baru');

            // Get stok saat ini
            $stok_saat_ini = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);
            $jumlah_saat_ini = $stok_saat_ini ? $stok_saat_ini->jumlah : 0;

            // Hitung selisih
            $selisih = $jumlah_baru - $jumlah_saat_ini;

            $data = [
                'id_barang' => $id_barang,
                'id_gudang' => $id_gudang,
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'jumlah_saat_ini' => $jumlah_saat_ini,
                'jumlah_baru' => $jumlah_baru,
                'selisih' => $selisih,
                'alasan' => $this->input->post('alasan'),
                'id_user' => $this->session->userdata('id_user')
            ];

            $insert = $this->Penyesuaian_model->insert_penyesuaian($data);

            if ($insert) {
                $id_penyesuaian = $this->db->insert_id();

                // Update stok gudang
                $this->update_stok_gudang($id_barang, $id_gudang, $selisih, $id_penyesuaian);

                $this->session->set_flashdata('success', 'Penyesuaian stok berhasil ditambahkan');
                redirect('penyesuaian');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan penyesuaian stok');
                redirect('penyesuaian/add');
            }
        }
    }

    // Helper function untuk update stok gudang
    private function update_stok_gudang($id_barang, $id_gudang, $selisih, $id_penyesuaian)
    {
        $this->load->model('stok/Stok_gudang_model');

        // Get perusahaan from gudang
        $this->load->model('perusahaan/Gudang_model');
        $gudang = $this->Gudang_model->get_gudang_by_id($id_gudang);
        $id_perusahaan = $gudang->id_perusahaan;

        // Cek apakah stok gudang sudah ada
        $stok_gudang = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);

        if ($stok_gudang) {
            // Update stok gudang yang sudah ada
            $jumlah = $stok_gudang->jumlah + $selisih;
            $this->Stok_gudang_model->update_stok($stok_gudang->id_stok, ['jumlah' => $jumlah]);
        } else {
            // Insert stok gudang baru
            $data_stok = [
                'id_perusahaan' => $id_perusahaan,
                'id_gudang' => $id_gudang,
                'id_barang' => $id_barang,
                'jumlah' => $selisih
            ];

            $this->Stok_gudang_model->insert_stok($data_stok);
        }

        // Insert log stok
        $this->load->model('stok/Log_stok_model');

        $jenis = ($selisih > 0) ? 'masuk' : 'keluar';
        $jumlah = abs($selisih);

        $data_log = [
            'id_barang' => $id_barang,
            'id_user' => $this->session->userdata('id_user'),
            'id_perusahaan' => $id_perusahaan,
            'id_gudang' => $id_gudang,
            'jenis' => 'penyesuaian',
            'jumlah' => $jumlah,
            'keterangan' => 'Penyesuaian Stok: ' . $this->input->post('alasan'),
            'id_referensi' => $id_penyesuaian,
            'tipe_referensi' => 'penyesuaian'
        ];

        $this->Log_stok_model->insert_log($data_log);
    }

    public function get_gudang_by_perusahaan()
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        $gudang = $this->Gudang_model->get_gudang_by_perusahaan($id_perusahaan);

        echo '<option value="">-- Pilih Gudang --</option>';
        foreach ($gudang as $row) {
            echo '<option value="' . $row->id_gudang . '">' . $row->nama_gudang . '</option>';
        }
    }

    public function get_barang_by_gudang()
    {
        $id_gudang = $this->input->post('id_gudang');

        $this->db->select('stok_gudang.*, barang.nama_barang, barang.sku');
        $this->db->from('stok_gudang');
        $this->db->join('barang', 'barang.id_barang = stok_gudang.id_barang');
        $this->db->where('stok_gudang.id_gudang', $id_gudang);
        $barang = $this->db->get()->result();

        echo '<option value="">-- Pilih Barang --</option>';
        foreach ($barang as $row) {
            echo '<option value="' . $row->id_barang . '" data-stok="' . $row->jumlah . '">' . $row->nama_barang . ' - ' . $row->sku . ' (Stok: ' . $row->jumlah . ')</option>';
        }
    }

    public function get_stok_barang()
    {
        $id_barang = $this->input->post('id_barang');
        $id_gudang = $this->input->post('id_gudang');

        $stok = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);

        if ($stok) {
            echo $stok->jumlah;
        } else {
            echo 0;
        }
    }
}