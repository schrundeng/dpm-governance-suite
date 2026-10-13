<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../config/database.php';

$route   = $_GET['route'] ?? '';
$parts   = explode('/', $route);
$section = $parts[0] ?? '';
$action  = $parts[1] ?? '';
$subAction = $parts[2] ?? '';

// Proteksi halaman yang butuh login
$publicRoutes = ['', 'auth/login', 'dokumen/download', 'dokumen/detail_json'];
if (!in_array($route, $publicRoutes) && empty($_SESSION['user_id'])) {
    header('Location: /auth/login');
    exit;
}

switch ($section) {
    case '':
        require_once __DIR__ . '/../app/controllers/DashboardController.class.php';
        $controller = new DashboardController($pdo);
        $controller->client();
        break;

    case 'auth':
        require_once __DIR__ . '/../app/controllers/AuthController.class.php';
        $controller = new AuthController($pdo);
        if ($action === 'login')    $controller->login();
        if ($action === 'logout')   $controller->logout();
        break;

    case 'dashboard':
        if ($action === 'admin') {
            require_once __DIR__ . '/../app/controllers/DashboardController.class.php';
            $controller = new DashboardController($pdo);
            $controller->admin();
        } else {
            header('Location: /');
            exit;
        }
        break;

    case 'admin':
        if ($action === 'document') {
            require_once __DIR__ . '/../app/controllers/DokumenController.class.php';
            $controller = new DokumenController($pdo);
            if ($subAction === 'create') $controller->create();
            if ($subAction === 'edit')   $controller->edit();
            if ($subAction === 'delete') $controller->delete();
            if ($subAction === 'detail') $controller->detail();
        } elseif ($action === 'category') {
            require_once __DIR__ . '/../app/controllers/DokumenController.class.php';
            $controller = new DokumenController($pdo);
            if ($subAction === 'create') $controller->createCategory();
            if ($subAction === 'delete') $controller->deleteCategory();
        }
        break;

    case 'dokumen':
        if ($action === 'download') {
            require_once __DIR__ . '/../app/controllers/DokumenController.class.php';
            $controller = new DokumenController($pdo);
            $controller->download();
        } elseif ($action === 'detail_json') {
            require_once __DIR__ . '/../app/controllers/DokumenController.class.php';
            $controller = new DokumenController($pdo);
            $controller->detailJson();
        }
        break;

    default:
        http_response_code(404);
        echo "Halaman tidak ditemukan. Route: " . htmlspecialchars($route);
        break;
}
