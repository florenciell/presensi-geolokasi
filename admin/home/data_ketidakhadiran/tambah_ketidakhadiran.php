<?php
include '../../../config.php'; // Pastikan path ini benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $dari = $_POST['dari'];
    $sampai = $_POST['sampai'];
    $nisn = $_POST['nisn']; // Mendapatkan NISN dari dropdown
    $id_kelas = $_POST['id_kelas']; // Mendapatkan id_kelas dari dropdown
    $keterangan = $_POST['keterangan'];

    // Validasi input
    if (!empty($dari) && !empty($sampai) && !empty($nisn) && !empty($id_kelas) && !empty($keterangan)) {
        // Query untuk mendapatkan id_siswa berdasarkan NISN
        $sql_id_siswa = "SELECT id FROM siswa WHERE nisn = '$nisn'";
        $result_id_siswa = mysqli_query($conn, $sql_id_siswa);
        $row_id_siswa = mysqli_fetch_assoc($result_id_siswa);
        $id_siswa = $row_id_siswa['id'];

        // Query untuk memasukkan data ke tabel ketidakhadiran
        $sql = "INSERT INTO ketidakhadiran (dari, sampai, id_siswa, id_kelas, keterangan)
                VALUES ('$dari', '$sampai', '$id_siswa', '$id_kelas', '$keterangan')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Data ketidakhadiran berhasil ditambahkan!'); window.location.href='ketidakhadiran.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Semua kolom harus diisi!'); window.location.href='tambah_ketidakhadiran.php';</script>";
    }
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
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Tambah Ketidakhadiran</h4><br>
                                <!-- <p class="card-description"> Basic form elements </p> -->
                                <form class="forms-sample" method="POST" action="tambah_ketidakhadiran.php">
                                    <div class="form-group">
                                        <label for="dari">Dari</label>
                                        <input type="date" class="form-control" id="dari" name="dari" placeholder="Dari" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="sampai">Sampai</label>
                                        <input type="date" class="form-control" id="sampai" name="sampai" placeholder="Sampai" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nisn">NISN</label>
                                        <select name="nisn" class="form-select" id="nisn" required>
                                            <option value="" disabled selected>Pilih NISN</option>
                                            <?php
                                            // Query untuk mendapatkan daftar siswa berdasarkan NISN
                                            $sql_siswa = "SELECT nisn, nama FROM siswa";
                                            $result_siswa = mysqli_query($conn, $sql_siswa);

                                            // Cek jika query berhasil dan ada hasil
                                            if ($result_siswa) {
                                                while ($row_siswa = mysqli_fetch_assoc($result_siswa)) {
                                                    echo "<option value='" . $row_siswa['nisn'] . "'>" . $row_siswa['nisn'] . " - " . $row_siswa['nama'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>Error fetching students</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="id_kelas">Kelas</label>
                                        <select name="id_kelas" class="form-select" id="id_kelas" required>
                                            <option value="" disabled selected>Pilih Kelas</option>
                                            <?php
                                            // Query untuk mendapatkan daftar kelas
                                            $sql_kelas = "SELECT id, kelas FROM kelas";
                                            $result_kelas = mysqli_query($conn, $sql_kelas);

                                            // Cek jika query berhasil dan ada hasil
                                            if ($result_kelas) {
                                                while ($row_kelas = mysqli_fetch_assoc($result_kelas)) {
                                                    echo "<option value='" . $row_kelas['id'] . "'>" . $row_kelas['kelas'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value='' disabled>Error fetching classes</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <select class="form-select" id="keterangan" name="keterangan" required>
                                            <option value="" disabled selected>Pilih Keterangan</option>
                                            <option value="Sakit">Sakit</option>
                                            <option value="Izin">Izin</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                                    <a href="ketidakhadiran.php" class="btn btn-light">Batal</a>

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