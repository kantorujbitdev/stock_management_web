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
        $this->load->model('penjualan/Log_status_penjualan_model');

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
            $data['penjualan'] = $this->Penjualan_model->get_all_penjualan_with_detail($filter);
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $filter['id_perusahaan'] = $id_perusahaan;
            $data['penjualan'] = $this->Penjualan_model->get_penjualan_by_perusahaan_with_detail($id_perusahaan, $filter);
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }

        $data['pelanggan'] = $this->Pelanggan_model->get_pelanggan_aktif();
        $data['filter'] = $filter;
        $data['content'] = 'penjualan/penjualan_list';
        $this->load->view('template/template', $data);
    }

    public function get_stock_by_barang()
    {
        $id_barang = $this->input->get('id_barang');
        if (!$id_barang) {
            echo json_encode([]);
            return;
        }

        // Get all warehouses with stock for this item
        $this->db->select('sg.id_gudang, g.nama_gudang, sg.jumlah');
        $this->db->from('stok_gudang sg');
        $this->db->join('gudang g', 'sg.id_gudang = g.id_gudang');
        $this->db->where('sg.id_barang', $id_barang);
        $this->db->where('sg.jumlah >', 0);
        $this->db->order_by('sg.jumlah', 'DESC');
        $stock = $this->db->get()->result();

        echo json_encode($stock);
    }

    public function add_process()
    {
        $this->form_validation->set_rules('id_pelanggan', 'Pelanggan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Generate invoice number
            $no_invoice = $this->generate_invoice();

            // Get items data directly from POST
            $items = $this->input->post('items');

            // Debug: Log items data
            log_message('debug', 'Items data: ' . print_r($items, true));

            // Validate items
            if (empty($items)) {
                $this->session->set_flashdata('error', 'Item penjualan tidak boleh kosong');
                redirect('penjualan/add');
            }

            // Insert penjualan header
            $data_penjualan = [
                'no_invoice' => $no_invoice,
                'id_user' => $this->session->userdata('id_user'),
                'id_pelanggan' => $this->input->post('id_pelanggan'),
                'tanggal_penjualan' => date('Y-m-d H:i:s'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => 'proses'
            ];

            // Debug: Log penjualan data
            log_message('debug', 'Penjualan data: ' . print_r($data_penjualan, true));

            $this->db->trans_start();

            try {
                // Validate stock before processing
                foreach ($items as $item) {
                    $stock_check = $this->Stok_gudang_model->get_stok_by_barang_gudang($item['id_barang'], $item['id_gudang']);
                    if (!$stock_check || $stock_check->jumlah < $item['jumlah']) {
                        throw new Exception('Stok tidak mencukupi untuk barang dengan ID: ' . $item['id_barang']);
                    }
                }

                $insert_penjualan = $this->Penjualan_model->insert_penjualan($data_penjualan);

                // Debug: Log insert result
                log_message('debug', 'Insert penjualan result: ' . ($insert_penjualan ? 'Success' : 'Failed'));

                if ($insert_penjualan) {
                    $id_penjualan = $this->db->insert_id();

                    // Debug: Log inserted ID
                    log_message('debug', 'Inserted penjualan ID: ' . $id_penjualan);

                    // Insert detail penjualan and reduce stock
                    foreach ($items as $item) {
                        $detail_data = [
                            'id_penjualan' => $id_penjualan,
                            'id_barang' => $item['id_barang'],
                            'id_gudang' => $item['id_gudang'], // Tambahkan ini
                            'jumlah' => $item['jumlah'],
                        ];

                        $insert_detail = $this->Detail_penjualan_model->insert_detail_penjualan($detail_data);

                        // Debug: Log detail insert result
                        log_message('debug', 'Insert detail result: ' . ($insert_detail ? 'Success' : 'Failed'));

                        if (!$insert_detail) {
                            throw new Exception('Gagal menyimpan detail penjualan');
                        }
                        // Tambahkan log status awal
                        $log_data = [
                            'id_penjualan' => $id_penjualan,
                            'id_user' => $this->session->userdata('id_user'),
                            'status' => 'proses',
                            'keterangan' => 'Penjualan dibuat'
                        ];
                        $this->Log_status_penjualan_model->insert_log($log_data);
                        // Reduce stock with better error handling
                        $reduce_result = $this->reduce_stock($item['id_barang'], $item['id_gudang'], $item['jumlah'], $id_penjualan);
                        if (!$reduce_result['success']) {
                            throw new Exception($reduce_result['message']);
                        }
                    }

                    $this->db->trans_complete();

                    // Debug: Log transaction status
                    log_message('debug', 'Transaction status: ' . ($this->db->trans_status() ? 'Success' : 'Failed'));

                    if ($this->db->trans_status() === FALSE) {
                        $this->session->set_flashdata('error', 'Gagal menyimpan penjualan');
                    } else {
                        $this->session->set_flashdata('success', 'Penjualan berhasil disimpan dengan invoice: ' . $no_invoice);
                    }
                } else {
                    throw new Exception('Gagal menyimpan data penjualan');
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                // Debug: Log exception
                log_message('error', 'Exception in add_process: ' . $e->getMessage());
                $this->session->set_flashdata('error', $e->getMessage());
            }

            redirect('penjualan');
        }
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
    // Tambahkan di Controller Penjualan.php
    private function get_status_icon($status)
    {
        $icons = [
            'proses' => 'clock',
            'packing' => 'box',
            'dikirim' => 'truck',
            'selesai' => 'check-circle',
            'batal' => 'times-circle'
        ];

        return $icons[$status] ?? 'question-circle';
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
        if ($this->session->userdata('id_role') == 4) {
            if ($data['penjualan']->id_perusahaan != $this->session->userdata('id_perusahaan')) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                redirect('penjualan');
            }
        }

        // Ambil riwayat status
        $this->load->model('penjualan/Log_status_penjualan_model');
        $data['riwayat_status'] = $this->Log_status_penjualan_model->get_log_by_penjualan($id);

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
        // Debug: Log parameters
        log_message('debug', 'Reduce stock params: ' . print_r(func_get_args(), true));

        // Get current stock
        $stock = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);

        // Debug: Log stock data
        log_message('debug', 'Stock data: ' . print_r($stock, true));

        if (!$stock) {
            return ['success' => false, 'message' => 'Stok tidak ditemukan'];
        }

        if ($stock->jumlah < $jumlah) {
            return ['success' => false, 'message' => 'Stok tidak mencukupi'];
        }

        // Get penjualan data for log
        $penjualan = $this->Penjualan_model->get_penjualan_by_id($id_penjualan);
        $no_invoice = $penjualan ? $penjualan->no_invoice : 'Unknown';

        // Reduce stock
        $update_data = [
            'jumlah' => $stock->jumlah - $jumlah,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $update_result = $this->Stok_gudang_model->update_stock($stock->id_stok, $update_data);

        if (!$update_result) {
            return ['success' => false, 'message' => 'Gagal mengupdate stok'];
        }

        // Log stock movement
        $log_data = [
            'id_barang' => $id_barang,
            'id_user' => $this->session->userdata('id_user'),
            'id_perusahaan' => $stock->id_perusahaan,
            'id_gudang' => $id_gudang,
            'jenis' => 'keluar',
            'jumlah' => $jumlah,
            'keterangan' => 'Penjualan Invoice: ' . $no_invoice,
            'id_referensi' => $id_penjualan,
            'tipe_referensi' => 'penjualan'
        ];

        $log_result = $this->Log_stok_model->insert_log($log_data);

        if (!$log_result) {
            return ['success' => false, 'message' => 'Gagal mencatat log stok'];
        }

        return ['success' => true];
    }
    public function update_status($id_penjualan, $status)
    {
        // Ambil data penjualan terlebih dahulu
        $penjualan = $this->Penjualan_model->get_penjualan_by_id($id_penjualan);

        if (!$penjualan) {
            $this->session->set_flashdata('error', 'Data penjualan tidak ditemukan');
            redirect('penjualan');
        }

        // Cek hak akses perusahaan
        $user_role = $this->session->userdata('id_role');
        $user_company = $this->session->userdata('id_perusahaan');

        // Jika bukan Super Admin (5) atau Admin Pusat (1), cek akses perusahaan
        if ($user_role != 5 && $user_role != 1) {
            // Ambil data penjualan dengan perusahaan
            $penjualan_with_company = $this->Penjualan_model->get_penjualan_by_id($id_penjualan);
            if ($penjualan_with_company && $penjualan_with_company->id_perusahaan != $user_company) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                redirect('penjualan');
            }
        }

        // Validasi status yang diizinkan berdasarkan role
        $allowed = false;

        // Super Admin (5) dan Admin Pusat (1) bisa mengubah ke status apa saja
        if ($user_role == 5 || $user_role == 1) {
            $allowed = true;
        }
        // Admin Packing (3) bisa mengubah ke packing, dikirim, selesai
        else if ($user_role == 3 && in_array($status, ['packing', 'dikirim', 'selesai'])) {
            $allowed = true;
        }
        // Sales Online (2) hanya bisa mengubah ke batal
        else if ($user_role == 2 && $status == 'batal') {
            $allowed = true;
        }

        if (!$allowed) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah status menjadi ' . $status);
            redirect('penjualan');
        }

        // Cek apakah status transition valid
        $current_status = $penjualan->status;
        $valid_transitions = [
            'proses' => ['packing', 'batal'],
            'packing' => ['dikirim'],
            'dikirim' => ['selesai'],
            'selesai' => [], // tidak bisa diubah lagi
            'batal' => [] // tidak bisa diubah lagi
        ];

        if (!in_array($status, $valid_transitions[$current_status])) {
            $this->session->set_flashdata('error', 'Tidak dapat mengubah status dari ' . $current_status . ' ke ' . $status);
            redirect('penjualan');
        }

        // Update status
        $this->Penjualan_model->update_penjualan($id_penjualan, ['status' => $status]);

        // Catat log perubahan status
        $keterangan = '';
        switch ($status) {
            case 'packing':
                $keterangan = 'Penjualan sedang dipacking';
                break;
            case 'dikirim':
                $keterangan = 'Penjualan telah dikirim';
                break;
            case 'selesai':
                $keterangan = 'Penjualan telah selesai';
                break;
            case 'batal':
                $keterangan = 'Penjualan dibatalkan';
                break;
            default:
                $keterangan = 'Status diubah menjadi ' . $status;
        }

        $log_data = [
            'id_penjualan' => $id_penjualan,
            'id_user' => $this->session->userdata('id_user'),
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $this->Log_status_penjualan_model->insert_log($log_data);

        $this->session->set_flashdata('success', 'Status penjualan berhasil diubah menjadi ' . $status);
        redirect('penjualan/view/' . $id_penjualan);
    }

}