<?php
class Dokumen {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function simpan($data) {
        if (!empty($data['is_pinned'])) {
            $this->unpinAll();
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO documents 
                (nomor, tahun, tentang, jenis, nama_file, nama_asli, keterangan, is_pinned)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['nomor'],
            $data['tahun'],
            $data['tentang'],
            $data['jenis'],
            $data['nama_file'],
            $data['nama_asli'],
            $data['keterangan'] ?? null,
            !empty($data['is_pinned']) ? 1 : 0
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        if (!empty($data['is_pinned'])) {
            $this->unpinAll();
        }

        $sql = "UPDATE documents SET 
                nomor = ?, 
                tahun = ?, 
                tentang = ?, 
                jenis = ?, 
                keterangan = ?, 
                is_pinned = ?";
        $params = [
            $data['nomor'],
            $data['tahun'],
            $data['tentang'],
            $data['jenis'],
            $data['keterangan'] ?? null,
            !empty($data['is_pinned']) ? 1 : 0
        ];

        // Jika upload file baru
        if (!empty($data['nama_file'])) {
            $sql .= ", nama_file = ?, nama_asli = ?";
            $params[] = $data['nama_file'];
            $params[] = $data['nama_asli'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM documents WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findPinned() {
        $stmt = $this->pdo->prepare("SELECT * FROM documents WHERE is_pinned = 1 LIMIT 1");
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findLatest() {
        $stmt = $this->pdo->prepare("SELECT * FROM documents ORDER BY created_at DESC, id DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch();
    }

    public function semua($filters = []) {
        $sql = "SELECT * FROM documents WHERE 1=1";
        $params = [];

        if (!empty($filters['jenis'])) {
            $sql .= " AND jenis = ?";
            $params[] = $filters['jenis'];
        }

        if (!empty($filters['tahun'])) {
            $sql .= " AND tahun = ?";
            $params[] = $filters['tahun'];
        }

        if (!empty($filters['search'])) {
            $keyword = '%' . $filters['search'] . '%';
            $sql .= " AND (nomor LIKE ? OR tahun LIKE ? OR tentang LIKE ?)";
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        $sql .= " ORDER BY tahun DESC, nomor DESC, created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function semuaTahun() {
        return $this->pdo->query("SELECT DISTINCT tahun FROM documents ORDER BY tahun DESC")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function tahunKategori($jenis) {
        $stmt = $this->pdo->prepare("SELECT DISTINCT tahun FROM documents WHERE jenis = ? ORDER BY tahun DESC");
        $stmt->execute([$jenis]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function semuaJenis() {
        return $this->pdo->query("SELECT nama FROM categories ORDER BY nama ASC")->fetchAll(PDO::FETCH_COLUMN);
    }

    public function semuaKategori() {
        return $this->pdo->query("SELECT * FROM categories ORDER BY nama ASC")->fetchAll();
    }

    public function tambahKategori($nama) {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO categories (nama) VALUES (?)");
        $stmt->execute([trim($nama)]);
    }

    public function hapusKategori($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function hapus($id) {
        $stmt = $this->pdo->prepare("DELETE FROM documents WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function unpinAll() {
        $this->pdo->query("UPDATE documents SET is_pinned = 0");
    }

    // ==========================================
    // RELATIONSHIPS MANAGEMENT
    // ==========================================

    public function saveRelations($documentId, $relations) {
        // Hapus relasi lama yang dibuat oleh dokumen ini
        $stmt = $this->pdo->prepare("DELETE FROM document_relations WHERE document_id = ?");
        $stmt->execute([$documentId]);

        if (empty($relations)) return;

        $stmt = $this->pdo->prepare(
            "INSERT INTO document_relations (document_id, related_id, tipe_relasi) VALUES (?, ?, ?)"
        );
        foreach ($relations as $rel) {
            if (!empty($rel['related_id']) && !empty($rel['tipe_relasi'])) {
                $stmt->execute([$documentId, $rel['related_id'], $rel['tipe_relasi']]);
            }
        }
    }

    public function getRelations($id) {
        // Relasi keluar (Dokumen ini mengubah/mencabut dokumen lain)
        $stmt = $this->pdo->prepare(
            "SELECT dr.tipe_relasi, d.id, d.jenis, d.nomor, d.tahun, d.tentang, d.nama_file 
             FROM document_relations dr
             JOIN documents d ON dr.related_id = d.id
             WHERE dr.document_id = ?"
        );
        $stmt->execute([$id]);
        $outgoing = $stmt->fetchAll();

        // Relasi masuk (Dokumen lain mengubah/mencabut dokumen ini)
        $stmt = $this->pdo->prepare(
            "SELECT dr.tipe_relasi, d.id, d.jenis, d.nomor, d.tahun, d.tentang, d.nama_file 
             FROM document_relations dr
             JOIN documents d ON dr.document_id = d.id
             WHERE dr.related_id = ?"
        );
        $stmt->execute([$id]);
        $incoming = $stmt->fetchAll();

        return [
            'outgoing' => $outgoing,
            'incoming' => $incoming
        ];
    }
}
