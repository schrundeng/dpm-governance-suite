-- DPM Budget System - Database Schema & Seed
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `komisi` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Accounts:
-- Admin: admin@example.com / admin123
-- User:  user@example.com / admin123
INSERT INTO `users` (`id`, `nama`, `komisi`, `email`, `password`, `role`, `is_active`, `created_at`) VALUES
(1, 'Administrator', NULL, 'admin@example.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'admin', 1, NOW()),
(2, 'Mahasiswa Delegasi', 'Komisi 1', 'user@example.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'user', 1, NOW());

-- --------------------------------------------------------
-- Table: dokumen
-- --------------------------------------------------------
DROP TABLE IF EXISTS `dokumen`;
CREATE TABLE `dokumen` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pengajuan` varchar(20) DEFAULT NULL,
  `user_id` int NOT NULL,
  `judul` varchar(200) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `nama_asli` varchar(255) NOT NULL,
  `jenis` enum('RAB','LPJ') NOT NULL,
  `status` enum('dikirim','in-review','diterima','ditolak') DEFAULT 'dikirim',
  `keterangan` text,
  `catatan_revisi` text,
  `file_feedback` varchar(255) DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_pengajuan` (`id_pengajuan`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `dokumen_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Dummy Documents (Covering all status & archive states):
INSERT INTO `dokumen` (`id`, `id_pengajuan`, `user_id`, `judul`, `nama_file`, `nama_asli`, `jenis`, `status`, `keterangan`, `catatan_revisi`, `file_feedback`, `is_archived`, `created_at`) VALUES
(1, 'DPM-2026-0001', 2, 'RAB Pelatihan Legislatif Mahasiswa 2026', 'budget_sample.pdf', 'RAB_Pelatihan_2026.pdf', 'RAB', 'dikirim', 'Pengajuan dana operasional dan konsumsi kegiatan pelatihan', NULL, NULL, 0, NOW()),
(2, 'DPM-2026-0002', 2, 'RAB Studi Banding Ormawa 2026', 'budget_sample.pdf', 'RAB_Studi_Banding_2026.pdf', 'RAB', 'in-review', 'Berkas sedang diverifikasi oleh Badan Anggaran', NULL, NULL, 0, NOW()),
(3, 'DPM-2026-0003', 2, 'LPJ Musyawarah Kerja Ormawa 2026', 'budget_sample.pdf', 'LPJ_Musker_2026.pdf', 'LPJ', 'diterima', 'Laporan pertanggungjawaban lengkap beserta bukti kuitansi', '', 'budget_sample.pdf', 0, NOW()),
(4, 'DPM-2026-0004', 2, 'RAB Pengadaan Sound System & Atribut', 'budget_sample.pdf', 'RAB_Pengadaan_2026.pdf', 'RAB', 'ditolak', 'Pengajuan dana inventaris sound system', 'Belum melampirkan minimal 2 surat pembanding harga vendor resmi. Silakan revisi lampiran anggaran.', NULL, 0, NOW()),
(5, 'DPM-2026-0005', 2, 'LPJ Sidang Pleno Awal Tahun 2026', 'budget_sample.pdf', 'LPJ_Sidang_Pleno_2026.pdf', 'LPJ', 'diterima', 'Dokumen anggaran periode sebelumnya', '', 'budget_sample.pdf', 1, DATE_SUB(NOW(), INTERVAL 20 DAY));

-- --------------------------------------------------------
-- Table: auth_tokens
-- --------------------------------------------------------
DROP TABLE IF EXISTS `auth_tokens`;
CREATE TABLE `auth_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: activity_log
-- --------------------------------------------------------
DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE `activity_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `dokumen_id` int DEFAULT NULL,
  `aksi` varchar(100) NOT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `dokumen_id` (`dokumen_id`),
  CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `activity_log_ibfk_2` FOREIGN KEY (`dokumen_id`) REFERENCES `dokumen` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: approval
-- --------------------------------------------------------
DROP TABLE IF EXISTS `approval`;
CREATE TABLE `approval` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dokumen_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `status` enum('dikirim','in-review','diterima','ditolak') DEFAULT 'in-review',
  `catatan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `dokumen_id` (`dokumen_id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `approval_ibfk_1` FOREIGN KEY (`dokumen_id`) REFERENCES `dokumen` (`id`) ON DELETE CASCADE,
  CONSTRAINT `approval_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity_log` (`user_id`, `dokumen_id`, `aksi`, `keterangan`, `created_at`) VALUES
(2, 1, 'upload', 'Upload dokumen: RAB Pelatihan Legislatif Mahasiswa 2026', NOW()),
(2, 2, 'upload', 'Upload dokumen: RAB Studi Banding Ormawa 2026', NOW()),
(1, 2, 'review', 'Status diubah ke in-review', NOW()),
(2, 3, 'upload', 'Upload dokumen: LPJ Musyawarah Kerja Ormawa 2026', NOW()),
(1, 3, 'diterima', 'Dokumen diterima dengan catatan approval', NOW()),
(2, 4, 'upload', 'Upload dokumen: RAB Pengadaan Sound System & Atribut', NOW()),
(1, 4, 'ditolak', 'Dokumen ditolak: perbaikan perbandingan vendor', NOW());

INSERT INTO `approval` (`dokumen_id`, `admin_id`, `status`, `catatan`, `created_at`) VALUES
(2, 1, 'in-review', 'Sedang diverifikasi rincian biaya konsumsi', NOW()),
(3, 1, 'diterima', 'Semua lampiran kuitansi valid dan sesuai anggaran', NOW()),
(4, 1, 'ditolak', 'Belum melampirkan minimal 2 surat pembanding harga vendor resmi', NOW());

SET FOREIGN_KEY_CHECKS = 1;
