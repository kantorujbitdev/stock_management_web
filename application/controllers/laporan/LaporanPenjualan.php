<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanPenjualan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('hak_akses');
        $this->load->model('laporan/Laporan_penjualan_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        $this->hak_akses->cek_akses('laporan_penjualan');
    }

    public function index()
    {
        $data['title'] = 'Laporan Penjualan';

        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        $status = $this->input->get('status');

        // Set default tanggal
        if (!$tanggal_awal) {
            $tanggal_awal = date('Y-m-01');
        }
        if (!$tanggal_akhir) {
            $tanggal_akhir = date('Y-m-t');
        }

        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['perusahaan'] = $this->Laporan_penjualan_model->get_perusahaan_list();
            $data['penjualan'] = $this->Laporan_penjualan_model->get_filtered_penjualan($id_perusahaan, $tanggal_awal, $tanggal_akhir, $status);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['penjualan'] = $this->Laporan_penjualan_model->get_filtered_penjualan($id_perusahaan_user, $tanggal_awal, $tanggal_akhir, $status);

            // Get perusahaan data for filter
            $this->load->model('perusahaan/Perusahaan_model');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan_user));
        }

        // Set filter values for view
        $data['filter'] = [
            'id_perusahaan' => $id_perusahaan,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'status' => $status
        ];

        $data['content'] = 'laporan/laporan_penjualan';
        $this->load->view('template/template', $data);
    }

    public function export_pdf()
    {
        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        $status = $this->input->get('status');

        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['penjualan'] = $this->Laporan_penjualan_model->get_filtered_penjualan($id_perusahaan, $tanggal_awal, $tanggal_akhir, $status);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['penjualan'] = $this->Laporan_penjualan_model->get_filtered_penjualan($id_perusahaan_user, $tanggal_awal, $tanggal_akhir, $status);
        }

        // Load library PDF
        $this->load->library('pdf');

        // Generate PDF
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->filename = "laporan_penjualan_" . date('YmdHis') . ".pdf";
        $this->pdf->load_view('laporan/laporan_penjualan_pdf', $data);
    }

    public function export_excel()
    {
        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        $status = $this->input->get('status');

        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['penjualan'] = $this->Laporan_penjualan_model->get_filtered_penjualan($id_perusahaan, $tanggal_awal, $tanggal_akhir, $status);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['penjualan'] = $this->Laporan_penjualan_model->get_filtered_penjualan($id_perusahaan_user, $tanggal_awal, $tanggal_akhir, $status);
        }

        // Load library Excel
        $this->load->library('excel');

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setTitle("Laporan Penjualan");
        $objPHPExcel->getProperties()->setSubject("Laporan Penjualan");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'LAPORAN PENJUALAN');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Periode: ' . date('d-m-Y', strtotime($tanggal_awal)) . ' s/d ' . date('d-m-Y', strtotime($tanggal_akhir)));

        // Header
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'No');
        $objPHPExcel->getActiveSheet()->setCellValue('B4', 'No Invoice');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Tanggal');
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Pelanggan');
        $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Total');
        $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Status');
        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'User');

        // Data
        $row = 5;
        $no = 1;
        foreach ($data['penjualan'] as $p) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $no++);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $p->no_invoice);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, date('d-m-Y', strtotime($p->tanggal_penjualan)));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $p->nama_pelanggan);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $p->total_harga);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $p->status);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $p->created_by);
            $row++;
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Laporan Penjualan');

        // Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_penjualan_' . date('YmdHis') . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}