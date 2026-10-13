<?php
require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../models/Dokumen.php';
require_once __DIR__ . '/../models/UserModel.class.php';

class DashboardController extends Controller {
    private $pdo;
    private $dokumenModel;

    public function __construct($pdo) {
        $this->pdo          = $pdo;
        $this->dokumenModel = new Dokumen($pdo);
    }

public function client() {
    $this->requireClient();

    $filters = [
        'status' => $_GET['status'] ?? '',
        'jenis'  => $_GET['jenis']  ?? '',
        'search' => $_GET['search'] ?? '',
    ];

    // DEBUG sementara
    error_log("=== FILTERS ===");
    error_log(json_encode($filters));

    $dokumens = $this->dokumenModel->milikUser($_SESSION['user_id'], $filters);

    require_once __DIR__ . '/../views/dashboard/client.php';
}

    public function admin() {
        $this->requireAdmin();

        $filters  = [
            'status'       => $_GET['status'] ?? '',
            'komisi'       => $_GET['komisi'] ?? '',
            'jenis'        => $_GET['jenis'] ?? '',
            'belum_review' => $_GET['belum_review'] ?? '',
            'search'       => $_GET['search'] ?? '',
        ];
        $dokumens = $this->dokumenModel->semua($filters);

        require_once __DIR__ . '/../views/dashboard/admin.php';
    }
}