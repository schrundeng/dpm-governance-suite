<?php
require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Dokumen.php';
require_once __DIR__ . '/../models/UserModel.class.php';
require_once __DIR__ . '/AuthController.class.php';

class DokumenController extends Controller {
    private $model;
    private $pdo;

    const ALLOWED_TYPES = ['application/pdf', 'image/jpeg', 'image/png'];
    const MAX_SIZE      = 10 * 1024 * 1024; // 10MB

    public function __construct($pdo) {
        $this->pdo   = $pdo;
        $this->model = new Dokumen($pdo);
    }

    public function upload() {
        $this->requireClient();

        $csrfToken     = $this->generateCsrfToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $this->verifyCsrfToken();

            $judul      = trim($_POST['judul'] ?? '');
            $jenis      = $_POST['jenis'] ?? '';
            $keterangan = trim($_POST['keterangan'] ?? '');
            $file       = $_FILES['dokumen'] ?? null;

            if (empty($judul) || empty($jenis) || !$file) {
                $_SESSION['error'] = 'Semua field wajib diisi.';
                header('Location: /dokumen/upload');
                exit;
            }

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = 'Gagal mengupload file.';
                header('Location: /dokumen/upload');
                exit;
            }

            if ($file['size'] > self::MAX_SIZE) {
                $_SESSION['error'] = 'Ukuran file maksimal 10MB.';
                header('Location: /dokumen/upload');
                exit;
            }

            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, self::ALLOWED_TYPES)) {
                $_SESSION['error'] = 'Format file hanya PDF, JPG, atau PNG.';
                header('Location: /dokumen/upload');
                exit;
            }

            $ekstensi   = pathinfo($file['name'], PATHINFO_EXTENSION);
            $namaFile   = uniqid('dok_') . '.' . strtolower($ekstensi);
            $uploadPath = '/var/www/html/uploads/' . $namaFile;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $_SESSION['error'] = 'Gagal menyimpan file.';
                header('Location: /dokumen/upload');
                exit;
            }

            $dokumenId = $this->model->simpan([
                'user_id'     => $_SESSION['user_id'],
                'judul'       => $judul,
                'nama_file'   => $namaFile,
                'nama_asli'   => $file['name'],
                'jenis'       => $jenis,
                'keterangan'  => $keterangan,
            ]);

            $this->catatLog($_SESSION['user_id'], $dokumenId, 'upload', 'Upload dokumen: ' . $judul);
            $this->notifikasiAdmin($dokumenId);

            $_SESSION['success'] = 'Dokumen berhasil diajukan!';
            header('Location: /dashboard/client');
            exit;
        }

        require_once __DIR__ . '/../views/dokumen/upload.php';
    }

    public function detail() {
        $this->requireLogin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /dashboard/client');
            exit;
        }

        $dokumen = $this->model->findById($id);
        if (!$dokumen) {
            http_response_code(404);
            echo "Dokumen tidak ditemukan.";
            exit;
        }

        if ($_SESSION['user_role'] === 'user' && $dokumen['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "Akses ditolak.";
            exit;
        }

        require_once __DIR__ . '/../views/dokumen/detail.php';
    }

    public function download() {
        $this->requireLogin();

        $id      = $_GET['id'] ?? null;
        $dokumen = $this->model->findById($id);

        if (!$dokumen) {
            http_response_code(404);
            echo "File tidak ditemukan.";
            exit;
        }

        if ($_SESSION['user_role'] === 'user') {
            if ($dokumen['user_id'] != $_SESSION['user_id'] || $dokumen['status'] !== 'diterima') {
                http_response_code(403);
                echo "Akses ditolak.";
                exit;
            }
        }

        $tipe     = $_GET['tipe'] ?? 'original';
        $namaFile = ($tipe === 'feedback' && $dokumen['file_feedback'])
                    ? $dokumen['file_feedback']
                    : $dokumen['nama_file'];

        $filePath = '/var/www/html/uploads/' . $namaFile;

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo "File tidak ditemukan di server.";
            exit;
        }

        $this->catatLog($_SESSION['user_id'], $id, 'download', 'Download dokumen: ' . $dokumen['judul']);

        $namaAsli = ($tipe === 'feedback') ? 'feedback_' . $dokumen['nama_asli'] : $dokumen['nama_asli'];
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $namaAsli . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    private function catatLog($userId, $dokumenId, $aksi, $keterangan) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO activity_log (user_id, dokumen_id, aksi, keterangan) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$userId, $dokumenId, $aksi, $keterangan]);
    }

    private function notifikasiAdmin($dokumenId) {
        $dokumen   = $this->model->findById($dokumenId);
        $userModel = new UserModel($this->pdo);
        $admins    = $userModel->semuaAdmin();

        $subject = '[WebDPM] Pengajuan Dokumen Baru — ' . $dokumen['id_pengajuan'];
        $body    = "
            <h3>Pengajuan Dokumen Baru</h3>
            <p><b>ID Pengajuan:</b> {$dokumen['id_pengajuan']}</p>
            <p><b>Dari:</b> {$dokumen['nama_user']} ({$dokumen['komisi']})</p>
            <p><b>Judul:</b> {$dokumen['judul']}</p>
            <p><b>Jenis:</b> {$dokumen['jenis']}</p>
            <p><b>Waktu:</b> {$dokumen['created_at']}</p>
            <br>
            <p><a href='" . APP_URL . "/dokumen/detail?id={$dokumen['id']}'>Lihat Dokumen</a></p>
        ";

        foreach ($admins as $admin) {
            AuthController::kirimEmail($admin['email'], $admin['nama'], $subject, $body);
        }
    }
}
