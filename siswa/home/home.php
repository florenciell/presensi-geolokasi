<?php
// Start session
session_start();

// Include configuration file to connect to the database
include_once '../../config.php';

// Set the default timezone to Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Initialize variables with default values
$latitude_siswa = '';
$longitude_siswa = '';
$radius = '';
$zona_waktu = '';
$tanggal_masuk = '';
$tanggal_keluar = '';
$jam_masuk = '';
$jam_keluar = ''; // Initialize jam_keluar

// Fetch current date and time
$currentDate = date('Y-m-d');
$currentTime = date('H:i');

// Check if session variable 'lokasi_presensi' is set
$lokasi_presensi = isset($_SESSION['lokasi_presensi']) ? $_SESSION['lokasi_presensi'] : null;

// Check if attendance location is found in the session
if ($lokasi_presensi) {
    if ($conn) {
        // Query to get location data based on location name
        $result = mysqli_query($conn, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");

        // Check if query was successful
        if ($result && mysqli_num_rows($result) > 0) {
            // Fetch location data from the result
            while ($lokasi = mysqli_fetch_array($result)) {
                $latitude_siswa = $lokasi['latitude'];
                $longitude_siswa = $lokasi['longitude'];
                $radius = $lokasi['radius'];
                $zona_waktu = $lokasi['zona_waktu'];
                $jam_keluar = $lokasi['jam_keluar']; // This will now work without warnings
            }
            if ($zona_waktu === 'WIB') {
                date_default_timezone_set('Asia/Jakarta');
            } elseif ($zona_waktu === 'WITA') {
                date_default_timezone_set('Asia/Makassar');
            } elseif ($zona_waktu === 'WIT') {
                date_default_timezone_set('Asia/Jayapura');
            }
        }
    }
}
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
    <link rel="stylesheet" href="../../dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../../dist/assets/css/style.css">
    <link rel="shortcut icon" href="../../dist/assets/images/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
            background-color: #f5f7fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .card-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            padding: 40px;
            flex-wrap: wrap;
        }

        .card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 350px;
            max-width: 100%;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            border-top: 8px solid #DA8CFF;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 26px;
            font-weight: 700;
            color: #9A55FF;
            margin-bottom: 15px;
        }

        .card-date {
            font-size: 18px;
            font-weight: 500;
            color: #666;
            margin-bottom: 20px;
        }

        .card-time {
            font-size: 40px;
            font-weight: 800;
            color: #DA8CFF;
            margin-bottom: 25px;
        }

        .card button {
            padding: 15px 35px;
            border-radius: 50px;
            border: none;
            font-size: 18px;
            font-weight: 700;
            color: white;
            cursor: pointer;
            background: linear-gradient(45deg, #DA8CFF, #9A55FF);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .card button:hover {
            transform: scale(1.1);
            opacity: 0.95;
        }

        @media (max-width: 768px) {
            .card-container {
                flex-direction: column;
                align-items: center;
            }

            .card {
                width: 100%;
                max-width: 100%;
            }
        }

        .container-scroller {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .main-panel {
            flex-grow: 1;
        }

        .content-wrapper {
            width: 100%;
            padding: 30px;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 100;
        }

        .footer p {
            font-size: 15px;
            font-weight: bold;
            background: linear-gradient(45deg, #DA8CFF, #9A55FF);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        .confirmation-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .icon-container {
            margin-bottom: 10px;
        }

        .confirmation-text {
            font-size: 22px;
            font-weight: 500;
            color: #333;
            text-align: center;
            margin: 0;
        }

        .navbar {
            padding: 10px 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar .navbar-brand img {
            height: 22px;
        }

        .navbar-nav-right {
            margin-left: auto;
            margin-right: 20px;
        }

        .nav-logout .nav-link {
            font-size: 20px;
            padding: 10px 20px;
            margin-right: 15px;
            position: relative;
            transition: transform 0.3s ease, background-color 0.3s ease;
            background-color: transparent;
            border-radius: 40px;
            display: inline-block;
        }

        .nav-logout .nav-link:hover {
            transform: scale(1.05);
            background-color: #f5f5f5;
        }

        .nav-logout .nav-link:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 40px;
            background-color: #dc3545;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .nav-logout .nav-link:hover:before {
            opacity: 1;
        }

        .mdi-power {
            font-size: 24px;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-menu-wrapper d-flex align-items-center">
                <a class="navbar-brand brand-logo" href="#">
                    <img src="../../dist/assets/images/Presensi.svg" alt="logo" />
                </a>
            </div>
            <div class="navbar-nav-right">
                <li class="nav-item nav-logout d-none d-lg-block">
                    <a class="nav-link" href="../../auth/logout.php">
                        <i class="mdi mdi-power"></i>
                    </a>
                </li>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="card-container">
                        <!-- Card for Presensi Masuk -->
                        <div class="card">
                            <div class="card-title">Presensi Masuk</div>

                            <?php
                            $id_siswa = $_SESSION['id'];
                            $tanggal_hari_ini = date('Y-m-d');
                            $cek_presensi_masuk = mysqli_query($conn, "SELECT * FROM presensi WHERE id_siswa = '$id_siswa' AND tanggal_masuk = '$tanggal_hari_ini'");
                            ?>

                            <?php if (mysqli_num_rows($cek_presensi_masuk) === 0) { ?>
                                <div class="card-date" id="date-masuk"><?php echo $currentDate; ?></div>
                                <div class="card-time" id="time-masuk"><?php echo $currentTime; ?></div>
                                <form action="../../siswa/presensi/presensi_masuk.php" method="POST">
                                    <input type="hidden" name="latitude_siswa" id="latitude_siswa">
                                    <input type="hidden" name="longitude_siswa" id="longitude_siswa">
                                    <input type="hidden" name="radius" id="radius" value="100">
                                    <input type="hidden" name="zona_waktu" id="zona_waktu">
                                    <input type="hidden" name="tanggal_masuk" id="tanggal_masuk">
                                    <input type="hidden" name="jam_masuk" id="jam_masuk">
                                    <button type="submit" name="tombol_masuk" class="btn-masuk">Masuk</button>
                                </form>
                            <?php } else { ?>
                                <div class="confirmation-message">
                                    <div class="icon-container">
                                        <i class="fa fa-check-circle" style="font-size: 60px; color: #DA8CFF;"></i>
                                    </div>
                                    <h4 class="confirmation-text">Anda telah melakukan presensi masuk</h4>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Card for Presensi Keluar -->
                        <div class="card">
                            <div class="card-title">Presensi Keluar</div>

                            <?php
                            $id_siswa = $_SESSION['id'];
                            $tanggal_hari_ini = date('Y-m-d');

                            // Check if the student has registered their entry for today
                            $cek_presensi_masuk = mysqli_query($conn, "SELECT * FROM presensi WHERE id_siswa = '$id_siswa' AND tanggal_masuk = '$tanggal_hari_ini'");
                            $presensi_masuk_exists = mysqli_num_rows($cek_presensi_masuk) > 0;

                            // Check if the student has registered their exit for today
                            $cek_presensi_keluar = mysqli_query($conn, "SELECT * FROM presensi WHERE id_siswa = '$id_siswa' AND tanggal_keluar = '$tanggal_hari_ini'");
                            $presensi_keluar_exists = mysqli_num_rows($cek_presensi_keluar) > 0;
                            ?>

                            <?php if ($presensi_masuk_exists && !$presensi_keluar_exists) { ?>
                                <div class="card-date" id="date-keluar"><?php echo $currentDate; ?></div>
                                <div class="card-time" id="time-keluar"><?php echo $currentTime; ?></div>
                                <form action="../../siswa/presensi/presensi_keluar.php" method="POST">
                                    <input type="hidden" name="latitude_siswa" id="latitude_siswa">
                                    <input type="hidden" name="longitude_siswa" id="longitude_siswa">
                                    <input type="hidden" name="radius" id="radius" value="100">
                                    <input type="hidden" name="zona_waktu" id="zona_waktu">
                                    <input type="hidden" name="tanggal_keluar" id="tanggal_keluar" value="<?php echo $tanggal_hari_ini; ?>">
                                    <input type="hidden" name="jam_keluar" id="jam_keluar">
                                    <button type="submit" name="tombol_keluar" class="btn-keluar">Keluar</button>
                                </form>
                            <?php } elseif (!$presensi_masuk_exists) { ?>
                                <div class="confirmation-message">
                                    <div class="icon-container">
                                        <i class="fa fa-exclamation-circle" style="font-size: 60px; color: #ffcc00;"></i>
                                    </div>
                                    <h4 class="confirmation-text">Anda harus presensi masuk terlebih dahulu</h4>
                                </div>
                            <?php } elseif ($presensi_keluar_exists) { ?>
                                <div class="confirmation-message">
                                    <div class="icon-container">
                                        <i class="fa fa-check-circle" style="font-size: 60px; color: #DA8CFF;"></i>
                                    </div>
                                    <h4 class="confirmation-text">Anda telah melakukan presensi keluar</h4>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; 2024 Cielynn. All rights reserved.</p>
        </footer>

    </div>

    <script>
        // Function to fetch real-time time and display it in the card
        function updateTime() {
            const timeMasukElement = document.getElementById('time-masuk');
            const timeKeluarElement = document.getElementById('time-keluar');

            const now = new Date();
            const options = {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false // 24-hour format
            };

            const timeString = now.toLocaleTimeString('en-US', options);

            timeMasukElement.textContent = timeString;
            timeKeluarElement.textContent = timeString;

            // Store time for entry form
            document.getElementById('jam_masuk').value = timeString;

            // Store time for exit form
            document.getElementById('jam_keluar').value = timeString;
        }

        // Function to get location using Geolocation API
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, handleError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Handle geolocation errors
        function handleError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        // Display user's position
        function showPosition(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Insert latitude and longitude values into the form
            document.getElementById('latitude_siswa').value = latitude;
            document.getElementById('longitude_siswa').value = longitude;

            document.getElementById('latitude_siswa').value = latitude;
            document.getElementById('longitude_siswa').value = longitude;
        }

        // Get location when the page loads
        window.onload = function() {
            getLocation();
            updateTime(); // Initialize time display
        };
    </script>

</body>

</html>