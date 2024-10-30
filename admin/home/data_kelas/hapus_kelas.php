<?php
// Include the database connection file
include_once '../../../config.php'; // Adjust the path according to your project structure

// Check if 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to delete the class record by its ID
    $sql = "DELETE FROM kelas WHERE id = $id";

    // Execute the query and check if successful
    if ($conn->query($sql) === TRUE) {
        // Redirect to the kelas list page with a success message
        echo "<script>
                alert('Data kelas berhasil dihapus.');
                window.location.href = 'kelas.php';
              </script>";
    } else {
        // Redirect to the kelas list page with an error message
        echo "<script>
                alert('Gagal menghapus data kelas: " . $conn->error . "');
                window.location.href = 'kelas.php';
              </script>";
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to the kelas list page if no 'id' parameter is provided
    echo "<script>
            alert('ID kelas tidak ditemukan.');
            window.location.href = 'kelas.php';
          </script>";
}
