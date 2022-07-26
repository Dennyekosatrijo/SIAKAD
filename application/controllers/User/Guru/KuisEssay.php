<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KuisEssay extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login_guru();
    }
    public function index()
    {
        cek_jumlah_keluar();
        $nip = $this->session->userdata('nip');

        $querykuis = "SELECT * FROM kuis JOIN mengajar on mengajar.id_mengajar = kuis.id_mengajar WHERE mengajar.nip=$nip";
        $querysoal = "SELECT MAX(soal.id_soal) AS hello FROM soal";

        $data['title'] = 'Input Soal Essay';
        $data['nama'] = $this->db->query($querykuis)->result();
        $data['soall'] = $this->db->query($querysoal)->row();
        $this->load->view('users/templates/header', $data);
        $this->load->view('users/templates/navguru');
        $this->load->view('users/guru/create_event/kuisessay', $data);
        $this->load->view('users/templates/footer');
        $this->load->view('auto');
    }
    public function edit($id)
    {
        $nip = $this->session->userdata('nip');

        $hasilnamaujian = "SELECT *, soal.id_soal as idk, tr_soal.id_soal as id FROM `tr_kuis` 
        JOIN kuis ON kuis.id_kuis = tr_kuis.id_kuis
        JOIN mengajar ON mengajar.id_mengajar = kuis.id_mengajar
        JOIN soal ON tr_kuis.id_soal = soal.id_soal
        LEFT JOIN tr_soal ON soal.id_soal = tr_soal.id_soal 
        WHERE tr_kuis.id_soal=$id ORDER BY tr_kuis.id_tr_kuis DESC";

        $querykuis = "SELECT * FROM kuis JOIN mengajar on mengajar.id_mengajar = kuis.id_mengajar WHERE mengajar.nip=$nip";

        $querykuisid = "SELECT * FROM kuis JOIN mengajar on mengajar.id_mengajar = kuis.id_mengajar WHERE mengajar.nip=$nip";

        $querysoal = "SELECT MAX(soal.id_soal) AS hello FROM soal";

        $data['title'] = 'Edit Soal Ujian Essay';
        $data['detail'] = $this->db->query($hasilnamaujian)->row();
        $data['det'] = $this->db->query($querykuisid)->row();
        $data['data'] = $this->db->get_where('admin', ['nip' => $this->session->userdata('nip')])->row_array();
        $this->load->view('users/templates/header', $data);
        $this->load->view('users/templates/navguru');
        $this->load->view('users/guru/create_event/edit_kuisessay', $data);
        $this->load->view('users/templates/footer');
    }

    public function update($id)
    {
        $this->form_validation->set_rules('tekssoal', 'Tekssoal', 'required|trim', [
            'required' => 'Field tidak boleh kosong'
        ]);

        if ($this->form_validation->run() == false) {
            $this->edit($id);
        } else {
            $nip = $this->session->userdata('nip');

            $idkuis = $this->input->post('idkuis');
            $tekssoal = $this->input->post('tekssoal');
            $poto = $_FILES['file_input']['name'];

            // $file_ext = pathinfo($_FILES['file_input']['name'], PATHINFO_EXTENSION);


            $config['upload_path']        =    './assets/users/upload';
            $config['allowed_types']    =    'jpg|jpeg|png';
            $config['max_size']            =    10000;
            // $config['file_name']		=	'picture-'.date('ymd').'-'.substr(md5(rand()),0,10);
            // $config['file_name']        =    $poto;

            $this->load->library('upload', $config);

            if (@$_FILES['file_input']['name'] != null) {
                if ($this->upload->do_upload('file_input')) {
                    $data = array(
                        'soal' => $tekssoal,
                        'nama_file' => preg_replace("/\s+/", "_", $poto),
                        'tanggal_input' => date('Y:m:d H:i:s'),
                        'nip' => $nip
                    );

                    $this->db->where('id_soal', $id)->update('soal', $data);

                    if ($this->db->affected_rows() > 0) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil di update</div>');
                        redirect('User/Guru/kuisessay/kuis/' . $idkuis);
                    }
                } else {
                    $error = array('error' => $this->upload->display_errors());
                    echo "<script>alert('Format file salah');</script>";
                    echo "<script>window.location='" . site_url('User/Guru/kuisessay/kuis/' . $idkuis) . "';</script>";
                }
                // if ($this->db->affected_rows() > 0) {
                //     echo "<script>alert('Data berhasil ditambah');</script>";
                // }
                // echo "<script>window.location='" . site_url('User/Guru/Dashboard') . "';</script>";
            } else {
                $data = array(
                    'soal' => $tekssoal,
                    'tanggal_input' => date('Y:m:d H:i:s'),
                    'nip' => $nip
                );

                $this->db->where('id_soal', $id)->update('soal', $data);

                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil di update</div>');
                    redirect('User/Guru/kuisessay/kuis/' . $idkuis);
                }
                echo "<script>window.location='" . site_url('User/Guru/kuisessay/kuis/' . $idkuis) . "';</script>";
            }
        }
    }


    public function delete($id, $id_kuis)
    {
        $delete = $this->db->where('id_soal', $id)->delete('tr_kuis');
        $delete = $this->db->where('id_soal', $id)->delete('soal');
        if ($delete) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data yang anda pilih telah terhapus</div>');
        } else {
            $this->session->set_flashdata('messgae', '<div class="alert alert-danger" role="alert">Tidak bisa hapus data</div>');
        }
        redirect('User/Guru/KuisEssay/kuis/' . $id_kuis);
    }

    public function kuis($id)
    {
        $nip = $this->session->userdata('nip');

        $hasilnamaujian = "SELECT *, soal.id_soal as idk, tr_soal.id_soal as id FROM `tr_kuis` 
        JOIN kuis ON kuis.id_kuis = tr_kuis.id_kuis
        JOIN mengajar ON mengajar.id_mengajar = kuis.id_mengajar
        JOIN soal ON tr_kuis.id_soal = soal.id_soal
        LEFT JOIN tr_soal ON soal.id_soal = tr_soal.id_soal 
        WHERE tr_kuis.id_kuis=$id 
        ORDER BY `tr_kuis`.`id_soal` ASC";

        $hasil = "SELECT * FROM `kuis` 
        WHERE kuis.id_kuis=$id";
        $data['hasil'] = $this->db->query($hasil)->row();
        $db = $this->db->query($hasil)->row();
        $seasson = [
            'id_kuis' => $id,
            'jml_soal' => $db->jml_soal,
            'jumlah_keluar' => $db->jumlah_keluar
        ];
        $this->session->set_userdata($seasson);


        $querykuis = "SELECT * FROM kuis JOIN mengajar on mengajar.id_mengajar = kuis.id_mengajar WHERE mengajar.nip=$nip";

        $querykuisid = "SELECT * FROM kuis JOIN mengajar on mengajar.id_mengajar = kuis.id_mengajar WHERE kuis.id_kuis=$id and mengajar.nip=$nip";

        $querysoal = "SELECT MAX(soal.id_soal) AS hello FROM soal";
        $pilgan = "SELECT * FROM `tr_kuis` 
        JOIN kuis ON kuis.id_kuis = tr_kuis.id_kuis
        JOIN mengajar ON mengajar.id_mengajar = kuis.id_mengajar
        JOIN soal ON tr_kuis.id_soal = soal.id_soal
        LEFT JOIN tr_soal ON soal.id_soal = tr_soal.id_soal ";

        $data['title'] = 'Input Soal Ujian Essay';
        $data['detail'] = $this->db->query($hasilnamaujian)->result();
        $data['nama'] = $this->db->query($querykuis)->result();
        $data['det'] = $this->db->query($querykuisid)->row();
        $data['soal'] = $this->db->query($querysoal)->row();
        $data['data'] = $this->db->get_where('admin', ['nip' => $this->session->userdata('nip')])->row_array();
        $this->load->view('users/templates/header', $data);
        $this->load->view('users/templates/navguru');
        $this->load->view('users/guru/create_event/kuisessay', $data);
        $this->load->view('users/templates/footer');
    }

    function tambahData($id)
    {
        $this->form_validation->set_rules('tekssoal', 'Tekssoal', 'required|trim', [
            'required' => 'Field tidak boleh kosong'
        ]);

        if ($this->form_validation->run() == false) {
            $this->kuis($id);
        } else {

            $nip = $this->session->userdata('nip');

            $id_kuis = $id;
            $soal = $this->input->post('soal');
            $tekssoal = $this->input->post('tekssoal');
            $poto = $_FILES['file_input']['name'];

            // $file_ext = pathinfo($_FILES['file_input']['name'], PATHINFO_EXTENSION);


            $config['upload_path']        =    './assets/users/upload';
            $config['allowed_types']    =    'jpg|jpeg|png';
            $config['max_size']            =    10000;
            // $config['file_name']		=	'picture-'.date('ymd').'-'.substr(md5(rand()),0,10);
            // $config['file_name']        =    $poto;

            $this->load->library('upload', $config);

            if (@$_FILES['file_input']['name'] != null) {
                if ($this->upload->do_upload('file_input')) {
                    $data = array(
                        'id_soal' => $soal,
                        'soal' => $tekssoal,
                        'nama_file' => preg_replace("/\s+/", "_", $poto),
                        'tanggal_input' => date('Y:m:d H:i:s'),
                        'nip' => $nip
                    );

                    $datakuis = array(
                        'id_soal' => $soal,
                        'id_kuis' => $id_kuis,
                    );

                    $this->db->insert('soal', $data);
                    $this->db->insert('tr_kuis', $datakuis);



                    if ($this->db->affected_rows() > 0) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil ditambah</div>');
                        redirect('User/Guru/kuisessay/kuis/' . $id_kuis);
                    }
                } else {
                    $error = array('error' => $this->upload->display_errors());
                    echo "<script>alert('Format file salah');</script>";
                    echo "<script>window.location='" . site_url('User/Guru/kuisessay/kuis/' . $id_kuis) . "';</script>";
                }
                // if ($this->db->affected_rows() > 0) {
                //     echo "<script>alert('Data berhasil ditambah');</script>";
                // }
                // echo "<script>window.location='" . site_url('User/Guru/Dashboard') . "';</script>";
            } else {
                $data = array(
                    'id_soal' => $soal,
                    'soal' => $tekssoal,
                    'tanggal_input' => date('Y:m:d H:i:s'),
                    'nip' => $nip
                );

                $datakuis = array(
                    'id_soal' => $soal,
                    'id_kuis' => $id_kuis,
                );

                $this->db->insert('soal', $data);
                $this->db->insert('tr_kuis', $datakuis);


                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil di tambah</div>');
                    redirect('User/Guru/kuisessay/kuis/' . $id_kuis);
                }
                echo "<script>window.location='" . site_url('User/Guru/kuisessay/kuis/' . $id_kuis) . "';</script>";
            }
        }
    }
}
