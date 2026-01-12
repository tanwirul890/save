-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 12, 2026 at 09:50 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `save`
--

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

CREATE TABLE `budget` (
  `id_budget_kategori` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_kategori` int DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `limit_budget` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `budget`
--

INSERT INTO `budget` (`id_budget_kategori`, `id_user`, `id_kategori`, `bulan`, `tahun`, `limit_budget`) VALUES
(2, 7, 5, 'December', 2025, 100000.00),
(5, 7, 3, 'December', 2025, 100000.00),
(6, 7, 4, 'December', 2025, 200000.00),
(7, 8, 3, 'January', 2026, 1000.00),
(8, 8, 4, 'January', 2026, 5000.00),
(9, 8, 5, 'January', 2026, 1000000.00),
(10, 9, 3, 'January', 2026, 500000.00);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `jenis`) VALUES
(1, 'Gaji', 'Pemasukan'),
(2, 'Bonus', 'Pemasukan'),
(3, 'Makan', 'Pengeluaran'),
(4, 'Transport', 'Pengeluaran'),
(5, 'Belanja', 'Pengeluaran');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id_profile` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `gender` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `negara` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id_profile`, `id_user`, `nickname`, `gender`, `negara`, `foto`, `no_hp`, `alamat`) VALUES
(3, 7, 'wirul', 'Laki-laki', 'Indonesia', NULL, '082275247053', 'bandung'),
(5, 9, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_kategori` int DEFAULT NULL,
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_kategori`, `jenis`, `nominal`, `keterangan`, `tanggal`) VALUES
(21, 7, 5, 'Pengeluaran', 85000.00, NULL, '2025-12-31'),
(22, 7, 2, 'Pemasukan', 100000.00, NULL, '2025-12-31'),
(23, 7, 3, 'Pengeluaran', 20000.00, NULL, '2025-12-01'),
(24, 7, 3, 'Pengeluaran', 10000.00, NULL, '2025-12-04'),
(25, 7, 3, 'Pengeluaran', 10000.00, NULL, '2025-12-05'),
(26, 7, 3, 'Pengeluaran', 10000.00, NULL, '2025-12-09'),
(27, 7, 3, 'Pengeluaran', 10000.00, NULL, '2025-12-11'),
(28, 7, 1, 'Pemasukan', 10000.00, NULL, '2025-12-13'),
(29, 7, 1, 'Pemasukan', 90000.00, NULL, '2026-01-22'),
(30, 7, 3, 'Pengeluaran', 20000.00, NULL, '2025-12-01'),
(31, 7, 4, 'Pengeluaran', 100000.00, NULL, '2025-12-31'),
(32, 7, 3, 'Pengeluaran', 10000.00, NULL, '2025-12-30'),
(33, 8, 1, 'Pemasukan', 2000000.00, NULL, '2026-01-09'),
(34, 8, 3, 'Pengeluaran', 99999.00, NULL, '2026-01-13'),
(35, 9, 1, 'Pemasukan', 100000.00, NULL, '2026-01-01'),
(36, 9, 3, 'Pengeluaran', 50000.00, NULL, '2026-01-16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `created_at`, `role`) VALUES
(7, 'wirul', 'wirul@gmail.com', '$2y$10$z0yuy5je7M6ttmRqSPiW3OHuO6ityctiiVDoM3PViPxEoAaYPAsfG', '2025-12-26 07:29:39', 'admin'),
(8, 'Nazriel', 'nazriel@gmail.com', '$2y$10$oZYirExr2.aEGUdDPFViFu5Ove.bJh7l53ZfWlYRC3T21/9MnI6/u', '2026-01-09 02:04:12', 'user'),
(9, 'jawir', 'jawir@gmil.com', '$2y$10$OUXPu1UCUa7w1MAF3VO2sO4J3jVdKULZR2Szw4wHaz28/u20kMfH.', '2026-01-09 02:41:27', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`id_budget_kategori`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `budget_ibfk_1` (`id_user`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id_profile`),
  ADD KEY `profile_ibfk_1` (`id_user`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `transaksi_ibfk_1` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `budget`
--
ALTER TABLE `budget`
  MODIFY `id_budget_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id_profile` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `budget`
--
ALTER TABLE `budget`
  ADD CONSTRAINT `budget_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `budget_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
