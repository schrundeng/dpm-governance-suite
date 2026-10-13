<?php
require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Dokumen.php';
require_once __DIR__ . '/../models/UserModel.class.php';
require_once __DIR__ . '/AuthController.class.php';

class ApprovalController extends Controller {
    private $pdo;
    private $dokumenModel;

    const ALLOWED_TYPES = ['application/pdf', 'image/jpeg', 'image/png'];
    const MAX_SIZE      = 10 * 1024 * 1024;

    public function __construct($pdo) {
        $this->pdo          = $pdo;
        $this->dokumenModel = new Dokumen($pdo);
    }

    // Set status ke in-review
    public function review() {
        $this->requireAdmin();

        $id      = $_GET['id'] ?? null;
        $dokumen = $this->dokumenModel->findById($id);

        if (!$dokumen || $dokumen['status'] !== 'dikirim') {
            header('Location: /dashboard/admin');
            exit;
        }

        $this->dokumenModel->updateStatus($id, 'in-review');

        // Catat log
        $this->catatLog($_SESSION['user_id'], $id, 'review', 'Status diubah ke in-review');

        $_SESSION['success'] = 'Status pengajuan diubah ke In-Review.';
        header('Location: /dashboard/admin');
        exit;
    }

    // Laman tindak lanjut
    public function tindaklanjut() {
        $this->requireAdmin();

        $id      = $_GET['id'] ?? null;
        $dokumen = $this->dokumenModel->findById($id);

        if (!$dokumen || $dokumen['status'] !== 'in-review') {
            $_SESSION['error'] = 'Dokumen harus berstatus In-Review untuk ditindaklanjuti.';
            header('Location: /dashboard/admin');
            exit;
        }

        $csrfToken = $this->generateCsrfToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $this->verifyCsrfToken(); // aktifkan setelah CSRF fix

            $status   = $_POST['status'] ?? '';
            $catatan  = trim($_POST['catatan'] ?? '');
            $file     = $_FILES['file_feedback'] ?? null;

            if (!in_array($status, ['diterima', 'ditolak'])) {
                $_SESSION['error'] = 'Status tidak valid.';
                header('Location: /approval/tindaklanjut?id=' . $id);
                exit;
            }

            if ($status === 'ditolak' && empty($catatan)) {
                $_SESSION['error'] = 'Catatan revisi wajib diisi jika dokumen ditolak.';
                header('Location: /approval/tindaklanjut?id=' . $id);
                exit;
            }

            // Handle file feedback (opsional)
            $namaFileFeedback = null;
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                if ($file['size'] > self::MAX_SIZE) {
                    $_SESSION['error'] = 'Ukuran file maksimal 10MB.';
                    header('Location: /approval/tindaklanjut?id=' . $id);
                    exit;
                }

                $finfo    = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mimeType, self::ALLOWED_TYPES)) {
                    $_SESSION['error'] = 'Format file hanya PDF, JPG, atau PNG.';
                    header('Location: /approval/tindaklanjut?id=' . $id);
                    exit;
                }

                $ekstensi         = pathinfo($file['name'], PATHINFO_EXTENSION);
                $namaFileFeedback = uniqid('feedback_') . '.' . strtolower($ekstensi);
                $uploadPath       = '/var/www/html/uploads/' . $namaFileFeedback;

                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $_SESSION['error'] = 'Gagal menyimpan file feedback.';
                    header('Location: /approval/tindaklanjut?id=' . $id);
                    exit;
                }
            }

            // Update status dokumen
            $this->dokumenModel->updateStatus($id, $status, $catatan, $namaFileFeedback);

            // Catat log
            $this->catatLog($_SESSION['user_id'], $id, $status, 'Dokumen ' . $status . ($catatan ? ': ' . $catatan : ''));

            // Kirim email ke client
            $this->notifikasiClient($id, $status, $catatan);

            $_SESSION['success'] = 'Dokumen berhasil di-' . $status . '.';
            header('Location: /dashboard/admin');
            exit;
        }

        require_once __DIR__ . '/../views/dashboard/tindaklanjut.php';
    }

    private function catatLog($userId, $dokumenId, $aksi, $keterangan) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO activity_log (user_id, dokumen_id, aksi, keterangan) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$userId, $dokumenId, $aksi, $keterangan]);
    }

    private function notifikasiClient($dokumenId, $status, $catatan) {
        $dokumen   = $this->dokumenModel->findById($dokumenId);
        $userModel = new UserModel($this->pdo);
        $client    = $userModel->findById($dokumen['user_id']);

        $statusLabel = $status === 'diterima' ? 'Diterima ✅' : 'Ditolak ❌';
        $subject     = '[WebDPM] Pengajuan ' . $dokumen['id_pengajuan'] . ' — ' . $statusLabel;

        $body = "
            <h3>Update Status Pengajuan</h3>
            <p>Halo <b>{$client['nama']}</b>,</p>
            <p>Pengajuan dokumen kamu telah diproses.</p>
            <br>
            <p><b>ID Pengajuan:</b> {$dokumen['id_pengajuan']}</p>
            <p><b>Judul:</b> {$dokumen['judul']}</p>
            <p><b>Status:</b> {$statusLabel}</p>
        ";

        if ($status === 'ditolak' && $catatan) {
            $body .= "<br><p><b>Catatan Revisi:</b></p><p>{$catatan}</p>";
        }

        if ($status === 'diterima') {
            $body .= "<br><p><a href='" . APP_URL . "/dokumen/detail?id={$dokumen['id']}'>Lihat & Download Dokumen</a></p>";
        }

        AuthController::kirimEmail($client['email'], $client['nama'], $subject, $body);
    }

    public function arsip() {
    $this->requireAdmin();

    $dokumens  = $this->dokumenModel->kadaluarsa();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $aksi = $_POST['aksi'] ?? '';

        // Hapus satu dokumen
        if ($aksi === 'hapus_satu') {
            $id      = $_POST['id'] ?? null;
            $dokumen = $this->dokumenModel->findById($id);

            if ($dokumen && in_array($dokumen['status'], ['diterima', 'ditolak'])) {
                // Hapus file dari uploads
                $this->hapusFile($dokumen['nama_file']);
                if ($dokumen['file_feedback']) {
                    $this->hapusFile($dokumen['file_feedback']);
                }
                // Hapus log terkait
                $this->pdo->prepare("DELETE FROM activity_log WHERE dokumen_id = ?")->execute([$id]);
                $this->pdo->prepare("DELETE FROM approval WHERE dokumen_id = ?")->execute([$id]);
                $this->dokumenModel->hapus($id);
                $_SESSION['success'] = 'Dokumen berhasil dihapus.';
            }
            header('Location: /approval/arsip');
            exit;
        }

            // Hapus semua yang kadaluarsa
            if ($aksi === 'hapus_semua') {
                $dokumens = $this->dokumenModel->kadaluarsa();
                foreach ($dokumens as $d) {
                    $this->hapusFile($d['nama_file']);
                    if ($d['file_feedback']) $this->hapusFile($d['file_feedback']);
                    $this->pdo->prepare("DELETE FROM activity_log WHERE dokumen_id = ?")->execute([$d['id']]);
                    $this->pdo->prepare("DELETE FROM approval WHERE dokumen_id = ?")->execute([$d['id']]);
                }
                $ids = array_column($dokumens, 'id');
                if (!empty($ids)) {
                    $this->dokumenModel->hapusBulk($ids);
                    $_SESSION['success'] = count($ids) . ' dokumen kadaluarsa berhasil dihapus.';
                } else {
                    $_SESSION['success'] = 'Tidak ada dokumen yang perlu dihapus.';
                }
                header('Location: /approval/arsip');
                exit;
            }
        }

        require_once __DIR__ . '/../views/dashboard/arsip.php';
    }

    public function archive() {
        $this->requireAdmin();

        $id      = $_GET['id'] ?? null;
        $dokumen = $this->dokumenModel->findById($id);

        if (!$dokumen) {
            $_SESSION['error'] = 'Dokumen tidak ditemukan.';
            header('Location: /dashboard/admin');
            exit;
        }

        $isOld = strtotime($dokumen['created_at']) <= strtotime('-2 weeks');
        $canArchive = in_array($dokumen['status'], ['diterima', 'ditolak']) && $isOld;

        if (!$canArchive) {
            $_SESSION['error'] = 'Dokumen belum layak diarsipkan (harus berstatus diterima/ditolak dan berumur > 2 minggu).';
            header('Location: /dashboard/admin');
            exit;
        }

        $this->dokumenModel->archive($id);

        $this->catatLog($_SESSION['user_id'], $id, 'archive', 'Dokumen dipindahkan ke arsip');

        $_SESSION['success'] = 'Dokumen berhasil diarsipkan.';
        header('Location: /dashboard/admin');
        exit;
    }

    public function restore() {
        $this->requireAdmin();

        $id      = $_GET['id'] ?? null;
        $dokumen = $this->dokumenModel->findById($id);

        if (!$dokumen || (int)$dokumen['is_archived'] !== 1) {
            $_SESSION['error'] = 'Dokumen tidak ditemukan di arsip.';
            header('Location: /approval/arsip');
            exit;
        }

        $this->dokumenModel->restore($id);

        $this->catatLog($_SESSION['user_id'], $id, 'restore-archive', 'Dokumen dipulihkan dari arsip');

        $_SESSION['success'] = 'Dokumen berhasil dipulihkan dari arsip.';
        header('Location: /approval/arsip');
        exit;
    }

        public function reuploadFeedback() {
        $this->requireAdmin();

        $id      = $_GET['id'] ?? null;
        $dokumen = $this->dokumenModel->findById($id);

        if (!$dokumen || $dokumen['status'] !== 'diterima') {
            $_SESSION['error'] = 'Hanya dokumen berstatus Diterima yang bisa diupdate file feedback-nya.';
            header('Location: /dashboard/admin');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $file = $_FILES['file_feedback'] ?? null;

            if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = 'File wajib diupload.';
                header('Location: /approval/reuploadFeedback?id=' . $id);
                exit;
            }

            if ($file['size'] > self::MAX_SIZE) {
                $_SESSION['error'] = 'Ukuran file maksimal 10MB.';
                header('Location: /approval/reuploadFeedback?id=' . $id);
                exit;
            }

            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mimeType, self::ALLOWED_TYPES)) {
                $_SESSION['error'] = 'Format file hanya PDF, JPG, atau PNG.';
                header('Location: /approval/reuploadFeedback?id=' . $id);
                exit;
            }

            // Hapus file feedback lama
            $feedbackLama = $this->dokumenModel->getFeedbackLama($id);
            if ($feedbackLama) {
                $pathLama = '/var/www/html/uploads/' . $feedbackLama;
                if (file_exists($pathLama)) unlink($pathLama);
            }

            // Simpan file baru
            $ekstensi         = pathinfo($file['name'], PATHINFO_EXTENSION);
            $namaFileFeedback = uniqid('feedback_') . '.' . strtolower($ekstensi);
            $uploadPath       = '/var/www/html/uploads/' . $namaFileFeedback;

            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $_SESSION['error'] = 'Gagal menyimpan file.';
                header('Location: /approval/reuploadFeedback?id=' . $id);
                exit;
            }

            // Update database
            $this->dokumenModel->updateFeedback($id, $namaFileFeedback);

            // Catat log
            $this->catatLog($_SESSION['user_id'], $id, 'reupload-feedback', 
                'File feedback diperbarui untuk dokumen: ' . $dokumen['judul']);

            // Kirim email notifikasi ke client
            $this->notifikasiReupload($id);

            $_SESSION['success'] = 'File feedback berhasil diperbarui.';
            header('Location: /dashboard/admin');
            exit;
        }

        require_once __DIR__ . '/../views/dashboard/reupload_feedback.php';
    }

    private function notifikasiReupload($dokumenId) {
        $dokumen   = $this->dokumenModel->findById($dokumenId);
        $userModel = new UserModel($this->pdo);
        $client    = $userModel->findById($dokumen['user_id']);

        $subject = '[WebDPM] File Dokumen Diperbarui — ' . $dokumen['id_pengajuan'];
        $body    = "
            <h3>File Dokumen Diperbarui</h3>
            <p>Halo <b>{$client['nama']}</b>,</p>
            <p>Admin telah memperbarui file dokumen pengajuan kamu.</p>
            <br>
            <p><b>ID Pengajuan:</b> {$dokumen['id_pengajuan']}</p>
            <p><b>Judul:</b> {$dokumen['judul']}</p>
            <br>
            <p><a href='" . APP_URL . "/dokumen/detail?id={$dokumen['id']}'>Lihat Dokumen Terbaru</a></p>
        ";

        AuthController::kirimEmail($client['email'], $client['nama'], $subject, $body);
    }

        private function hapusFile($namaFile) {
            $path = '/var/www/html/uploads/' . $namaFile;
            if (file_exists($path)) {
                unlink($path);
            }
        }

}