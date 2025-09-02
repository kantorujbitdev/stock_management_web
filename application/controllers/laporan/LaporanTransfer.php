<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanTransfer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('hak_akses');
        $this->load->model('laporan/Laporan_transfer_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        $this->hak_akses->cek_akses('laporan_transfer');
    }

    public function index()
    {
        $data['title'] = 'Laporan Transfer Stok';

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
            $data['perusahaan'] = $this->Laporan_transfer_model->get_perusahaan_list();
            $data['transfer'] = $this->Laporan_transfer_model->get_filtered_transfer($id_perusahaan, $tanggal_awal, $tanggal_akhir, $status);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['transfer'] = $this->Laporan_transfer_model->get_filtered_transfer($id_perusahaan_user, $tanggal_awal, $tanggal_akhir, $status);

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

        $data['content'] = 'laporan/laporan_transfer';
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
            $data['transfer'] = $this->Laporan_transfer_model->get_filtered_transfer($id_perusahaan, $tanggal_awal, $tanggal_akhir, $status);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['transfer'] = $this->Laporan_transfer_model->get_filtered_transfer($id_perusahaan_user, $tanggal_awal, $tanggal_akhir, $status);
        }

        // Load library PDF
        $this->load->library('pdf');

        // Generate PDF
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->filename = "laporan_transfer_" . date('YmdHis') . ".pdf";
        $this->pdf->load_view('laporan/laporan_transfer_pdf', $data);
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
            $data['transfer'] = $this->Laporan_transfer_model->get_filtered_transfer($id_perusahaan, $tanggal_awal, $tanggal_akhir, $status);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['transfer'] = $this->Laporan_transfer_model->get_filtered_transfer($id_perusahaan_user, $tanggal_awal, $tanggal_akhir, $status);
        }

        // Load library Excel
        $this->load->library('excel');

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setTitle("Laporan Transfer Stok");
        $objPHPExcel->getProperties()->setSubject("Laporan Transfer Stok");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'LAPORAN TRANSFER STOK');
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Periode: ' . date('d-m-Y', strtotime($tanggal_awal)) . ' s/d ' . date('d-m-Y', strtotime($tanggal_akhir)));

        // Header
        $objPHPExcel->getActiveSheet()->setCellValue('A4', 'No');
        $objPHPExcel->getActiveSheet()->setCellValue('B4', 'No Transfer');
        $objPHPExcel->getActiveSheet()->setCellValue('C4', 'Tanggal');
        $objPHPExcel->getActiveSheet()->setCellValue('D4', 'Barang');
        $objPHPExcel->getActiveSheet()->setCellValue('E4', 'Gudang Asal');
        $objPHPExcel->getActiveSheet()->setCellValue('F4', 'Gudang Tujuan');
        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Jumlah');
        $objPHPExcel->getActiveSheet()->setCellValue('H4', 'Status');
        $objPHPExcel->getActiveSheet()->setCellValue('I4', 'User');

        // Data
        $row = 5;
        $no = 1;
        foreach ($data['transfer'] as $t) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $no++);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $t->no_transfer);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, date('d-m-Y H:i', strtotime($t->tanggal)));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $t->nama_barang);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $t->gudang_asal);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $t->gudang_tujuan);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $t->jumlah);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $t->status);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $t->created_by);
            $row++;
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Laporan Transfer Stok');

        // Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_transfer_' . date('YmdHis') . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}