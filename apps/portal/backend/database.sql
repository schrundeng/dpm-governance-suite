-- DPM UB 2026 MySQL Schema

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS aspirasi (
    id VARCHAR(36) PRIMARY KEY,
    nama VARCHAR(120) NOT NULL,
    nim VARCHAR(30) NOT NULL,
    fakultas VARCHAR(120) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    judul VARCHAR(160) NOT NULL,
    isi TEXT NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS status_checks (
    id VARCHAR(36) PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    timestamp TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Default Admin: admin / admin123
INSERT INTO users (id, username, password, created_at) VALUES
(1, 'admin', '$2y$10$QO9H8bK6v8b2q9tZlKjR7uW1qUfI0nO1z0Yv6L2H5pG1s7e5v4u3a', NOW())
ON DUPLICATE KEY UPDATE id=id;

-- Seed Comprehensive Sample Aspirasi (All States & Categories)
INSERT INTO aspirasi (id, nama, nim, fakultas, kategori, judul, isi, status, created_at) VALUES
('a1b2c3d4-e5f6-4a1b-8c2d-000000000001', 'Ahmad Fauzan', '235150201111045', 'Fakultas Ilmu Komputer', 'Akademik', 'Transparansi Bobot Penilaian Ujian Akhir dan Tugas Besar', 'Mohon pertimbangan DPM untuk mengadvokasikan standardisasi transparansi komponen penilaian praktikum dan tugas besar pada portal SIAM, terutama untuk mahasiswa yang mengambil semester akselerasi.', 'pending', '2026-10-10 09:15:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000002', 'Nadia Putri Rahmawati', '225030200111019', 'Fakultas Ilmu Administrasi', 'Akademik', 'Perpanjangan Masa Batas Akhir Pembayaran UKT Semester Genap', 'Banyak rekan mahasiswa terdampak keterlambatan pencairan program magang MSIB. Kami memohon perpanjangan waktu pengajuan penundaan dan cicilan UKT minimal 14 hari kerja.', 'diproses', '2026-10-09 14:30:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000003', 'Dimas Surya Pratama', '215080100111032', 'Fakultas Perikanan dan Ilmu Kelautan', 'Akademik', 'Digitalisasi Pengajuan Bebas Pustaka dan Laboratorium', 'Usulan penyatuan berkas administrasi yudisium ke sistem terpadu agar mahasiswa tingkat akhir tidak perlu mengumpulkan tanda tangan fisik dari lab ke lab.', 'selesai', '2026-10-07 11:20:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000004', 'Siti Nurhaliza', '245060301111022', 'Fakultas Teknik', 'Fasilitas', 'Perbaikan Unit AC dan Ventilasi Gedung Kuliah Bersama (GKB)', 'Suhu di ruang kelas GKB lantai 4 sangat panas pada jam perkuliahan siang hari (13.00-15.00) dikarenakan dua unit pendingin ruangan tidak beroperasi selama dua pekan.', 'pending', '2026-10-12 10:45:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000005', 'Rian Hidayat', '235120101111088', 'Fakultas Kedokteran', 'Fasilitas', 'Optimalisasi Bandwidth Wi-Fi Kampus dan Stopkontak Gazebo', 'Koneksi eduroam dan UB-Wifi sering mengalami packet loss tinggi saat jam ujian daring. Mohon penambahan access point pada area ruang komunal dan gazebo.', 'diproses', '2026-10-08 16:10:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000006', 'Putri Larasati', '225090100111041', 'Fakultas MIPA', 'Fasilitas', 'Pencahayaan Jalur Pejalan Kaki Lingkar Dalam Kampus Malam Hari', 'Lampu penerangan jalan di jalur antara perpustakaan pusat menuju asrama mahasiswa padam, menimbulkan risiko keselamatan bagi mahasiswi yang pulang praktikum malam.', 'selesai', '2026-10-06 19:40:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000007', 'Bagas Wicaksono', '225040101111077', 'Fakultas Hukum', 'Kebijakan', 'Evaluasi Jam Malam Pembatasan Aktivitas Sekretariat Ormawa', 'Aturan pembatasan jam operasional sekretariat ormawa hingga pukul 21.00 WIB sangat menghambat persiapan delegasi lomba nasional. Kami mengusulkan dispensasi surat izin berkegiatan hingga pukul 23.00 WIB.', 'pending', '2026-10-11 13:00:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000008', 'Clara Stefani', '235020200111054', 'Fakultas Ekonomi dan Bisnis', 'Kebijakan', 'Standardisasi Mekanisme LPJ Dana Delegasi Prestasi Mahasiswa', 'Mekanisme reimbursement biaya pendaftaran lomba ilmiah mahasiswa seringkali memakan waktu lebih dari dua bulan. Diperlukan SOP percepatan verifikasi bukti transaksi tingkat rektorat.', 'diproses', '2026-10-09 08:30:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000009', 'Farhan Ramadhan', '245100100111015', 'Fakultas Teknologi Pertanian', 'Kesejahteraan', 'Subsidi Kuota Kantin Sehat bagi Mahasiswa Prasejahtera', 'Mengingat tingginya lonjakan harga bahan pangan di sekitar kos lingkar kampus, diharapkan DPM menginisiasi program voucher makan bergizi bekerjasama dengan Paguyuban Kantin Kampus.', 'diproses', '2026-10-08 11:15:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000010', 'Anindya Zahra', '235110101111063', 'Fakultas Ilmu Budaya', 'Kesejahteraan', 'Peningkatan Kuota Layanan Konseling Psikologis Kampus', 'Antrean konseling mahasiswa pada Pusat Layanan Konseling dan Disabilitas (PLKD) sangat padat dengan waiting list mencapai 3 minggu. Mohon penambahan jumlah konselor sebaya.', 'selesai', '2026-10-05 15:50:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000011', 'Kevin Ardiansyah', '245050100111090', 'Fakultas Peternakan', 'Lainnya', 'Penyediaan Shuttle Bus Kampus Ramah Mahasiswa Disabilitas', 'Fasilitas bus keliling kampus saat ini belum memiliki akses ramp untuk pengguna kursi roda. Mohon koordinasi dengan bagian sarpras rektorat untuk pengadaan unit bus inklusif.', 'pending', '2026-10-13 14:05:00'),
('a1b2c3d4-e5f6-4a1b-8c2d-000000000012', 'Zulfa Maulida', '225130100111012', 'Fakultas Kedokteran Gigi', 'Lainnya', 'Integrasi Kalender Sidang Parlemen ke Google Calendar Mahasiswa', 'Usulan integrasi publikasi agenda persidangan, dengar pendapat, dan uji publik peraturan kemahasiswaan ke platform kalender digital yang mudah disubscribe mahasiswa umum.', 'selesai', '2026-10-06 09:25:00')
ON DUPLICATE KEY UPDATE id=id;
