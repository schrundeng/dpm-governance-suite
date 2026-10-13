CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(256) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    is_active TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) UNIQUE NOT NULL
);

INSERT IGNORE INTO categories (nama) VALUES ('UU'), ('PP'), ('Perpres'), ('Perpu');

CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor VARCHAR(50) NOT NULL,
    tahun INT NOT NULL,
    tentang TEXT NOT NULL,
    jenis VARCHAR(50) NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    nama_asli VARCHAR(255) NOT NULL,
    keterangan TEXT NULL,
    is_pinned TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS document_relations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_id INT NOT NULL,
    related_id INT NOT NULL,
    tipe_relasi VARCHAR(50) NOT NULL,
    FOREIGN KEY (document_id) REFERENCES documents (id) ON DELETE CASCADE,
    FOREIGN KEY (related_id) REFERENCES documents (id) ON DELETE CASCADE
);

-- Seed Sample Statutes
INSERT IGNORE INTO documents (id, nomor, tahun, tentang, jenis, nama_file, nama_asli, keterangan, is_pinned, created_at) VALUES
(1, '04/DPM/UN/2026', 2026, 'Tata Tertib Persidangan dan Mekanisme Pengawasan Organisasi Kemahasiswaan', 'UU', 'legislative_sample.pdf', 'UU_DPM_04_2026_Tata_Tertib.pdf', 'Peraturan resmi tata tertib persidangan dan pengawasan ormawa Universitas Nusantara', 1, NOW()),
(2, '02/PP/DPM-UN/2026', 2026, 'Standarisasi Format Pelaporan Keuangan dan Verifikasi LPJ Kemahasiswaan', 'PP', 'pp_sample.pdf', 'PP_02_2026_Format_LPJ.pdf', 'Petunjuk teknis pelaksanaan pelaporan pertanggungjawaban keuangan ormawa', 0, NOW()),
(3, '01/PERPRES/EM-UN/2026', 2026, 'Struktur Organisasi dan Tata Kerja Eksekutif Mahasiswa Periode 2026', 'Perpres', 'perpres_sample.pdf', 'Perpres_01_2026_SOTK_EM.pdf', 'Ketetapan Presiden Mahasiswa mengenai susunan kementerian dan birokrasi kabinet', 0, NOW()),
(4, '01/PERPU/DPM-UN/2025', 2025, 'Mekanisme Keadaan Mendesak dan Penyelenggaraan Pemilihan Raya Mahasiswa Daring', 'Perpu', 'perpu_sample.pdf', 'Perpu_01_2025_Pemira_Daring.pdf', 'Peraturan darurat transisi penyelenggaraan pemira sistem pemungutan elektronik', 0, NOW());

-- Seed Sample Document Relation (PP No. 02 Melaksanakan UU No. 04)
INSERT IGNORE INTO document_relations (id, document_id, related_id, tipe_relasi) VALUES
(1, 2, 1, 'Melaksanakan');
