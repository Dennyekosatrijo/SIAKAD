<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jadwal extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        cek_login_admin();
    }

    public function index()
    {
        $queryjadwal = "SELECT * FROM jadwal join mengajar on mengajar.id_mengajar = jadwal.id_mengajar 
        join guru on guru.nip = mengajar.nip join mata_pelajaran on mata_pelajaran.id_mapel = mengajar.id_mapel 
        join penjurusan on penjurusan.id_jurusan = mengajar.id_jurusan JOIN kelas on kelas.id_kelas = penjurusan.id_kelas";

        $data['title'] = 'Jadwal Mata Pelajaran';
        $data['data'] = $this->db->get_where('admin', ['nip' => $this->session->userdata('nip')])->row_array();
        $data['jadwal'] = $this->db->query($queryjadwal)->result();
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar', $data);
        $this->load->view('admin/templates/topbar', $data);
        $this->load->view('admin/jadwal', $data);
        $this->load->view('admin/templates/footer', $data);
    }

    public function tambahData()
    {
        $querymengajar = "SELECT * FROM mengajar join guru on guru.nip = mengajar.nip 
        join mata_pelajaran on mata_pelajaran.id_mapel = mengajar.id_mapel 
        join penjurusan on penjurusan.id_jurusan = mengajar.id_jurusan JOIN kelas on kelas.id_kelas = penjurusan.id_kelas ORDER BY mata_pelajaran ASC, kelas ASC, nama_jurusan ASC";

        $data['title'] = 'Tambah Jadwal Pelajaran';
        $data['mengajar'] = $this->db->query($querymengajar)->result();
        $data['data'] = $this->db->get_where('admin', ['nip' => $this->session->userdata('nip')])->row_array();
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar', $data);
        $this->load->view('admin/templates/topbar', $data);
        $this->load->view('admin/tambahjadwal', $data);
        $this->load->view('admin/templates/footer', $data);
    }

    public function simpanData()
    {

        $this->form_validation->set_rules('mulai', 'Mulai', 'required|trim', [
            'required' => 'Field tidak boleh kosong'
        ]);
        $this->form_validation->set_rules('akhir', 'Akhir', 'required|trim', [
            'required' => 'Field tidak boleh kosong'
        ]);

        if ($this->form_validation->run() == false) {
            $this->tambahData();
        } else {
            $mengajar = $this->input->post('mengajar');
            $hari = $this->input->post('hari');
            $mulai = $this->input->post('mulai');
            $akhir = $this->input->post('akhir');

            $data = [
                'id_mengajar' => $mengajar,
                'hari' => $hari,
                'waktu_mulai' => $mulai,
                'waktu_akhir' => $akhir
            ];

            $simpan = $this->db->insert('jadwal', $data);

            if ($simpan) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambah</div>');
            } else {
                $this->session->set_flashdata('messgae', '<div class="alert alert-danger" role="alert">Data tidak berhasil ditambah</div>');
            }

            redirect('Admin/jadwal', $data);
        }
    }

    public function delete($id)
    {
        $delete = $this->db->where('id_jadwal', $id)->delete('jadwal');
        if ($delete) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data yang anda pilih telah terhapus</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Tidak bisa hapus data</div>');
        }
        redirect('Admin/jadwal');
    }

    public function edit($id)
    {
        $queryjadwal = "SELECT * FROM jadwal join mengajar on mengajar.id_mengajar = jadwal.id_mengajar 
        join guru on guru.nip = mengajar.nip join mata_pelajaran on mata_pelajaran.id_mapel = mengajar.id_mapel 
        join penjurusan on penjurusan.id_jurusan = mengajar.id_jurusan JOIN kelas on kelas.id_kelas = penjurusan.id_kelas WHERE jadwal.id_jadwal=$id";

        $querymengajar = "SELECT * FROM mengajar join guru on guru.nip = mengajar.nip 
        join mata_pelajaran on mata_pelajaran.id_mapel = mengajar.id_mapel 
        join penjurusan on penjurusan.id_jurusan = mengajar.id_jurusan JOIN kelas on kelas.id_kelas = penjurusan.id_kelas ORDER BY mata_pelajaran ASC, kelas ASC, nama_jurusan ASC";

        $data['title'] = 'Edit Jadwal Pelajaran';
        $data['mengajar'] = $this->db->query($querymengajar)->result();
        $data['jadwal'] = $this->db->query($queryjadwal)->row();
        $data['data'] = $this->db->get_where('admin', ['nip' => $this->session->userdata('nip')])->row_array();
        $this->load->view('admin/templates/header', $data);
        $this->load->view('admin/templates/sidebar', $data);
        $this->load->view('admin/templates/topbar', $data);
        $this->load->view('admin/edit_jadwal', $data);
        $this->load->view('admin/templates/footer', $data);
    }

    public function update($id)
    {
        $this->form_validation->set_rules('mulai', 'Mulai', 'required|trim', [
            'required' => 'Field tidak boleh kosong'
        ]);
        $this->form_validation->set_rules('akhir', 'Akhir', 'required|trim', [
            'required' => 'Field tidak boleh kosong'
        ]);

        if ($this->form_validation->run() == false) {
            $this->tambahData();
        } else {
            $mengajar = $this->input->post('mengajar');
            $hari = $this->input->post('hari');
            $mulai = $this->input->post('mulai');
            $akhir = $this->input->post('akhir');

            $data = [
                'id_mengajar' => $mengajar,
                'hari' => $hari,
                'waktu_mulai' => $mulai,
                'waktu_akhir' => $akhir
            ];

            $save = $this->db->where('id_jadwal', $id)->update('jadwal', $data);

            if ($save) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diubah</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data tidak berhasil diubah</div>');
            }


            redirect('Admin/jadwal');
        }
    }
}
