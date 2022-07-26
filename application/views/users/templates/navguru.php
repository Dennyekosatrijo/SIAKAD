<!-- banner -->
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-nav">
    <button class="navbar-toggler mx-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse text-center" id="navbarSupportedContent">
        <ul class="navbar-nav justify-content-center">
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= base_url('User/Guru/Dashboard') ?>">Dashboard
                    <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Event
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?= base_url('User/Guru/BuatKuis') ?>">Buat Tugas</a>
                    <a class="dropdown-item" href="<?= base_url('User/Guru/Kuisessay') ?>">Input Soal Ujian</a>
                </div>
            </li>
            <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Absensi Siswa
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?= base_url('User/Guru/KelasAbsen') ?>">Buat Absensi</a>
                    <a class="dropdown-item" href="<?= base_url('User/Guru/Report_absensi') ?>">Hasil Absensi</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= base_url('User/Guru/Absensi'); ?>">Absensi Guru</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= base_url('User/Guru/TampilKuis'); ?>">Tampil Kuis</a>
            </li>
            <?php
            $nip = $this->session->userdata('nip');
            $querymapel = "SELECT * FROM mengajar JOIN guru on guru.nip = mengajar.nip 
            JOIN mata_pelajaran on mata_pelajaran.id_mapel = mengajar.id_mapel 
            JOIN penjurusan on penjurusan.id_jurusan = mengajar.id_jurusan 
            JOIN kelas on kelas.id_kelas = penjurusan.id_kelas WHERE mengajar.nip=$nip";
            $mapel = $this->db->query($querymapel)->result(); ?>
            <li class="nav-item dropdown ">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Mata Pelajaran
                </a>
                <div class="dropdown-menu">
                    <?php foreach ($mapel as $m) : ?>
                        <a class="dropdown-item" href="<?= base_url('User/Guru/Mapel/getMapel/' . $m->id_mengajar) ?>"><?= $m->mata_pelajaran ?> <?= $m->kelas ?> <?= $m->nama_jurusan ?></a>
                    <?php endforeach; ?>
                </div>
            </li>
            <?php
            $nip = $this->session->userdata('nip');
            $queryWalikelas = "SELECT * FROM `wali_kelas` WHERE nip = $nip";
            $wali = $this->db->query($queryWalikelas)->result();
            if ($wali) { ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= base_url('User/Guru/wali_kelas/DaftarSiswa') ?>">Wali Kelas</a>
                </li>
            <?php } else {
                echo '';
            } ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= base_url('User/Guru/jadwal'); ?>">Jadwal Mata Pelajaran</a>
            </li>
        </ul>
    </div>
</nav>

<!-- //navigation -->