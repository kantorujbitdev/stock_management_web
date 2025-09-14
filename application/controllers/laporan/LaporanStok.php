<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanStok extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('hak_akses');
        $this->load->model('laporan/Laporan_stok_model');
        $this->load->model('perusahaan/Perusahaan_model');
        $this->load->model('master/Kategori_model');
        $this->load->model('perusahaan/Gudang_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        $this->hak_akses->cek_akses('laporan_stok');
    }

    public function index()
    {
        $data['title'] = 'Laporan Stok';

        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $id_gudang = $this->input->get('id_gudang');
        $id_kategori = $this->input->get('id_kategori');
        $stock_status = $this->input->get('stock_status');

        // Prepare filter array
        $filter = [
            'id_perusahaan' => $id_perusahaan,
            'id_gudang' => $id_gudang,
            'id_kategori' => $id_kategori,
            'stock_status' => $stock_status
        ];

        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($filter);

            // Get gudang list based on selected perusahaan
            if ($id_perusahaan) {
                $data['gudang'] = $this->Gudang_model->get_gudang_by_perusahaan($id_perusahaan);
            } else {
                $data['gudang'] = [];
            }

            // Get kategori list based on selected perusahaan
            if ($id_perusahaan) {
                $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
            } else {
                $data['kategori'] = [];
            }
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $filter['id_perusahaan'] = $id_perusahaan_user;

            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($filter);

            // Get perusahaan data for filter
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan_user));

            // Get gudang list based on selected perusahaan
            $data['gudang'] = $this->Gudang_model->get_gudang_by_perusahaan($id_perusahaan_user);

            // Get kategori list based on selected perusahaan
            $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan_user);
        }

        // Set filter values for view
        $data['filter'] = $filter;

        // Calculate summary data
        $data['summary'] = [
            'total_items' => count($data['stok']),
            'total_stok_awal' => array_sum(array_column($data['stok'], 'stok_awal')),
            'total_pembelian_masuk' => array_sum(array_column($data['stok'], 'pembelian_masuk')),
            'total_retur_masuk' => array_sum(array_column($data['stok'], 'retur_masuk')),
            'total_penjualan_keluar' => array_sum(array_column($data['stok'], 'penjualan_keluar')),
            'total_retur_keluar' => array_sum(array_column($data['stok'], 'retur_keluar')),
            'total_stok_akhir' => array_sum(array_column($data['stok'], 'jumlah')),
            'stok_low_count' => count(array_filter($data['stok'], function ($item) {
                return $item->jumlah > 0 && $item->jumlah < 10;
            })),
            'stok_empty_count' => count(array_filter($data['stok'], function ($item) {
                return $item->jumlah == 0;
            })),
            'stok_over_count' => count(array_filter($data['stok'], function ($item) {
                return $item->jumlah > 100;
            }))
        ];

        $data['content'] = 'laporan/laporan_stok';
        $this->load->view('template/template', $data);
    }

    public function get_gudang_by_perusahaan()
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        if (!$id_perusahaan) {
            echo '';
            return;
        }

        $gudang = $this->Gudang_model->get_gudang_by_perusahaan($id_perusahaan);
        $options = '<option value="">-- Semua --</option>';
        foreach ($gudang as $row) {
            $options .= '<option value="' . $row->id_gudang . '">' . $row->nama_gudang . '</option>';
        }
        echo $options;
    }

    public function get_kategori_by_perusahaan()
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        if (!$id_perusahaan) {
            echo '';
            return;
        }

        $kategori = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
        $options = '<option value="">-- Semua --</option>';
        foreach ($kategori as $row) {
            $options .= '<option value="' . $row->id_kategori . '">' . $row->nama_kategori . '</option>';
        }
        echo $options;
    }

    public function get_retur_summary()
    {
        $filter = [
            'id_perusahaan' => $this->input->get('id_perusahaan'),
            'id_gudang' => $this->input->get('id_gudang'),
            'id_kategori' => $this->input->get('id_kategori')
        ];

        $retur_summary = $this->Laporan_stok_model->get_retur_summary($filter);
        echo json_encode($retur_summary);
    }

    public function export_pdf()
    {
        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $id_gudang = $this->input->get('id_gudang');
        $id_kategori = $this->input->get('id_kategori');
        $stock_status = $this->input->get('stock_status');

        // Prepare filter array
        $filter = [
            'id_perusahaan' => $id_perusahaan,
            'id_gudang' => $id_gudang,
            'id_kategori' => $id_kategori,
            'stock_status' => $stock_status
        ];

        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($filter);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $filter['id_perusahaan'] = $id_perusahaan_user;
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($filter);
        }

        // Load library PDF
        $this->load->library('pdf');

        // Generate PDF
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->filename = "laporan_stok_" . date('YmdHis') . ".pdf";
        $this->pdf->load_view('laporan/laporan_stok_pdf', $data);
    }

    public function export_excel()
    {
        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $id_gudang = $this->input->get('id_gudang');
        $id_kategori = $this->input->get('id_kategori');
        $stock_status = $this->input->get('stock_status');

        // Prepare filter array
        $filter = [
            'id_perusahaan' => $id_perusahaan,
            'id_gudang' => $id_gudang,
            'id_kategori' => $id_kategori,
            'stock_status' => $stock_status
        ];

        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($filter);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $filter['id_perusahaan'] = $id_perusahaan_user;
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($filter);
        }

        // Load library Excel
        $this->load->library('excel');

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setTitle("Laporan Stok");
        $objPHPExcel->getProperties()->setSubject("Laporan Stok");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'LAPORAN STOK');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Tanggal: ' . date('d-m-Y'));

        // Header
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'No');
        $objPHPExcel->getActiveSheet()->setCellValue('B4', 'Kode Barang');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Nama Barang');
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Kategori');
        $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Perusahaan');
        $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Gudang');
        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Stok Awal');
        $objPHPExcel->getActiveSheet()->setCellValue('H4', 'Pembelian');
        $objPHPExcel->getActiveSheet()->setCellValue('I4', 'Retur Masuk');
        $objPHPExcel->getActiveSheet()->setCellValue('J4', 'Penjualan');
        $objPHPExcel->getActiveSheet()->setCellValue('K4', 'Retur Keluar');
        $objPHPExcel->getActiveSheet()->setCellValue('L4', 'Stok Akhir');
        $objPHPExcel->getActiveSheet()->setCellValue('M4', 'Status');

        // Data
        $row = 5;
        $no = 1;
        foreach ($data['stok'] as $s) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $no++);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $s->sku);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $s->nama_barang);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $s->nama_kategori);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $s->nama_perusahaan);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $s->nama_gudang);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $s->stok_awal);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $s->pembelian_masuk);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $s->retur_masuk);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $s->penjualan_keluar);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $s->retur_keluar);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $row, $s->jumlah);

            // Status
            $status_text = 'Normal';
            if ($s->jumlah == 0) {
                $status_text = 'Stok Habis';
            } elseif ($s->jumlah < 10) {
                $status_text = 'Stok Menipis';
            } elseif ($s->jumlah > 100) {
                $status_text = 'Stok Berlebih';
            }

            $objPHPExcel->getActiveSheet()->setCellValue('M' . $row, $status_text);
            $row++;
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Laporan Stok');

        // Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_stok_' . date('YmdHis') . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}