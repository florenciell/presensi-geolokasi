<?php
// Koneksi ke database
$host = 'localhost'; // Ganti dengan host database Anda
$user = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$database = 'presensi'; // Ganti dengan nama database Anda

// Membuat koneksi
$koneksi = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Cek jika ada ID lokasi yang diberikan
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Hapus data lokasi berdasarkan ID
    $sql = "DELETE FROM lokasi_presensi WHERE id = $id";

    if ($koneksi->query($sql) === TRUE) {
        echo "Data berhasil dihapus.";
        // Redirect ke halaman lain atau tampilkan pesan sukses
        header("Location: lokasi_presensi.php"); // Ganti dengan URL yang sesuai
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
} else {
    echo "ID lokasi tidak diberikan.";
}

// Tutup koneksi
$koneksi->close();
