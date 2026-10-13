<?php
// Baca file .env manual
$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // skip komentar
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

// Database
$host = $_ENV['DB_HOST'] ?? 'mysql_container_baru';
$db   = $_ENV['DB_NAME'] ?? 'dpm_budget';
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
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage());
}

// Email config (dipakai oleh semua controller yang butuh kirim email)
define('MAIL_HOST',         $_ENV['MAIL_HOST']         ?? 'smtp.gmail.com');
define('MAIL_PORT',         $_ENV['MAIL_PORT']         ?? 587);
define('MAIL_USERNAME',     $_ENV['MAIL_USERNAME']     ?? 'noreply@example.com');
define('MAIL_PASSWORD',     $_ENV['MAIL_PASSWORD']     ?? '');
define('MAIL_FROM_ADDRESS', $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@example.com');
define('MAIL_FROM_NAME',    $_ENV['MAIL_FROM_NAME']    ?? 'DPM Budget System');
define('APP_URL',           $_ENV['APP_URL']           ?? 'http://localhost:8085');