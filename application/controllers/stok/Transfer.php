<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transfer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('stok/Transfer_model');
        $this->load->model('perusahaan/Perusahaan_model');
        $this->load->model('perusahaan/Gudang_model');
        $this->load->model('master/Barang_model');
        $this->load->model('stok/Stok_gudang_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        $this->hak_akses->cek_akses('transfer');
    }

    public function index()
    {
        $data['title'] = 'Data Transfer Stok';

        // Jika Super Admin, tampilkan semua transfer
        if ($this->session->userdata('id_role') == 5) {
            $data['transfer'] = $this->Transfer_model->get_all_transfer();
        } else {
            // Jika Admin Pusat, tampilkan transfer milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['transfer'] = $this->Transfer_model->get_transfer_by_perusahaan($id_perusahaan);
        }

        $data['content'] = 'stok/transfer_list';
        $this->load->view('template/template', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah Transfer Stok';

        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }

        $data['content'] = 'stok/transfer_form';
        $this->load->view('template/template', $data);
    }

    public function add_process()
    {
        $this->form_validation->set_rules('id_barang', 'Barang', 'required');
        $this->form_validation->set_rules('id_gudang_asal', 'Gudang Asal', 'required');
        $this->form_validation->set_rules('id_gudang_tujuan', 'Gudang Tujuan', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_perusahaan_input = $this->input->post('id_perusahaan');

                if ($id_perusahaan_user != $id_perusahaan_input) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                    redirect('transfer');
                }
            }

            $id_barang = $this->input->post('id_barang');
            $id_gudang_asal = $this->input->post('id_gudang_asal');
            $id_gudang_tujuan = $this->input->post('id_gudang_tujuan');
            $jumlah = $this->input->post('jumlah');

            // Cek apakah gudang asal dan tujuan berbeda
            if ($id_gudang_asal == $id_gudang_tujuan) {
                $this->session->set_flashdata('error', 'Gudang asal dan tujuan tidak boleh sama');
                redirect('transfer/add');
            }

            // Cek stok di gudang asal
            $stok_asal = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang_asal);

            if (!$stok_asal || $stok_asal->jumlah < $jumlah) {
                $this->session->set_flashdata('error', 'Stok di gudang asal tidak mencukupi');
                redirect('transfer/add');
            }

            // Generate no transfer
            $id_perusahaan = $this->input->post('id_perusahaan');
            $no_transfer = 'TRF-' . date('Ymd') . '-' . $this->Transfer_model->get_next_number($id_perusahaan);

            $data = [
                'no_transfer' => $no_transfer,
                'id_barang' => $id_barang,
                'id_gudang_asal' => $id_gudang_asal,
                'id_gudang_tujuan' => $id_gudang_tujuan,
                'jumlah' => $jumlah,
                'id_user' => $this->session->userdata('id_user'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => 'pending'
            ];

            $insert = $this->Transfer_model->insert_transfer($data);

            if ($insert) {
                $this->session->set_flashdata('success', 'Transfer stok berhasil ditambahkan. Menunggu persetujuan.');
                redirect('transfer');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan transfer stok');
                redirect('transfer/add');
            }
        }
    }

    public function approve($id)
    {
        // Cek apakah user punya akses ke transfer ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $transfer = $this->Transfer_model->get_transfer_by_id($id);

            // Get gudang to check perusahaan
            $gudang_asal = $this->Gudang_model->get_gudang_by_id($transfer->id_gudang_asal);

            if ($gudang_asal->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke transfer ini');
                redirect('transfer');
            }
        }

        $transfer = $this->Transfer_model->get_transfer_by_id($id);

        if ($transfer->status != 'pending') {
            $this->session->set_flashdata('error', 'Transfer ini sudah diproses');
            redirect('transfer');
        }

        // Cek stok di gudang asal
        $stok_asal = $this->Stok_gudang_model->get_stok_by_barang_gudang($transfer->id_barang, $transfer->id_gudang_asal);

        if (!$stok_asal || $stok_asal->jumlah < $transfer->jumlah) {
            $this->session->set_flashdata('error', 'Stok di gudang asal tidak mencukupi');
            redirect('transfer');
        }

        // Update status transfer
        $this->Transfer_model->update_transfer($id, ['status' => 'selesai']);

        // Update stok gudang asal
        $this->Stok_gudang_model->update_stok($stok_asal->id_stok, ['jumlah' => $stok_asal->jumlah - $transfer->jumlah]);

        // Update stok gudang tujuan
        $stok_tujuan = $this->Stok_gudang_model->get_stok_by_barang_gudang($transfer->id_barang, $transfer->id_gudang_tujuan);

        if ($stok_tujuan) {
            $this->Stok_gudang_model->update_stok($stok_tujuan->id_stok, ['jumlah' => $stok_tujuan->jumlah + $transfer->jumlah]);
        } else {
            // Get perusahaan from gudang
            $gudang_tujuan = $this->Gudang_model->get_gudang_by_id($transfer->id_gudang_tujuan);

            $data_stok = [
                'id_perusahaan' => $gudang_tujuan->id_perusahaan,
                'id_gudang' => $transfer->id_gudang_tujuan,
                'id_barang' => $transfer->id_barang,
                'jumlah' => $transfer->jumlah
            ];

            $this->Stok_gudang_model->insert_stok($data_stok);
        }

        // Insert log stok keluar dari gudang asal
        $this->load->model('stok/Log_stok_model');

        $gudang_asal = $this->Gudang_model->get_gudang_by_id($transfer->id_gudang_asal);

        $data_log_asal = [
            'id_barang' => $transfer->id_barang,
            'id_user' => $this->session->userdata('id_user'),
            'id_perusahaan' => $gudang_asal->id_perusahaan,
            'id_gudang' => $transfer->id_gudang_asal,
            'jenis' => 'transfer_keluar',
            'jumlah' => $transfer->jumlah,
            'keterangan' => 'Transfer ke ' . $this->Gudang_model->get_gudang_by_id($transfer->id_gudang_tujuan)->nama_gudang,
            'id_referensi' => $id,
            'tipe_referensi' => 'transfer'
        ];

        $this->Log_stok_model->insert_log($data_log_asal);

        // Insert log stok masuk ke gudang tujuan
        $gudang_tujuan = $this->Gudang_model->get_gudang_by_id($transfer->id_gudang_tujuan);

        $data_log_tujuan = [
            'id_barang' => $transfer->id_barang,
            'id_user' => $this->session->userdata('id_user'),
            'id_perusahaan' => $gudang_tujuan->id_perusahaan,
            'id_gudang' => $transfer->id_gudang_tujuan,
            'jenis' => 'transfer_masuk',
            'jumlah' => $transfer->jumlah,
            'keterangan' => 'Transfer dari ' . $gudang_asal->nama_gudang,
            'id_referensi' => $id,
            'tipe_referensi' => 'transfer'
        ];

        $this->Log_stok_model->insert_log($data_log_tujuan);

        $this->session->set_flashdata('success', 'Transfer stok berhasil disetujui');
        redirect('transfer');
    }

    public function reject($id)
    {
        // Cek apakah user punya akses ke transfer ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $transfer = $this->Transfer_model->get_transfer_by_id($id);

            // Get gudang to check perusahaan
            $gudang_asal = $this->Gudang_model->get_gudang_by_id($transfer->id_gudang_asal);

            if ($gudang_asal->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke transfer ini');
                redirect('transfer');
            }
        }

        $transfer = $this->Transfer_model->get_transfer_by_id($id);

        if ($transfer->status != 'pending') {
            $this->session->set_flashdata('error', 'Transfer ini sudah diproses');
            redirect('transfer');
        }

        // Update status transfer
        $this->Transfer_model->update_transfer($id, ['status' => 'batal']);

        $this->session->set_flashdata('success', 'Transfer stok berhasil dibatalkan');
        redirect('transfer');
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
        $this->db->where('stok_gudang.jumlah >', 0);
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