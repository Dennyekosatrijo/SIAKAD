<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<html lang="en">

<head>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>

<body>
  <br>
  <div class="container">
    <br>
    <h3>Status</h3>
    <br>
    <table class="table table-striped">
      <tbody>
        <form action="<?= base_url('User/Siswa/Kuis/Submit/'); ?>" method="POST">
          <?php $querysoal = "SELECT MAX(id_ujian) AS id FROM ikut_ujian";
          $naa = $this->db->query($querysoal)->row();
          ?>
          <input hidden type="text" name="id_kuis" value="<?= $jawaban->id_kuis; ?>">
          <input hidden type="text" name="id_ujian" value="<?= $naa->id + 1; ?>">
          <tr>
            <td>Nama Ujian</td>
            <td><?= $jawaban->nama_ujian; ?></td>
          </tr>
          <tr>
            <td>Nama Mata Pelajaran</td>
            <td><?= $jawaban->mata_pelajaran; ?></td>
          </tr>

          <tr>
            <td>Nama Kelas</td>
            <td><?= $jawaban->kelas, ' ', $jawaban->nama_jurusan ?></td>
          </tr>
          <tr>
            <td>Ujian Berakhir</td>
            <td><?= $jawaban->tanggal_berakhir ?></td>
          </tr>
          <tr>
            <td>Ujian dimulai</td>
            <td><?= $jawaban->tanggal_mulai ?></td>
          </tr>
          <tr>
            <td>Status Ujian</td>
            <td><?= $jawaban->jenis; ?></td>
          </tr>
          <tr>
            <td>Jumlah Soal</td>
            <td><?= $jawaban->jumlah_keluar ?> </td>
          </tr>
          <tr>
            <td>Waktu Ujian</td>
            <td><?= $jawaban->menit, ' Menit' ?> </td>
          </tr>
      </tbody>
    </table>

    <?php
    if ($sql) : ?>
      <a href="<?= base_url('User/Siswa/Kuis/TampilHasil/' . $sql->id_ujian . '/' . $sql->id_kuis); ?>" class="btn btn-primary">Review ujian</a>
    <?php else : ?>
      <?php if ($sql1->jml_soal < $sql1->jumlah_keluar) : ?>
        <p>kuis belum tersedia, Soal yang diinput masih kurang, Silahkan menghubungi guru anda!</p>
      <?php else : ?>
        <button id="lo" class="btn btn-primary">Ikut Ujian</button>
      <?php endif; ?>
      <script>
        // Mengatur waktu akhir perhitungan mundur
        var countDownDate = new Date("<?= $jawaban->tanggal_berakhir ?>").getTime();

        // Memperbarui hitungan mundur setiap 1 detik


        // Untuk mendapatkan tanggal dan waktu hari ini
        var now = new Date().getTime();

        // Temukan jarak antara sekarang dan tanggal hitung mundur
        var distance = countDownDate - now;

        // Perhitungan waktu untuk hari, jam, menit dan detik
        // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        // var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        // var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        // var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // // Keluarkan hasil dalam elemen dengan id = "demo"
        // document.getElementById("demo").innerHTML = days + "d " + hours + "h " +
        //   minutes + "m " + seconds + "s ";

        // Jika hitungan mundur selesai, tulis beberapa teks 
        if (distance < 0) {
          // clearInterval(x);
          document.getElementById("lo").remove();
        }
      </script>
    <?php endif; ?>
    <hr>
    </form>
    <br>
    <br>
  </div>
</body>

</html>