<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

<style>
    #map {
        height: 250px;
    }
</style>

<?php
// Start session
session_start();

// Include configuration file to connect to the database
include_once '../../config.php';

// Initialize variables
$latitude_siswa = null;
$longitude_siswa = null;
$zona_waktu = null;
$tanggal_masuk = null;
$jam_masuk = null;
$latitude_lab = null;
$longitude_lab = null;
$radius_lab = null;

// Set timezone to Jakarta
date_default_timezone_set('Asia/Jakarta');

// Get current date and time
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// Check if 'Ambil Gambar dan Presensi' button is pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form and sanitize inputs
    $latitude_siswa = filter_input(INPUT_POST, 'latitude_siswa', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $longitude_siswa = filter_input(INPUT_POST, 'longitude_siswa', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $zona_waktu = filter_input(INPUT_POST, 'zona_waktu', FILTER_SANITIZE_STRING);
    $tanggal_masuk = filter_input(INPUT_POST, 'tanggal_masuk', FILTER_SANITIZE_STRING);
    $jam_masuk = filter_input(INPUT_POST, 'jam_masuk', FILTER_SANITIZE_STRING);
    $photo_data = filter_input(INPUT_POST, 'photo', FILTER_SANITIZE_STRING);

    // Set default values if not set from POST
    if (!$tanggal_masuk) {
        $tanggal_masuk = $current_date;
    }
    if (!$jam_masuk) {
        $jam_masuk = $current_time;
    }

    // Query to get latitude, longitude, and radius from lokasi_presensi
    $query = "SELECT latitude, longitude, radius FROM lokasi_presensi LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $latitude_lab = (float)$row['latitude'];
        $longitude_lab = (float)$row['longitude'];
        $radius_lab = (float)$row['radius'];
    } else {
        echo "Data lokasi presensi tidak ditemukan atau error: " . mysqli_error($conn);
        exit;
    }

    // Validate coordinates
    if (is_numeric($latitude_siswa) && is_numeric($longitude_siswa)) {
        // Haversine formula to calculate distance
        $earth_radius = 6371000; // in meters
        $dLat = deg2rad($latitude_lab - $latitude_siswa);
        $dLon = deg2rad($longitude_lab - $longitude_siswa);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($latitude_siswa)) * cos(deg2rad($latitude_lab)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $jarak_meter = $earth_radius * $c;

        if ($jarak_meter > $radius_lab) {
            $_SESSION['gagal'] = "Anda berada di luar area presensi";
            header("Location: ../home/home.php");
            exit;
        }

        // Save presensi data into the database logic here
    } else {
        $_SESSION['gagal'] = "Koordinat tidak valid.";
        header("Location: ../home/home.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi</title>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            display: flex;
            flex-direction: column;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin: auto;
        }

        .form-container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #mapContainer {
            width: 100%;
            height: 250px;
            position: relative;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .button-wrapper {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        button {
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .button-presensi {
            background-color: #DA8CFF;
        }

        .button-presensi:hover {
            background-color: #9A55FF;
        }

        .button-presensi:active {
            transform: translateY(1px);
        }

        .snapshot {
            margin-top: 15px;
            border: 2px solid #DA8CFF;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            height: 250px;
            display: none;
            background-color: #f8f8f8;
            position: relative;
        }

        .snapshot h3 {
            position: absolute;
            top: 10px;
            left: 10px;
            margin: 0;
            padding: 5px 10px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-radius: 5px;
        }

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .headline {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .icon {
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-container">
            <h2 class="headline">Presensi Masuk</h2>
            <div id="mapContainer">
                <div id="map"></div>
            </div>
            <div class="button-wrapper">
                <form method="post">
                    <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($_SESSION['id']) ?>">
                    <input type="hidden" name="latitude_siswa" value="">
                    <input type="hidden" name="longitude_siswa" value="">
                    <input type="hidden" name="zona_waktu" value="">
                    <input type="hidden" name="tanggal_masuk" value="<?= $current_date; ?>" readonly>
                    <input type="hidden" name="jam_masuk" value="<?= $current_time; ?>" readonly>
                    <button type="button" class="button-presensi" id="ambil-foto">
                        <i class="fas fa-camera icon"></i> Masuk
                    </button>
                </form>
            </div>
            <div class="snapshot" id="my_result">
                <h3>Snapshot</h3>
                <img id="snapshotImage" src="" alt="Snapshot">
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Set up webcam
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        // Function to take a snapshot
        function takeSnapshot() {
            Webcam.snap(function(data_uri) {
                // Display the snapshot result
                document.getElementById('my_result').style.display = 'block';
                document.getElementById('snapshotImage').src = data_uri;

                // Create an XMLHttpRequest to send the image and other data
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState === 4) {
                        if (this.status === 200) {
                            console.log(this.responseText); // Check server response
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Presensi Anda telah dicatat!',
                                confirmButtonText: 'Ok'
                            }).then(() => {
                                window.location.href = "../home/home.php";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Ada kesalahan saat mencatat presensi.',
                                confirmButtonText: 'Ok'
                            });
                        }
                    }
                };
                xhttp.open("POST", "presensi_masuk_aksi.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                // Prepare data to send
                const params = new URLSearchParams();
                params.append('photo', data_uri);
                params.append('id', document.querySelector('input[name="id"]').value);
                params.append('latitude_siswa', document.querySelector('input[name="latitude_siswa"]').value);
                params.append('longitude_siswa', document.querySelector('input[name="longitude_siswa"]').value);
                params.append('tanggal_masuk', document.querySelector('input[name="tanggal_masuk"]').value);
                params.append('jam_masuk', document.querySelector('input[name="jam_masuk"]').value);

                // Send the request with the parameters
                xhttp.send(params.toString());
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Attach the webcam without displaying it
            Webcam.attach(document.createElement('div'));

            // Add event listener to the photo button
            document.getElementById('ambil-foto').addEventListener('click', function() {
                takeSnapshot();
            });

            // Get user location and populate the form
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    document.querySelector('input[name="latitude_siswa"]').value = latitude;
                    document.querySelector('input[name="longitude_siswa"]').value = longitude;

                    // Update the map with the current location
                    if (typeof circle !== 'undefined') {
                        map.removeLayer(circle); // Remove the old circle
                    }

                    circle = L.circle([latitude, longitude], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: 500
                    }).addTo(map).bindPopup("Lokasi Anda saat ini").openPopup();

                    // Center the map on the current location
                    map.setView([latitude, longitude], 13);
                }, function(error) {
                    console.error("Geolocation error: ", error);
                });
            } else {
                console.error("Geolocation is not supported by this browser.");
            }
        });

        // Initialize map
        let latitude_lab = <?= $latitude_lab ?>;
        let longitude_lab = <?= $longitude_lab ?>;

        let map = L.map('map').setView([latitude_lab, longitude_lab], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker = L.marker([latitude_lab, longitude_lab]).addTo(map);
    </script>

</body>

</html>