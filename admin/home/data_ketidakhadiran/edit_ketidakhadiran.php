<?php
// Koneksi ke database
include '../../../config.php';

// Ambil ID ketidakhadiran dari parameter GET
$id = $_GET['id'] ?? '';

// Jika ID tidak ada, redirect ke halaman ketidakhadiran
if (empty($id)) {
    header("Location: ketidakhadiran.php");
    exit();
}

// Ambil data ketidakhadiran berdasarkan ID
$sql = "SELECT * FROM ketidakhadiran WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan, redirect ke halaman ketidakhadiran
if (!$data) {
    header("Location: ketidakhadiran.php");
    exit();
}

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dari = $_POST['dari'];
    $sampai = $_POST['sampai'];
    $id_siswa = $_POST['nama'];
    $id_kelas = $_POST['kelas'];
    $keterangan = $_POST['keterangan'];

    $update_sql = "UPDATE ketidakhadiran SET dari='$dari', sampai='$sampai', id_siswa='$id_siswa', id_kelas='$id_kelas', keterangan='$keterangan' WHERE id='$id'";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: ketidakhadiran.php?status=success");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Ambil data siswa untuk dropdown
$siswa_query = "SELECT * FROM siswa";
$siswa_result = mysqli_query($conn, $siswa_query);

// Ambil data kelas untuk dropdown
$kelas_query = "SELECT * FROM kelas";
$kelas_result = mysqli_query($conn, $kelas_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Ketidakhadiran - E-Presensi</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
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
                        <a class="nav-link" href="../data_siswa/siswa.php">
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
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit Ketidakhadiran</h4><br>
                                <form class="forms-sample" method="POST" action="">
                                    <div class="form-group">
                                        <label for="dari">Dari</label>
                                        <input type="date" class="form-control" id="dari" name="dari" value="<?php echo htmlspecialchars($data['dari']); ?>" placeholder="Dari">
                                    </div>
                                    <div class="form-group">
                                        <label for="sampai">Sampai</label>
                                        <input type="date" class="form-control" id="sampai" name="sampai" value="<?php echo htmlspecialchars($data['sampai']); ?>" placeholder="Sampai">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <select class="form-select" id="nama" name="nama" required>
                                            <option value="" disabled>Pilih Nama</option>
                                            <?php while ($siswa = mysqli_fetch_assoc($siswa_result)): ?>
                                                <option value="<?php echo htmlspecialchars($siswa['id']); ?>" <?php echo ($siswa['id'] == $data['id_siswa']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($siswa['nama']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="kelas">Kelas</label>
                                        <select class="form-select" id="kelas" name="kelas" required>
                                            <option value="" disabled>Pilih Kelas</option>
                                            <?php while ($kelas = mysqli_fetch_assoc($kelas_result)): ?>
                                                <option value="<?php echo htmlspecialchars($kelas['id']); ?>" <?php echo ($kelas['id'] == $data['id_kelas']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($kelas['kelas']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <select class="form-select" name="keterangan" id="keterangan" required>
                                            <option value="" disabled>Keterangan</option>
                                            <option value="Sakit" <?php echo ($data['keterangan'] == 'Sakit') ? 'selected' : ''; ?>>Sakit</option>
                                            <option value="Izin" <?php echo ($data['keterangan'] == 'Izin') ? 'selected' : ''; ?>>Izin</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary me-2">Update</button>
                                    <a href="ketidakhadiran.php" class="btn btn-light">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <!-- Footer content here -->
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">© 2024 E-PRESENSI</span>
                </div>
            </div>
        </footer>
        <!-- partial -->
    </div>
    <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>

    <!-- plugins:js -->
    <script src="../../../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../../../dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../../dist/assets/js/off-canvas.js"></script>
    <script src="../../../dist/assets/js/hoverable-collapse.js"></script>
    <script src="../../../dist/assets/js/misc.js"></script>
    <script src="../../../dist/assets/js/settings.js"></script>
    <script src="../../../dist/assets/js/todolist.js"></script>
    <!-- endinject -->
</body>

</html>