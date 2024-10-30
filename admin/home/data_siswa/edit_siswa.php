<?php
// Include database connection
include '../../../config.php';

// Fetch student data based on the ID passed in the URL (e.g., edit_siswa.php?id=1)
if (isset($_GET['id'])) {
    $id_siswa = $_GET['id'];

    // Query to fetch student data
    $student_query = "SELECT * FROM siswa WHERE id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bind_param("i", $id_siswa);
    $stmt->execute();
    $student_result = $stmt->get_result();

    if ($student_result->num_rows == 1) {
        $student_data = $student_result->fetch_assoc();
        $nama = $student_data['nama'];
        $nisn = $student_data['nisn'];
        $jenis_kelamin = $student_data['jenis_kelamin'];
        $kelas_id = $student_data['kelas']; // This will store the current class ID
        $foto = $student_data['foto']; // Fetch the existing photo

        // Query to fetch username and password from the `users` table based on `id_siswa`
        $user_query = "SELECT username, password FROM users WHERE id_siswa = ?";
        $stmt = $conn->prepare($user_query);
        $stmt->bind_param("i", $id_siswa);
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result->num_rows == 1) {
            $user_data = $user_result->fetch_assoc();
            $username = $user_data['username'];
            $password = $user_data['password'];
        } else {
            $username = ''; // Default value if user is not found
            $password = ''; // Default value if user is not found
        }
    } else {
        echo "Student not found.";
        exit;
    }
} else {
    echo "No student ID provided.";
    exit;
}

// Fetch classes for the dropdown
$class_query = "SELECT id, kelas FROM kelas"; // Use the correct column name 'kelas'
$class_result = $conn->query($class_query);

// Update student data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$nama = $_POST['Nama'];
$nisn = $_POST['NISN'];
$jenis_kelamin = $_POST['JenisKelamin'];
$kelas_id = $_POST['Kelas'];
$username = $_POST['Username'];
$password = $_POST['Password'];

// Hash the password before storing it in the database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Handle file upload (same as before)
if (!empty($_FILES['Foto']['name'])) {
$foto_name = $_FILES['Foto']['name'];
$foto_tmp_name = $_FILES['Foto']['tmp_name'];$foto_size = $_FILES['Foto']['size'];
$foto_error = $_FILES['Foto']['error'];

$foto_dir = "../../../asset/foto_siswa/";
$foto_path = $foto_dir . basename($foto_name);

$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
$foto_ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

if (in_array($foto_ext, $allowed_extensions)) {
if ($foto_error === 0) {
if ($foto_size < 5000000) {
if (!empty($foto) && file_exists($foto_dir . $foto)) {
unlink($foto_dir . $foto);
}
move_uploaded_file($foto_tmp_name, $foto_path);
} else {
echo "File size is too large.";
exit;
}
} else {
echo "Error uploading file.";
exit;
}
} else {
echo "Invalid file type. Please upload JPG, JPEG, PNG, or GIF.";
exit;
}
} else {
$foto_path = $foto;
}

// Update student data
$update_query = "UPDATE siswa SET 
nama = ?, 
nisn = ?, 
jenis_kelamin = ?, 
kelas = ?, 
foto = ? 
WHERE id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("sssssi", $nama, $nisn, $jenis_kelamin, $kelas_id, $foto_path, $id_siswa);
$stmt->execute();

// Update username and hashed password in the `users` table based on `id_siswa`
$update_user_query = "UPDATE users SET username = ?, password = ? WHERE id_siswa = ?";
$stmt = $conn->prepare($update_user_query);
$stmt->bind_param("ssi", $username, $hashed_password, $id_siswa);
$stmt->execute();

header("Location: siswa.php");
exit();
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
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

    <!-- Custom CSS -->
    <style>
        .photo-container {
            display: flex;
            align-items: start;
            justify-content: start;
            margin-top: 10px;
        }

        .photo-container img {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            margin-bottom: 10px;
        }
    </style>


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
                                <h4 class="card-title">Edit Siswa</h4><br>
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id_siswa" value="<?php echo $id_siswa; ?>">
                                    <div class="form-group">
                                        <label for="Name">Nama</label>
                                        <input type="text" name="Nama" class="form-control" value="<?php echo htmlspecialchars($nama); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="NISN">NISN</label>
                                        <input type="text" name="NISN" class="form-control" value="<?php echo htmlspecialchars($nisn); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="JenisKelamin">Jenis Kelamin</label>
                                        <select class="form-select" name="JenisKelamin" id="JenisKelamin" required>
                                            <option value="" disabled>Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki" <?php if ($jenis_kelamin == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                                            <option value="Perempuan" <?php if ($jenis_kelamin == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Kelas">Kelas</label>
                                        <select class="form-select" name="Kelas" id="Kelas" required>
                                            <option value="" disabled>Pilih Kelas</option>
                                            <?php while ($class_row = $class_result->fetch_assoc()) { ?>
                                                <option value="<?php echo $class_row['id']; ?>" <?php if ($kelas_id == $class_row['id']) echo 'selected'; ?>>
                                                    <?php echo htmlspecialchars($class_row['kelas']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="Foto">Foto</label>
                                        <input type="file" name="Foto" class="form-control">
                                        <?php if (!empty($foto)) { ?>
                                            <div class="photo-container">
                                                <img src="../../../asset/foto_siswa/<?php echo htmlspecialchars($foto); ?>" alt="Foto">
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="Username">Username</label>
                                        <input type="text" name="Username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Password">Password</label>
                                        <input type="password" name="Password" class="form-control" value="<?php echo htmlspecialchars($password); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                                    <a href="siswa.php" class="btn btn-light">Cancel</a>
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