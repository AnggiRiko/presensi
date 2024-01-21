-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Jan 2024 pada 10.15
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

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
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id` int(11) NOT NULL,
  `jabatan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id`, `jabatan`) VALUES
(2, 'Admin'),
(5, 'IT Support'),
(6, 'Marketing');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ketidakhadiran`
--

CREATE TABLE `ketidakhadiran` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` varchar(225) NOT NULL,
  `file` varchar(225) NOT NULL,
  `status_pengajuan` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi_presensi`
--

CREATE TABLE `lokasi_presensi` (
  `id` int(11) NOT NULL,
  `nama_lokasi` varchar(225) NOT NULL,
  `alamat_lokasi` varchar(225) NOT NULL,
  `tipe_lokasi` varchar(225) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `radius` varchar(11) NOT NULL,
  `zona_waktu` varchar(4) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `lokasi_presensi`
--

INSERT INTO `lokasi_presensi` (`id`, `nama_lokasi`, `alamat_lokasi`, `tipe_lokasi`, `latitude`, `longitude`, `radius`, `zona_waktu`, `jam_masuk`, `jam_pulang`) VALUES
(8, 'Kantor Pusat', 'Jl. Hasan Kepala Ratu, Gg. Merpati, No. 39, Kel. Sindang Sari, Kec. Kotabumi, Lampung Utara', 'Pusat', '-4.815032', '104.8788366', '10000000000', 'WIB', '08:00:00', '08:15:00'),
(10, 'Kantor Cabang', 'Jl. Jend. Sudirman No.43, Kel. Cempedak, Kec. Kotabumi, Lampung Utara', 'Cabang', '-4.822513', '104.8801637', '100', 'WIB', '08:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `no_handphone` varchar(20) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `lokasi_presensi` varchar(50) NOT NULL,
  `foto` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id`, `nip`, `nama`, `jenis_kelamin`, `alamat`, `no_handphone`, `jabatan`, `lokasi_presensi`, `foto`) VALUES
(1, 'PEG-001', 'Anggi Riko', 'Laki-laki', 'Sindang sari', '08xxxxxxxxxx', 'Admin', 'Kantor Pusat', 'profile.jpg'),
(9, 'PEG-002', 'Reza Aditya', 'Laki-laki', 'Jl. Abung Raya Timur No.90, Kotabumi Ps., Kec. Kotabumi, Kabupaten Lampung Utara, Lampung 34517', '08XXXXXXXXXX', 'IT Support', 'Kantor Pusat', 'foto formal.jpg'),
(10, 'PEG-003', 'Merry Cantika', 'Perempuan', 'Jl. Kesehatan No.40, Tj. Aman, Kec. Kotabumi Sel., Kabupaten Lampung Utara, Lampung 34516', '08XXXXXXXXXX', 'Marketing', 'Kantor Cabang', 'foto formal2.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `presensi`
--

CREATE TABLE `presensi` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `foto_masuk` varchar(225) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `jam_keluar` time NOT NULL,
  `foto_keluar` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(225) NOT NULL,
  `status` varchar(20) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `id_pegawai`, `username`, `password`, `status`, `role`) VALUES
(1, 1, 'anggi', '$2y$10$2WbUPiX9B5pjJyrgwkrGq.t5Pg0TZeXB7BlXTG8aaFB1s59NP1Z7m', 'Aktif', 'admin'),
(9, 9, 'reza', '$2y$10$tNEHwLD4zfvMKZ3Y5KZ8lu7JEvL7u5s0fP92JSFwX/UYS0ODVW7s2', 'Aktif', 'pegawai'),
(10, 10, 'merry', '$2y$10$IbzjXkCtqnmKzA/MJaMfzuCz2Z475VF79nUIF6yd7KiL6ku4rR4QK', 'Aktif', 'pegawai');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indeks untuk tabel `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `lokasi_presensi`
--
ALTER TABLE `lokasi_presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `ketidakhadiran`
--
ALTER TABLE `ketidakhadiran`
  ADD CONSTRAINT `ketidakhadiran_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
