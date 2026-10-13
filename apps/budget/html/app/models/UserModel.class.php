<?php
class UserModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (nama, komisi, email, password, role) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['nama'],
            $data['komisi'] ?? null,
            $data['email'],
            $data['password'],
            $data['role']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updatePassword($id, $hashedPassword) {
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $id]);
    }

    public function updateProfile($id, $data) {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET nama = ?, komisi = ? WHERE id = ?"
        );
        $stmt->execute([$data['nama'], $data['komisi'] ?? null, $id]);
    }

    public function semuaAdmin() {
        return $this->pdo->query(
            "SELECT * FROM users WHERE role = 'admin'"
        )->fetchAll();
    }

    public function semua() {
        return $this->pdo->query(
            "SELECT id, nama, komisi, email, role, created_at 
             FROM users ORDER BY created_at DESC"
        )->fetchAll();
    }

    public function activate($email) {
        $stmt = $this->pdo->prepare("UPDATE users SET is_active = 1 WHERE email = ?");
        $stmt->execute([$email]);
    }
}