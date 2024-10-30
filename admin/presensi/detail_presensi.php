<?php
include '../../config.php';

$id = $_GET['id'];

// Query to fetch detailed attendance data
$query = "
    SELECT 
        siswa.nama, siswa.nisn, 
        presensi.jam_masuk, presensi.jam_keluar, presensi.tanggal_masuk,
        presensi.foto_masuk, presensi.foto_keluar, 
        CASE
            WHEN presensi.jam_masuk <= '07:00:00' THEN 'ON TIME'
            WHEN presensi.jam_masuk > '07:00:00' AND presensi.jam_masuk <= '08:00:00' THEN 'TELAT'
            ELSE 'ALPA'
        END AS Status
    FROM presensi
    JOIN siswa ON presensi.id_siswa = siswa.id
    WHERE presensi.id = '$id'";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Presensi</title>
    <!-- Include your CSS -->
</head>

<body>
    <h3>Detail Presensi</h3>
    <p>Nama: <?= $data['nama'] ?></p>
    <p>NISN: <?= $data['nisn'] ?></p>
    <p>Tanggal Masuk: <?= $data['tanggal_masuk'] ?></p>
    <p>Jam Masuk: <?= $data['jam_masuk'] ?></p>
    <p>Jam Keluar: <?= $data['jam_keluar'] ?></p>
    <p>Status: <?= $data['Status'] ?></p>
    <p>Foto Masuk: <img src="<?= $data['foto_masuk'] ?>" alt="Foto Masuk" width="100"></p>
    <p>Foto Keluar: <img src="<?= $data['foto_keluar'] ?>" alt="Foto Keluar" width="100"></p>

    <a href="rekap_presensi.php" class="btn btn-primary">Kembali</a>
</body>

</html>