-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Apr 2022 pada 16.45
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lts_db_manajemenkeluarga`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_data_income`
--

CREATE TABLE `tbl_data_income` (
  `id_income` int(11) NOT NULL,
  `id_pemasukan` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jumlah_income` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbl_data_income`
--

-- INSERT INTO `tbl_data_income` (`id_income`, `id_pemasukan`, `keterangan`, `jumlah_income`) VALUES
-- (1, 1, 'Gaji Bulan April 2022 di PT. Global Pristya Group (Goodeva Technology) 11 Bulan sudah di kantor ini.', 5850000),
-- (3, 3, 'Gaji dari Freelance Mengerjakan Aplikasi Mahasiswa Menampilkan Lokasi pada Maps di Android.', 350000),
-- (4, 4, 'Dapat Hasil Jualan Baju untuk Wanita, Gamis di Shopee, Terjual 4 Baju.', 375000),
-- (5, 5, 'Simple Pemasukan Test', 10000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_data_outcome`
--

CREATE TABLE `tbl_data_outcome` (
  `id_outcome` int(11) NOT NULL,
  `id_pengeluaran` int(11) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jumlah_outcome` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbl_data_outcome`
--

-- INSERT INTO `tbl_data_outcome` (`id_outcome`, `id_pengeluaran`, `keterangan`, `jumlah_outcome`) VALUES
-- (1, 2, 'Cicilan Laptop Abi Bulan ke 12', 576000),
-- (2, 1, 'Beli popok dede Agnia 1 Bal', 150000),
-- (3, 3, 'Bayar Listrik Bulanan April 2022', 150000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_log_history_mkk`
--

CREATE TABLE `tbl_log_history_mkk` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `waktu_akses` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `versi_apps` text DEFAULT NULL,
  `build_version` text DEFAULT NULL,
  `token_akses_log` text NOT NULL,
  `Aksi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbl_log_history_mkk`
--

-- INSERT INTO `tbl_log_history_mkk` (`id_log`, `id_user`, `waktu_akses`, `versi_apps`, `build_version`, `token_akses_log`, `Aksi`) VALUES
-- (3, 2, '2022-04-24 14:48:27', 'V1.0', 'Build 24042022.2113', '', ''),
-- (4, 2, '2022-04-25 08:07:52', 'V1.0', 'Build 24042022.2113', '', ''),
-- (5, 2, '2022-04-25 08:11:00', 'V1.0', 'Build 24042022.2113', '', ''),
-- (6, 2, '2022-04-25 08:19:17', 'V1.0', 'Build 24042022.2113', '', ''),
-- (7, 2, '2022-04-25 08:20:03', 'V1.0', 'Build 24042022.2113', '', ''),
-- (8, 2, '2022-04-25 08:20:06', 'V1.0', 'Build 24042022.2113', '', ''),
-- (9, 2, '2022-04-25 08:20:08', 'V1.0', 'Build 24042022.2113', '', ''),
-- (10, 2, '2022-04-25 08:20:09', 'V1.0', 'Build 24042022.2113', '', ''),
-- (11, 2, '2022-04-25 08:20:11', 'V1.0', 'Build 24042022.2113', '', ''),
-- (12, 2, '2022-04-25 08:20:13', 'V1.0', 'Build 24042022.2113', '', ''),
-- (13, 2, '2022-04-25 08:20:14', 'V1.0', 'Build 24042022.2113', '', ''),
-- (14, 2, '2022-04-25 08:20:15', 'V1.0', 'Build 24042022.2113', '', ''),
-- (15, 2, '2022-04-25 08:20:16', 'V1.0', 'Build 24042022.2113', '', ''),
-- (16, 2, '2022-04-25 08:20:17', 'V1.0', 'Build 24042022.2113', '', ''),
-- (17, 2, '2022-04-25 08:21:11', 'V1.0', 'Build 24042022.2113', '', ''),
-- (18, 2, '2022-04-25 08:22:48', 'V1.0', 'Build 24042022.2113', '', ''),
-- (19, 2, '2022-04-25 08:23:14', 'V1.0', 'Build 24042022.2113', '', ''),
-- (20, 2, '2022-04-25 08:25:10', 'V1.0', 'Build 24042022.2113', 'c1cac8af59bb60344515734e4fe8cdfe', ''),
-- (21, 3, '2022-04-25 08:30:27', 'V1.0', 'Build 24042022.2113', '396b754e48b3cb7e763dc56d38987c96', ''),
-- (22, 3, '2022-04-25 09:18:37', 'V1.0', 'Build 24042022.2113', '796a59ab1d67aa7e888926b34aca7e7b', 'Login'),
-- (23, 3, '2022-04-25 09:20:32', 'V1.0', 'Build 24042022.2113', 'af1aabfaf51aa57d8d918d300ebdc631', 'Login'),
-- (24, 3, '2022-04-25 22:01:01', 'V1.0', 'Build 24042022.2113', 'a82f7e34bb7d21603d3b23118d99c80c', 'Login'),
-- (25, 2, '2022-04-28 23:03:54', 'V1.0', 'Build 24042022.2113', 'cf88ba365967159f0d561cc45773d9a3', 'Login');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pemasukan_mkk`
--

CREATE TABLE `tbl_pemasukan_mkk` (
  `id_pemasukan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_tambah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kategori_inc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbl_pemasukan_mkk`
--

-- INSERT INTO `tbl_pemasukan_mkk` (`id_pemasukan`, `id_user`, `tanggal_tambah`, `kategori_inc`) VALUES
-- (1, 2, '2022-04-27 17:07:20', 'Pemasukan'),
-- (3, 2, '2022-04-27 17:28:20', 'Pemasukan'),
-- (4, 1, '2022-04-27 17:30:17', 'Pemasukan'),
-- (5, 2, '2022-04-29 11:53:24', 'Pemasukan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pengeluaran_mkk`
--

CREATE TABLE `tbl_pengeluaran_mkk` (
  `id_pengeluaran` int(11) NOT NULL,
  `id_income` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_pengeluaran` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `kategori_out` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbl_pengeluaran_mkk`
--

-- INSERT INTO `tbl_pengeluaran_mkk` (`id_pengeluaran`, `id_income`, `id_user`, `tanggal_pengeluaran`, `kategori_out`) VALUES
-- (1, 1, 1, '2022-04-27 18:19:41', 'Dede Aghnia'),
-- (2, 1, 1, '2022-04-27 18:21:41', 'Cicilan'),
-- (3, 1, 1, '2022-04-27 18:51:41', 'Bulanan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `kode_user` text DEFAULT NULL,
  `nama_user` text DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `alias` text DEFAULT NULL,
  `username` text DEFAULT NULL,
  `kunci` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `tanggal_terdaftar` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_access` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `token_akses` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbl_user`
--

-- INSERT INTO `tbl_user` (`id_user`, `kode_user`, `nama_user`, `gender`, `alias`, `username`, `kunci`, `email`, `tanggal_terdaftar`, `last_access`, `token_akses`) VALUES
-- (1, 'U902848234', 'Fithri Febiyani Yunus', 'Perempuan', 'Mami Mbul', 'fithri123', 'e10adc3949ba59abbe56e057f20f883e', 'fithrifebiyani@gmail.com', '2022-04-25 08:08:58', '2022-04-27 17:15:38', 'abcdefgh_ijk'),
-- (2, 'U932489584', 'Asep Septiadi', 'Laki-laki', 'Abah Mbul', 'asep123', 'e10adc3949ba59abbe56e057f20f883e', 'asepseptiadi@gmail.com', '2022-04-17 22:40:57', '2022-04-28 23:03:54', 'cf88ba365967159f0d561cc45773d9a3'),
-- (3, 'U932489586', 'Aghnia Almahyra', 'Perempuan', 'Dede Aghnia', 'aghnia123', 'e10adc3949ba59abbe56e057f20f883e', 'aghnia@gmail.com', '2022-04-25 08:13:27', '2022-04-25 22:01:01', 'a82f7e34bb7d21603d3b23118d99c80c');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_data_income`
--
ALTER TABLE `tbl_data_income`
  ADD PRIMARY KEY (`id_income`);

--
-- Indeks untuk tabel `tbl_data_outcome`
--
ALTER TABLE `tbl_data_outcome`
  ADD PRIMARY KEY (`id_outcome`);

--
-- Indeks untuk tabel `tbl_log_history_mkk`
--
ALTER TABLE `tbl_log_history_mkk`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `tbl_pemasukan_mkk`
--
ALTER TABLE `tbl_pemasukan_mkk`
  ADD PRIMARY KEY (`id_pemasukan`);

--
-- Indeks untuk tabel `tbl_pengeluaran_mkk`
--
ALTER TABLE `tbl_pengeluaran_mkk`
  ADD PRIMARY KEY (`id_pengeluaran`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_data_income`
--
ALTER TABLE `tbl_data_income`
  MODIFY `id_income` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT untuk tabel `tbl_data_outcome`
--
ALTER TABLE `tbl_data_outcome`
  MODIFY `id_outcome` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT untuk tabel `tbl_log_history_mkk`
--
ALTER TABLE `tbl_log_history_mkk`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT untuk tabel `tbl_pemasukan_mkk`
--
ALTER TABLE `tbl_pemasukan_mkk`
  MODIFY `id_pemasukan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT untuk tabel `tbl_pengeluaran_mkk`
--
ALTER TABLE `tbl_pengeluaran_mkk`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
