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

        // Seed default admin: admin / admin123
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        if ($stmt->fetchColumn() == 0) {
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $insert->execute(['admin', $hashedPassword]);
        }
    }
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}

// App config
define('CORS_ORIGINS', $_ENV['CORS_ORIGINS'] ?? '*');
