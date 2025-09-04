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

        // Get data based on role
        if ($this->session->userdata('id_role') == 5) {
            $data['barang'] = $this->Barang_model->get_all_barang();
        } else {
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['barang'] = $this->Barang_model->get_barang_by_perusahaan($id_perusahaan);
        }

        $data['content'] = 'master/barang_list';
        $this->load->view('template/template', $data);
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