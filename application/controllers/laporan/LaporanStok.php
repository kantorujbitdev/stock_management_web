<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanStok extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('hak_akses');
        $this->load->model('laporan/Laporan_stok_model');
        
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Cek hak akses
        $this->hak_akses->cek_akses('laporan_stok');
    }

    public function index() {
        $data['title'] = 'Laporan Stok';
        
        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $id_gudang = $this->input->get('id_gudang');
        $id_kategori = $this->input->get('id_kategori');
        
        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['perusahaan'] = $this->Laporan_stok_model->get_perusahaan_list();
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($id_perusahaan, $id_gudang, $id_kategori);
            
            // Get gudang list based on selected perusahaan
            if ($id_perusahaan) {
                $data['gudang'] = $this->Laporan_stok_model->get_gudang_by_perusahaan($id_perusahaan);
            } else {
                $data['gudang'] = [];
            }
            
            // Get kategori list based on selected perusahaan
            if ($id_perusahaan) {
                $data['kategori'] = $this->Laporan_stok_model->get_kategori_by_perusahaan($id_perusahaan);
            } else {
                $data['kategori'] = [];
            }
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($id_perusahaan_user, $id_gudang, $id_kategori);
            
            // Get perusahaan data for filter
            $this->load->model('perusahaan/Perusahaan_model');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan_user));
            
            // Get gudang list based on selected perusahaan
            $data['gudang'] = $this->Laporan_stok_model->get_gudang_by_perusahaan($id_perusahaan_user);
            
            // Get kategori list based on selected perusahaan
            $data['kategori'] = $this->Laporan_stok_model->get_kategori_by_perusahaan($id_perusahaan_user);
        }
        
        // Set filter values for view
        $data['filter'] = [
            'id_perusahaan' => $id_perusahaan,
            'id_gudang' => $id_gudang,
            'id_kategori' => $id_kategori
        ];
        
        $data['content'] = 'laporan/laporan_stok';
        $this->load->view('template/template', $data);
    }

    public function export_pdf() {
        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $id_gudang = $this->input->get('id_gudang');
        $id_kategori = $this->input->get('id_kategori');
        
        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($id_perusahaan, $id_gudang, $id_kategori);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($id_perusahaan_user, $id_gudang, $id_kategori);
        }
        
        // Load library PDF
        $this->load->library('pdf');
        
        // Generate PDF
        $this->pdf->setPaper('A4', 'landscape');
        $this->pdf->filename = "laporan_stok_" . date('YmdHis') . ".pdf";
        $this->pdf->load_view('laporan/laporan_stok_pdf', $data);
    }

    public function export_excel() {
        // Get filter values
        $id_perusahaan = $this->input->get('id_perusahaan');
        $id_gudang = $this->input->get('id_gudang');
        $id_kategori = $this->input->get('id_kategori');
        
        // Get data berdasarkan role
        if ($this->session->userdata('id_role') == 5) {
            // Super Admin - lihat semua data
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($id_perusahaan, $id_gudang, $id_kategori);
        } else {
            // User lain - lihat data perusahaannya saja
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $data['stok'] = $this->Laporan_stok_model->get_filtered_stok($id_perusahaan_user, $id_gudang, $id_kategori);
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
        $objPHPExcel->getActiveSheet()->setCellValue('G4', 'Stok');
        
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
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $s->jumlah);
            $row++;
        }
        
        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Laporan Stok');
        
        // Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_stok_' . date('YmdHis') . '.xls"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}