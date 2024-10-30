<?php
// Sambungkan ke database
include('../../config.php');

// Ambil data presensi dan hitung status berdasarkan logika PHP
$query = "SELECT jam_masuk FROM presensi";
$result = mysqli_query($conn, $query);

// Inisialisasi variabel
$totalAlpa = 0;
$totalTelat = 0;
$totalOnTime = 0;

// Waktu batas untuk kondisi
$onTimeBoundary = strtotime("07:00:00");
$lateBoundary = strtotime("08:00:00");

while ($row = mysqli_fetch_assoc($result)) {
    $jam_masuk = strtotime($row['jam_masuk']);

    if (empty($row['jam_masuk'])) {
        $totalAlpa++;
    } elseif ($jam_masuk <= $onTimeBoundary) {
        $totalOnTime++;
    } elseif ($jam_masuk > $onTimeBoundary && $jam_masuk <= $lateBoundary) {
        $totalTelat++;
    } else {
        $totalAlpa++; // This covers cases where the time is after the late boundary
    }
}

// Output the results
// echo "Total On Time: " . $totalOnTime . "<br>";
// echo "Total Telat: " . $totalTelat . "<br>";
// echo "Total Alpa: " . $totalAlpa . "<br>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-Presensi</title>
    <link rel="stylesheet" href="../../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../dist/assets/css/style.css">
    <link rel="shortcut icon" href="../../dist/assets/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo" href="index.html"><img src="../../dist/assets/images/Presensi.svg" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index.html">💜</a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <!-- <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button> -->
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
                        <a class="nav-link" id="fullscreen-button" href="javascript:void(0);">
                            <i class="mdi mdi-fullscreen"></i>
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
                        <a class="nav-link" href="#">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_siswa/siswa.php">
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
                                    <a class="nav-link" href="data_kelas/kelas.php">Kelas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="data_lokasi_presensi/lokasi_presensi.php">Lokasi Presensi</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../presensi/rekap_presensi.php">
                            <span class="menu-title">Rekapitulasi</span>
                            <i class="mdi mdi-clipboard-text menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="data_ketidakhadiran/ketidakhadiran.php">
                            <span class="menu-title">Ketidakhadiran</span>
                            <i class="mdi mdi-mail menu-icon"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- partial -->

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi mdi-home"></i>
                            </span> Dashboard
                        </h3>
                    </div>
                    <div class="row">
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-danger card-img-holder text-white">
                                <div class="card-body">
                                    <img src="../../dist/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Total Alpa <i class="mdi mdi-chart-line mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5"><?php echo $totalAlpa; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="../../dist/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Total Telat <i class="mdi mdi-bookmark-outline mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5"><?php echo $totalTelat; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-success card-img-holder text-white">
                                <div class="card-body">
                                    <img src="../../dist/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Total On Time<i class="mdi mdi-diamond mdi-24px float-end"></i></h4>
                                    <h2 class="mb-5"><?php echo $totalOnTime; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <h4 class="card-title float-start">Data Presensi</h4>
                                    </div>
                                    <canvas id="visit-sale-chart" class="mt-4"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Data Presensi</h4>
                                    <div class="doughnutjs-wrapper d-flex justify-content-center">
                                        <canvas id="traffic-chart"></canvas>
                                    </div>
                                </div>
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
    <script>
        const dataPresensi = {
            alpa: <?php echo $totalAlpa; ?>,
            telat: <?php echo $totalTelat; ?>,
            onTime: <?php echo $totalOnTime; ?>
        };

        const ctx1 = document.getElementById('visit-sale-chart').getContext('2d');
        const visitSaleChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Alpa', 'Telat', 'On Time'],
                datasets: [{
                    label: 'Data Presensi',
                    data: [dataPresensi.alpa, dataPresensi.telat, dataPresensi.onTime],
                    backgroundColor: ['#ff6384', '#36a2eb', '#4caf50']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        const ctx2 = document.getElementById('traffic-chart').getContext('2d');
        const trafficChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Alpa', 'Telat', 'On Time'],
                datasets: [{
                    data: [dataPresensi.alpa, dataPresensi.telat, dataPresensi.onTime],
                    backgroundColor: ['#ff6384', '#36a2eb', '#4caf50']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    <script>
        const fullscreenButton = document.getElementById('fullscreen-button');

        const toggleFullScreen = () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        };

        fullscreenButton.addEventListener('click', toggleFullScreen);
    </script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('sidebar-minimized');
        });
    </script>
</body>

</html>