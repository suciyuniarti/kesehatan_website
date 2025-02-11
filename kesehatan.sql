-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Feb 2025 pada 01.16
-- Versi server: 10.4.32-MariaDB-log
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kesehatan_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `image`, `category`, `author`, `created_at`, `user_id`) VALUES
(7, 'Pentingnya Gizi Seimbang untuk Kesehatan Tubuh', 'Gizi seimbang adalah kunci utama dalam menjaga kesehatan tubuh dan mencegah berbagai penyakit. Pola makan yang baik harus mencakup karbohidrat, protein, lemak sehat, vitamin, dan mineral dalam proporsi yang sesuai dengan kebutuhan tubuh.\\r\\nKarbohidrat dari nasi, roti, atau kentang berperan sebagai sumber energi utama. Protein dari daging, ikan, tahu, dan tempe membantu pertumbuhan serta perbaikan sel tubuh. Lemak sehat dari alpukat, kacang-kacangan, dan minyak zaitun penting untuk fungsi otak dan hormon. Selain itu, sayur dan buah kaya vitamin serta serat yang mendukung pencernaan dan sistem kekebalan tubuh.\\r\\nKurangnya gizi seimbang dapat menyebabkan berbagai masalah kesehatan, seperti obesitas, malnutrisi, hingga penyakit kronis seperti diabetes dan tekanan darah tinggi. Oleh karena itu, penting untuk mengonsumsi makanan dengan porsi yang tepat serta menjaga pola hidup sehat dengan rutin berolahraga dan minum air putih yang cukup.', 'uploads/1739209946_4.png', 'Gizi', 'Sastia', '2025-02-10 00:47:35', 0),
(11, 'Menjaga Kesehatan Mental di Tengah Kesibukan', 'Di era modern yang serba cepat, kesehatan mental sering kali terabaikan. Padahal, kesehatan mental yang baik sangat penting untuk menjaga keseimbangan hidup dan produktivitas sehari-hari.\\r\\n\\r\\nStres yang berlebihan, tekanan pekerjaan, serta tuntutan sosial bisa menjadi pemicu gangguan mental seperti kecemasan dan depresi. Oleh karena itu, penting bagi kita untuk mengenali tanda-tanda gangguan mental, seperti perubahan suasana hati yang drastis, sulit tidur, atau merasa lelah secara emosional.\\r\\n\\r\\nBeberapa cara sederhana untuk menjaga kesehatan mental adalah dengan menjaga pola tidur yang cukup, rutin berolahraga, mengonsumsi makanan sehat, dan meluangkan waktu untuk bersantai. Selain itu, berbicara dengan orang terdekat atau berkonsultasi dengan profesional juga dapat membantu mengatasi tekanan yang dirasakan.\\r\\n\\r\\nKesehatan mental sama pentingnya dengan kesehatan fisik. Dengan merawat diri sendiri dan mengenali kebutuhan emosional, kita dapat menjalani hidup yang lebih seimbang dan bahagia.', 'uploads/1739209889_5.png', 'Kesehatan Mental', 'Batari', '2025-02-10 17:38:42', 0),
(12, 'Manfaat Olahraga bagi Kesehatan Tubuh dan Pikiran', 'Olahraga merupakan aktivitas yang sangat penting untuk menjaga kesehatan tubuh dan pikiran. Dengan rutin berolahraga, seseorang dapat meningkatkan daya tahan tubuh, memperkuat otot dan tulang, serta menjaga berat badan ideal. Selain itu, olahraga juga memiliki manfaat besar bagi kesehatan mental, seperti mengurangi stres, meningkatkan mood, dan membantu tidur lebih nyenyak.\\r\\n\\r\\nBeberapa jenis olahraga yang mudah dilakukan sehari-hari antara lain berjalan kaki, berlari, bersepeda, dan berenang. Aktivitas fisik ini tidak hanya menyegarkan tubuh tetapi juga dapat meningkatkan konsentrasi dan produktivitas dalam menjalani aktivitas sehari-hari. Oleh karena itu, penting bagi setiap orang untuk meluangkan waktu berolahraga minimal 30 menit setiap hari demi kesehatan yang optimal.', 'uploads/1739209802_3.png', 'Olahraga', 'Fitri Ramada', '2025-02-10 17:50:02', 0),
(13, 'Pola Hidup Sehat: Kunci Kehidupan yang Lebih Baik', 'Pola hidup sehat adalah fondasi utama untuk menjaga tubuh tetap bugar dan meningkatkan kualitas hidup. Dengan menerapkan kebiasaan sehat setiap hari, kita dapat mencegah berbagai penyakit dan merasa lebih energik dalam menjalani aktivitas.\r\n\r\nBeberapa langkah sederhana dalam menerapkan pola hidup sehat meliputi konsumsi makanan bergizi seimbang, rutin berolahraga, tidur yang cukup, serta mengelola stres dengan baik. Menghindari kebiasaan buruk seperti merokok dan konsumsi alkohol berlebihan juga sangat penting untuk menjaga kesehatan jangka panjang.\r\n\r\nSelain itu, menjaga kebersihan diri dan lingkungan, serta rutin melakukan pemeriksaan kesehatan, dapat membantu mendeteksi potensi masalah kesehatan lebih dini. Dengan disiplin dan konsistensi dalam menerapkan pola hidup sehat, kita bisa menikmati hidup yang lebih produktif dan bahagia.', 'uploads/1739227529_pola hidup sehat.png', 'Pola Hidup Sehat', NULL, '2025-02-10 22:45:29', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@kesehatan.com', '$2y$10$4xNipbUI/plz62MR8WaESeypVupzTzJcIft9gUnKf/fsdtHQQDnYi', 'admin', '2025-01-30 21:16:53'),
(2, 'sastia', 'sastiangraeni@gmail.com', '$2y$10$i./bO7j4lRlg1VGyrCms0.whYk4cLSZQ79pJ/GAQi4PJbKdRqy7Au', 'user', '2025-02-10 14:43:20');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
