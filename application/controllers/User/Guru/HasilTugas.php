<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HasilTugas extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		cek_login_guru();
		cek_jumlah_keluar();
	}
	public function index()
	{
		$data['title'] = 'Hasil Tugas Siswa';
		$this->load->view('users/templates/header', $data);
		$this->load->view('users/templates/navguru');
		$this->load->view('users/guru/create_event/hasiltugas');
		$this->load->view('users/templates/footer');
	}
}
