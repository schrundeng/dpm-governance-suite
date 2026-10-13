<?php
require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Dokumen.php';

class DokumenController extends Controller {
    private $model;
    private $pdo;

    const ALLOWED_TYPES = ['application/pdf'];
    const MAX_SIZE      = 20 * 1024 * 1024; // 20MB

    public function __construct($pdo) {
        $this->pdo   = $pdo;
        $this->model = new Dokumen($pdo);
    }

    public function create() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();

            $nomor      = trim($_POST['nomor'] ?? '');
            $tahun      = intval($_POST['tahun'] ?? 0);
            $tentang    = trim($_POST['tentang'] ?? '');
            $jenis      = trim($_POST['jenis'] ?? '');
            $keterangan = trim($_POST['keterangan'] ?? '');
            $is_pinned  = !empty($_POST['is_pinned']);
            $file       = $_FILES['dokumen'] ?? null;

            if (empty($nomor) || empty($tahun) || empty($tentang) || empty($jenis) || !$file || $file['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = 'Semua field wajib diisi dan file PDF harus diunggah.';
                $_SESSION['form_data'] = $_POST;
                header('Location: /admin/document/create');
                exit;
            }

            if ($file['size'] > self::MAX_SIZE) {
                $_SESSION['error'] = 'Ukuran file maksimal 20MB.';
                $_SESSION['form_data'] = $_POST;
                header('Location: /admin/document/create');
                exit;
            }

            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, self::ALLOWED_TYPES)) {
                $_SESSION['error'] = 'Hanya file PDF yang diperbolehkan.';
                $_SESSION['form_data'] = $_POST;
                header('Location: /admin/document/create');
                exit;
            }

            $sanitizedName = $this->sanitizeFilename($file['name']);
            $uploadDir = '/var/www/html/uploads';
            $finalName = $this->getUniqueFilename($uploadDir, $sanitizedName);
            $uploadPath = $uploadDir . '/' . $finalName;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $_SESSION['error'] = 'Gagal menyimpan file PDF ke server.';
                header('Location: /admin/document/create');
                exit;
            }

            $dokumenId = $this->model->simpan([
                'nomor'      => $nomor,
                'tahun'      => $tahun,
                'tentang'    => $tentang,
                'jenis'      => $jenis,
                'nama_file'  => $finalName,
                'nama_asli'  => $file['name'],
                'keterangan' => $keterangan,
                'is_pinned'  => $is_pinned
            ]);

            // Simpan relasi jika ada
            if (isset($_POST['relations']) && is_array($_POST['relations'])) {
                $relations = [];
                foreach ($_POST['relations'] as $rel) {
                    if (!empty($rel['related_id']) && !empty($rel['tipe_relasi'])) {
                        $relations[] = [
                            'related_id'  => intval($rel['related_id']),
                            'tipe_relasi' => trim($rel['tipe_relasi'])
                        ];
                    }
                }
                $this->model->saveRelations($dokumenId, $relations);
            }

            $_SESSION['success'] = 'Dokumen undang-undang berhasil ditambahkan.';
            header('Location: /dashboard/admin');
            exit;
        }

        $csrfToken = $this->generateCsrfToken();
        // Ambil semua dokumen & kategori untuk pilihan relasi/jenis
        $allDocs = $this->model->semua();
        $categories = $this->model->semuaKategori();
        require_once __DIR__ . '/../views/dokumen/upload.php';
    }

    public function edit() {
        $this->requireAdmin();
        $id = intval($_GET['id'] ?? 0);
        $dokumen = $this->model->findById($id);

        if (!$dokumen) {
            http_response_code(404);
            echo "Dokumen tidak ditemukan.";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();

            $nomor      = trim($_POST['nomor'] ?? '');
            $tahun      = intval($_POST['tahun'] ?? 0);
            $tentang    = trim($_POST['tentang'] ?? '');
            $jenis      = trim($_POST['jenis'] ?? '');
            $keterangan = trim($_POST['keterangan'] ?? '');
            $is_pinned  = !empty($_POST['is_pinned']);
            $file       = $_FILES['dokumen'] ?? null;

            if (empty($nomor) || empty($tahun) || empty($tentang) || empty($jenis)) {
                $_SESSION['error'] = 'Kolom nomor, tahun, tentang, dan jenis wajib diisi.';
                $_SESSION['form_data'] = $_POST;
                header('Location: /admin/document/edit?id=' . $id);
                exit;
            }

            $updateData = [
                'nomor'      => $nomor,
                'tahun'      => $tahun,
                'tentang'    => $tentang,
                'jenis'      => $jenis,
                'keterangan' => $keterangan,
                'is_pinned'  => $is_pinned
            ];

            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                if ($file['size'] > self::MAX_SIZE) {
                    $_SESSION['error'] = 'Ukuran file maksimal 20MB.';
                    $_SESSION['form_data'] = $_POST;
                    header('Location: /admin/document/edit?id=' . $id);
                    exit;
                }

                $finfo    = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, self::ALLOWED_TYPES)) {
                    $_SESSION['error'] = 'Hanya file PDF yang diperbolehkan.';
                    $_SESSION['form_data'] = $_POST;
                    header('Location: /admin/document/edit?id=' . $id);
                    exit;
                }

                $sanitizedName = $this->sanitizeFilename($file['name']);
                $uploadDir = '/var/www/html/uploads';
                $finalName = $this->getUniqueFilename($uploadDir, $sanitizedName);
                $uploadPath = $uploadDir . '/' . $finalName;

                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    // Hapus file lama jika ada
                    $oldFilePath = $uploadDir . '/' . $dokumen['nama_file'];
                    if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                    $updateData['nama_file'] = $finalName;
                    $updateData['nama_asli'] = $file['name'];
                }
            }

            $this->model->update($id, $updateData);

            // Simpan relasi baru
            $relations = [];
            if (isset($_POST['relations']) && is_array($_POST['relations'])) {
                foreach ($_POST['relations'] as $rel) {
                    if (!empty($rel['related_id']) && !empty($rel['tipe_relasi'])) {
                        $relations[] = [
                            'related_id'  => intval($rel['related_id']),
                            'tipe_relasi' => trim($rel['tipe_relasi'])
                        ];
                    }
                }
            }
            $this->model->saveRelations($id, $relations);

            $_SESSION['success'] = 'Dokumen undang-undang berhasil diperbarui.';
            header('Location: /dashboard/admin');
            exit;
        }

        $csrfToken = $this->generateCsrfToken();
        // Ambil semua dokumen untuk relasi, kecuali dokumen ini sendiri
        $allDocs = $this->model->semua();
        $allDocs = array_filter($allDocs, function($d) use ($id) {
            return $d['id'] != $id;
        });

        // Ambil relasi & kategori
        $categories = $this->model->semuaKategori();
        $currentRelations = $this->model->getRelations($id);

        require_once __DIR__ . '/../views/dokumen/upload.php';
    }

    public function delete() {
        $this->requireAdmin();
        $id = intval($_GET['id'] ?? 0);
        $dokumen = $this->model->findById($id);

        if (!$dokumen) {
            http_response_code(404);
            echo "Dokumen tidak ditemukan.";
            exit;
        }

        // Hapus file fisik
        $filePath = '/var/www/html/uploads/' . $dokumen['nama_file'];
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }

        // Hapus database
        $this->model->hapus($id);

        $_SESSION['success'] = 'Dokumen undang-undang berhasil dihapus.';
        header('Location: /dashboard/admin');
        exit;
    }

    public function download() {
        $id = intval($_GET['id'] ?? 0);
        $dokumen = $this->model->findById($id);

        if (!$dokumen) {
            http_response_code(404);
            echo "File tidak ditemukan.";
            exit;
        }

        $filePath = '/var/www/html/uploads/' . $dokumen['nama_file'];

        if (!file_exists($filePath) || !is_file($filePath)) {
            http_response_code(404);
            echo "File tidak ditemukan di server.";
            exit;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $dokumen['nama_asli'] . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    public function detail() {
        $this->requireAdmin();
        $id = intval($_GET['id'] ?? 0);
        $dokumen = $this->model->findById($id);

        if (!$dokumen) {
            http_response_code(404);
            echo "Dokumen tidak ditemukan.";
            exit;
        }

        $relations = $this->model->getRelations($id);

        require_once __DIR__ . '/../views/dokumen/detail_admin.php';
    }

    public function detailJson() {
        $id = intval($_GET['id'] ?? 0);
        $dokumen = $this->model->findById($id);
        if (!$dokumen) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Dokumen tidak ditemukan']);
            exit;
        }
        $relations = $this->model->getRelations($id);
        
        header('Content-Type: application/json');
        echo json_encode([
            'id' => $dokumen['id'],
            'nomor' => $dokumen['nomor'],
            'tahun' => $dokumen['tahun'],
            'tentang' => $dokumen['tentang'],
            'jenis' => $dokumen['jenis'],
            'nama_file' => $dokumen['nama_file'],
            'nama_asli' => $dokumen['nama_asli'],
            'keterangan' => $dokumen['keterangan'],
            'is_pinned' => $dokumen['is_pinned'],
            'relations' => $relations
        ]);
        exit;
    }

    public function createCategory() {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();
            $nama = trim($_POST['nama'] ?? '');
            if (!empty($nama)) {
                $this->model->tambahKategori($nama);
                $_SESSION['success'] = 'Jenis peraturan baru berhasil ditambahkan.';
            } else {
                $_SESSION['error'] = 'Nama jenis peraturan tidak boleh kosong.';
            }
        }
        header('Location: /dashboard/admin');
        exit;
    }

    public function deleteCategory() {
        $this->requireAdmin();
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->model->hapusKategori($id);
            $_SESSION['success'] = 'Jenis peraturan berhasil dihapus.';
        }
        header('Location: /dashboard/admin');
        exit;
    }

    // ==========================================
    // HELPERS
    // ==========================================

    private function sanitizeFilename($filename) {
        $pathinfo = pathinfo($filename);
        // Ganti karakter non-alphanumeric/dot/dash dengan underscore
        $filenameStr = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $pathinfo['filename']);
        $extension = isset($pathinfo['extension']) ? strtolower($pathinfo['extension']) : '';
        return $filenameStr . ($extension ? '.' . $extension : '');
    }

    private function getUniqueFilename($dir, $filename) {
        $pathinfo = pathinfo($filename);
        $filenameStr = $pathinfo['filename'];
        $extension = isset($pathinfo['extension']) ? '.' . strtolower($pathinfo['extension']) : '';
        
        $finalFilename = $filename;
        $counter = 1;
        while (file_exists($dir . '/' . $finalFilename)) {
            $finalFilename = $filenameStr . '_' . $counter . $extension;
            $counter++;
        }
        return $finalFilename;
    }
}
