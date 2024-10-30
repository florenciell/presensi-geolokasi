<?php
// Include the database connection file
include_once '../../../config.php'; // Sesuaikan dengan struktur project Anda

// Cek apakah parameter 'id' tersedia di URL
if (isset($_GET['id'])) {
  $id_siswa = $_GET['id'];

  // Query untuk mengambil path file foto dari database
  $sql_foto = "SELECT foto FROM siswa WHERE id = ?";
  $stmt_foto = $conn->prepare($sql_foto);
  $stmt_foto->bind_param("i", $id_siswa);
  $stmt_foto->execute();
  $result_foto = $stmt_foto->get_result();

  if ($result_foto->num_rows > 0) {
    $row = $result_foto->fetch_assoc();
    $foto = $row['foto']; // Ambil nama file foto

    // Definisikan path menuju foto
    $foto_path = "../../../asset/foto_siswa/" . $foto;

    // Cek apakah file ada, lalu hapus
    if (file_exists($foto_path)) {
      unlink($foto_path); // Hapus file dari direktori
    }

    // SQL query untuk menghapus record siswa berdasarkan ID
    $sql_delete_siswa = "DELETE FROM siswa WHERE id = ?";
    $stmt_delete_siswa = $conn->prepare($sql_delete_siswa);
    $stmt_delete_siswa->bind_param("i", $id_siswa);

    // Eksekusi query dan cek apakah berhasil
    if ($stmt_delete_siswa->execute()) {
      // Foreign key dengan ON DELETE CASCADE akan menghapus user terkait
      echo "<script>
              alert('Data siswa dan foto berhasil dihapus.');
              window.location.href = 'siswa.php';
            </script>";
    } else {
      echo "<script>
              alert('Gagal menghapus data siswa: " . $stmt_delete_siswa->error . "');
              window.location.href = 'siswa.php';
            </script>";
    }

    // Tutup statement setelah eksekusi
    $stmt_delete_siswa->close();
  } else {
    // Jika data siswa tidak ditemukan berdasarkan ID
    echo "<script>
            alert('Data siswa tidak ditemukan.');
            window.location.href = 'siswa.php';
          </script>";
  }

  // Tutup statement
  $stmt_foto->close();

  // Tutup koneksi database
  $conn->close();
} else {
  // Redirect ke halaman siswa jika parameter 'id' tidak ditemukan
  echo "<script>
          alert('ID siswa tidak ditemukan.');
          window.location.href = 'siswa.php';
        </script>";
}
