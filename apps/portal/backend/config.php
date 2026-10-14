<?php
date_default_timezone_set('Asia/Jakarta');

// Read .env file manually
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // skip comments
        if (strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value);
        if (!empty($key)) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Database config
$host = $_ENV['DB_HOST'] ?? 'db';
$db   = $_ENV['DB_NAME'] ?? 'dpm_portal';
$user = $_ENV['DB_USER'] ?? 'admin';
$pass = $_ENV['DB_PASSWORD'] ?? 'admin';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Self-healing database: automatically initialize schema if not present
    $tablesNeeded = ['users', 'aspirasi', 'status_checks'];
    $initializeSchema = false;

    foreach ($tablesNeeded as $table) {
        try {
            $pdo->query("SELECT 1 FROM $table LIMIT 1");
        } catch (Exception $e) {
            $initializeSchema = true;
            break;
        }
    }

    if ($initializeSchema) {
        $sqlPath = __DIR__ . '/database.sql';
        if (file_exists($sqlPath)) {
            $sql = file_get_contents($sqlPath);
            $pdo->exec($sql);
        }
    }

    // Seed default admin: admin / admin123 if users table is empty
    $stmtAdmin = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmtAdmin->fetchColumn() == 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $insert->execute(['admin', $hashedPassword]);
    }

    // Seed comprehensive dummy aspirasi for all states and categories if empty
    $stmtAspirasi = $pdo->query("SELECT COUNT(*) FROM aspirasi");
    if ($stmtAspirasi->fetchColumn() == 0) {
        $dummyAspirasi = [
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000001',
                'nama' => 'Ahmad Fauzan',
                'nim' => '235150201111045',
                'fakultas' => 'Fakultas Ilmu Komputer',
                'kategori' => 'Akademik',
                'judul' => 'Transparansi Bobot Penilaian Ujian Akhir dan Tugas Besar',
                'isi' => 'Mohon pertimbangan DPM untuk mengadvokasikan standardisasi transparansi komponen penilaian praktikum dan tugas besar pada portal SIAM, terutama untuk mahasiswa yang mengambil semester akselerasi.',
                'status' => 'pending',
                'created_at' => '2026-10-10 09:15:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000002',
                'nama' => 'Nadia Putri Rahmawati',
                'nim' => '225030200111019',
                'fakultas' => 'Fakultas Ilmu Administrasi',
                'kategori' => 'Akademik',
                'judul' => 'Perpanjangan Masa Batas Akhir Pembayaran UKT Semester Genap',
                'isi' => 'Banyak rekan mahasiswa terdampak keterlambatan pencairan program magang MSIB. Kami memohon perpanjangan waktu pengajuan penundaan dan cicilan UKT minimal 14 hari kerja.',
                'status' => 'diproses',
                'created_at' => '2026-10-09 14:30:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000003',
                'nama' => 'Dimas Surya Pratama',
                'nim' => '215080100111032',
                'fakultas' => 'Fakultas Perikanan dan Ilmu Kelautan',
                'kategori' => 'Akademik',
                'judul' => 'Digitalisasi Pengajuan Bebas Pustaka dan Laboratorium',
                'isi' => 'Usulan penyatuan berkas administrasi yudisium ke sistem terpadu agar mahasiswa tingkat akhir tidak perlu mengumpulkan tanda tangan fisik dari lab ke lab.',
                'status' => 'selesai',
                'created_at' => '2026-10-07 11:20:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000004',
                'nama' => 'Siti Nurhaliza',
                'nim' => '245060301111022',
                'fakultas' => 'Fakultas Teknik',
                'kategori' => 'Fasilitas',
                'judul' => 'Perbaikan Unit AC dan Ventilasi Gedung Kuliah Bersama (GKB)',
                'isi' => 'Suhu di ruang kelas GKB lantai 4 sangat panas pada jam perkuliahan siang hari (13.00-15.00) dikarenakan dua unit pendingin ruangan tidak beroperasi selama dua pekan.',
                'status' => 'pending',
                'created_at' => '2026-10-12 10:45:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000005',
                'nama' => 'Rian Hidayat',
                'nim' => '235120101111088',
                'fakultas' => 'Fakultas Kedokteran',
                'kategori' => 'Fasilitas',
                'judul' => 'Optimalisasi Bandwidth Wi-Fi Kampus dan Stopkontak Gazebo',
                'isi' => 'Koneksi eduroam dan UB-Wifi sering mengalami packet loss tinggi saat jam ujian daring. Mohon penambahan access point pada area ruang komunal dan gazebo.',
                'status' => 'diproses',
                'created_at' => '2026-10-08 16:10:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000006',
                'nama' => 'Putri Larasati',
                'nim' => '225090100111041',
                'fakultas' => 'Fakultas MIPA',
                'kategori' => 'Fasilitas',
                'judul' => 'Pencahayaan Jalur Pejalan Kaki Lingkar Dalam Kampus Malam Hari',
                'isi' => 'Lampu penerangan jalan di jalur antara perpustakaan pusat menuju asrama mahasiswa padam, menimbulkan risiko keselamatan bagi mahasiswi yang pulang praktikum malam.',
                'status' => 'selesai',
                'created_at' => '2026-10-06 19:40:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000007',
                'nama' => 'Bagas Wicaksono',
                'nim' => '225040101111077',
                'fakultas' => 'Fakultas Hukum',
                'kategori' => 'Kebijakan',
                'judul' => 'Evaluasi Jam Malam Pembatasan Aktivitas Sekretariat Ormawa',
                'isi' => 'Aturan pembatasan jam operasional sekretariat ormawa hingga pukul 21.00 WIB sangat menghambat persiapan delegasi lomba nasional. Kami mengusulkan dispensasi surat izin berkegiatan hingga pukul 23.00 WIB.',
                'status' => 'pending',
                'created_at' => '2026-10-11 13:00:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000008',
                'nama' => 'Clara Stefani',
                'nim' => '235020200111054',
                'fakultas' => 'Fakultas Ekonomi dan Bisnis',
                'kategori' => 'Kebijakan',
                'judul' => 'Standardisasi Mekanisme LPJ Dana Delegasi Prestasi Mahasiswa',
                'isi' => 'Mekanisme reimbursement biaya pendaftaran lomba ilmiah mahasiswa seringkali memakan waktu lebih dari dua bulan. Diperlukan SOP percepatan verifikasi bukti transaksi tingkat rektorat.',
                'status' => 'diproses',
                'created_at' => '2026-10-09 08:30:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000009',
                'nama' => 'Farhan Ramadhan',
                'nim' => '245100100111015',
                'fakultas' => 'Fakultas Teknologi Pertanian',
                'kategori' => 'Kesejahteraan',
                'judul' => 'Subsidi Kuota Kantin Sehat bagi Mahasiswa Prasejahtera',
                'isi' => 'Mengingat tingginya lonjakan harga bahan pangan di sekitar kos lingkar kampus, diharapkan DPM menginisiasi program voucher makan bergizi bekerjasama dengan Paguyuban Kantin Kampus.',
                'status' => 'diproses',
                'created_at' => '2026-10-08 11:15:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000010',
                'nama' => 'Anindya Zahra',
                'nim' => '235110101111063',
                'fakultas' => 'Fakultas Ilmu Budaya',
                'kategori' => 'Kesejahteraan',
                'judul' => 'Peningkatan Kuota Layanan Konseling Psikologis Kampus',
                'isi' => 'Antrean konseling mahasiswa pada Pusat Layanan Konseling dan Disabilitas (PLKD) sangat padat dengan waiting list mencapai 3 minggu. Mohon penambahan jumlah konselor sebaya.',
                'status' => 'selesai',
                'created_at' => '2026-10-05 15:50:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000011',
                'nama' => 'Kevin Ardiansyah',
                'nim' => '245050100111090',
                'fakultas' => 'Fakultas Peternakan',
                'kategori' => 'Lainnya',
                'judul' => 'Penyediaan Shuttle Bus Kampus Ramah Mahasiswa Disabilitas',
                'isi' => 'Fasilitas bus keliling kampus saat ini belum memiliki akses ramp untuk pengguna kursi roda. Mohon koordinasi dengan bagian sarpras rektorat untuk pengadaan unit bus inklusif.',
                'status' => 'pending',
                'created_at' => '2026-10-13 14:05:00'
            ],
            [
                'id' => 'a1b2c3d4-e5f6-4a1b-8c2d-000000000012',
                'nama' => 'Zulfa Maulida',
                'nim' => '225130100111012',
                'fakultas' => 'Fakultas Kedokteran Gigi',
                'kategori' => 'Lainnya',
                'judul' => 'Integrasi Kalender Sidang Parlemen ke Google Calendar Mahasiswa',
                'isi' => 'Usulan integrasi publikasi agenda persidangan, dengar pendapat, dan uji publik peraturan kemahasiswaan ke platform kalender digital yang mudah disubscribe mahasiswa umum.',
                'status' => 'selesai',
                'created_at' => '2026-10-06 09:25:00'
            ]
        ];

        $ins = $pdo->prepare("INSERT INTO aspirasi (id, nama, nim, fakultas, kategori, judul, isi, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($dummyAspirasi as $d) {
            $ins->execute([
                $d['id'],
                $d['nama'],
                $d['nim'],
                $d['fakultas'],
                $d['kategori'],
                $d['judul'],
                $d['isi'],
                $d['status'],
                $d['created_at']
            ]);
        }
    }
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}

// App config
define('CORS_ORIGINS', $_ENV['CORS_ORIGINS'] ?? '*');
