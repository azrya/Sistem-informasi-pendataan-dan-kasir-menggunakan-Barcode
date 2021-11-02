-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Sep 2021 pada 11.16
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbminimarket`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telp` varchar(12) NOT NULL,
  `level` varchar(10) NOT NULL,
  `blokir` varchar(2) NOT NULL,
  `id_session` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`username`, `password`, `nama_lengkap`, `email`, `telp`, `level`, `blokir`, `id_session`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 'Lucky Maulidia', 'administrator@gmail.com', '081267771344', 'Admin', 'N', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `costumer`
--

CREATE TABLE `costumer` (
  `id_costumer` int(5) NOT NULL,
  `nama_costumer` varchar(30) NOT NULL,
  `no_telpon` varchar(15) NOT NULL,
  `alamat_lengkap` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `faktur`
--

CREATE TABLE `faktur` (
  `id_faktur` int(5) NOT NULL,
  `no_faktur` varchar(20) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_produk`
--

CREATE TABLE `kategori_produk` (
  `id_kategori` int(5) NOT NULL,
  `nama_kategori` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id_orders` int(5) NOT NULL,
  `no_orders` varchar(12) COLLATE latin1_general_ci NOT NULL,
  `id_costumer` int(5) NOT NULL,
  `nama_kasir` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `tgl_order` date NOT NULL,
  `jam_order` time NOT NULL,
  `bayar` int(10) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders_detail`
--

CREATE TABLE `orders_detail` (
  `id_orders` int(5) NOT NULL,
  `id_produk` int(5) NOT NULL,
  `jumlah` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders_temp`
--

CREATE TABLE `orders_temp` (
  `id_orders_temp` int(5) NOT NULL,
  `id_produk` int(5) NOT NULL,
  `id_session` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `jumlah` int(5) NOT NULL,
  `tgl_order_temp` date NOT NULL,
  `jam_order_temp` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(5) NOT NULL,
  `kode_produk` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `id_kategori` int(5) NOT NULL,
  `nama_produk` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `deskripsi` text COLLATE latin1_general_ci NOT NULL,
  `harga` int(20) NOT NULL,
  `harga_grosir` int(20) NOT NULL,
  `harga_pokok` int(20) NOT NULL,
  `satuan` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `berat` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `diskon` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `tgl_masuk` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_pembelian`
--

CREATE TABLE `produk_pembelian` (
  `id_produk_pembelian` int(5) NOT NULL,
  `id_faktur` varchar(20) NOT NULL,
  `id_produk` int(5) NOT NULL,
  `id_supplier` int(5) NOT NULL,
  `jumlah` int(5) NOT NULL,
  `tanggal_masuk` datetime NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `return_produk`
--

CREATE TABLE `return_produk` (
  `id_return` int(5) NOT NULL,
  `id_produk` int(5) NOT NULL,
  `id_supplier` int(5) NOT NULL,
  `jumlah` int(5) NOT NULL,
  `waktu_return` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `statis`
--

CREATE TABLE `statis` (
  `judul` varchar(255) NOT NULL,
  `halaman` varchar(20) NOT NULL,
  `detail` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `statis`
--

INSERT INTO `statis` (`judul`, `halaman`, `detail`) VALUES
('Selamat datang di sistem informasi Penjualan', 'home', '<p>System aplikasi point of sale adalah software yang di rancang, untuk mempermudah user / kasir dalam melakukan transaksi penjulan dan pembelian barang, software point of sale sudah bisa menghitung stock barang secara otomatis. software ini bisa digunakan di toko, minimarket dll. Selain itu keunggulan software ini sudah mencakup, pembayaran hutang, pembayaran piutang dan retur pembelian, retur penjualan barang , penjualan jasa dan software ini sudah dilengkapi dengan beberapa laporan-laporan yang bertujuan untuk mempermudah user dalam mengontrol data barang data â€“ data transaksi penjualan dan pembelian maupun retur barang secara baik. </p>\r\n\r\n<p>Adapun laporan point of sale adalah laporan master barang, laporan transaksi penjualan dan pembelian barang, laporan stock, laporan mutasi stock, laporan daftar customer, laporan piutang , laporan rekap umur piutang, laporan rugi laba dll. Software ini sudah dilengkapi dengan user password level sehingga hak akses user dalam mengoperasikan software bisa di control atau dibatasi yang bertujuan untuk menjaga kerahasiaan data yang semuanya sudah teritegrasi yang dikumpulkan dalam satu modul poin of sale.</p>\r\n\r\n<p>Selain itu keunggulan software ini sudah mencakup, pembayaran hutang, pembayaran piutang dan retur pembelian, retur penjualan barang , penjualan jasa dan software ini sudah dilengkapi dengan beberapa laporan-laporan yang bertujuan untuk mempermudah user dalam mengontrol data barang data â€“ data transaksi penjualan dan pembelian maupun retur barang secara baik. </p>');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(5) NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `bank` varchar(100) NOT NULL,
  `no_rekening` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `username` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `no_telp` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `level` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT 'customer',
  `aktif` enum('Y','N') COLLATE latin1_general_ci NOT NULL DEFAULT 'N',
  `id_session` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `alamat_lengkap` varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `email`, `no_telp`, `level`, `aktif`, `id_session`, `alamat_lengkap`) VALUES
('kasir', 'c7911af3adbd12a035b289556d96470a', 'Lucky Maulidia', 'luckymaulidia@gmail.com', '082244417498', 'customer', 'Y', 'c7911af3adbd12a035b289556d96470a', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indeks untuk tabel `costumer`
--
ALTER TABLE `costumer`
  ADD PRIMARY KEY (`id_costumer`);

--
-- Indeks untuk tabel `faktur`
--
ALTER TABLE `faktur`
  ADD PRIMARY KEY (`id_faktur`);

--
-- Indeks untuk tabel `kategori_produk`
--
ALTER TABLE `kategori_produk`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_orders`);

--
-- Indeks untuk tabel `orders_temp`
--
ALTER TABLE `orders_temp`
  ADD PRIMARY KEY (`id_orders_temp`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indeks untuk tabel `produk_pembelian`
--
ALTER TABLE `produk_pembelian`
  ADD PRIMARY KEY (`id_produk_pembelian`);

--
-- Indeks untuk tabel `return_produk`
--
ALTER TABLE `return_produk`
  ADD PRIMARY KEY (`id_return`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `costumer`
--
ALTER TABLE `costumer`
  MODIFY `id_costumer` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `faktur`
--
ALTER TABLE `faktur`
  MODIFY `id_faktur` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori_produk`
--
ALTER TABLE `kategori_produk`
  MODIFY `id_kategori` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `orders_temp`
--
ALTER TABLE `orders_temp`
  MODIFY `id_orders_temp` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=437;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk_pembelian`
--
ALTER TABLE `produk_pembelian`
  MODIFY `id_produk_pembelian` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `return_produk`
--
ALTER TABLE `return_produk`
  MODIFY `id_return` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(5) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
