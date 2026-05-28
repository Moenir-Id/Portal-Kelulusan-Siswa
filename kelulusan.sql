-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 29, 2026 at 10:53 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kelulusan`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('pengumuman-kelulusan-cache-settings_all', 'a:14:{s:12:\"sekolah_logo\";s:49:\"logo/TFrDof4SExBh7aALUmbhMLOjo1KXc9DR4ojpD3Di.png\";s:12:\"sekolah_nama\";s:11:\"MA AL-AZHAR\";s:12:\"sekolah_npsn\";s:12:\"131235020036\";s:14:\"sekolah_alamat\";s:26:\"JL. MANGGA 11 RINGINPUTIH-\";s:12:\"sekolah_telp\";s:1:\"-\";s:13:\"sekolah_email\";N;s:15:\"sekolah_website\";N;s:16:\"pengumuman_judul\";N;s:16:\"pengumuman_aktif\";s:1:\"1\";s:15:\"countdown_aktif\";s:1:\"1\";s:15:\"countdown_waktu\";s:19:\"2026-03-18 08:45:00\";s:15:\"countdown_label\";N;s:13:\"pesan_sebelum\";N;s:13:\"pesan_sesudah\";N;}', 1773801293);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_create_siswas_table', 1),
(5, '2024_01_01_000002_create_settings_table', 2),
(6, '2026_03_19_000001_drop_jurusan_from_siswas', 3),
(7, '2026_03_19_000001_create_siswa_accounts_table', 4),
(8, '2026_03_19_000002_create_momen_table', 4),
(9, '2026_03_19_000003_add_foto_profil_to_siswas', 5),
(10, '2024_01_01_000001_add_plain_password_to_siswa_accounts', 6),
(11, 'xxxx_alter_siswas_add_lulus_bersyarat_status', 7),
(12, '2026_04_29_000001_add_last_login_to_accounts', 8);

-- --------------------------------------------------------

--
-- Table structure for table `momen`
--

CREATE TABLE `momen` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_account_id` bigint UNSIGNED NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1UkVXoK5lQlnT39hXKY1Q7oixVLnZWYo7iricsFU', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQllveU5XOXo5OXVOOW56WjhGd2VtanBVRFQ4UGczYUFKRXlJRmtpaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9rZWx1bHVzYW4udGVzdC9hZG1pbi9zZXR0aW5nIjtzOjU6InJvdXRlIjtzOjEzOiJhZG1pbi5zZXR0aW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NzM3OTY2NDY7fX0=', 1773797696),
('apkZpMPGtvJgLN1PwBpxS46APBpp0E6LzCLXvoRV', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNUpiakExS2lBbjIwOGdaTklQWnJTdG5UNlJ2SjFzNzRTWU9VVlZpUyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE1OiJhZG1pbi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc3Mzc4MTk3Mzt9fQ==', 1773786260),
('XGVUZFpR27YzZ6Y5fZhmmaDXOY2vsbxfDsxUP8e5', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibFJoOFBiQTNlTTc3WUZBdGhGNzl1OEY1cjBqMmRvWmFtOXFiTjY3RyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzczNzk2NDQ1O319', 1773796493);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'sekolah_logo', 'uploads/logo/logo_1777426247.png', '2026-03-17 14:24:08', '2026-04-29 01:30:47'),
(2, 'sekolah_nama', 'MAS Al-Azhar', '2026-03-17 14:24:08', '2026-04-29 01:57:09'),
(3, 'sekolah_npsn', '20584468', '2026-03-17 14:24:08', '2026-03-18 15:36:16'),
(4, 'sekolah_alamat', 'Jl. Mangga 11 Ringinputih, Sampung, Ponorogo', '2026-03-17 14:24:08', '2026-03-18 14:53:29'),
(5, 'sekolah_telp', NULL, '2026-03-17 14:24:08', '2026-04-28 13:09:25'),
(6, 'sekolah_email', 'maalazhar.ponorogo@gmail.com', '2026-03-17 14:24:08', '2026-04-28 13:09:25'),
(7, 'sekolah_website', 'https://ma-alazhar.maarifponorogo.sch.id/', '2026-03-17 14:24:08', '2026-04-28 09:30:52'),
(8, 'pengumuman_judul', NULL, '2026-03-17 14:24:08', '2026-03-17 14:24:08'),
(9, 'pengumuman_aktif', '1', '2026-03-17 14:24:08', '2026-03-17 14:24:08'),
(10, 'countdown_aktif', '1', '2026-03-17 14:24:08', '2026-03-17 18:23:31'),
(11, 'countdown_waktu', '2026-04-29 10:45:00', '2026-03-17 14:24:08', '2026-04-28 09:30:52'),
(12, 'countdown_label', NULL, '2026-03-17 14:24:08', '2026-03-17 14:24:08'),
(13, 'pesan_sebelum', NULL, '2026-03-17 14:24:08', '2026-03-17 14:24:08'),
(14, 'pesan_sesudah', NULL, '2026-03-17 14:24:08', '2026-03-17 14:24:08'),
(15, 'sekolah_instansi', NULL, '2026-03-18 21:49:15', '2026-03-18 21:49:15'),
(16, 'sekolah_nsm', '131235020036', '2026-03-18 21:49:15', '2026-03-18 15:36:16'),
(17, 'sekolah_akreditasi', 'Terakreditasi B', '2026-03-18 21:49:15', '2026-03-18 14:51:02'),
(18, 'kepala_nama', 'SUPRIYANTO, M.Pd', '2026-03-18 21:49:15', '2026-04-29 02:07:20'),
(19, 'kepala_nip', NULL, '2026-03-18 21:49:15', '2026-03-18 15:37:04'),
(20, 'format_nomor_surat', '{nomor}/SKL/MAAZ/{bulan}/{tahun}', '2026-03-18 22:31:03', '2026-03-18 15:32:26'),
(21, 'tahun_pelajaran', '2025/2026', '2026-03-19 05:17:55', '2026-03-18 22:22:31');

-- --------------------------------------------------------

--
-- Table structure for table `siswas`
--

CREATE TABLE `siswas` (
  `id` bigint UNSIGNED NOT NULL,
  `nisn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_lulus` year NOT NULL,
  `nilai_rata` decimal(5,2) DEFAULT NULL,
  `status` enum('LULUS','TIDAK LULUS','LULUS BERSYARAT') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `foto_profil` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswas`
--

INSERT INTO `siswas` (`id`, `nisn`, `nama`, `kelas`, `tahun_lulus`, `nilai_rata`, `status`, `catatan`, `foto_profil`, `created_at`, `updated_at`) VALUES
(1, '0075227239', 'FABIAN AMANDA RAHMADHANY', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0075227239_1777382574.jpg', '2026-04-28 13:21:17', '2026-04-28 13:22:54'),
(2, '0078292309', 'AZKA NIAM MUBARAK', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0078292309_1777382574.jpg', '2026-04-28 13:21:17', '2026-04-28 13:22:54'),
(3, '0078654976', 'HILMA ZULIATUL FAZA', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0078654976_1777382574.jpg', '2026-04-28 13:21:17', '2026-04-28 13:22:54'),
(4, '0081228460', 'RIFA ROHMANITA AINI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0081228460_1777382574.jpg', '2026-04-28 13:21:18', '2026-04-28 13:22:54'),
(5, '0083081762', 'SIKA MUAYYIDATIL FU ADAH', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0083081762_1777382574.jpg', '2026-04-28 13:21:18', '2026-04-28 13:22:54'),
(6, '0083462767', 'NISA\'UL MAHMUDAH', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0083462767_1777382574.jpg', '2026-04-28 13:21:18', '2026-04-28 13:22:54'),
(7, '0084778992', 'M. FAIZAL ARIFIN', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0084778992_1777382575.jpg', '2026-04-28 13:21:18', '2026-04-28 13:22:55'),
(8, '0076168816', '\'AFIF DHIYAURROHIM', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0076168816_1777382574.jpg', '2026-04-28 13:21:19', '2026-04-28 13:22:54'),
(9, '0081095349', 'AISYA AULIA FINATA', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0081095349_1777382574.jpg', '2026-04-28 13:21:19', '2026-04-28 13:22:54'),
(10, '0077774905', 'ALVEYA SEKAR DEALOVEVA', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0077774905_1777382574.jpg', '2026-04-28 13:21:19', '2026-04-28 13:22:54'),
(11, '0081532925', 'AMRINA MAULIDI HASANAH', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0081532925_1777382574.jpg', '2026-04-28 13:21:19', '2026-04-28 13:22:54'),
(12, '0072324688', 'ANGGA ALDI PRATAMA', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0072324688_1777382574.jpg', '2026-04-28 13:21:20', '2026-04-28 13:22:54'),
(13, '0087693007', 'ANGGA DWI NUGROHO', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0087693007_1777382575.jpg', '2026-04-28 13:21:20', '2026-04-28 13:22:55'),
(14, '0073719547', 'ANZAUN LAQIF IZDIHAR', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0073719547_1777382574.jpg', '2026-04-28 13:21:20', '2026-04-28 13:22:54'),
(15, '0088969161', 'ARYA ADINDRA DWI SAPUTRA', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0088969161_1777382574.jpg', '2026-04-28 13:21:20', '2026-04-28 13:22:54'),
(16, '0078103635', 'DENI SETYAWAN', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/siswa_0078103635_1777383770.jpg', '2026-04-28 13:21:21', '2026-04-28 13:42:50'),
(17, '0073587458', 'ERLIN KARTIKA SARI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0073587458_1777382574.jpg', '2026-04-28 13:21:21', '2026-04-28 13:22:54'),
(18, '0071358815', 'FRENDI KURNIAWAN', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0071358815_1777382574.jpg', '2026-04-28 13:21:21', '2026-04-28 13:22:54'),
(19, '0086968282', 'ILHAM RAFA SADHIYA', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0086968282_1777382575.jpg', '2026-04-28 13:21:21', '2026-04-28 13:22:55'),
(20, '0079347886', 'KURNIA AGUNG ROMDHANI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0079347886_1777382574.jpg', '2026-04-28 13:21:22', '2026-04-28 13:22:54'),
(21, '0072978150', 'LUTFI FAJAR ARDIANSYAH', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0072978150_1777382574.jpg', '2026-04-28 13:21:22', '2026-04-28 13:22:54'),
(22, '0087528725', 'M. LUTHFAN AGIL NUHA NAFI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0087528725_1777382575.jpg', '2026-04-28 13:21:22', '2026-04-28 13:22:55'),
(23, '0085262043', 'MARBELIA INDAH DEWANTARI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0085262043_1777382575.jpg', '2026-04-28 13:21:23', '2026-04-28 13:22:55'),
(24, '0067479909', 'MUHAMAD FIRDAUS', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0067479909_1777382574.jpg', '2026-04-28 13:21:23', '2026-04-28 13:22:54'),
(25, '0064263322', 'MUHAMMAD FARIS MUZAKI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0064263322_1777382574.jpg', '2026-04-28 13:21:23', '2026-04-28 13:22:54'),
(26, '0077334683', 'MUHAMMAD SHOLIKIN', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0077334683_1777382574.jpg', '2026-04-28 13:21:23', '2026-04-28 13:22:54'),
(27, '0077008974', 'MUHAMMAD ULUL ABSHOR', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0077008974_1777382574.jpg', '2026-04-28 13:21:24', '2026-04-28 13:22:54'),
(28, '0086810936', 'MUHAMMAD YAZID FADHLULLOH', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0086810936_1777382575.jpg', '2026-04-28 13:21:24', '2026-04-28 13:22:55'),
(29, '0072178155', 'RENDY CHANDRA ADITAMA PUTRA', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0072178155_1777382574.jpg', '2026-04-28 13:21:24', '2026-04-28 13:22:54'),
(30, '0077245739', 'RIZKI AZHARI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0077245739_1777382574.jpg', '2026-04-28 13:21:24', '2026-04-28 13:22:54'),
(31, '0059398619', 'SILVINA AYUNDA SARI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0059398619_1777382574.jpg', '2026-04-28 13:21:25', '2026-04-28 13:22:54'),
(32, '0079633515', 'SITI ASHFIYATUL MAROM', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0079633515_1777382574.jpg', '2026-04-28 13:21:25', '2026-04-28 13:22:54'),
(33, '0072858762', 'SITI ROFFATUL JANAH', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0072858762_1777382574.jpg', '2026-04-28 13:21:25', '2026-04-28 13:22:54'),
(34, '0084591901', 'WAHYU SAPUTRA', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0084591901_1777382574.jpg', '2026-04-28 13:21:25', '2026-04-28 13:22:54'),
(35, '0076273798', 'WILDAN ROFIUL HIDAYAT', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0076273798_1777382574.jpg', '2026-04-28 13:21:26', '2026-04-28 13:22:54'),
(36, '0088073251', 'ZAHRA AYUDYA SETYAN TORO PUTRI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0088073251_1777382575.jpg', '2026-04-28 13:21:26', '2026-04-28 13:22:55'),
(37, '0072935529', 'ZAHRO DWI DAMAYATI', 'XII', '2026', NULL, 'LULUS', '', 'uploads/foto-profil/0072935529_1777382574.jpg', '2026-04-28 13:21:26', '2026-04-28 13:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `siswa_accounts`
--

CREATE TABLE `siswa_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `nisn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `plain_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_count` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswa_accounts`
--

INSERT INTO `siswa_accounts` (`id`, `siswa_id`, `nisn`, `password`, `plain_password`, `remember_token`, `last_login_at`, `last_login_ip`, `login_count`, `created_at`, `updated_at`) VALUES
(1, 1, '0075227239', '$2y$12$CXCo1qqn4c.QYv.W339zMOToc5GnkBBaNTi8S4vK9TVx84GYX6Zca', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:17', '2026-04-28 13:21:17'),
(2, 2, '0078292309', '$2y$12$p/apVAW5jJGC4FjMTTGfYuUmVEI1R84akoqDbAAWQWXeQPywB7wA6', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:17', '2026-04-28 13:21:17'),
(3, 3, '0078654976', '$2y$12$NDCZOWqg74J33.WYoHxwpOCR52iQDZgphWmQTFmEKxn5IpBeOI0pi', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:18', '2026-04-28 13:21:18'),
(4, 4, '0081228460', '$2y$12$h23cobLM0aoQ36evMvDYzeLjWjQ/3lspzSNkrSySUcP1UQfcVhsAi', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:18', '2026-04-28 13:21:18'),
(5, 5, '0083081762', '$2y$12$pPaNCgsM4vm9mc3SQv5AkueSN4keafazdnBvxoIHc6u1wZ3hyoYUO', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:18', '2026-04-28 13:21:18'),
(6, 6, '0083462767', '$2y$12$RtOg.cj2eRtxhTJ1iJ37R.XLSf82NrxoLGUtsPVXA2G/xJh2fcd2y', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:18', '2026-04-28 13:21:18'),
(7, 7, '0084778992', '$2y$12$bxtSRJNpwtqOlSo9KrAXtO7JHY21Z6g1C9hrUESteviNsCHnlpOy2', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:19', '2026-04-28 13:21:19'),
(8, 8, '0076168816', '$2y$12$XZ796qE7qhQTzT3DSI8hRu4U2T2GWjR/eHgosPi/6wo6KfEoBjJIe', 'maazjaya', NULL, '2026-04-29 08:09:53', '127.0.0.1', 1, '2026-04-28 13:21:19', '2026-04-29 08:09:53'),
(9, 9, '0081095349', '$2y$12$Ft8JREqXUThjXImeinsZGuva8kgau7EDoUIziOxyOSzMsPnatFzpa', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:19', '2026-04-28 13:21:19'),
(10, 10, '0077774905', '$2y$12$.Fr5DRhHPHSc6x6lZHLAkuiGh3KCLjdFDojYZ8wTGCZrYzQ3nEmkS', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:19', '2026-04-28 13:21:19'),
(11, 11, '0081532925', '$2y$12$dtvjeDEkFmm2TVHCiD0SQOgC5Xdv61yFTrdeShzfFiGejswjC6hTG', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:20', '2026-04-28 13:21:20'),
(12, 12, '0072324688', '$2y$12$yS6Ce2.p9H9eE8Y3P9q0NujCBt0sVeb4YX.yaRV/v9dDohs2yADhy', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:20', '2026-04-28 13:21:20'),
(13, 13, '0087693007', '$2y$12$OLHib1P0FGKH4/2wK64SsuhjqBjM84Joqug7Yf9WTT8Q7TOPJeVdm', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:20', '2026-04-28 13:21:20'),
(14, 14, '0073719547', '$2y$12$.d9HG.IgnRlZnPt80InRlOM2DuNj3sp7VsTIMuejejz5JosJhA/OS', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:20', '2026-04-28 13:21:20'),
(15, 15, '0088969161', '$2y$12$eaCOKNRDbWQ6KYpD.h2jh.xR6VjiHAdq8h2VGXG2F1vKNX77c9PuG', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:21', '2026-04-28 13:21:21'),
(16, 16, '0078103635', '$2y$12$mCETjjhqNoBBfx8thFLui.EPy5ljcIZcsfDJFTsAqUaNqozXTvmbu', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:21', '2026-04-28 13:21:21'),
(17, 17, '0073587458', '$2y$12$SQbNaNwzWmDv8ZFne5T7K.vXdlCp80glZEj0BKORKlZMFj4AsX9nG', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:21', '2026-04-28 13:21:21'),
(18, 18, '0071358815', '$2y$12$DooMNXtfQDVoav6pcgtXSe2kxUDlm3tWzDzfIcRVxUb6xyzQ5GVmK', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:21', '2026-04-28 13:21:21'),
(19, 19, '0086968282', '$2y$12$AyCUvhfCbVkA3p7G8aYvO.cd3/bsgjB6tfspVNY8XI1lf38G5/z7a', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:22', '2026-04-28 13:21:22'),
(20, 20, '0079347886', '$2y$12$cy.G8vQpX8oxqIWuoTk8AOASzFBmv8B4HXqhOspuQc4nNugac9SMe', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:22', '2026-04-28 13:21:22'),
(21, 21, '0072978150', '$2y$12$I5ATxa//vTdCmJPJ6nzl6ed6j/uF6ZDAm93gPyguwq1.gG/pPw28O', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:22', '2026-04-28 13:21:22'),
(22, 22, '0087528725', '$2y$12$z8iWkGOQJ.NmjHrlNTdGke.5H/BtHbIxO8nszyeOOsoBjh9nTuGgS', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:23', '2026-04-28 13:21:23'),
(23, 23, '0085262043', '$2y$12$In5k4Gwu2CpQfNk.9J7SP.Gvbu6ce0amH5dWrYrYG1DpL9er8HEGe', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:23', '2026-04-28 13:21:23'),
(24, 24, '0067479909', '$2y$12$.iQzHXCvgY9ifYbNjTdQ..hHIXbwVtqQAAdDsoIatcD/Ym7.2d09m', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:23', '2026-04-28 13:21:23'),
(25, 25, '0064263322', '$2y$12$jl0JuIyjjo1M63HVRSukJOFc4F3UaMmigNQZvrt78voQjY8IUtFWG', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:23', '2026-04-28 13:21:23'),
(26, 26, '0077334683', '$2y$12$Y4bALRqcG2GGcwCS9YNlSernV9i.JJZdOtJuYsuXBSKis5jbqxZSe', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:24', '2026-04-28 13:21:24'),
(27, 27, '0077008974', '$2y$12$IXInpb8PF9EtZwGlOmtCse1k90JNOfUafiw5v.8DH2dyjNuAfekxW', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:24', '2026-04-28 13:21:24'),
(28, 28, '0086810936', '$2y$12$1I33YhFaOnLd0GJKzW.l4uej4yRPOj.M8jRFvCZ/jvIT8codcYnOu', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:24', '2026-04-28 13:21:24'),
(29, 29, '0072178155', '$2y$12$rlelkope9leAJYiPW9JUd.Xlu3fT1Ppggh8TRA23.U.LcZexfdUju', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:24', '2026-04-28 13:21:24'),
(30, 30, '0077245739', '$2y$12$m5eOAhXRMU9CV4Hvc/Unrel6yQNei8Fc0r9wo2cZoSnJdDUHDB53G', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:25', '2026-04-28 13:21:25'),
(31, 31, '0059398619', '$2y$12$XsbRo/XeQZG/Ze.6UeL9Ke5tZCFVOVUsekjR1JU9poHo4EglT49BW', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:25', '2026-04-28 13:21:25'),
(32, 32, '0079633515', '$2y$12$tsgxk88iyNUG48h/ERo7zuyTcp1dLuu6n1gNA7KRXxBPBOi55PJei', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:25', '2026-04-28 13:21:25'),
(33, 33, '0072858762', '$2y$12$2soLkYAGpvw7b/qVGvLNf.L.zyoCtSbXOtsMauMWY4650svHSdQz6', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:25', '2026-04-28 13:21:25'),
(34, 34, '0084591901', '$2y$12$6W2lQF9eq.NlLV04zjOqZuaMCmzAtfMcetDT.xUPsnHJaSQOBFjc.', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:26', '2026-04-28 13:21:26'),
(35, 35, '0076273798', '$2y$12$cN.tcXg4NLR17ZW/q5YdUeQbLUrAY.MKt9JnpKM6oK8t9V2W/tfga', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:26', '2026-04-28 13:21:26'),
(36, 36, '0088073251', '$2y$12$8I10R88qxFZzyo/eSO6iKe0aKaDzyU3AibuObHyR2XMehYteTfmoq', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:26', '2026-04-28 13:21:26'),
(37, 37, '0072935529', '$2y$12$kWsyNVRMxaOM3Vgh3AYOw.7s3t5cLtttqsXFNbGYWJnT2MUH74DZ6', 'maazjaya', NULL, NULL, NULL, 0, '2026-04-28 13:21:26', '2026-04-28 13:21:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_count` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `last_login_at`, `last_login_ip`, `login_count`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@sekolah.com', NULL, '$2y$12$u0IArm6aAM61Lwf7JjlMw.0CZdFRFXskI5cBh6mpWeffxoIoHoiS6', NULL, NULL, NULL, 0, '2026-03-17 08:53:44', '2026-03-17 08:53:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `momen`
--
ALTER TABLE `momen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `momen_siswa_account_id_foreign` (`siswa_account_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `siswas`
--
ALTER TABLE `siswas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswas_nisn_unique` (`nisn`);

--
-- Indexes for table `siswa_accounts`
--
ALTER TABLE `siswa_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswa_accounts_nisn_unique` (`nisn`),
  ADD KEY `siswa_accounts_siswa_id_foreign` (`siswa_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `momen`
--
ALTER TABLE `momen`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `siswas`
--
ALTER TABLE `siswas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `siswa_accounts`
--
ALTER TABLE `siswa_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `momen`
--
ALTER TABLE `momen`
  ADD CONSTRAINT `momen_siswa_account_id_foreign` FOREIGN KEY (`siswa_account_id`) REFERENCES `siswa_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `siswa_accounts`
--
ALTER TABLE `siswa_accounts`
  ADD CONSTRAINT `siswa_accounts_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
