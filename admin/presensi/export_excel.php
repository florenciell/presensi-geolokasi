<?php
include '../../config.php';
require '../../vendor/autoload.php'; // Pastikan PHP Spreadsheet sudah terinstal melalui Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Ambil nilai bulan dari parameter GET, atau default ke bulan saat ini
$filterMonth = isset($_GET['filterMonth']) ? $_GET['filterMonth'] : date('Y-m');

// Query data berdasarkan bulan
$query = "
    SELECT 
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
    WHERE DATE_FORMAT(presensi.tanggal_masuk, '%Y-%m') = '$filterMonth'
    ORDER BY presensi.jam_masuk ASC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}

// Membuat file Excel baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Rekap Presensi');

// Menulis header kolom
$headers = ['Nama', 'NISN', 'Kelas', 'Tanggal', 'Jam Masuk', 'Jam Keluar', 'Status'];
$sheet->fromArray($headers, NULL, 'A1');

// Menulis data dari database
$rowIndex = 2; // Mulai dari baris kedua
while ($row = mysqli_fetch_assoc($result)) {
    $dataRow = [
        $row['nama'],
        $row['nisn'],
        $row['kelas'],
        $row['tanggal_masuk'],
        $row['jam_masuk'],
        $row['jam_keluar'],
        $row['Status'],
    ];
    $sheet->fromArray($dataRow, NULL, "A$rowIndex");
    $rowIndex++;
}

// Konfigurasi file Excel
$filename = "Rekap_Presensi_$filterMonth.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
