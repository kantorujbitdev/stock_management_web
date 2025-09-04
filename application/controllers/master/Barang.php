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

        // Jika Super Admin, tampilkan semua barang
        if ($this->session->userdata('id_role') == 5) {
            $data['barang'] = $this->Barang_model->get_all_barang();
        } else {
            // Jika Admin Pusat, tampilkan barang milik perusahaannya saja
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['barang'] = $this->Barang_model->get_barang_by_perusahaan($id_perusahaan);
        }

        $data['content'] = 'master/barang_list';
        $this->load->view('template/template', $data);
    }

    public function add()
    {
        $data['title'] = 'Tambah Barang';

        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
            // Ambil semua kategori aktif untuk Super Admin
            // $data['kategori'] = $this->Kategori_model->get_all_kategori();
            $data['kategori'] = array();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
            $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
        }

        $data['content'] = 'master/barang_form';
        $this->load->view('template/template', $data);
    }

    // public function add() {
    //     $data['title'] = 'Tambah Barang';

    //     // Jika Super Admin, tampilkan semua perusahaan
    //     if ($this->session->userdata('id_role') == 5) {
    //         $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
    //         $data['kategori'] = array(); // Kosongkan, akan diisi via AJAX
    //     } else {
    //         // Jika Admin Pusat, hanya tampilkan perusahaannya
    //         $id_perusahaan = $this->session->userdata('id_perusahaan');
    //         $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
    //         $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
    //     }

    //     $data['content'] = 'master/barang_form';
    //     $this->load->view('template/template', $data);
    // }


    public function add_process()
    {
        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('sku', 'SKU', 'required|callback_check_sku');

        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {
            // Cek hak akses perusahaan
            if ($this->session->userdata('id_role') != 5) {
                $id_perusahaan_user = $this->session->userdata('id_perusahaan');
                $id_perusahaan_input = $this->input->post('id_perusahaan');

                if ($id_perusahaan_user != $id_perusahaan_input) {
                    $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                    redirect('barang');
                }
            }

            // Upload gambar jika ada
            $gambar = '';
            if (!empty($_FILES['gambar']['name'])) {
                $config['upload_path'] = './uploads/barang/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $gambar = $upload_data['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('barang/add');
                }
            }

            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'id_kategori' => $this->input->post('id_kategori'),
                'nama_barang' => $this->input->post('nama_barang'),
                'sku' => $this->input->post('sku'),
                'deskripsi' => $this->input->post('deskripsi'),
                'gambar' => $gambar,
                'aktif' => 1 // Default aktif
            ];

            $this->Barang_model->insert_barang($data);
            $this->session->set_flashdata('success', 'Barang berhasil ditambahkan');
            redirect('barang');
        }
    }

    public function edit($id)
    {
        // Cek apakah user punya akses ke barang ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $barang = $this->Barang_model->get_barang_by_id($id);

            if ($barang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
                redirect('barang');
            }
        }

        $data['title'] = 'Edit Barang';
        $data['barang'] = $this->Barang_model->get_barang_by_id($id);

        // Jika Super Admin, tampilkan semua perusahaan
        if ($this->session->userdata('id_role') == 5) {
            $data['perusahaan'] = $this->Perusahaan_model->get_perusahaan_aktif();
            // $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($data['barang']->id_perusahaan);
            $data['kategori'] = array();
        } else {
            // Jika Admin Pusat, hanya tampilkan perusahaannya
            $id_perusahaan = $this->session->userdata('id_perusahaan');
            $data['perusahaan'] = array($this->Perusahaan_model->get_perusahaan_by_id($id_perusahaan));
            $data['kategori'] = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);
        }

        $data['content'] = 'master/barang_form';
        $this->load->view('template/template', $data);
    }

    public function edit_process()
    {
        $id = $this->input->post('id_barang');

        // Cek hak akses perusahaan
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $id_perusahaan_input = $this->input->post('id_perusahaan');

            if ($id_perusahaan_user != $id_perusahaan_input) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke perusahaan ini');
                redirect('barang');
            }
        }

        $this->form_validation->set_rules('id_perusahaan', 'Perusahaan', 'required');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('sku', 'SKU', 'required|callback_check_sku_edit[' . $id . ']');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
        } else {
            // Upload gambar jika ada
            $gambar = $this->input->post('gambar_lama');
            if (!empty($_FILES['gambar']['name'])) {
                $config['upload_path'] = './uploads/barang/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $gambar = $upload_data['file_name'];

                    // Hapus gambar lama jika ada
                    if ($this->input->post('gambar_lama') && file_exists('./uploads/barang/' . $this->input->post('gambar_lama'))) {
                        unlink('./uploads/barang/' . $this->input->post('gambar_lama'));
                    }
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('barang/edit/' . $id);
                }
            }

            $data = [
                'id_perusahaan' => $this->input->post('id_perusahaan'),
                'id_kategori' => $this->input->post('id_kategori'),
                'nama_barang' => $this->input->post('nama_barang'),
                'sku' => $this->input->post('sku'),
                'deskripsi' => $this->input->post('deskripsi'),
                'gambar' => $gambar,
            ];

            $this->Barang_model->update_barang($id, $data);
            $this->session->set_flashdata('success', 'Barang berhasil diupdate');
            redirect('barang');
        }
    }

    public function nonaktif($id)
    {
        // Cek apakah user punya akses ke barang ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $barang = $this->Barang_model->get_barang_by_id($id);

            if ($barang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
                redirect('barang');
            }
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
        // Cek apakah user punya akses ke barang ini
        if ($this->session->userdata('id_role') != 5) {
            $id_perusahaan_user = $this->session->userdata('id_perusahaan');
            $barang = $this->Barang_model->get_barang_by_id($id);

            if ($barang->id_perusahaan != $id_perusahaan_user) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke barang ini');
                redirect('barang');
            }
        }

        if ($this->Barang_model->update_status($id, 1)) {
            $this->session->set_flashdata('success', 'Barang berhasil diaktifkan kembali');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengaktifkan barang');
        }
        redirect('barang');
    }

    // Callback validation untuk SKU
    public function check_sku($sku)
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        if ($this->Barang_model->check_sku($sku, $id_perusahaan)) {
            $this->form_validation->set_message('check_sku', 'SKU sudah digunakan di perusahaan ini');
            return FALSE;
        }
        return TRUE;
    }

    // Callback validation untuk SKU (edit)
    public function check_sku_edit($sku, $id_barang)
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        if ($this->Barang_model->check_sku($sku, $id_perusahaan, $id_barang)) {
            $this->form_validation->set_message('check_sku_edit', 'SKU sudah digunakan di perusahaan ini');
            return FALSE;
        }
        return TRUE;
    }

    // public function get_kategori_by_perusahaan()
    // {
    //     // Izinkan baik GET maupun POST
    //     $id_perusahaan = $this->input->get('id_perusahaan') ?: $this->input->post('id_perusahaan');

    //     // Debug log
    //     log_message('debug', 'AJAX Request - ID Perusahaan: ' . $id_perusahaan);

    //     if (!$id_perusahaan) {
    //         echo '<option value="">-- Pilih Kategori --</option>';
    //         return;
    //     }

    //     $kategori = $this->Kategori_model->get_kategori_by_perusahaan($id_perusahaan);

    //     // Debug log
    //     log_message('debug', 'Jumlah kategori ditemukan: ' . count($kategori));

    //     $options = '<option value="">-- Pilih Kategori --</option>';
    //     foreach ($kategori as $row) {
    //         $options .= '<option value="' . $row->id_kategori . '">' . $row->nama_kategori . '</option>';
    //     }

    //     echo $options;
    // }

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

    // Get barang by perusahaan (untuk AJAX)
    public function get_barang_by_perusahaan()
    {
        $id_perusahaan = $this->input->post('id_perusahaan');
        $barang = $this->Barang_model->get_barang_by_perusahaan($id_perusahaan);

        echo '<option value="">-- Pilih Barang --</option>';
        foreach ($barang as $row) {
            echo '<option value="' . $row->id_barang . '">' . $row->nama_barang . ' (' . $row->sku . ')</option>';
        }
    }
}