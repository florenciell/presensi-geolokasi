<?php
// Koneksi ke database
include '../../../config.php'; // Pastikan Anda memiliki file koneksi database

// Ambil ID ketidakhadiran dari parameter GET
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Jika ID tidak ada, redirect ke halaman ketidakhadiran
if (empty($id)) {
    header("Location: ketidakhadiran.php");
    exit();
}

// Siapkan query untuk menghapus data ketidakhadiran berdasarkan ID
$sql = "DELETE FROM ketidakhadiran WHERE id = ?";

// Gunakan prepared statements
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Bind parameter dan execute statement
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Jika berhasil, tampilkan pesan sukses dan redirect
        echo "<script>
            alert('Data ketidakhadiran berhasil dihapus!');
            window.location.href = 'ketidakhadiran.php';
        </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>
            alert('Error: " . $stmt->error . "');
            window.location.href = 'ketidakhadiran.php';
        </script>";
    }

    // Tutup statement
    $stmt->close();
} else {
    // Jika tidak bisa prepare statement, tampilkan pesan error
    echo "<script>
        alert('Error: " . $conn->error . "');
        window.location.href = 'ketidakhadiran.php';
    </script>";
}

// Tutup koneksi database
$conn->close();
