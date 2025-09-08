<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StokAwal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('stok/Stok_awal_model');
        $this->load->model('perusahaan/Perusahaan_model');
        $this->load->model('perusahaan/Gudang_model');
        $this->load->model('master/Barang_model');
        $this->load->model('stok/Stok_gudang_model');
        $this->load->model('stok/Log_stok_model');

        $this->load->helper('date');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        if (!$this->hak_akses->cek_akses('stok_awal')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke menu Stok Awal');
            redirect('dashboard');
        }
    }


    // Helper function untuk update stok gudang
// Perbaikan fungsi update_stok_gudang
    private function update_stok_gudang($id_barang, $id_gudang, $qty, $jenis, $qty_lama = 0, $id_perusahaan = null)
    {
        $this->load->model('stok/Stok_gudang_model');
        $this->load->model('stok/Log_stok_model');

        // Gunakan transaksi untuk keamanan data
        $this->db->trans_start();

        // Ambil data perusahaan - perbaikan di sini
        if ($id_perusahaan === null) {
            $id_perusahaan = $this->input->post('id_perusahaan') ?: $this->session->userdata('id_perusahaan');
        }

        // Debug: Log parameter
        log_message('debug', 'Update stok gudang params: ' . json_encode([
            'id_barang' => $id_barang,
            'id_gudang' => $id_gudang,
            'qty' => $qty,
            'jenis' => $jenis,
            'qty_lama' => $qty_lama,
            'id_perusahaan' => $id_perusahaan
        ]));

        // Cek stok gudang
        $stok_gudang = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);

        if ($stok_gudang) {
            // Hitung stok baru
            if ($jenis == 'stok_awal') {
                $jumlah = $stok_gudang->jumlah - $qty_lama + $qty;
            } else {
                $jumlah = $stok_gudang->jumlah + $qty;
            }

            // Debug: Log update
            log_message('debug', 'Updating existing stock: ' . json_encode([
                'old_stock' => $stok_gudang->jumlah,
                'new_stock' => $jumlah
            ]));

            // Update stok
            $update_result = $this->Stok_gudang_model->update_stok($stok_gudang->id_stok, ['jumlah' => $jumlah]);

            if (!$update_result) {
                log_message('error', 'Failed to update stock_gudang record');
                $this->db->trans_rollback();
                return false;
            }
        } else {
            // Debug: Log insert
            log_message('debug', 'Inserting new stock record');

            // Insert stok baru
            $data_stok = [
                'id_perusahaan' => $id_perusahaan,
                'id_gudang' => $id_gudang,
                'id_barang' => $id_barang,
                'jumlah' => $qty
            ];

            $insert_result = $this->Stok_gudang_model->insert_stok($data_stok);

            if (!$insert_result) {
                log_message('error', 'Failed to insert stock_gudang record');
                $this->db->trans_rollback();
                return false;
            }
        }

        // Insert log stok
        $data_log = [
            'id_barang' => $id_barang,
            'id_user' => $this->session->userdata('id_user'),
            'id_perusahaan' => $id_perusahaan,
            'id_gudang' => $id_gudang,
            'jenis' => ($qty > 0) ? 'masuk' : 'keluar',
            'jumlah' => abs($qty),
            'keterangan' => 'Stok Awal: ' . $this->input->post('keterangan'),
            'id_referensi' => isset($id) ? $id : null,
            'tipe_referensi' => 'stok_awal'
        ];

        $log_result = $this->Log_stok_model->insert_log($data_log);

        if (!$log_result) {
            log_message('error', 'Failed to insert stock log');
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Transaction failed in update_stok_gudang');
            return false;
        }

        return true;
    }
    public function get_gudang_by_perusahaan()
    {
        // Terima GET request (seperti di Barang controller)
        $id_perusahaan = $this->input->get('id_perusahaan');

        if (!$id_perusahaan) {
            echo '<option value="">-- Pilih Gudang --</option>';
            return;
        }

        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $user_company = $this->session->userdata('id_perusahaan');
            if ($id_perusahaan != $user_company) {
                echo '<option value="">-- Tidak Ada Akses --</option>';
                return;
            }
        }

        $gudang = $this->Gudang_model->get_gudang_by_perusahaan($id_perusahaan);

        $options = '<option value="">-- Pilih Gudang --</option>';
        foreach ($gudang as $row) {
            $options .= '<option value="' . $row->id_gudang . '">' . $row->nama_gudang . '</option>';
        }

        echo $options;
    }

    public function get_barang_by_perusahaan()
    {
        // Terima GET request (seperti di Barang controller)
        $id_perusahaan = $this->input->get('id_perusahaan');

        if (!$id_perusahaan) {
            echo '<option value="">-- Pilih Barang --</option>';
            return;
        }

        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $user_company = $this->session->userdata('id_perusahaan');
            if ($id_perusahaan != $user_company) {
                echo '<option value="">-- Tidak Ada Akses --</option>';
                return;
            }
        }

        $barang = $this->Barang_model->get_barang_by_perusahaan($id_perusahaan);

        $options = '<option value="">-- Pilih Barang --</option>';
        foreach ($barang as $row) {
            $options .= '<option value="' . $row->id_barang . '">' . $row->nama_barang . ' - ' . $row->sku . '</option>';
        }

        echo $options;
    }

    // Export ke Excel
    public function export_excel()
    {
        $data['stok_awal'] = $this->Stok_awal_model->get_all_stok_awal();

        $this->load->library('excel');
        $object = new PHPExcel();

        // Set properties
        $object->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Barang')
            ->setCellValue('C1', 'SKU')
            ->setCellValue('D1', 'Gudang')
            ->setCellValue('E1', 'Perusahaan')
            ->setCellValue('F1', 'Qty Awal')
            ->setCellValue('G1', 'Keterangan')
            ->setCellValue('H1', 'Dibuat Oleh');

        $row = 2;
        $no = 1;
        foreach ($data['stok_awal'] as $s) {
            $object->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $s->nama_barang)
                ->setCellValue('C' . $row, $s->sku)
                ->setCellValue('D' . $row, $s->nama_gudang)
                ->setCellValue('E' . $row, $s->nama_perusahaan)
                ->setCellValue('F' . $row, $s->qty_awal)
                ->setCellValue('G' . $row, $s->keterangan)
                ->setCellValue('H' . $row, $s->created_by_name);
            $row++;
        }

        $filename = 'Stok_Awal_' . date('Y-m-d') . '.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        $objWriter->save('php://output');
    }

    // Form validation rules
    private function _set_rules()
    {
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_gudang', 'Gudang', 'required');
        $this->form_validation->set_rules('id_barang', 'Barang', 'required');
        $this->form_validation->set_rules('qty_awal', 'Qty Awal', 'required|numeric|greater_than_equal_to[0]|max_length[10]');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'max_length[255]');
    }

    public function index()
    {
        $data['title'] = 'Data Barang';

        // Filter parameters
        $filter = [
            'id_perusahaan' => $this->input->get('id_perusahaan'),
            'id_gudang' => $this->input->get('id_gudang'),
            'id_barang' => $this->input->get('id_barang'),
            'stock_status' => $this->input->get('stock_status') // all, empty, has_stock
        ];

        // Get data with filter
        if ($this->session->userdata('id_role') == 5) {
            $data['barang'] = $this->Stok_awal_model->get_barang_with_stock_status($filter);
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $filter['id_perusahaan'] = $id_perusahaan;
            $data['barang'] = $this->Stok_awal_model->get_barang_with_stock_status($filter);
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }

        $data['filter'] = $filter;
        $data['content'] = 'stok/stok_awal_list_v2';
        $this->load->view('template/template', $data);
    }

    // Form untuk input stok awal per barang
    public function input_stok($id_barang)
    {
        $data['title'] = 'Input Stok Awal';
        $data['barang'] = $this->Barang_model->get_barang_by_id($id_barang);

        if (!$data['barang']) {
            show_404();
        }

        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            if ($data['barang']->id_perusahaan != $this->session->userdata('id_perusahaan')) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
                redirect('stok_awal');
            }
        }

        // Ambil gudang untuk perusahaan ini
        $data['gudang'] = $this->Gudang_model->get_gudang_by_perusahaan($data['barang']->id_perusahaan);

        $data['content'] = 'stok/input_stok_form';
        $this->load->view('template/template', $data);
    }

    // Proses input stok awal
    public function process_input_stok()
    {
        $id_barang = $this->input->post('id_barang');
        $id_gudang = $this->input->post('id_gudang');
        $qty_awal = $this->input->post('qty_awal');

        // Validasi
        if (!$id_barang || !$id_gudang || !$qty_awal || $qty_awal <= 0) {
            $this->session->set_flashdata('error', 'Data tidak valid');
            redirect('stok_awal');
        }

        // Cek hak akses
        $barang = $this->Barang_model->get_barang_by_id($id_barang);
        if (!$barang) {
            show_404();
        }

        if ($this->session->userdata('id_role') != 5) {
            if ($barang->id_perusahaan != $this->session->userdata('id_perusahaan')) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
                redirect('stok_awal');
            }
        }

        // Cek apakah stok awal sudah ada
        if ($this->Stok_awal_model->check_stok_awal_exists($id_barang, $id_gudang)) {
            $this->session->set_flashdata('error', 'Stok awal untuk barang ini di gudang ini sudah ada');
            redirect('stok_awal');
        }

        // Simpan stok awal
        $data_stok_awal = [
            'id_barang' => $id_barang,
            'id_gudang' => $id_gudang,
            'id_perusahaan' => $barang->id_perusahaan,
            'qty_awal' => $qty_awal,
            'keterangan' => $this->input->post('keterangan'),
            'created_by' => $this->session->userdata('id_user')
        ];

        $insert = $this->Stok_awal_model->insert_stok_awal($data_stok_awal);

        if ($insert) {
            // Perbaikan: Pass id_perusahaan explicitly dan cek hasilnya
            $update_stok = $this->update_stok_gudang($id_barang, $id_gudang, $qty_awal, 'stok_awal', 0, $barang->id_perusahaan);

            if ($update_stok) {
                $this->session->set_flashdata('success', 'Stok awal berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Stok awal berhasil ditambahkan, tetapi gagal memperbarui stok gudang');
                log_message('error', 'Failed to update stok_gudang after inserting stok_awal');
            }
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan stok awal');
        }

        redirect('stok_awal');
    }


}