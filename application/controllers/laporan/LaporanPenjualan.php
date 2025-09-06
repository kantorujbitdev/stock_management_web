<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanPenjualan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('laporan/Laporan_penjualan_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        $this->hak_akses->cek_akses('laporan_penjualan');
    }

    public function index()
    {
        // Ambil parameter filter
        $filter = [
            'id_perusahaan' => $this->input->get('id_perusahaan'),
            'tanggal_awal' => $this->input->get('tanggal_awal'),
            'tanggal_akhir' => $this->input->get('tanggal_akhir'),
            'status' => $this->input->get('status')
        ];

        // Set default tanggal jika tidak ada
        if (empty($filter['tanggal_awal'])) {
            $filter['tanggal_awal'] = date('Y-m-01'); // Awal bulan
        }
        if (empty($filter['tanggal_akhir'])) {
            $filter['tanggal_akhir'] = date('Y-m-d'); // Hari ini
        }

        // Validasi perusahaan untuk user bukan super admin
        if ($this->session->userdata('id_role') != 5) { // Bukan super admin
            $filter['id_perusahaan'] = $this->session->userdata('id_perusahaan');
        }

        // Ambil data
        $data['penjualan'] = $this->Laporan_penjualan_model->get_filtered_penjualan(
            $filter['id_perusahaan'],
            $filter['tanggal_awal'],
            $filter['tanggal_akhir'],
            $filter['status']
        );

        // Ambil data perusahaan berdasarkan role user
        $id_role = $this->session->userdata('id_role');
        if ($id_role == 5) { // Super Admin
            $data['perusahaan'] = $this->Laporan_penjualan_model->get_perusahaan_list();
        } else {
            // Hanya tampilkan perusahaan user tersebut
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = $this->db->get_where('perusahaan', ['id_perusahaan' => $id_perusahaan])->result();
        }

        $data['filter'] = $filter;

        // Load view
        $data['content'] = 'laporan/laporan_penjualan';
        $this->load->view('template/template', $data);
    }
    public function detail($id_penjualan)
    {
        // Cek hak akses
        $this->hak_akses->cek_akses('laporan_penjualan');

        // Ambil data penjualan
        $data['penjualan'] = $this->Laporan_penjualan_model->get_detail_penjualan($id_penjualan);

        if (!$data['penjualan']) {
            show_404();
        }

        // Ambil detail barang
        $data['detail_barang'] = $this->Laporan_penjualan_model->get_detail_barang($id_penjualan);

        // Ambil log status
        $data['log_status'] = $this->Laporan_penjualan_model->get_log_status($id_penjualan);

        // Ambil tanggal status terakhir
        $data['last_status'] = $this->Laporan_penjualan_model->get_last_status_date($id_penjualan);

        // Load view
        $data['content'] = 'laporan/laporan_penjualan_detail';
        $this->load->view('template/template', $data);
    }
    public function export_pdf()
    {
        // Cek hak akses
        $this->hak_akses->cek_akses('laporan_penjualan');

        // Ambil parameter filter
        $filter = [
            'id_perusahaan' => $this->input->get('id_perusahaan'),
            'tanggal_awal' => $this->input->get('tanggal_awal'),
            'tanggal_akhir' => $this->input->get('tanggal_akhir'),
            'status' => $this->input->get('status')
        ];

        // Ambil data
        $data['penjualan'] = $this->Laporan_penjualan_model->get_filtered_penjualan(
            $filter['id_perusahaan'],
            $filter['tanggal_awal'],
            $filter['tanggal_akhir'],
            $filter['status']
        );
        $data['filter'] = $filter;

        // Load library PDF
        $this->load->library('pdf');

        // Set paper size
        $this->pdf->setPaper('A4', 'landscape');

        // Load view untuk PDF
        $this->pdf->load_view('laporan/laporan_penjualan_pdf', $data);

        // Output PDF
        $this->pdf->stream("laporan_penjualan_" . date('Ymd') . ".pdf", array("Attachment" => false));
    }

    public function export_excel()
    {
        // Cek hak akses
        $this->hak_akses->cek_akses('laporan_penjualan');

        // Ambil parameter filter
        $filter = [
            'id_perusahaan' => $this->input->get('id_perusahaan'),
            'tanggal_awal' => $this->input->get('tanggal_awal'),
            'tanggal_akhir' => $this->input->get('tanggal_akhir'),
            'status' => $this->input->get('status')
        ];

        // Ambil data
        $data['penjualan'] = $this->Laporan_penjualan_model->get_filtered_penjualan(
            $filter['id_perusahaan'],
            $filter['tanggal_awal'],
            $filter['tanggal_akhir'],
            $filter['status']
        );
        $data['filter'] = $filter;

        // Load library Excel
        $this->load->library('excel');
        $objPHPExcel = $this->excel->getPHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setTitle("Laporan Penjualan")
            ->setDescription("Laporan Penjualan");

        // Add header data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'No Invoice')
            ->setCellValue('C1', 'Pelanggan');

        // Tambahkan kolom perusahaan jika super admin
        if ($this->session->userdata('id_role') == 5) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D1', 'Perusahaan');
            $col_barang = 'E';
            $col_jumlah = 'F';
            $col_status = 'G';
            $col_user = 'H';
        } else {
            $col_barang = 'D';
            $col_jumlah = 'E';
            $col_status = 'F';
            $col_user = 'G';
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($col_barang . '1', 'Barang')
            ->setCellValue($col_jumlah . '1', 'Jumlah Item')
            ->setCellValue($col_status . '1', 'Status')
            ->setCellValue($col_user . '1', 'User');

        // Add data
        $row = 2;
        $no = 1;

        foreach ($data['penjualan'] as $p) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, $no++)
                ->setCellValue('B' . $row, $p->no_invoice)
                ->setCellValue('C' . $row, $p->nama_pelanggan);

            // Tambahkan kolom perusahaan jika super admin
            if ($this->session->userdata('id_role') == 5) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D' . $row, $p->nama_perusahaan);
            }

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($col_barang . $row, $p->daftar_barang)
                ->setCellValue($col_jumlah . $row, $p->jumlah_item)
                ->setCellValue($col_status . $row, $p->status)
                ->setCellValue($col_user . $row, $p->created_by);

            $row++;
        }

        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Laporan Penjualan');

        // Set active sheet index to the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="laporan_penjualan_' . date('Ymd') . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = $this->excel->createWriter('Excel5');
        $objWriter->save('php://output');
        exit;
    }
}