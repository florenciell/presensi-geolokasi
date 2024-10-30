<?php
// Connect to the database
include '../../config.php'; // Assumes config.php contains the connection logic

// Check if a date filter is applied
$filterDate = isset($_GET['filterDate']) ? $_GET['filterDate'] : null;

$query = "
    SELECT 
        siswa.id AS id, 
        siswa.nama AS nama, 
        siswa.nisn AS nisn, 
        kelas.kelas AS kelas, 
        presensi.jam_masuk AS jam_masuk, 
        presensi.jam_keluar AS jam_keluar, 
        presensi.tanggal_masuk AS tanggal_masuk,
        CASE
            WHEN presensi.jam_masuk <= '07:00:00' THEN 'ON TIME'
            WHEN presensi.jam_masuk > '07:00:00' AND presensi.jam_masuk <= '08:00:00' THEN 'TELAT'
            ELSE 'ALPA'
        END AS Status
    FROM presensi
    JOIN siswa ON presensi.id_siswa = siswa.id
    JOIN kelas ON siswa.kelas = kelas.id
    ";

// If a date filter is applied, add it to the query
if ($filterDate) {
    $query .= " WHERE presensi.tanggal_masuk = '$filterDate' ";
}

$query .= " ORDER BY presensi.jam_masuk ASC";

$result = mysqli_query($conn, $query);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-Presensi</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../dist/assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="../../dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../../dist/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../../dist/assets/images/favicon.png" />

    <style>
        .input-group {
            max-width: 400px;
            /* Increased max width for better usability */
            display: flex;
            /* Use flexbox for alignment */
        }

        .input-group .form-control {
            height: calc(2.5rem + 2px);
            /* Set height for input */
        }

        .input-group .btn {
            height: calc(2.5rem + 2px);
            /* Match height with input */
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
                <a class="navbar-brand brand-logo" href="index.html"><img src="../../dist/assets/images/Presensi.svg" alt="logo" /></a>
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
                        <a class="nav-link" href="../../auth/logout.php">
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
                        <a class="nav-link" href="../../admin/home/home.php">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../home/data_siswa/siswa.php">
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
                                    <a class="nav-link" href="../home/data_kelas/kelas.php">Kelas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../home/data_lokasi_presensi/lokasi_presensi.php">Lokasi Presensi</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <span class="menu-title">Rekapitulasi</span>
                            <i class="mdi mdi-clipboard-text menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../home/data_ketidakhadiran/ketidakhadiran.php">
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
                                <i class="mdi mdi-clipboard-text"></i>
                            </span> Data Rekap Presensi
                        </h3>
                        <form action="" method="GET" class="d-flex align-items-center">
                            <div class="input-group" style="max-width: 500px; display: flex;">
                                <input type="date" id="filterDate" name="filterDate" class="form-control rounded"
                                    value="<?= isset($_GET['filterDate']) ? $_GET['filterDate'] : ''; ?>"
                                    onchange="this.form.submit()" required>
                                <input type="month" id="filterMonth" name="filterMonth" class="form-control ms-2 rounded"
                                    value="<?= isset($_GET['filterMonth']) ? $_GET['filterMonth'] : ''; ?>"
                                    onchange="this.form.submit()">
                                <a href="export_excel.php?filterMonth=<?= isset($_GET['filterMonth']) ? $_GET['filterMonth'] : ''; ?>"
                                    target="_blank" class="btn btn-primary ms-2 rounded" style="height: calc(2.5rem + 2px);">
                                    Export Excel
                                </a>
                            </div>
                        </form>


                    </div>

                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title mb-0">Rekapitulasi</h4>
                                    </div>

                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NISN</th>
                                                <th>Kelas</th>
                                                <th>Tanggal</th>
                                                <th>Hadir</th>
                                                <th>Pulang</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= isset($row['nama']) ? $row['nama'] : 'N/A'; ?></td>
                                                    <td><?= isset($row['nisn']) ? $row['nisn'] : 'N/A'; ?></td>
                                                    <td><?= isset($row['kelas']) ? $row['kelas'] : 'N/A'; ?></td>
                                                    <td><?= isset($row['tanggal_masuk']) ? $row['tanggal_masuk'] : 'N/A'; ?></td>
                                                    <td><?= isset($row['jam_masuk']) ? $row['jam_masuk'] : 'N/A'; ?></td>
                                                    <td><?= isset($row['jam_keluar']) ? $row['jam_keluar'] : 'N/A'; ?></td>
                                                    <td>
                                                        <label class="badge badge-gradient-<?= $row['Status'] == 'ON TIME' ? 'success' : ($row['Status'] == 'TELAT' ? 'warning' : 'danger') ?>">
                                                            <?= $row['Status']; ?>
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal -->
                <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="infoModalLabel">Information</h5>
                            </div>
                            <div class="modal-body">
                                <!-- Add your information content here -->
                                <p>This is some information about the attendance system.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024 <a href="https://www.bootstrapdash.com/" target="_blank">BootstrapDash</a>. All rights reserved.</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <script src="../../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../dist/assets/vendors/chart.js/chart.umd.js"></script>
    <script src="../../dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../../dist/assets/js/off-canvas.js"></script>
    <script src="../../dist/assets/js/misc.js"></script>
    <script src="../../dist/assets/js/settings.js"></script>
    <script src="../../dist/assets/js/todolist.js"></script>
</body>

</html>