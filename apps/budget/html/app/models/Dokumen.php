<?php
class Dokumen {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function simpan($data) {
        $tahun = date('Y');
        $stmt  = $this->pdo->query("SELECT COUNT(*) FROM dokumen WHERE YEAR(created_at) = $tahun");
        $count = $stmt->fetchColumn();
        $idPengajuan = 'DPM-' . $tahun . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        $stmt = $this->pdo->prepare(
            "INSERT INTO dokumen 
                (id_pengajuan, user_id, judul, nama_file, nama_asli, jenis, keterangan, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'dikirim')"
        );
        $stmt->execute([
            $idPengajuan,
            $data['user_id'],
            $data['judul'],
            $data['nama_file'],
            $data['nama_asli'],
            $data['jenis'],
            $data['keterangan'] ?? null,
        ]);
        return $this->pdo->lastInsertId();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare(
            "SELECT d.*, u.nama as nama_user, u.komisi
             FROM dokumen d
             JOIN users u ON d.user_id = u.id
             WHERE d.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function milikUser($userId, $filters = []) {
        $sql = "SELECT d.*
                FROM dokumen d
                WHERE d.user_id = ?";
        $params = [$userId];

        if (!empty($filters['status'])) {
            $sql .= " AND d.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['jenis'])) {
            $sql .= " AND d.jenis = ?";
            $params[] = $filters['jenis'];
        }
        if (!empty($filters['search'])) {
            $keyword = '%' . $filters['search'] . '%';
            $sql .= " AND (d.id_pengajuan LIKE ? OR d.judul LIKE ?)";
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $sql .= " ORDER BY d.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();

        error_log("Results count: " . count($results));

        return $results;
    }

        public function updateFeedback($id, $namaFileFeedback) {
        $stmt = $this->pdo->prepare(
            "UPDATE dokumen SET file_feedback = ? WHERE id = ?"
        );
        $stmt->execute([$namaFileFeedback, $id]);
    }

    public function getFeedbackLama($id) {
        $stmt = $this->pdo->prepare(
            "SELECT file_feedback FROM dokumen WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public function semua($filters = []) {
        $sql = "SELECT d.*, u.nama as nama_user, u.komisi
                FROM dokumen d
                JOIN users u ON d.user_id = u.id
                WHERE d.is_archived = 0";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND d.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['komisi'])) {
            $sql .= " AND u.komisi = ?";
            $params[] = $filters['komisi'];
        }
        if (!empty($filters['jenis'])) {
            $sql .= " AND d.jenis = ?";
            $params[] = $filters['jenis'];
        }
        if (!empty($filters['belum_review'])) {
            $sql .= " AND d.status = 'dikirim'";
        }

        $sql .= " ORDER BY d.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status, $catatan = null, $fileFeedback = null) {
        $stmt = $this->pdo->prepare(
            "UPDATE dokumen 
             SET status = ?, catatan_revisi = ?, file_feedback = ?
             WHERE id = ?"
        );
        $stmt->execute([$status, $catatan, $fileFeedback, $id]);
    }

    public function kadaluarsa() {
        return $this->pdo->query(
            "SELECT d.*, u.nama as nama_user, u.komisi
            FROM dokumen d
            JOIN users u ON d.user_id = u.id
            WHERE d.is_archived = 1
            ORDER BY d.created_at ASC"
        )->fetchAll();
    }

    public function archive($id) {
        $stmt = $this->pdo->prepare("UPDATE dokumen SET is_archived = 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function restore($id) {
        $stmt = $this->pdo->prepare("UPDATE dokumen SET is_archived = 0 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function hapus($id) {
        $stmt = $this->pdo->prepare("DELETE FROM dokumen WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function hapusBulk($ids) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare("DELETE FROM dokumen WHERE id IN ($placeholders)");
        $stmt->execute($ids);
    }
}