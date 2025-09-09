<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Retur extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('penjualan/Retur_model');
        $this->load->model('penjualan/Detail_retur_model');
        $this->load->model('penjualan/Penjualan_model');
        $this->load->model('penjualan/Detail_penjualan_model');
        $this->load->model('master/Pelanggan_model');
        $this->load->model('master/Barang_model');
        $this->load->model('stok/Stok_gudang_model');
        $this->load->model('stok/Log_stok_model');
        $this->load->model('perusahaan/Perusahaan_model');
        // Tambahkan model log status retur
        $this->load->model('laporan/Log_status_retur_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        // Cek hak akses
        if (!$this->hak_akses->cek_akses('retur')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke menu Retur');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['title'] = 'Data Retur Penjualan';
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
            $data['retur'] = $this->Retur_model->get_all_retur_with_detail($filter);
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $filter['id_perusahaan'] = $id_perusahaan;
            $data['retur'] = $this->Retur_model->get_retur_by_perusahaan_with_detail($id_perusahaan, $filter);
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }

        $data['pelanggan'] = $this->Pelanggan_model->get_pelanggan_aktif();
        $data['filter'] = $filter;
        $data['content'] = 'penjualan/retur_list';
        $this->load->view('template/template', $data);
    }

    public function add($id_penjualan = null)
    {
        $data['title'] = 'Tambah Retur Penjualan';

        // Jika tidak ada ID penjualan, tampilkan pilihan penjualan
        if (!$id_penjualan) {
            if ($this->session->userdata('id_role') == 5) {
                // Ambil semua penjualan dengan status selesai
                $all_penjualan = $this->Penjualan_model->get_all_penjualan_with_detail(['status' => 'selesai']);
                // Filter penjualan yang bisa diretur
                $data['penjualan'] = [];
                foreach ($all_penjualan as $p) {
                    if ($this->cek_bisa_retur($p->id_penjualan)) {
                        $data['penjualan'][] = $p;
                    }
                }
            } else {
                $id_perusahaan = $this->session->userdata('id_perusahaan');
                // Ambil penjualan perusahaan dengan status selesai
                $all_penjualan = $this->Penjualan_model->get_penjualan_by_perusahaan_with_detail($id_perusahaan, ['status' => 'selesai']);
                // Filter penjualan yang bisa diretur
                $data['penjualan'] = [];
                foreach ($all_penjualan as $p) {
                    if ($this->cek_bisa_retur($p->id_penjualan)) {
                        $data['penjualan'][] = $p;
                    }
                }
            }
            $data['content'] = 'penjualan/retur_pilih_penjualan';
            $this->load->view('template/template', $data);
            return;
        }

        // Get penjualan data
        $data['penjualan'] = $this->Penjualan_model->get_penjualan_by_id($id_penjualan);
        $data['detail_penjualan'] = $this->Detail_penjualan_model->get_detail_by_penjualan($id_penjualan);

        if (!$data['penjualan']) {
            show_404();
        }

        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') == 4) {
            if ($data['penjualan']->id_perusahaan != $this->session->userdata('id_perusahaan')) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke penjualan ini');
                redirect('retur');
            }
        }

        // Cek status penjualan, hanya yang selesai yang bisa diretur
        if ($data['penjualan']->status != 'selesai') {
            $this->session->set_flashdata('error', 'Hanya penjualan dengan status selesai yang dapat diretur');
            redirect('retur');
        }

        // Cek apakah penjualan ini masih bisa diretur
        if (!$this->cek_bisa_retur($id_penjualan)) {
            $this->session->set_flashdata('error', 'Penjualan ini tidak bisa diretur karena semua barang sudah diretur');
            redirect('retur');
        }

        $data['content'] = 'penjualan/retur_form';
        $this->load->view('template/template', $data);
    }

    // Tambah method untuk cek apakah penjualan bisa diretur
    private function cek_bisa_retur($id_penjualan)
    {
        // Ambil detail penjualan
        $detail_penjualan = $this->Detail_penjualan_model->get_detail_by_penjualan($id_penjualan);

        // Ambil semua retur untuk penjualan ini
        $this->db->select('drp.id_barang, SUM(drp.jumlah_retur) as total_retur');
        $this->db->from('detail_retur_penjualan drp');
        $this->db->join('retur_penjualan rp', 'drp.id_retur = rp.id_retur', 'left');
        $this->db->where('rp.id_penjualan', $id_penjualan);
        $this->db->where('rp.status !=', 'batal'); // Kecuali retur yang dibatalkan
        $this->db->group_by('drp.id_barang');
        $retur_data = $this->db->get()->result();

        // Jika tidak ada retur sama sekali, bisa diretur
        if (empty($retur_data)) {
            return true;
        }

        // Cek apakah ada barang yang belum diretur sepenuhnya
        foreach ($detail_penjualan as $dp) {
            $total_retur = 0;
            foreach ($retur_data as $rd) {
                if ($rd->id_barang == $dp->id_barang) {
                    $total_retur = $rd->total_retur;
                    break;
                }
            }
            // Jika jumlah retur kurang dari jumlah jual, masih bisa diretur
            if ($total_retur < $dp->jumlah) {
                return true;
            }
        }

        // Semua barang sudah diretur sepenuhnya
        return false;
    }

    public function add_process()
    {
        $this->form_validation->set_rules('id_penjualan', 'Penjualan', 'required');
        $this->form_validation->set_rules('alasan_retur', 'Alasan Retur', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->add($this->input->post('id_penjualan'));
        } else {
            // Generate nomor retur
            $no_retur = $this->generate_no_retur();

            // Get items data directly from POST
            $items = $this->input->post('items');

            // Validate items
            if (empty($items)) {
                $this->session->set_flashdata('error', 'Item retur tidak boleh kosong');
                redirect('retur/add/' . $this->input->post('id_penjualan'));
            }

            // Get penjualan data
            $penjualan = $this->Penjualan_model->get_penjualan_by_id($this->input->post('id_penjualan'));

            // Insert retur header
            $data_retur = [
                'no_retur' => $no_retur,
                'id_user' => $this->session->userdata('id_user'),
                'id_penjualan' => $this->input->post('id_penjualan'),
                'tanggal_retur' => date('Y-m-d H:i:s'),
                'alasan_retur' => $this->input->post('alasan_retur'),
                'status' => 'diproses' // PERUBAHAN: dari 'diterima' menjadi 'diproses'
            ];

            $this->db->trans_start();
            try {
                $insert_retur = $this->Retur_model->insert_retur($data_retur);

                if ($insert_retur) {
                    $id_retur = $this->db->insert_id();

                    // Insert log status awal
                    $log_data = [
                        'id_retur' => $id_retur,
                        'id_user' => $this->session->userdata('id_user'),
                        'status' => 'diproses', // PERUBAHAN: sesuaikan dengan status di atas
                        'keterangan' => 'Retur dibuat'
                    ];
                    $this->Log_status_retur_model->insert_log($log_data);

                    // Insert detail retur
                    foreach ($items as $item) {
                        $detail_data = [
                            'id_retur' => $id_retur,
                            'id_barang' => $item['id_barang'],
                            'jumlah_retur' => $item['jumlah'],
                            'alasan_barang' => $item['kondisi']
                        ];
                        $insert_detail = $this->Detail_retur_model->insert_detail_retur($detail_data);

                        if (!$insert_detail) {
                            throw new Exception('Gagal menyimpan detail retur');
                        }

                        // HAPUS: Proses stok dipindahkan ke update_status saat status diterima
                    }

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        $this->session->set_flashdata('error', 'Gagal menyimpan retur');
                    } else {
                        $this->session->set_flashdata('success', 'Retur berhasil disimpan dengan nomor: ' . $no_retur);
                    }
                } else {
                    throw new Exception('Gagal menyimpan data retur');
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', $e->getMessage());
            }

            redirect('retur');
        }
    }

    public function view($id)
    {
        $data['title'] = 'Detail Retur Penjualan';
        $data['retur'] = $this->Retur_model->get_retur_by_id($id);
        $data['detail'] = $this->Detail_retur_model->get_detail_by_retur($id);

        if (!$data['retur']) {
            show_404();
        }

        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') == 4) {
            if ($data['retur']->id_perusahaan != $this->session->userdata('id_perusahaan')) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke retur ini');
                redirect('retur');
            }
        }

        // Ambil riwayat status
        $data['riwayat_status'] = $this->Log_status_retur_model->get_log_by_retur($id);

        $data['content'] = 'penjualan/retur_detail';
        $this->load->view('template/template', $data);
    }

    public function update_status($id_retur, $status)
    {
        // Ambil data retur terlebih dahulu
        $retur = $this->Retur_model->get_retur_by_id($id_retur);
        if (!$retur) {
            $this->session->set_flashdata('error', 'Data retur tidak ditemukan');
            redirect('retur');
        }

        // Cek hak akses perusahaan
        $user_role = $this->session->userdata('id_role');
        $user_company = $this->session->userdata('id_perusahaan');

        // Jika bukan Super Admin (5) atau Admin Pusat (1), cek akses perusahaan
        if ($user_role != 5 && $user_role != 1) {
            if ($retur->id_perusahaan != $user_company) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke retur ini');
                redirect('retur');
            }
        }

        // Validasi status yang diizinkan berdasarkan role
        $allowed = false;
        // Super Admin (5) dan Admin Pusat (1) bisa mengubah ke status apa saja
        if ($user_role == 5 || $user_role == 1) {
            $allowed = true;
        }
        // Admin Gudang (4) bisa mengubah ke diterima, ditolak, selesai
        else if ($user_role == 4 && in_array($status, ['diterima', 'ditolak', 'selesai'])) {
            $allowed = true;
        }

        // Sales Online (2) hanya bisa mengubah ke batal
        else if ($user_role == 2 && $status == 'batal') {
            $allowed = true;
        }

        if (!$allowed) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah status menjadi ' . $status);
            redirect('retur');
        }

        // Cek apakah status transition valid
        $current_status = $retur->status;
        $valid_transitions = [
            'diproses' => ['diterima', 'ditolak', 'batal'], // PERUBAHAN: dari 'diterima' menjadi 'diproses'
            'diterima' => ['selesai'],
            'ditolak' => [],
            'batal' => [],
            'selesai' => []
        ];

        if (!in_array($status, $valid_transitions[$current_status])) {
            $this->session->set_flashdata('error', 'Tidak dapat mengubah status dari ' . $current_status . ' ke ' . $status);
            redirect('retur');
        }

        // Update status
        $this->Retur_model->update_retur($id_retur, ['status' => $status]);

        // Catat log perubahan status
        $keterangan = '';
        switch ($status) {
            case 'diterima':
                $keterangan = 'Retur diterima';
                // Tambah stok hanya saat status diterima
                $detail_retur = $this->Detail_retur_model->get_detail_by_retur($id_retur);
                foreach ($detail_retur as $detail) {
                    // Ambil data gudang dari detail penjualan asli
                    $detail_penjualan = $this->Detail_penjualan_model->get_detail_by_penjualan($retur->id_penjualan);
                    $id_gudang = null;
                    foreach ($detail_penjualan as $dp) {
                        if ($dp->id_barang == $detail->id_barang) {
                            $id_gudang = $dp->id_gudang;
                            break;
                        }
                    }

                    if ($id_gudang) {
                        // Tambah stok kembali
                        $add_result = $this->add_stock($detail->id_barang, $id_gudang, $detail->jumlah_retur, $id_retur);
                        if (!$add_result['success']) {
                            $this->session->set_flashdata('error', $add_result['message']);
                            redirect('retur/view/' . $id_retur);
                        }
                    }
                }
                break;
            case 'diproses':
                $keterangan = 'Retur sedang diproses';
                break;
            case 'selesai':
                $keterangan = 'Retur selesai diproses';
                break;
            case 'ditolak':
                $keterangan = 'Retur ditolak';
                break;
            case 'batal':
                $keterangan = 'Retur dibatalkan';
                break;
        }

        $log_data = [
            'id_retur' => $id_retur,
            'id_user' => $this->session->userdata('id_user'),
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $this->Log_status_retur_model->insert_log($log_data);

        $this->session->set_flashdata('success', 'Status retur berhasil diubah menjadi ' . $status);
        redirect('retur/view/' . $id_retur);
    }

    private function generate_no_retur()
    {
        $prefix = 'RET-' . date('Ym');
        $last_retur = $this->Retur_model->get_last_retur($prefix);

        if ($last_retur) {
            $last_number = intval(substr($last_retur, -4));
            $new_number = str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $new_number = '0001';
        }

        return $prefix . $new_number;
    }

    private function add_stock($id_barang, $id_gudang, $jumlah, $id_retur)
    {
        // Get current stock
        $stock = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);

        if (!$stock) {
            // Create new stock record if not exists
            $insert_data = [
                'id_perusahaan' => $this->session->userdata('id_perusahaan'),
                'id_barang' => $id_barang,
                'id_gudang' => $id_gudang,
                'jumlah' => $jumlah,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $insert_result = $this->Stok_gudang_model->insert_stock($insert_data);

            if (!$insert_result) {
                return ['success' => false, 'message' => 'Gagal membuat record stok baru'];
            }

            $id_stok = $this->db->insert_id();
        } else {
            // Update existing stock
            $update_data = [
                'jumlah' => $stock->jumlah + $jumlah,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $update_result = $this->Stok_gudang_model->update_stock($stock->id_stok, $update_data);

            if (!$update_result) {
                return ['success' => false, 'message' => 'Gagal mengupdate stok'];
            }

            $id_stok = $stock->id_stok;
        }

        // Get retur data for log
        $retur = $this->Retur_model->get_retur_by_id($id_retur);
        $no_retur = $retur ? $retur->no_retur : 'Unknown';

        // Log stock movement
        $log_data = [
            'id_barang' => $id_barang,
            'id_user' => $this->session->userdata('id_user'),
            'id_perusahaan' => $stock ? $stock->id_perusahaan : $this->session->userdata('id_perusahaan'),
            'id_gudang' => $id_gudang,
            'jenis' => 'masuk',
            'jumlah' => $jumlah,
            'keterangan' => 'Retur Penjualan: ' . $no_retur,
            'id_referensi' => $id_retur,
            'tipe_referensi' => 'retur'
        ];

        $log_result = $this->Log_stok_model->insert_log($log_data);

        if (!$log_result) {
            return ['success' => false, 'message' => 'Gagal mencatat log stok'];
        }

        return ['success' => true];
    }
}