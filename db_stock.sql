-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 31, 2025 at 09:09 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `id_perusahaan` int(11) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `sku` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penerimaan`
--

CREATE TABLE `detail_penerimaan` (
  `id_detail` int(11) NOT NULL,
  `id_penerimaan` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah_diterima` int(11) NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail` int(11) NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_retur_penjualan`
--

CREATE TABLE `detail_retur_penjualan` (
  `id_detail_retur` int(11) NOT NULL,
  `id_retur` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah_retur` int(11) NOT NULL,
  `alasan_barang` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gudang`
--

CREATE TABLE `gudang` (
  `id_gudang` int(11) NOT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `nama_gudang` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gudang`
--

INSERT INTO `gudang` (`id_gudang`, `id_perusahaan`, `nama_gudang`, `alamat`, `telepon`, `created_by`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 1, 'Gudang Utama Jakarta', 'Jl. Sudirman No. 123', '021-11111111', 8, 1, '2025-08-29 10:10:00', NULL),
(2, 1, 'Gudang Cabang Bekasi', 'Jl. Ahmad Yani No. 456', '021-22222222', 8, 1, '2025-08-29 10:15:00', NULL),
(3, 2, 'Gudang Pusat', 'Jl. Thamrin No. 456', '021-33333333', 8, 1, '2025-08-29 10:20:00', NULL),
(9, 1, 'Gudang Edit', 'asdas', 'asda', 10, 0, '2025-09-01 00:00:51', NULL),
(10, 1, 'asdsad', 'asdas', 'asdas', 10, 1, '2025-09-01 01:00:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hak_akses_fitur`
--

CREATE TABLE `hak_akses_fitur` (
  `id_hak_akses` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `nama_fitur` varchar(50) NOT NULL,
  `akses` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hak_akses_fitur`
--

INSERT INTO `hak_akses_fitur` (`id_hak_akses`, `id_role`, `nama_fitur`, `akses`) VALUES
(1, 1, 'dashboard', 1),
(2, 1, 'perusahaan', 1),
(3, 1, 'gudang', 1),
(4, 1, 'kategori', 1),
(5, 1, 'barang', 1),
(6, 1, 'supplier', 1),
(7, 1, 'pelanggan', 1),
(8, 1, 'stok_awal', 1),
(9, 1, 'penerimaan', 1),
(10, 1, 'transfer', 1),
(11, 1, 'penyesuaian', 1),
(12, 1, 'riwayat', 1),
(13, 1, 'laporan_stok', 1),
(14, 1, 'laporan_penjualan', 1),
(15, 1, 'laporan_retur', 1),
(16, 1, 'laporan_transfer', 1),
(17, 2, 'dashboard', 1),
(18, 2, 'penjualan', 1),
(19, 2, 'pelanggan', 1),
(20, 2, 'laporan_penjualan', 1),
(21, 3, 'dashboard', 1),
(22, 3, 'penjualan', 1),
(23, 3, 'laporan_penjualan', 1),
(24, 4, 'dashboard', 1),
(25, 4, 'retur', 1),
(26, 4, 'laporan_retur', 1),
(27, 5, 'dashboard', 1),
(28, 5, 'perusahaan', 1),
(29, 5, 'gudang', 1),
(30, 5, 'kategori', 1),
(31, 5, 'barang', 1),
(32, 5, 'supplier', 1),
(33, 5, 'pelanggan', 1),
(34, 5, 'stok_awal', 1),
(35, 5, 'penerimaan', 1),
(36, 5, 'transfer', 1),
(37, 5, 'penyesuaian', 1),
(38, 5, 'riwayat', 1),
(39, 5, 'penjualan', 1),
(40, 5, 'retur', 1),
(41, 5, 'laporan_stok', 1),
(42, 5, 'laporan_penjualan', 1),
(43, 5, 'laporan_retur', 1),
(44, 5, 'laporan_transfer', 1),
(45, 5, 'user', 1),
(46, 5, 'hak_akses', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `id_perusahaan` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `id_perusahaan`, `nama_kategori`, `deskripsi`, `status_aktif`, `created_at`) VALUES
(2, 1, 'asdasdasd', 'asdfas\r\n', 1, '2025-09-01 00:10:00'),
(3, 1, 'asdfsa', 'asdas', 0, '2025-09-01 00:12:50'),
(4, 1, 'lkm', 'lkm', 0, '2025-09-01 00:42:46'),
(5, 2, 'asda', 'asdfa\r\n', 1, '2025-09-01 00:48:28'),
(6, 2, 'asdas', 'asfdas', 1, '2025-09-01 00:56:33'),
(7, 1, 'kategori nya di edit', 'asdasd', 1, '2025-09-01 00:56:41'),
(8, 1, 'maju berjasa kategori', 'sjdhkjasndkj', 1, '2025-09-01 00:57:29');

-- --------------------------------------------------------

--
-- Table structure for table `log_stok`
--

CREATE TABLE `log_stok` (
  `id_log` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `id_gudang` int(11) DEFAULT NULL,
  `jenis` enum('masuk','keluar','retur','transfer_keluar','transfer_masuk','penyesuaian') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `id_referensi` int(11) DEFAULT NULL COMMENT 'ID transaksi terkait (penjualan/penerimaan/retur)',
  `tipe_referensi` enum('penjualan','penerimaan','retur','transfer','penyesuaian') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `penerimaan_barang`
--

CREATE TABLE `penerimaan_barang` (
  `id_penerimaan` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_penerimaan` datetime NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `status` enum('draft','diterima','dibatalkan') DEFAULT 'draft',
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_sistem`
--

CREATE TABLE `pengaturan_sistem` (
  `id_pengaturan` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `value` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengaturan_sistem`
--

INSERT INTO `pengaturan_sistem` (`id_pengaturan`, `key`, `value`, `keterangan`) VALUES
(1, 'nama_toko', 'Toko Saya', 'Nama toko default'),
(2, 'alamat_toko', 'Jl. Contoh No. 123', 'Alamat toko'),
(3, 'minimal_stok', '5', 'Minimal stok untuk notifikasi');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `no_invoice` varchar(50) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tanggal_penjualan` datetime NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('proses','packing','dikirim','selesai','batal') DEFAULT 'proses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id_perusahaan` int(11) NOT NULL,
  `nama_perusahaan` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `perusahaan`
--

INSERT INTO `perusahaan` (`id_perusahaan`, `nama_perusahaan`, `alamat`, `telepon`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'PT. Maju Bersama', 'Jl. Sudirman No. 123', '021-12345678', 1, '2025-08-29 10:00:00', NULL),
(2, 'CV. Sukses Jaya', 'Jl. Thamrin No. 456', '021-87654321', 1, '2025-08-29 10:05:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `retur_penjualan`
--

CREATE TABLE `retur_penjualan` (
  `id_retur` int(11) NOT NULL,
  `no_retur` varchar(50) DEFAULT NULL,
  `id_penjualan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_retur` datetime NOT NULL,
  `alasan_retur` text NOT NULL,
  `status` enum('diterima','diproses','selesai','ditolak') DEFAULT 'diterima'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id_role`, `nama_role`) VALUES
(1, 'Admin Pusat'),
(2, 'Sales Online'),
(3, 'Admin Packing'),
(4, 'Admin Retur'),
(5, 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `stok_awal`
--

CREATE TABLE `stok_awal` (
  `id_stok_awal` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `id_perusahaan` int(11) NOT NULL,
  `qty_awal` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stok_gudang`
--

CREATE TABLE `stok_gudang` (
  `id_stok` int(11) NOT NULL,
  `id_perusahaan` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stok`
--

CREATE TABLE `transfer_stok` (
  `id_transfer` int(11) NOT NULL,
  `no_transfer` varchar(50) DEFAULT NULL,
  `id_barang` int(11) NOT NULL,
  `id_gudang_asal` int(11) NOT NULL,
  `id_gudang_tujuan` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `keterangan` varchar(255) DEFAULT NULL,
  `status` enum('pending','selesai','batal') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `id_gudang` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `password_hash`, `id_role`, `id_perusahaan`, `id_gudang`, `created_by`, `aktif`, `created_at`, `updated_at`, `last_login`, `foto_profil`) VALUES
(5, 'Akbar', 'sales_jakarta', '$2y$10$E3cAIafPO6X3RezKVsEQuuXeoiAgemMabpvvryziya1Qfby6474Ja', 2, 1, 1, NULL, 1, '2025-04-16 13:53:26', '2025-06-30 14:20:11', NULL, NULL),
(8, 'Super Admin Nama', 'admin', '$2y$10$Jk0IyD8hFSY6CbpX5MJtD.3GlVjw.g9hAVM/rymsjmza2cnTl02aq', 5, NULL, NULL, NULL, 1, '2025-04-21 09:48:25', '2025-04-21 10:46:15', '2025-09-01 01:58:28', NULL),
(10, 'Admin Pusat Nama', 'admin2', '$2y$10$ykg6ygxyjhuAzuYhpA2UqObN.bJt41ONsPrGlmeksSiGKBAWLawHy', 1, 1, 1, NULL, 1, '2025-04-21 14:01:42', '2025-04-21 16:48:20', '2025-09-01 01:58:25', NULL),
(12, 'Sales Bandung Nama', 'sales_bandung', '$2y$10$E3cAIafPO6X3RezKVsEQuuXeoiAgemMabpvvryziya1Qfby6474Ja', 2, 1, 1, NULL, 1, '2025-04-21 14:26:38', '2025-06-30 13:33:03', '2025-08-30 14:28:26', NULL),
(13, 'Admin Pack', 'admin_packing', '$2y$10$W9j/soAn2GZ3loyo2Sbq8uU7MNuIjQpY78FrhdEKYLCP7TuupZD1m', 3, 1, 2, NULL, 1, '2025-08-27 20:07:00', '2025-08-27 20:46:31', NULL, NULL),
(14, 'Admin Retur Nama', 'admin_retur', '$2y$10$OyPBW3BDCPUbJKye1Q7H8.wL61P53dj2jqK.NAEXO6CIYhScrDGIm', 4, 1, 1, NULL, 1, '2025-08-27 20:07:50', NULL, '2025-08-30 14:28:05', NULL),
(16, 'kkkkk', 'kk', '$2y$10$xfh70OfZsFYw1CIEVfpwcOp3pUzWwaHbzKAWaBE/TdInI0kkmX8Hq', 5, NULL, NULL, 8, 1, '2025-08-30 14:32:17', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `uniq_sku_perusahaan` (`id_perusahaan`,`sku`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `detail_penerimaan`
--
ALTER TABLE `detail_penerimaan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_penerimaan_detail` (`id_penerimaan`),
  ADD KEY `fk_barang_penerimaan` (`id_barang`);

--
-- Indexes for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_penjualan_detail` (`id_penjualan`),
  ADD KEY `fk_barang_penjualan` (`id_barang`);

--
-- Indexes for table `detail_retur_penjualan`
--
ALTER TABLE `detail_retur_penjualan`
  ADD PRIMARY KEY (`id_detail_retur`),
  ADD KEY `fk_retur` (`id_retur`),
  ADD KEY `fk_barang` (`id_barang`);

--
-- Indexes for table `gudang`
--
ALTER TABLE `gudang`
  ADD PRIMARY KEY (`id_gudang`),
  ADD KEY `id_perusahaan` (`id_perusahaan`);

--
-- Indexes for table `hak_akses_fitur`
--
ALTER TABLE `hak_akses_fitur`
  ADD PRIMARY KEY (`id_hak_akses`),
  ADD UNIQUE KEY `uniq_role_fitur` (`id_role`,`nama_fitur`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `uniq_kategori_perusahaan` (`id_perusahaan`,`nama_kategori`);

--
-- Indexes for table `log_stok`
--
ALTER TABLE `log_stok`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_barang` (`id_barang`),
  ADD KEY `idx_gudang` (`id_gudang`),
  ADD KEY `idx_referensi` (`id_referensi`),
  ADD KEY `idx_tipe_referensi` (`tipe_referensi`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `penerimaan_barang`
--
ALTER TABLE `penerimaan_barang`
  ADD PRIMARY KEY (`id_penerimaan`);

--
-- Indexes for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  ADD PRIMARY KEY (`id_pengaturan`),
  ADD UNIQUE KEY `uniq_key` (`key`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id_perusahaan`);

--
-- Indexes for table `retur_penjualan`
--
ALTER TABLE `retur_penjualan`
  ADD PRIMARY KEY (`id_retur`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `stok_awal`
--
ALTER TABLE `stok_awal`
  ADD PRIMARY KEY (`id_stok_awal`),
  ADD KEY `fk_stok_awal_barang` (`id_barang`),
  ADD KEY `fk_stok_awal_gudang` (`id_gudang`),
  ADD KEY `fk_stok_awal_perusahaan` (`id_perusahaan`);

--
-- Indexes for table `stok_gudang`
--
ALTER TABLE `stok_gudang`
  ADD PRIMARY KEY (`id_stok`),
  ADD UNIQUE KEY `uniq_barang_gudang` (`id_barang`,`id_gudang`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `transfer_stok`
--
ALTER TABLE `transfer_stok`
  ADD PRIMARY KEY (`id_transfer`),
  ADD KEY `fk_transfer_barang` (`id_barang`),
  ADD KEY `fk_transfer_gudang_asal` (`id_gudang_asal`),
  ADD KEY `fk_transfer_gudang_tujuan` (`id_gudang_tujuan`),
  ADD KEY `fk_transfer_user` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_role` (`id_role`),
  ADD KEY `id_cabang` (`id_gudang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `detail_penerimaan`
--
ALTER TABLE `detail_penerimaan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_retur_penjualan`
--
ALTER TABLE `detail_retur_penjualan`
  MODIFY `id_detail_retur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gudang`
--
ALTER TABLE `gudang`
  MODIFY `id_gudang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hak_akses_fitur`
--
ALTER TABLE `hak_akses_fitur`
  MODIFY `id_hak_akses` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `log_stok`
--
ALTER TABLE `log_stok`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penerimaan_barang`
--
ALTER TABLE `penerimaan_barang`
  MODIFY `id_penerimaan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  MODIFY `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perusahaan`
--
ALTER TABLE `perusahaan`
  MODIFY `id_perusahaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `retur_penjualan`
--
ALTER TABLE `retur_penjualan`
  MODIFY `id_retur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stok_awal`
--
ALTER TABLE `stok_awal`
  MODIFY `id_stok_awal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_gudang`
--
ALTER TABLE `stok_gudang`
  MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transfer_stok`
--
ALTER TABLE `transfer_stok`
  MODIFY `id_transfer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Constraints for table `detail_penerimaan`
--
ALTER TABLE `detail_penerimaan`
  ADD CONSTRAINT `fk_barang_penerimaan` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_penerimaan_detail` FOREIGN KEY (`id_penerimaan`) REFERENCES `penerimaan_barang` (`id_penerimaan`);

--
-- Constraints for table `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `fk_barang_penjualan` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_penjualan_detail` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`);

--
-- Constraints for table `detail_retur_penjualan`
--
ALTER TABLE `detail_retur_penjualan`
  ADD CONSTRAINT `fk_barang_retur` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_retur_detail` FOREIGN KEY (`id_retur`) REFERENCES `retur_penjualan` (`id_retur`);

--
-- Constraints for table `gudang`
--
ALTER TABLE `gudang`
  ADD CONSTRAINT `gudang_ibfk_1` FOREIGN KEY (`id_perusahaan`) REFERENCES `perusahaan` (`id_perusahaan`);

--
-- Constraints for table `stok_awal`
--
ALTER TABLE `stok_awal`
  ADD CONSTRAINT `fk_stok_awal_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_stok_awal_gudang` FOREIGN KEY (`id_gudang`) REFERENCES `gudang` (`id_gudang`),
  ADD CONSTRAINT `fk_stok_awal_perusahaan` FOREIGN KEY (`id_perusahaan`) REFERENCES `perusahaan` (`id_perusahaan`);

--
-- Constraints for table `transfer_stok`
--
ALTER TABLE `transfer_stok`
  ADD CONSTRAINT `fk_transfer_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_transfer_gudang_asal` FOREIGN KEY (`id_gudang_asal`) REFERENCES `gudang` (`id_gudang`),
  ADD CONSTRAINT `fk_transfer_gudang_tujuan` FOREIGN KEY (`id_gudang_tujuan`) REFERENCES `gudang` (`id_gudang`),
  ADD CONSTRAINT `fk_transfer_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role_user` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
