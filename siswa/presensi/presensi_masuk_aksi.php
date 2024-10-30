<?php
// Start session
session_start();

// Include configuration file to connect to the database
include_once '../../config.php';

// Get POST data
$file_foto = $_POST['photo'];
$id_siswa = $_POST['id'];
$tanggal_masuk = $_POST['tanggal_masuk']; // Corrected this line
$jam_masuk = $_POST['jam_masuk'];

// Process photo
$foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
$foto = str_replace(' ', '+', $foto); // Fix any whitespace that may be present
$data = base64_decode($foto); // Decode base64

// Define file name for saving
$nama_file = 'foto/masuk_' . date('Y-m-d_H-i-s') . '.png'; // Add timestamp for uniqueness
$result = file_put_contents($nama_file, $data); // Save data to file

// Check if file was successfully saved
if ($result === false) {
    $_SESSION['gagal'] = "Gagal menyimpan foto.";
    header("Location: ../home/home.php");
    exit;
}

// Save attendance data to the database
$result = mysqli_query($conn, "INSERT INTO presensi (id_siswa, tanggal_masuk, jam_masuk, foto_masuk) VALUES ('$id_siswa', '$tanggal_masuk', '$jam_masuk', '$nama_file')");

if ($result) {
    $_SESSION['berhasil'] = "Presensi masuk berhasil";
} else {
    $_SESSION['gagal'] = "Presensi masuk gagal: " . mysqli_error($conn);
}

// Redirect after processing
header("Location: ../home/home.php");
exit;
