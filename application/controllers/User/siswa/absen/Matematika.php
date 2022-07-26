<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Matematika extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        cek_login_siswa();
        cek_ujian();
    }
    public function index()
    {
        $nis = $this->session->userdata('nis');

        $querytrabsen = "SELECT absen_siswa.tanggal_berakhir, guru.nama, mata_pelajaran.mata_pelajaran, tr_absen_siswa.status FROM 
        tr_absen_siswa JOIN absen_siswa ON tr_absen_siswa.id_absen = absen_siswa.id_absen JOIN mengajar ON mengajar.id_mengajar = 
        absen_siswa.id_mengajar JOIN guru ON guru.nip = mengajar.nip JOIN mata_pelajaran ON mata_pelajaran.id_mapel = mengajar.id_mapel 
        WHERE mata_pelajaran.mata_pelajaran='Matematika' and tr_absen_siswa.nis=$nis;";

        $queryabsen = "SELECT absen_siswa.id_absen, absen_siswa.tanggal_berakhir, guru.nama, mata_pelajaran.mata_pelajaran FROM 
        absen_siswa JOIN mengajar ON mengajar.id_mengajar = absen_siswa.id_mengajar JOIN guru ON guru.nip = 
        mengajar.nip JOIN mata_pelajaran ON mata_pelajaran.id_mapel = mengajar.id_mapel WHERE NOT EXISTS
        (SELECT * FROM tr_absen_siswa WHERE absen_siswa.id_absen=tr_absen_siswa.id_absen and tr_absen_siswa.nis=$nis) ";

        // $queryabsen = "SELECT *
        // FROM absen_guru
        // WHERE NOT EXISTS
        // (SELECT * FROM tr_absen_guru WHERE absen_guru.id_absen=tr_absen_guru.id_absen) ";

        $data['title'] = 'Absensi';
        $data['data'] = $this->db->get_where('siswa', ['nis' => $this->session->userdata('nis')])->row_array();
        $data['absen'] = $this->db->query($querytrabsen)->result();
        $data['tanggal'] = $this->db->query($queryabsen)->result();
        $this->load->view('users/templates/header', $data);
        $this->load->view('users/templates/navsiswa');
        $this->load->view('users/templates/navAbsensi');
        $this->load->view('users/siswa/absen/matematika');
        $this->load->view('users/templates/footer');
        $this->load->view('auto');
    }
}
