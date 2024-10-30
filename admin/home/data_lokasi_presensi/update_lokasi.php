<?php
// Koneksi ke database
$host = 'localhost'; // Ganti dengan host database Anda
$user = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda
$database = 'presensi'; // Ganti dengan nama database Anda

// Membuat koneksi
$koneksi = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Cek jika ada ID lokasi yang diberikan
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data lokasi berdasarkan ID
    $sql = "SELECT * FROM lokasi_presensi WHERE id = $id";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        $lokasi = $result->fetch_assoc();
    } else {
        die("Data tidak ditemukan.");
    }
} else {
    die("ID lokasi tidak diberikan.");
}

// Proses form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_lokasi = $_POST['nama_lokasi'];
    $alamat_lokasi = $_POST['alamat_lokasi'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $radius = $_POST['radius'];
    $zona_waktu = $_POST['zona_waktu'];
    $jam_masuk = $_POST['jam_masuk'];
    $jam_pulang = $_POST['jam_pulang'];

    // Validasi data
    if (!empty($nama_lokasi) && !empty($alamat_lokasi) && !empty($latitude) && !empty($longitude) && !empty($radius) && !empty($zona_waktu) && !empty($jam_masuk) && !empty($jam_pulang)) {
        // Siapkan query SQL
        $sql = "UPDATE lokasi_presensi 
                SET nama_lokasi='$nama_lokasi', alamat_lokasi='$alamat_lokasi', latitude='$latitude', longitude='$longitude', radius='$radius', zona_waktu='$zona_waktu', jam_masuk='$jam_masuk', jam_pulang='$jam_pulang'
                WHERE id=$id";

        if ($koneksi->query($sql) === TRUE) {
            echo "Data berhasil diperbarui.";
            // Redirect ke halaman lain atau tampilkan pesan sukses
            header("Location: lokasi_presensi.php"); // Ganti dengan URL yang sesuai
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    } else {
        echo "Semua field harus diisi!";
    }
}

// Tutup koneksi
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Update Lokasi Presensi</title>
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
    <link rel="stylesheet" href="../../../dist/assets/css/style.css">
    <!-- endinject -->
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
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Update Lokasi Presensi</h4><br>
                                <form class="forms-sample" method="POST" action="">
                                    <div class="form-group">
                                        <label for="nama_lokasi">Nama Lokasi</label>
                                        <input type="text" class="form-control" id="nama_lokasi" name="nama_lokasi" value="<?php echo htmlspecialchars($lokasi['nama_lokasi']); ?>" placeholder="Masukan Lokasi">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_lokasi">Alamat Lokasi</label>
                                        <input type="text" class="form-control" id="alamat_lokasi" name="alamat_lokasi" value="<?php echo htmlspecialchars($lokasi['alamat_lokasi']); ?>" placeholder="Masukan alamat">
                                    </div>
                                    <div class="form-group">
                                        <label for="latitude">Latitude</label>
                                        <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($lokasi['latitude']); ?>" placeholder="Masukan Latitude">
                                    </div>
                                    <div class="form-group">
                                        <label for="longitude">Longitude</label>
                                        <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($lokasi['longitude']); ?>" placeholder="Masukan Longitude">
                                    </div>
                                    <div class="form-group">
                                        <label for="radius">Radius</label>
                                        <input type="text" class="form-control" id="radius" name="radius" value="<?php echo htmlspecialchars($lokasi['radius']); ?>" placeholder="Masukan Radius">
                                    </div>
                                    <div class="form-group">
                                        <label for="zona_waktu">Zona Waktu</label>
                                        <input type="text" class="form-control" id="zona_waktu" name="zona_waktu" value="<?php echo htmlspecialchars($lokasi['zona_waktu']); ?>" placeholder="Masukan Zona Waktu">
                                    </div>
                                    <div class="form-group">
                                        <label for="jam_masuk">Jam Masuk</label>
                                        <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" value="<?php echo htmlspecialchars($lokasi['jam_masuk']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="jam_pulang">Jam Pulang</label>
                                        <input type="time" class="form-control" id="jam_pulang" name="jam_pulang" value="<?php echo htmlspecialchars($lokasi['jam_pulang']); ?>">
                                    </div>
                                    <button type="submit" class="btn btn-gradient-primary me-2">Update</button>
                                    <a href="lokasi_presensi.php" class="btn btn-light">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="container-fluid clearfix">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">© 2024 <a href="https://www.instagram.com/kecil.tambahan/" target="_blank">Kecil Tambahan</a>. All rights reserved.</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <!-- plugins:js -->
    <script src="../../../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../../dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="../../../dist/assets/js/off-canvas.js"></script>
    <script src="../../../dist/assets/js/hoverable-collapse.js"></script>
    <script src="../../../dist/assets/js/misc.js"></script>
    <script src="../../../dist/assets/js/settings.js"></script>
    <script src="../../../dist/assets/js/todolist.js"></script>
    <!-- endinject -->
</body>

</html>