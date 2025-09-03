<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('penjualan/Penjualan_model');
        $this->load->model('penjualan/Detail_penjualan_model');
        $this->load->model('master/Pelanggan_model');
        $this->load->model('master/Barang_model');
        $this->load->model('stok/Stok_gudang_model');
        $this->load->model('stok/Log_stok_model');
        $this->load->model('perusahaan/Perusahaan_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        if (!$this->hak_akses->cek_akses('penjualan')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke menu Penjualan');
            redirect('dashboard');
        }
    }


    public function index()
    {
        $data['title'] = 'Data Penjualan';

        // Filter parameters
        $filter = [
            'id_perusahaan' => $this->input->get('id_perusahaan'),
            'id_pelanggan' => $this->input->get('id_pelanggan'),
            'status' => $this->input->get('status'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to')
        ];

        // Get data with filter
        if ($this->session->userdata('id_role') == 5) {
            $data['penjualan'] = $this->Penjualan_model->get_all_penjualan($filter);
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $filter['id_perusahaan'] = $id_perusahaan;
            $data['penjualan'] = $this->Penjualan_model->get_penjualan_by_perusahaan($id_perusahaan, $filter);
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }

        $data['pelanggan'] = $this->Pelanggan_model->get_pelanggan_aktif();
        $data['filter'] = $filter;
        $data['content'] = 'penjualan/penjualan_list';
        $this->load->view('template/template', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah Penjualan';

        // Get data for dropdown
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
            $data['pelanggan'] = $this->Pelanggan_model->get_pelanggan_aktif();
        } else {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
            $data['pelanggan'] = $this->Pelanggan_model->get_pelanggan_by_perusahaan($id_perusahaan);
        }

        $data['content'] = 'penjualan/penjualan_form';
        $this->load->view('template/template', $data);
    }
    public function add_process()
    {
        $this->form_validation->set_rules('id_pelanggan', 'Pelanggan', 'required');
        $this->form_validation->set_rules('items', 'Items', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                // Validate company access if needed
            }

            // Generate invoice number
            $no_invoice = $this->generate_invoice();

            // Calculate totals
            $items = json_decode($this->input->post('items'), true);
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['subtotal'];
            }

            $diskon = $this->input->post('diskon') ?: 0;
            $pajak = $this->input->post('pajak') ?: 0;
            $total_bayar = $subtotal - $diskon + $pajak;

            // Insert penjualan header
            $data_penjualan = [
                'no_invoice' => $no_invoice,
                'id_user' => $this->session->userdata('id_user'),
                'id_pelanggan' => $this->input->post('id_pelanggan'),
                'tanggal_penjualan' => date('Y-m-d H:i:s'),
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'pajak' => $pajak,
                'total_harga' => $total_bayar,
                'keterangan' => $this->input->post('keterangan'),
                'status' => 'proses'
            ];

            $this->db->trans_start();

            $insert_penjualan = $this->Penjualan_model->insert_penjualan($data_penjualan);

            if ($insert_penjualan) {
                $id_penjualan = $this->db->insert_id();

                // Insert detail penjualan and reduce stock
                foreach ($items as $item) {
                    $detail_data = [
                        'id_penjualan' => $id_penjualan,
                        'id_barang' => $item['id_barang'],
                        'id_gudang' => $item['id_gudang'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $item['harga_satuan'],
                        'diskon_item' => $item['diskon_item'] ?: 0,
                        'subtotal' => $item['subtotal']
                    ];

                    $this->Detail_penjualan_model->insert_detail_penjualan($detail_data);

                    // Reduce stock
                    $this->reduce_stock($item['id_barang'], $item['id_gudang'], $item['jumlah'], $id_penjualan);
                }

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('error', 'Gagal menyimpan penjualan');
                } else {
                    $this->session->set_flashdata('success', 'Penjualan berhasil disimpan dengan invoice: ' . $no_invoice);
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Gagal menyimpan penjualan');
            }

            redirect('penjualan');
        }
    }

    public function view($id)
    {
        $data['title'] = 'Detail Penjualan';
        $data['penjualan'] = $this->Penjualan_model->get_penjualan_by_id($id);
        $data['detail'] = $this->Detail_penjualan_model->get_detail_by_penjualan($id);

        if (!$data['penjualan']) {
            show_404();
        }

        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            if ($data['penjualan']->id_perusahaan != $this->session->userdata('id_perusahaan')) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                redirect('penjualan');
            }
        }

        $data['content'] = 'penjualan/penjualan_detail';
        $this->load->view('template/template', $data);
    }

    public function get_barang_by_perusahaan()
    {
        $id_perusahaan = $this->input->get('id_perusahaan');

        if (!$id_perusahaan) {
            echo json_encode([]);
            return;
        }

        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $user_company = $this->session->userdata('id_perusahaan');
            if ($id_perusahaan != $user_company) {
                echo json_encode([]);
                return;
            }
        }

        $barang = $this->Barang_model->get_barang_with_stock($id_perusahaan);
        echo json_encode($barang);
    }

    public function get_stock_by_barang()
    {
        $id_barang = $this->input->get('id_barang');

        if (!$id_barang) {
            echo json_encode([]);
            return;
        }

        $stock = $this->Stok_gudang_model->get_stock_by_barang($id_barang);
        echo json_encode($stock);
    }

    private function generate_invoice()
    {
        $prefix = 'INV-' . date('Ym');
        $last_invoice = $this->Penjualan_model->get_last_invoice($prefix);

        if ($last_invoice) {
            $last_number = intval(substr($last_invoice, -4));
            $new_number = str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $new_number = '0001';
        }

        return $prefix . $new_number;
    }

    private function reduce_stock($id_barang, $id_gudang, $jumlah, $id_penjualan)
    {
        // Get current stock
        $stock = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);

        if (!$stock || $stock->jumlah < $jumlah) {
            throw new Exception('Stok tidak mencukupi');
        }

        // Reduce stock
        $this->Stok_gudang_model->update_stock($stock->id_stok, [
            'jumlah' => $stock->jumlah - $jumlah,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Log stock movement
        $this->Log_stok_model->insert_log([
            'id_barang' => $id_barang,
            'id_user' => $this->session->userdata('id_user'),
            'id_perusahaan' => $stock->id_perusahaan,
            'id_gudang' => $id_gudang,
            'jenis' => 'keluar',
            'jumlah' => $jumlah,
            'keterangan' => 'Penjualan Invoice: ' . $this->Penjualan_model->get_penjualan_by_id($id_penjualan)->no_invoice,
            'id_referensi' => $id_penjualan,
            'tipe_referensi' => 'penjualan'
        ]);
    }
}