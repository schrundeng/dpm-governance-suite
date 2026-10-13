<?php
class Controller {

    /**
     * Load model by name
     */
    public function loadModel($modelName) {
        $modelPath = __DIR__ . '/../models/' . $modelName . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            if (class_exists($modelName)) {
                return new $modelName();
            } else {
                throw new Exception("Class '$modelName' tidak ditemukan di '$modelPath'.");
            }
        } else {
            throw new Exception("File model '$modelPath' tidak ditemukan.");
        }
    }

    /**
     * Load view dan kirim data ke dalamnya
     */
    public function loadView($viewName, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("File view '$viewPath' tidak ditemukan.");
        }
    }

    /**
     * Pastikan user sudah login, jika belum redirect ke login
     * Juga cek session timeout 30 menit
     */
    protected function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            session_unset();
            session_destroy();
            $_SESSION['error'] = 'Sesi kamu telah berakhir, silakan login kembali.';
            header('Location: /auth/login');
            exit;
        }

        $_SESSION['last_activity'] = time();
    }

    /**
     * Pastikan user adalah admin
     */
    protected function requireAdmin() {
        $this->requireLogin();
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /dashboard/client');
            exit;
        }
    }

    /**
     * Pastikan user adalah client
     */
    protected function requireClient() {
        $this->requireLogin();
        if ($_SESSION['user_role'] !== 'user') {
            header('Location: /dashboard/admin');
            exit;
        }
    }

    /**
     * Generate & simpan CSRF token ke session
     */
    protected function generateCsrfToken() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    /**
     * Verifikasi CSRF token dari POST
     */
    protected function verifyCsrfToken() {
        $submitted = $_POST['csrf_token'] ?? '';
        $stored    = $_SESSION['csrf_token'] ?? '';
        if (!$submitted || !hash_equals($stored, $submitted)) {
            http_response_code(403);
            die('Permintaan tidak valid (CSRF). Silakan kembali dan coba lagi.');
        }
    }

    /**
     * Redirect ke URL tertentu
     */
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        $_SESSION[$type] = $message;
    }
}