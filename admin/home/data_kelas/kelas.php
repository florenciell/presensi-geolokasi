<?php
// Include the database connection file
include_once '../../../config.php'; // Sesuaikan path dengan struktur direktori Anda

// Fetch all classes from the 'kelas' table
$sql = "SELECT * FROM kelas";
$result = $conn->query($sql);
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
                                    <a class="nav-link" href="#">Kelas</a>
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
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi mdi-school"></i>
                            </span> Data Kelas
                        </h3>
                        <!-- <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
                                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                                </li>
                            </ul>
                        </nav> -->
                    </div>
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <!-- Judul Kartu -->
                                        <h4 class="card-title mb-0">Kelas</h4>
                                        <!-- Tombol Tambah Data -->
                                        <button type="button" class="btn btn-primary btn-sm" style="min-width: 150px;" onclick="window.location.href='tambah_kelas.php'">
                                            <i class=" mdi mdi-plus"></i> Tambah Data
                                        </button>
                                    </div>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kelas</th>
                                                <th>Aksi</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($result) > 0) {
                                                $no = 1;
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . $no++ . "</td>";
                                                    echo "<td>" . $row['kelas'] . "</td>";
                                                    echo "<td>
                                                    <div class='dropdown'>
                                                        <button class='btn btn-secondary btn-sm' type='button' id='dropdownMenuButton" . $row['id'] . "' data-bs-toggle='dropdown' aria-expanded='false' style='background: none; border: none;'>
                                                            <i class='mdi mdi-dots-vertical' style='color: gray; font-size: 1.2rem;'></i>
                                                        </button>
                                                        <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton" . $row['id'] . "'>
                                                            <li><a class='dropdown-item' href='edit_kelas.php?id=" . $row['id'] . "'>Edit</a></li>
                                                            <li><a class='dropdown-item' href='hapus_kelas.php?id=" . $row['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus kelas ini?\");'>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </td>";

                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='3'>Data tidak ditemukan</td></tr>";
                                            }
                                            ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <style>
                        .btn {
                            font-size: 14px;
                            padding: 10px 20px;
                            border-radius: 5px;
                        }

                        .card-title {
                            font-size: 18px;
                            font-weight: 500;
                        }

                        .table th,
                        .table td {
                            vertical-align: middle;
                            font-size: 14px;
                        }
                    </style>

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