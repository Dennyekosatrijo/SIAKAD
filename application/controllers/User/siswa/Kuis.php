<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kuis extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    cek_login_siswa();
  }
  public function index()
  {
  }

  public function Ikut($id)
  {
    // cek_ujian();
    $nis = $this->session->userdata('nis');
    cek_ujian();

    $jawabantugas = "SELECT *, kuis.jam as Jam FROM `kuis` 
    JOIN mengajar ON mengajar.id_mengajar = kuis.id_mengajar
    JOIN penjurusan ON penjurusan.id_jurusan = mengajar.id_jurusan
    JOIN kelas ON kelas.id_kelas = Penjurusan.id_kelas
    JOIN mata_pelajaran ON mata_pelajaran.id_mapel = mengajar.id_mapel
    WHERE kuis.id_kuis=$id";

    $sql = "SELECT * FROM `kuis` 
    JOIN ikut_ujian ON ikut_ujian.id_kuis = kuis.id_kuis
    JOIN mengajar ON mengajar.id_mengajar = kuis.id_mengajar
    WHERE EXISTS
    (SELECT * FROM ikut_ujian WHERE kuis.id_kuis = ikut_ujian.id_kuis AND ikut_ujian.nis = $nis)
    AND kuis.id_kuis = $id AND ikut_ujian.nis = $nis";
    $sql1 = "SELECT * FROM `kuis` 
    WHERE kuis.id_kuis = $id";

    $data['title'] = 'Ikut Ujian';
    $data['data'] = $this->db->get_where('siswa', ['nis' => $this->session->userdata('nis')])->row_array();
    $data['jawaban'] = $this->db->query($jawabantugas)->row();
    $data['sql'] = $this->db->query($sql)->row();
    $data['sql1'] = $this->db->query($sql1)->row();
    $this->load->view('users/templates/header', $data);
    $this->load->view('users/templates/navsiswa');
    $this->load->view('users/siswa/kuis');
    $this->load->view('users/templates/footer');
  }
  public function Submit()
  {
    $nis = $this->session->userdata('nis');

    $id_ujian = $this->input->post('id_ujian');
    $id_kuis = $this->input->post('id_kuis');

    $data = [
      'nis' => $nis,
      'id_ujian' => $id_ujian,
      'id_kuis' => $id_kuis,
      'nilai' => 0,
      'status' => '1',
      'proses' => 'belum',
      'time' => date('Y-m-d H:i:s')
    ];
    $this->db->insert('ikut_ujian', $data);

    $jawabantugas = "SELECT * FROM `kuis` 
    WHERE kuis.id_kuis=$id_kuis";

    $limit = $this->db->query($jawabantugas)->row();

    $hasilnamaujian = "SELECT *, soal.id_soal as sid, tr_soal.id_soal as trid FROM `tr_kuis` 
    JOIN soal ON tr_kuis.id_soal = soal.id_soal
    LEFT JOIN tr_soal ON soal.id_soal = tr_soal.id_soal 
    WHERE tr_kuis.id_kuis = $id_kuis
    ORDER BY RAND() LIMIT $limit->jumlah_keluar";

    $detail = $this->db->query($hasilnamaujian)->result();
    $s = 0;
    foreach ($detail as $d) :
      $dataa = [
        'id_ujian' => $id_ujian,
        'id_tr_kuis' => $d->id_tr_kuis,
        'waktu' => date('Y:m:d H:i:' . $s++)
      ];
      $this->db->insert('jawaban_ujian', $dataa);
    endforeach;

    redirect('User/Siswa/Kuis/TampilUjian/' . $id_ujian . '/' . $id_kuis);
  }
  public function TampilUjian($id_ujian, $id_kuis)
  {
    $nis = $this->session->userdata('nis');
    $ikut = "SELECT * FROM ikut_ujian WHERE id_ujian = $id_ujian";
    $ik = $this->db->query($ikut)->row();

    $se = [
      'id_ujian' => $id_ujian,
      'now' => $ik->time,
      'id_kuis' => $id_kuis,
      'belum' => $ik->proses
    ];
    $this->session->set_userdata($se);
    $soal = "SELECT *, soal.id_soal as sid, tr_soal.id_soal as trid FROM `jawaban_ujian`  
    JOIN tr_kuis ON tr_kuis.id_tr_kuis = jawaban_ujian.id_tr_kuis
    JOIN soal ON tr_kuis.id_soal = soal.id_soal
    LEFT JOIN tr_soal ON soal.id_soal = tr_soal.id_soal
    WHERE jawaban_ujian.id_ujian = $id_ujian
    ORDER BY `jawaban_ujian`.`waktu` ASC";
    $kuis = "SELECT * FROM kuis WHERE id_kuis = $id_kuis";
    $data['kuis'] = $this->db->query($kuis)->row();

    $data['title'] = 'Ujian ';
    $data['soal'] = $this->db->query($soal)->result();
    $data['soal1'] = $this->db->query($soal)->row();
    $this->load->view('users/templates/header', $data);
    $this->load->view('users/templates/navsiswa');
    $this->load->view('users/siswa/tampilkuis');
    //    $this->load->view('users/templates/footer');
  }
  public function TampilHasil($id_ujian, $id_kuis)
  {
    cek_ujian();
    $nis = $this->session->userdata('nis');

    $soal = "SELECT *, soal.id_soal as sid, tr_soal.id_soal as trid FROM `jawaban_ujian`  
    JOIN tr_kuis ON tr_kuis.id_tr_kuis = jawaban_ujian.id_tr_kuis
    JOIN soal ON tr_kuis.id_soal = soal.id_soal
    LEFT JOIN tr_soal ON soal.id_soal = tr_soal.id_soal
    WHERE jawaban_ujian.id_ujian = $id_ujian
    ORDER BY `jawaban_ujian`.`waktu` ASC";

    $kuis = "SELECT * FROM kuis WHERE id_kuis = $id_kuis";

    $data['title'] = 'Ujian ';
    $data['soal'] = $this->db->query($soal)->result();
    $data['kuis'] = $this->db->query($kuis)->row();
    $this->load->view('users/templates/header', $data);
    $this->load->view('users/templates/navsiswa');
    $this->load->view('users/siswa/tampilhasil');
    $this->load->view('users/templates/footer');
  }
  public function Tambah($id_ujian)
  {

    $nis = $this->session->userdata('nis');
    // $id_ujian = $this->input->get('id_ujian1');
    // $soall = "SELECT *, soal.id_soal as sid, tr_soal.id_soal as trid FROM `jawaban_ujian`  
    //   JOIN tr_kuis ON tr_kuis.id_tr_kuis = jawaban_ujian.id_tr_kuis
    //   JOIN soal ON tr_kuis.id_soal = soal.id_soal
    //   LEFT JOIN tr_soal ON soal.id_soal = tr_soal.id_soal
    //   WHERE jawaban_ujian.id_ujian = $id_ujian
    //   ORDER BY `jawaban_ujian`.`waktu` ASC";

    // $soal = $this->db->query($soall)->result();

    // $no = 1;
    // foreach ($soal as $s) :
    // $id_tr_kuis1 = $_GET['id_tr_kuis2'];
    // $id_jawaban = $_GET['id_jawaban2'];
    $id_tr_kuis = $this->input->post('id_tr_kuis');
    $jawaban = $this->input->post('jawaban');

    $update = "UPDATE jawaban_ujian SET jawaban = '$jawaban' WHERE id_ujian = $id_ujian AND id_tr_kuis = $id_tr_kuis";
    $a = $this->db->query($update);
    //   $no++;
    // endforeach;
  }
  public function Selesai($id_ujian, $id_kuis)
  {
    $upd = "UPDATE ikut_ujian SET proses = 'selesai' WHERE id_ujian = $id_ujian";
    $v = $this->db->query($upd);
    // echo "<script>alert('Data berhasil di update');</script>";
    redirect('User/siswa/Kuis/Ikut/' . $id_kuis);
  }
}
