<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('hak_akses');
        $this->load->model('master/Barang_model');
        $this->load->model('perusahaan/Perusahaan_model');
        $this->load->model('master/Kategori_model');
        $this->load->model('stok/Stok_awal_model');
        $this->load->model('stok/Stok_gudang_model');
        $this->load->model('stok/Log_stok_model');
        $this->load->model('perusahaan/Gudang_model');

        // Cek login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }

        // Cek hak akses
        $this->hak_akses->cek_akses('barang');
    }


    public function index()
    {
        $data['title'] = 'Data Barang';

        // Get pagination parameters
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $limit = 10; // Number of items per page
        $offset = ($page - 1) * $limit;

        // Filter parameters
        $filter = [
            'id_perusahaan' => $this->session->userdata('id_role') == 5 ? $this->input->get('id_perusahaan') : $this->session->userdata('id_perusahaan'),
            'id_kategori' => $this->input->get('id_kategori'),
            'stock_status' => $this->input->get('stock_status'), // all, empty, has_stock
            'search' => $this->input->get('search'),
            'status' => $this->input->get('status'),
            'sort_by' => $this->input->get('sort_by'),
            'limit' => $limit,
            'offset' => $offset
        ];

        // Get data based on role
        if ($this->session->userdata('id_role') == 5) {
            // Untuk Super Admin, jika ada filter perusahaan, gunakan filter tersebut
            if (!empty($filter['id_perusahaan'])) {
                $data['barang'] = $this->Barang_model->get_barang_with_stok_status($filter);
                $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($filter['id_perusahaan']);
            } else {
                // Jika tidak ada filter perusahaan, ambil semua barang dari semua perusahaan
                $data['barang'] = $this->Barang_model->get_barang_with_stok_status($filter);
                // Ambil semua kategori dari semua perusahaan untuk Super Admin
                $data['kategori'] = $this->Kategori_model->get_all_kategori();
            }
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
        } else {
            // Untuk role lainnya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $filter['id_perusahaan'] = $id_perusahaan;
            $data['barang'] = $this->Barang_model->get_barang_with_stok_status($filter);
            $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
        }

        // Get total items for pagination
        $total_items = $this->Barang_model->count_barang_with_stok_status($filter);

        // Pagination data
        $data['total_items'] = $total_items;
        $data['current_page'] = $page;
        $data['items_per_page'] = $limit;
        $data['has_more'] = ($offset + $limit) < $total_items;
        $data['filter'] = $filter;

        $data['content'] = 'master/barang_part/index';
        $this->load->view('template/template', $data);
    }

    // Method untuk load more data via AJAX
    public function load_more()
    {
        // Check if AJAX request
        if (!$this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Direct access not allowed']);
            return;
        }

        // Get pagination parameters
        $page = $this->input->post('page') ? (int) $this->input->post('page') : 1;
        $limit = 10; // Same as index
        $offset = ($page - 1) * $limit;

        // Filter parameters
        $filter = [
            'id_perusahaan' => $this->input->post('id_perusahaan'),
            'id_kategori' => $this->input->post('id_kategori'),
            'stock_status' => $this->input->post('stock_status'),
            'search' => $this->input->post('search'),
            'status' => $this->input->post('status'),
            'sort_by' => $this->input->post('sort_by'),
            'limit' => $limit,
            'offset' => $offset
        ];

        // Get data
        $barang = $this->Barang_model->get_barang_with_stok_status($filter);

        // Get total items for pagination
        try {
            $total_items = $this->Barang_model->count_barang_with_stok_status($filter);
            $showing_count = ($offset + count($barang));
            $has_more = ($offset + $limit) < $total_items;

            // Generate HTML for each item
            $html = [];
            foreach ($barang as $b) {
                ob_start();
                $this->load->view('master/barang_part/barang_card', ['b' => $b]);
                $html[] = ob_get_clean();
            }

            // Return JSON response
            echo json_encode([
                'success' => true,
                'html' => $html,
                'showing_count' => $showing_count,
                'total_items' => $total_items,
                'current_page' => $page,
                'has_more' => $has_more
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    // Get gudang by perusahaan for AJAX
    public function get_gudang_by_perusahaan()
    {
        $id_perusahaan = $this->input->get('id_perusahaan');
        if (!$id_perusahaan) {
            echo json_encode([]);
            return;
        }
        $gudang = $this->Gudang_model->get_gudang_by_perusahaan($id_perusahaan);
        echo json_encode($gudang);
    }

    // Input stok awal process
    public function input_stok_awal_process()
    {
        // Cek hak akses hanya untuk role 1 dan 5
        if ($this->session->userdata('id_role') != 1 && $this->session->userdata('id_role') != 5) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah stok awal');
            redirect('barang');
        }

        $id_barang = $this->input->post('id_barang');
        $id_gudang = $this->input->post('id_gudang');
        $qty_awal = $this->input->post('qty_awal');

        // Validasi
        if (!$id_barang || !$id_gudang || !$qty_awal || $qty_awal <= 0) {
            $this->session->set_flashdata('error', 'Data tidak valid');
            redirect('barang');
        }

        // Cek hak akses perusahaan
        $barang = $this->Barang_model->get_barang_by_id($id_barang);
        if (!$barang) {
            show_404();
        }

        if ($this->session->userdata('id_role') != 5) {
            if ($barang->id_perusahaan != $this->session->userdata('id_perusahaan')) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
                redirect('barang');
            }
        }

        // Cek apakah stok awal sudah ada - Perbaikan di sini
        if ($this->Stok_awal_model->check_stok_awal_exists($id_barang, $id_gudang)) {
            $this->session->set_flashdata('error', 'Stok awal untuk barang ini sudah ada');
            redirect('barang');
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

        // Gunakan transaksi untuk keamanan data
        $this->db->trans_start();

        // Insert stok awal
        $insert_stok_awal = $this->Stok_awal_model->insert_stok_awal($data_stok_awal);

        if ($insert_stok_awal) {
            // Update stok gudang
            $stok_gudang = $this->Stok_gudang_model->get_stok_by_barang_gudang($id_barang, $id_gudang);

            if ($stok_gudang) {
                // Update stok existing
                $this->Stok_gudang_model->update_stok($stok_gudang->id_stok, ['jumlah' => $qty_awal]);
            } else {
                // Insert stok baru
                $data_stok = [
                    'id_perusahaan' => $barang->id_perusahaan,
                    'id_gudang' => $id_gudang,
                    'id_barang' => $id_barang,
                    'jumlah' => $qty_awal
                ];
                $this->Stok_gudang_model->insert_stok($data_stok);
            }

            // Insert log stok
            $data_log = [
                'id_barang' => $id_barang,
                'id_user' => $this->session->userdata('id_user'),
                'id_perusahaan' => $barang->id_perusahaan,
                'id_gudang' => $id_gudang,
                'jenis' => 'masuk',
                'jumlah' => $qty_awal,
                'keterangan' => 'Stok Awal: ' . $this->input->post('keterangan'),
                'id_referensi' => $insert_stok_awal,
                'tipe_referensi' => 'stok_awal'
            ];
            $this->Log_stok_model->insert_log($data_log);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menambahkan stok awal');
        } else {
            $this->session->set_flashdata('success', 'Stok awal berhasil ditambahkan');
        }

        redirect('barang');
    }


    public function add()
    {
        $data['title'] = 'Tambah Barang';

        // Ambil data dari flashdata jika ada
        $data['old_input'] = $this->session->flashdata('old_input');
        $data['validation_errors'] = $this->session->flashdata('validation_errors');

        // Setup form data
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();

            // Jika Super Admin dan ada input perusahaan, ambil kategori berdasarkan perusahaan
            if ($this->input->post('id_perusahaan')) {
                $id_perusahaan = $this->input->post('id_perusahaan');
                $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
            } else {
                $data['kategori'] = array();
            }
        } else {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
            $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
        }

        $data['content'] = 'master/barang_form';
        $this->load->view('template/template', $data);
    }

    public function edit($id)
    {
        // Cek akses
        if (!$this->_check_access($id)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
            redirect('barang');
        }

        $data['title'] = 'Edit Barang';
        $data['barang'] = $this->Barang_model->get_barang_by_id($id);
        $data = $this->_setup_form_data($data, $data['barang']->id_perusahaan);
        $data['content'] = 'master/barang_form';
        $this->load->view('template/template', $data);
    }

    public function nonaktif($id)
    {
        if (!$this->_check_access($id)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
            redirect('barang');
        }

        if ($this->Barang_model->update_status($id, 0)) {
            $this->session->set_flashdata('success', 'Barang berhasil dinonaktifkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menonaktifkan barang');
        }
        redirect('barang');
    }

    public function aktif($id)
    {
        if (!$this->_check_access($id)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
            redirect('barang');
        }

        if ($this->Barang_model->update_status($id, 1)) {
            $this->session->set_flashdata('success', 'Barang berhasil diaktifkan kembali');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan barang');
        }
        redirect('barang');
    }

    public function get_kategori_by_perusahaan()
    {
        $id_perusahaan = $this->input->get('id_perusahaan');
        if (!$id_perusahaan) {
            echo json_encode([]);
            return;
        }

        $kategori = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
        echo json_encode($kategori);
    }

    public function get_barang_by_perusahaan()
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        $barang = $this->Barang_model->get_barang_by_perusahaan($id_perusahaan);

        $options = '<option value="">-- Pilih Barang --</option>';
        foreach ($barang as $row) {
            $options .= '<option value="' . $row->id_barang . '">' . $row->nama_barang . ' (' . $row->sku . ')</option>';
        }

        echo $options;
    }
    public function add_process()
    {
        // Validasi form
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('sku', 'SKU', 'required|callback_check_sku');

        if ($this->form_validation->run() == FALSE) {
            // Simpan error messages ke flashdata
            $this->session->set_flashdata('error', 'Periksa kembali inputan Anda');
            $this->session->set_flashdata('validation_errors', $this->form_validation->error_array());

            // Simpan input lama untuk repopulate form
            $this->session->set_flashdata('old_input', $this->input->post());

            redirect('barang/add');
            return;
        }

        // Handle upload gambar
        $gambar = $this->_handle_image_upload();
        if ($gambar === FALSE) {
            redirect('barang/add');
            return;
        }

        // Simpan data barang
        $data_barang = [
            'id_perusahaan' => $this->input->post('id_perusahaan'),
            'id_kategori' => $this->input->post('id_kategori'),
            'nama_barang' => $this->input->post('nama_barang'),
            'sku' => $this->input->post('sku'),
            'deskripsi' => $this->input->post('deskripsi'),
            'gambar' => $gambar,
            'aktif' => 1
        ];

        if ($this->Barang_model->insert_barang($data_barang)) {
            $this->session->set_flashdata('success', 'Data barang berhasil disimpan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data barang');
        }

        redirect('barang');
    }
    public function edit_process()
    {
        $id_barang = $this->input->post('id_barang');
        $barang_lama = $this->Barang_model->get_barang_by_id($id_barang);

        // Validasi form
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('sku', 'SKU', 'required|callback_check_sku_edit[' . $id_barang . ']');

        if ($this->form_validation->run() == FALSE) {
            // Simpan error messages ke flashdata
            $this->session->set_flashdata('error', 'Periksa kembali inputan Anda');
            $this->session->set_flashdata('validation_errors', $this->form_validation->error_array());

            // Simpan input lama untuk repopulate form
            $this->session->set_flashdata('old_input', $this->input->post());

            redirect('barang/edit/' . $id_barang);
            return;
        }

        // Handle upload gambar
        $gambar = $this->_handle_image_upload($barang_lama->gambar);
        if ($gambar === FALSE) {
            redirect('barang/edit/' . $id_barang);
            return;
        }

        // Update data barang
        $data_barang = [
            'id_perusahaan' => $this->input->post('id_perusahaan'),
            'id_kategori' => $this->input->post('id_kategori'),
            'nama_barang' => $this->input->post('nama_barang'),
            'sku' => $this->input->post('sku'),
            'deskripsi' => $this->input->post('deskripsi'),
            'gambar' => $gambar,
            'aktif' => 1
        ];

        if ($this->Barang_model->update_barang($id_barang, $data_barang)) {
            $this->session->set_flashdata('success', 'Data barang berhasil diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data barang');
        }

        redirect('barang');
    }

    // Callback untuk validasi SKU unik
    public function check_sku($sku)
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        if ($this->Barang_model->check_sku_unique($sku, $id_perusahaan)) {
            $this->form_validation->set_message('check_sku', 'SKU sudah ada di perusahaan ini');
            return FALSE;
        }
        return TRUE;
    }

    // Callback untuk validasi SKU unik saat edit
    public function check_sku_edit($sku, $id_barang)
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        if ($this->Barang_model->check_sku_unique($sku, $id_perusahaan, $id_barang)) {
            $this->form_validation->set_message('check_sku_edit', 'SKU sudah ada di perusahaan ini');
            return FALSE;
        }
        return TRUE;
    }

    // Private method untuk setup form data
    private function _setup_form_data($data, $id_perusahaan = null)
    {
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
            $data['kategori'] = array();
        } else {
            $id_perusahaan = $id_perusahaan ?: $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
            $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
        }
        return $data;
    }

    // Private method untuk cek akses
    private function _check_access($id_barang)
    {
        // Super Admin memiliki akses ke semua barang
        if ($this->session->userdata('id_role') == 5) {
            return TRUE;
        }

        $barang = $this->Barang_model->get_barang_by_id($id_barang);
        $id_perusahaan_user = $this->session->userdata('id_perusahaan');

        return ($barang && $barang->id_perusahaan == $id_perusahaan_user);
    }

    // Private method untuk handle upload gambar
    private function _handle_image_upload($old_image = null)
    {
        // Jika tidak ada file yang diupload, kembalikan gambar lama
        if (empty($_FILES['gambar']['name'])) {
            return $old_image;
        }

        $config['upload_path'] = './uploads/barang/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('gambar')) {
            $upload_data = $this->upload->data();
            $new_image = $upload_data['file_name'];

            // Hapus gambar lama jika ada
            if ($old_image && file_exists('./uploads/barang/' . $old_image)) {
                unlink('./uploads/barang/' . $old_image);
            }

            return $new_image;
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupload gambar: ' . $this->upload->display_errors());
            return FALSE;
        }
    }
}