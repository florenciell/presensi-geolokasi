<?php
// Include your database connection
include '../../../config.php';

// Ambil data kelas dari tabel kelas
$kelas_result = $conn->query("SELECT id, kelas FROM kelas");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $nama = $_POST['Nama'];
    $nisn = $_POST['NISN'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $kelas = $_POST['Kelas'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password sebelum disimpan
    $status = $_POST['status'];
    $role = $_POST['role'];

    // Validasi role hanya boleh 'admin' atau 'siswa'
    if ($role !== 'admin' && $role !== 'siswa') {
        echo "Role tidak valid. Hanya 'admin' atau 'siswa' yang diperbolehkan.";
        exit();
    }

    // Handle file upload
    $foto = '';
    if (isset($_FILES['Foto']) && $_FILES['Foto']['error'] == 0) {
        $fileTmpPath = $_FILES['Foto']['tmp_name'];
        $fileName = $_FILES['Foto']['name'];
        $fileSize = $_FILES['Foto']['size'];
        $fileType = $_FILES['Foto']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExts)) {
            $uploadFileDir = '../../../asset/foto_siswa/';
            $dest_file_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_file_path)) {
                $foto = $fileName;
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type.";
        }
    }

    // Insert data into 'siswa' table first
    $sql_siswa = "INSERT INTO siswa (nama, nisn, jenis_kelamin, kelas, foto) VALUES (?, ?, ?, ?, ?)";
    if ($stmt_siswa = $conn->prepare($sql_siswa)) {
        // Bind the parameters
        $stmt_siswa->bind_param("sssss", $nama, $nisn, $jenis_kelamin, $kelas, $foto);

        // Execute the query
        if ($stmt_siswa->execute()) {
            // Get the inserted siswa ID
            $siswa_id = $stmt_siswa->insert_id;

            // Insert data into 'users' table with the foreign key `id_siswa`
            $sql_users = "INSERT INTO users (username, password, status, role, id_siswa) VALUES (?, ?, ?, ?, ?)";
            if ($stmt_users = $conn->prepare($sql_users)) {
                // Bind parameters
                $stmt_users->bind_param("ssssi", $username, $password, $status, $role, $siswa_id);

                // Execute query
                if ($stmt_users->execute()) {
                    // Redirect to siswa.php with a success message
                    header("Location: siswa.php?message=success");
                    exit();
                } else {
                    echo "Error inserting into users table: " . $stmt_users->error;
                }

                // Close the statement
                $stmt_users->close();
            } else {
                echo "Error preparing users statement: " . $conn->error;
            }
        } else {
            echo "Error inserting into siswa table: " . $stmt_siswa->error;
        }

        // Close the statement
        $stmt_siswa->close();
    } else {
        echo "Error preparing siswa statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (required for Select2) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-Presensi</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../../dist/assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="../../../dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../../../dist/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../../../dist/assets/images/favicon.png" />

</head>

<body>
    <div class="container-scroller">
        <!-- <div class="row p-0 m-0 proBanner" id="proBanner">
            <div class="col-md-12 p-0 m-0">
                <div class="card-body card-body-padding d-flex align-items-center justify-content-between">
                    <div class="ps-lg-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="https://www.bootstrapdash.com/product/purple-bootstrap-admin-template/" target="_blank" class="btn me-2 buy-now-btn border-0">Buy Now</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="https://www.bootstrapdash.com/product/purple-bootstrap-admin-template/"><i class="mdi mdi-home me-3 text-white"></i></a>
                        <button id="bannerClose" class="btn border-0 p-0">
                            <i class="mdi mdi-close text-white mr-0"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo" href="index.html"><img src="../../../dist/assets/images/Presensi.svg" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index.html">💜</a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <div class="search-field d-none d-md-block">
                    <form class="d-flex align-items-center h-100" action="#">
                        <div class="input-group">
                            <div class="input-group-prepend bg-transparent">
                                <i class="input-group-text border-0 mdi mdi-magnify"></i>
                            </div>
                            <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
                        </div>
                    </form>
                </div>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item d-none d-lg-block full-screen-link">
                        <a class="nav-link">
                            <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                        </a>
                    </li>

                    <li class="nav-item nav-logout d-none d-lg-block">
                        <a class="nav-link" href="../../../auth/logout.php">
                            <i class="mdi mdi-power"></i>
                        </a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../../../admin/home/home.php">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="menu-title">Siswa</span>
                            <i class="mdi mdi-account menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <span class="menu-title">Master Data</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-database menu-icon"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="../data_kelas/kelas.php">Kelas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../data_lokasi_presensi/lokasi_presensi.php">Lokasi Presensi</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../presensi/rekap_presensi.php">
                            <span class="menu-title">Rekapitulasi</span>
                            <i class="mdi mdi-clipboard-text menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../data_ketidakhadiran/ketidakhadiran.php">
                            <span class="menu-title">Ketidakhadiran</span>
                            <i class="mdi mdi-mail menu-icon"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Tambah Siswa</h4><br>
                                <form method="POST" action="tambah_siswa.php" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Form for adding student -->
                                            <div class="form-group">
                                                <label for="Name">Nama</label>
                                                <input type="text" name="Nama" class="form-control" placeholder="Masukan Nama" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="NISN">NISN</label>
                                                <input type="text" name="NISN" class="form-control" placeholder="Masukan NISN" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Jenis Kelamin</label>
                                                <select class="form-select" name="jenis_kelamin" id="exampleSelectGender" required>
                                                    <option value="" disabled selected>Jenis Kelamin</option>
                                                    <option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Kelas">Kelas</label>
                                                <select name="Kelas" class="form-select" id="exampleSelectKelas" required>
                                                    <option value="" disabled selected>Pilih Kelas</option>
                                                    <?php
                                                    if ($kelas_result->num_rows > 0) {
                                                        while ($row = $kelas_result->fetch_assoc()) {
                                                            echo "<option value='" . $row['id'] . "'>" . $row['kelas'] . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option value=''>No classes available</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="Foto">Foto</label>
                                                <input type="file" name="Foto" class="form-control" accept="image/*">
                                            </div>
                                        </div>

                                        <!-- Tambahkan field Username, Password, dan Role di kolom kanan -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" name="username" class="form-control" placeholder="Masukan Username" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" class="form-control" placeholder="Masukan Password" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="" disabled selected>Pilih Status</option>
                                                    <option value="Aktif">Aktif</option>
                                                    <option value="Nonaktif">Nonaktif</option>
                                                </select>
                                                </div>
                                            <div class="form-group">
                                                <label for="role">Role</label>
                                                <select name="role" class="form-select" required>
                                                    <option value="" disabled selected>Pilih Role</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="siswa">Siswa</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="./siswa.php" class="btn btn-light">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024 <a href="https://www.bootstrapdash.com/" target="_blank">BootstrapDash</a>. All rights reserved.</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../../../dist/assets/vendors/chart.js/chart.umd.js"></script>
    <script src="../../../dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../../dist/assets/js/off-canvas.js"></script>
    <script src="../../../dist/assets/js/misc.js"></script>
    <script src="../../../dist/assets/js/settings.js"></script>
    <script src="../../../dist/assets/js/todolist.js"></script>
    <script src="../../../dist/assets/js/jquery.cookie.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../../../dist/assets/js/dashboard.js"></script>
    <!-- End custom js for this page -->
</body>

</html>