-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table laravel_kas.akun_kas: ~0 rows (approximately)
REPLACE INTO `akun_kas` (`id`, `nama_akun`, `tampil`) VALUES
	(4, 'CV BRI 331203120958432', 'y');

-- Dumping data for table laravel_kas.cache: ~6 rows (approximately)
REPLACE INTO `cache` (`id`, `key`, `value`, `expiration`) VALUES
	(406, 'gunawan|127.0.0.1:timer', 'i:1741755426;', '1741755426'),
	(407, 'gunawan|127.0.0.1', 'i:1;', '1741755426'),
	(628, 'administrator|127.0.0.1:timer', 'i:1743738600;', '1743738601'),
	(629, 'administrator|127.0.0.1', 'i:1;', '1743738601'),
	(652, '|127.0.0.1:timer', 'i:1743738695;', '1743738695'),
	(653, '|127.0.0.1', 'i:1;', '1743738695');

-- Dumping data for table laravel_kas.categories: ~2 rows (approximately)
REPLACE INTO `categories` (`id`, `name`, `kategori`, `created_at`, `updated_at`) VALUES
	(1, 'Pemasukan', 'income', NULL, NULL),
	(2, 'Pengeluaran', 'expense', NULL, NULL);

-- Dumping data for table laravel_kas.categori_suppliers: ~0 rows (approximately)
REPLACE INTO `categori_suppliers` (`id`, `kategori`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'Supplier', 'CV ADI PERKASA', NULL, NULL);

-- Dumping data for table laravel_kas.kas_bank: ~2 rows (approximately)
REPLACE INTO `kas_bank` (`id`, `tanggal_bukti`, `nama_akun`, `from`, `nomor_bukti`, `from_account_id`, `to_account_id`, `subcategories_id`, `kategori`, `jumlah`, `nama_pelanggan`, `nama_sales_utang`, `keterangan`, `nama_user`, `tanggal_log`) VALUES
	(1, '2025-03-12 13:13:50', 'CV BRI 331203120958432', 'Kas', NULL, NULL, NULL, 1, 'Gaji', 3260000, NULL, NULL, 'Gaji Pokok', 'Gunawan Halim', '2025-03-12 12:56:52'),
	(2, '2025-03-12 13:14:14', 'CV BRI 331203120958432', 'Kas', NULL, NULL, NULL, 1, 'Gaji', 650000, NULL, NULL, 'Casual 5 Hari', 'Gunawan Halim', '2025-03-12 12:56:52'),
	(3, '2025-03-12 13:14:38', 'CV BRI 331203120958432', 'Kas', NULL, NULL, NULL, 2, 'Biaya Hidup', -1000000, NULL, NULL, 'Tabungan Bulanan', 'Gunawan Halim', '2025-03-12 12:56:52');

-- Dumping data for table laravel_kas.migrations: ~0 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '2024_03_27_231100_create_roles_permission_table', 1),
	(3, '2024_05_12_061729_create_categori_suppliers_table', 1),
	(4, '2024_05_17_065821_create_akun_kas_table', 1),
	(5, '2024_05_17_070040_create_categories_table', 1),
	(6, '2024_05_17_070041_create_subcategories_table', 1),
	(7, '2024_05_17_070056_create_pelanggan_table', 1),
	(8, '2024_05_17_070202_create_penjualan_table', 1),
	(9, '2024_05_17_071011_create_kas_bank_table', 1),
	(10, '2024_05_17_071016_create_piutang_table', 1),
	(11, '2024_07_05_160252_create_pembelian_table', 1),
	(12, '2024_07_05_160916_create_utang_table', 1),
	(13, '2024_07_08_030852_create_pelanggan_pembeli_table', 1),
	(14, '2024_07_13_144816_create_transfer_logs_table', 1);

-- Dumping data for table laravel_kas.password_reset_tokens: ~0 rows (approximately)

-- Dumping data for table laravel_kas.pelanggan: ~0 rows (approximately)

-- Dumping data for table laravel_kas.pelanggan_pembeli: ~0 rows (approximately)

-- Dumping data for table laravel_kas.pembelian: ~0 rows (approximately)

-- Dumping data for table laravel_kas.penjualan: ~0 rows (approximately)

-- Dumping data for table laravel_kas.piutang: ~0 rows (approximately)

-- Dumping data for table laravel_kas.roles_permission: ~0 rows (approximately)

-- Dumping data for table laravel_kas.sessions: ~2 rows (approximately)
REPLACE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('UUVf8LzcxvmGGU5i7vOayKwHhResOKKDCb3b4s84', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQUI0WjVBYkZlODROVU84OEhOUmxJc3I0TVN6amREcEh0Y0g1WkFqWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL2Rpc2FibGVkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxNDoicmVtZW1iZXJfdG9rZW4iO3M6NDA6IktQZ2VZRDEySVpoYUUyWUNWd1NTcEJzVlBCM3pVYVMya0NhWHVRTTAiO3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1743738694),
	('wW3Wqu0q2mmeKHXI0xMt2c11hgfxwLYlOWuDhmAK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiall3VWtoMXBSWW5YVWJCUGhKZFdpWkFKUFQxU2Nsa1BXOXFEZ01XViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1743736559);

-- Dumping data for table laravel_kas.subcategories: ~2 rows (approximately)
REPLACE INTO `subcategories` (`id`, `kategori_id`, `name`) VALUES
	(1, 1, 'Gaji'),
	(2, 2, 'Biaya Hidup');

-- Dumping data for table laravel_kas.transfer_logs: ~0 rows (approximately)

-- Dumping data for table laravel_kas.users: ~4 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `role`, `status_aktif`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `tanggal_login`, `created_at`, `updated_at`) VALUES
	(1, 'Admin A', 'Admin', '0', 'Administrator', 'adminuser@gmail.com', '2025-03-11 23:48:23', '21232f297a57a5a743894a0e4a801fc3', 'KPgeYD12IZhaE2YCVwSSpBsVPB3zUaS2kCaXuQM0', '2025-04-04 11:51:30', NULL, '2025-04-04 03:49:59'),
	(2, 'Jerry', 'Manager', '1', 'Jerry', 'jerryuser@gmail.com', '2025-03-11 23:48:23', '21232f297a57a5a743894a0e4a801fc3', NULL, NULL, NULL, NULL),
	(3, 'Gunawan Halim', 'Owner', '1', 'Gunawan', 'testing123@gmail.com', '2025-03-11 23:48:23', '21232f297a57a5a743894a0e4a801fc3', NULL, '2025-04-04 11:38:18', NULL, '2025-03-12 04:57:05');

-- Dumping data for table laravel_kas.utang: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
