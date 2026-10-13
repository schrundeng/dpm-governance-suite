<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';

// Helper: send JSON responses
function sendJson($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Helper: format database timestamp to ISO 8601 UTC with microseconds and timezone
function formatIsoTimestamp($dbTimestamp) {
    $dt = new DateTime($dbTimestamp, new DateTimeZone('Asia/Jakarta'));
    return $dt->format('Y-m-d\TH:i:s.uP');
}

// Helper: generate UUID v4
function generateUuid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// CORS Headers Injection
$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
$allowedOrigins = explode(',', CORS_ORIGINS);
$corsOrigin = '*';
if (CORS_ORIGINS === '*') {
    $corsOrigin = '*';
} elseif (in_array($origin, $allowedOrigins)) {
    $corsOrigin = $origin;
}

header("Access-Control-Allow-Origin: $corsOrigin");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Parse request path
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$parsedUrl = parse_url($requestUri);
$path = rtrim($parsedUrl['path'], '/');
if (empty($path)) {
    $path = '/';
}

// API Routes
if (strpos($path, '/api') === 0) {
    if ($path === '/api') {
        sendJson(["message" => "DPM UB 2026 — Parlemen Pilar Karsa API"]);
    }

    // POST /api/status
    if ($path === '/api/status' && $requestMethod === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['client_name'])) {
            sendJson(["detail" => "Missing field: client_name"], 422);
        }

        $id = generateUuid();
        $clientName = trim($data['client_name']);
        $timestamp = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d H:i:s.u');

        $stmt = $pdo->prepare("INSERT INTO status_checks (id, client_name, timestamp) VALUES (?, ?, ?)");
        $stmt->execute([$id, $clientName, $timestamp]);

        sendJson([
            "id" => $id,
            "client_name" => $clientName,
            "timestamp" => formatIsoTimestamp($timestamp)
        ]);
    }

    // GET /api/status
    if ($path === '/api/status' && $requestMethod === 'GET') {
        $stmt = $pdo->query("SELECT * FROM status_checks ORDER BY timestamp DESC LIMIT 100");
        $rows = $stmt->fetchAll();
        
        $results = [];
        foreach ($rows as $row) {
            $results[] = [
                "id" => $row['id'],
                "client_name" => $row['client_name'],
                "timestamp" => formatIsoTimestamp($row['timestamp'])
            ];
        }
        sendJson($results);
    }

    // POST /api/aspirasi
    if ($path === '/api/aspirasi' && $requestMethod === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            sendJson(["detail" => "Invalid JSON body"], 422);
        }

        $requiredFields = ['nama', 'nim', 'fakultas', 'kategori', 'judul', 'isi'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                sendJson(["detail" => "Missing field: $field"], 422);
            }
        }

        $nama = trim($data['nama'] ?? '');
        $nim = trim($data['nim'] ?? '');
        $fakultas = trim($data['fakultas'] ?? '');
        $kategori = trim($data['kategori'] ?? '');
        $judul = trim($data['judul'] ?? '');
        $isi = trim($data['isi'] ?? '');

        if (strlen($nama) < 2 || strlen($nama) > 120) {
            sendJson(["detail" => "nama must be between 2 and 120 characters"], 422);
        }
        if (strlen($nim) < 3 || strlen($nim) > 30) {
            sendJson(["detail" => "nim must be between 3 and 30 characters"], 422);
        }
        if (strlen($fakultas) < 2 || strlen($fakultas) > 120) {
            sendJson(["detail" => "fakultas must be between 2 and 120 characters"], 422);
        }
        $allowedKategori = ["Akademik", "Fasilitas", "Kebijakan", "Kesejahteraan", "Lainnya"];
        if (!in_array($kategori, $allowedKategori)) {
            sendJson(["detail" => "kategori must be one of: " . implode(', ', $allowedKategori)], 422);
        }
        if (strlen($judul) < 3 || strlen($judul) > 160) {
            sendJson(["detail" => "judul must be between 3 and 160 characters"], 422);
        }
        if (strlen($isi) < 10 || strlen($isi) > 4000) {
            sendJson(["detail" => "isi must be between 10 and 4000 characters"], 422);
        }

        $id = generateUuid();
        $status = "pending";
        $createdAt = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d H:i:s.u');

        $stmt = $pdo->prepare("INSERT INTO aspirasi (id, nama, nim, fakultas, kategori, judul, isi, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $nama, $nim, $fakultas, $kategori, $judul, $isi, $status, $createdAt]);

        sendJson([
            "id" => $id,
            "nama" => $nama,
            "nim" => $nim,
            "fakultas" => $fakultas,
            "kategori" => $kategori,
            "judul" => $judul,
            "isi" => $isi,
            "status" => $status,
            "created_at" => formatIsoTimestamp($createdAt)
        ], 201);
    }

    // GET /api/aspirasi
    if ($path === '/api/aspirasi' && $requestMethod === 'GET') {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

        if ($limit < 1 || $limit > 500) {
            sendJson(["detail" => "limit must be between 1 and 500"], 400);
        }

        $stmt = $pdo->prepare("SELECT * FROM aspirasi ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $results = [];
        foreach ($rows as $row) {
            $results[] = [
                "id" => $row['id'],
                "nama" => $row['nama'],
                "nim" => $row['nim'],
                "fakultas" => $row['fakultas'],
                "kategori" => $row['kategori'],
                "judul" => $row['judul'],
                "isi" => $row['isi'],
                "status" => $row['status'],
                "created_at" => formatIsoTimestamp($row['created_at'])
            ];
        }
        sendJson($results);
    }

    // GET /api/aspirasi/stats
    if ($path === '/api/aspirasi/stats' && $requestMethod === 'GET') {
        $stmtTotal = $pdo->query("SELECT COUNT(*) FROM aspirasi");
        $total = (int)$stmtTotal->fetchColumn();

        $stmtPending = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE status = 'pending'");
        $pending = (int)$stmtPending->fetchColumn();

        $stmtDiproses = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE status = 'diproses'");
        $diproses = (int)$stmtDiproses->fetchColumn();

        $stmtSelesai = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE status = 'selesai'");
        $selesai = (int)$stmtSelesai->fetchColumn();

        sendJson([
            "total" => $total,
            "pending" => $pending,
            "diproses" => $diproses,
            "selesai" => $selesai
        ]);
    }

    // If no API endpoint matched
    sendJson(["detail" => "Endpoint not found"], 404);
}

// Web routes (Admin portal)
if ($path === '/admin/login') {
    if (isset($_SESSION['admin_user'])) {
        header('Location: /admin/dashboard');
        exit;
    }

    if ($requestMethod === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_user'] = $user['username'];
            header('Location: /admin/dashboard');
            exit;
        } else {
            $_SESSION['login_error'] = "Username atau password salah.";
            header('Location: /admin/login');
            exit;
        }
    }

    require_once __DIR__ . '/views/login.php';
    exit;
}

if ($path === '/admin/dashboard') {
    if (!isset($_SESSION['admin_user'])) {
        header('Location: /admin/login');
        exit;
    }

    // Load statistics for dashboard view
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM aspirasi");
    $totalCount = (int)$stmtTotal->fetchColumn();

    $stmtPending = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE status = 'pending'");
    $pendingCount = (int)$stmtPending->fetchColumn();

    $stmtDiproses = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE status = 'diproses'");
    $diprosesCount = (int)$stmtDiproses->fetchColumn();

    $stmtSelesai = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE status = 'selesai'");
    $selesaiCount = (int)$stmtSelesai->fetchColumn();

    // Filters and Search
    $search = trim($_GET['search'] ?? '');
    $statusFilter = trim($_GET['status'] ?? '');
    $kategoriFilter = trim($_GET['kategori'] ?? '');

    $query = "SELECT * FROM aspirasi WHERE 1=1";
    $params = [];

    if ($search !== '') {
        $query .= " AND (nama LIKE ? OR nim LIKE ? OR judul LIKE ? OR isi LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
    }

    if ($statusFilter !== '') {
        $query .= " AND status = ?";
        $params[] = $statusFilter;
    }

    if ($kategoriFilter !== '') {
        $query .= " AND kategori = ?";
        $params[] = $kategoriFilter;
    }

    $query .= " ORDER BY created_at DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $aspirasiList = $stmt->fetchAll();

    require_once __DIR__ . '/views/dashboard.php';
    exit;
}

if ($path === '/admin/aspirasi/update-status' && $requestMethod === 'POST') {
    if (!isset($_SESSION['admin_user'])) {
        http_response_code(403);
        echo "Access denied";
        exit;
    }

    $id = $_POST['id'] ?? '';
    $status = $_POST['status'] ?? '';
    $allowedStatus = ['pending', 'diproses', 'selesai'];

    if (!empty($id) && in_array($status, $allowedStatus)) {
        $stmt = $pdo->prepare("UPDATE aspirasi SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    }

    header('Location: /admin/dashboard');
    exit;
}

if ($path === '/admin/logout') {
    unset($_SESSION['admin_user']);
    session_destroy();
    header('Location: /admin/login');
    exit;
}

// Fallback to 404
http_response_code(404);
echo "Page not found";
exit;
