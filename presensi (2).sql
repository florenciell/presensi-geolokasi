-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 10:58 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `kelas` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `kelas`) VALUES
(1, '12 RPL 1'),
(5, '12 RPL 2'),
(6, '12 RPL 3'),
(7, '12 RPL 4');

-- --------------------------------------------------------

--
-- Table structure for table `ketidakhadiran`
--

CREATE TABLE `ketidakhadiran` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `dari` date NOT NULL,
  `sampai` date NOT NULL,
  `keterangan` text NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ketidakhadiran`
--

INSERT INTO `ketidakhadiran` (`id`, `id_siswa`, `dari`, `sampai`, `keterangan`, `nama`, `nisn`, `kelas`, `id_kelas`) VALUES
(18, 21, '2024-10-20', '2024-10-31', 'Sakit', '', '', '', 1),
(20, 22, '2024-10-21', '2024-10-23', 'Sakit', '', '', '', 1),
(21, 23, '2024-10-21', '2024-10-24', 'Sakit', '', '', '', 1),
(25, 36, '2024-10-26', '2024-10-27', 'Sakit', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_presensi`
--

CREATE TABLE `lokasi_presensi` (
  `id` int(11) NOT NULL,
  `nama_lokasi` varchar(255) NOT NULL,
  `alamat_lokasi` varchar(255) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `radius` int(11) NOT NULL,
  `zona_waktu` varchar(4) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lokasi_presensi`
--

INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `alamat_lokasi`, `latitude`, `longitude`, `radius`, `zona_waktu`, `jam_masuk`, `jam_pulang`) VALUES
(7, 'Lab 7', 'Jember', '-8.212637759277223', '113.45931513833973', 100000, 'WIB', '07:00:00', '15:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `foto_masuk` varchar(225) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `jam_keluar` time NOT NULL,
  `foto_keluar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presensi`
--

INSERT INTO `presensi` (`id`, `id_siswa`, `tanggal_masuk`, `jam_masuk`, `foto_masuk`, `tanggal_keluar`, `jam_keluar`, `foto_keluar`) VALUES
(163, 26, '2024-10-26', '14:12:12', 'foto/masuk_2024-10-26_09-12-32.png', '2024-10-29', '06:52:35', 'foto/keluar_2024-10-29_00-52-38.png'),
(164, 36, '2024-10-26', '14:22:18', 'foto/masuk_2024-10-26_09-22-26.png', '2024-10-29', '06:57:57', 'foto/keluar_2024-10-29_00-57-59.png'),
(165, 38, '2024-10-26', '14:22:49', 'foto/masuk_2024-10-26_09-22-51.png', '2024-10-29', '06:54:36', 'foto/keluar_2024-10-29_00-54-38.png'),
(166, 26, '2024-10-29', '06:52:26', 'foto/masuk_2024-10-29_00-52-31.png', '2024-10-29', '06:52:35', 'foto/keluar_2024-10-29_00-52-38.png'),
(167, 38, '2024-10-29', '06:54:14', 'foto/masuk_2024-10-29_00-54-32.png', '2024-10-29', '06:54:36', 'foto/keluar_2024-10-29_00-54-38.png'),
(168, 36, '2024-10-29', '06:57:49', 'foto/masuk_2024-10-29_00-57-51.png', '2024-10-29', '06:57:57', 'foto/keluar_2024-10-29_00-57-59.png'),
(169, 41, '2024-10-29', '07:28:21', 'foto/masuk_2024-10-29_01-28-22.png', '2024-10-29', '07:28:25', 'foto/keluar_2024-10-29_01-28-26.png'),
(170, 42, '2024-10-29', '09:03:54', 'foto/masuk_2024-10-29_03-03-56.png', '2024-10-29', '09:03:58', 'foto/keluar_2024-10-29_03-03-59.png');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nisn` varchar(50) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `lokasi_presensi` varchar(50) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id`, `nisn`, `nama`, `jenis_kelamin`, `kelas`, `lokasi_presensi`, `kelas_id`, `foto`) VALUES
(26, '12345678909880', 'Aline van Derren', 'Perempuan', '1', '', NULL, 'jurnali.png'),
(27, '1234567890988000', 'Sekar Ningrum Indah Permaisuri', 'Laki-laki', '1', '', NULL, 'WhatsApp Image 2024-08-20 at 21.26.16_003cfb72.jpg'),
(36, '1234567890988555', 'Cathrine van Derren', 'Perempuan', '1', '', NULL, 'Screenshot 2024-10-24 123508.png'),
(38, '123456765339', 'Derren Wu', 'Laki-laki', '1', '', NULL, 'Screenshot 2024-10-24 123508.png'),
(41, '1234567890981', 'Rafius', 'Laki-laki', '1', '', NULL, 'Screenshot 2024-10-24 123508.png'),
(42, '1234567890978', 'Wonigit', 'Perempuan', '1', '', NULL, 'fajar r.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `username` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `status` varchar(20) NOT NULL,
  `role` enum('admin','siswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_siswa`, `username`, `password`, `status`, `role`) VALUES
(19, 26, 'aline', '$2y$10$qvJ6oLRY28G8xQf/GtT./OS8SqcqU5oz3sBfjaa1KSeg5mRxfD7Ye', 'Aktif', 'siswa'),
(20, 27, 'adi', '$2y$10$4rhGo6YUaPlIZ3/VSP4Teu6gc4bU8q8REUHruj5ObHadKnrzmCR3q', 'Aktif', 'admin'),
(23, 36, 'cat', '$2y$10$F0vH8RDuXF4AWCfpHzF76eodVrLEVlLeLWupzuRf5nEXOLac/zRK.', 'Aktif', 'siswa'),
(25, 38, 'wu', '$2y$10$/vyCD.2XG0Flizpyd294TON4uKNVfEu9HTW4Rp5q6wOyd/dBiuSKC', 'Aktif', 'siswa'),
(27, 27, 'sekar', '$2y$10$4rhGo6YUaPlIZ3/VSP4Teu6gc4bU8q8REUHruj5ObHadKnrzmCR3q', 'Aktif', 'admin'),
(29, 41, 'siti', '$2y$10$cj9ydEzjzLCmOeaGqt1moOwcfuBmc03EVSCr9b8Ci8Tt9ZpumghWi', 'Aktif', 'siswa'),
(30, 42, 'win', '$2y$10$StSN3GVGCKMm.GgI5h24vOXyxvNKWVJgAIAHyhtVeISXuHdrLApTm', 'Aktif', 'siswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kelas` (`kelas_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `fk_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
