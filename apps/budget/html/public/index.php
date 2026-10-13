<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/database.php';

$route   = $_GET['route'] ?? '';
$parts   = explode('/', $route);
$section = $parts[0] ?? '';
$action  = $parts[1] ?? '';

// Jika route kosong, redirect ke login
if (empty($section)) {
    header('Location: /auth/login');
    exit;
}

// Proteksi halaman yang butuh login
$publicRoutes = ['auth/login', 'auth/register', 'auth/verify'];
if (!in_array($route, $publicRoutes) && empty($_SESSION['user_id'])) {
    header('Location: /auth/login');
    exit;
}

switch ($section) {
    case 'auth':
        require_once __DIR__ . '/../app/controllers/AuthController.class.php';
        $controller = new AuthController($pdo);
        if ($action === 'login')    $controller->login();
        if ($action === 'register') $controller->register();
        if ($action === 'verify')   $controller->verify();
        if ($action === 'logout')   $controller->logout();
        if ($action === 'profile')  $controller->profile();
        break;

    case 'dokumen':
        require_once __DIR__ . '/../app/controllers/DokumenController.class.php';
        $controller = new DokumenController($pdo);
        if ($action === 'upload')   $controller->upload();
        if ($action === 'detail')   $controller->detail();
        if ($action === 'download') $controller->download();
        break;
    
    case 'dashboard':
        require_once __DIR__ . '/../app/controllers/DashboardController.class.php';
        $controller = new DashboardController($pdo);
        if ($action === 'client') $controller->client();
        if ($action === 'admin')  $controller->admin();
        break;

    case 'approval':
        require_once __DIR__ . '/../app/controllers/ApprovalController.class.php';
        $controller = new ApprovalController($pdo);
        if ($action === 'review')        $controller->review();
        if ($action === 'tindaklanjut')  $controller->tindaklanjut();
        if ($action === 'arsip')        $controller->arsip();
        if ($action === 'archive')      $controller->archive();
        if ($action === 'restore')      $controller->restore();
        if ($action === 'reuploadFeedback')  $controller->reuploadFeedback();
        break;
        
    default:
        http_response_code(404);
        echo "Halaman tidak ditemukan. Route: " . htmlspecialchars($route);
        break;
}