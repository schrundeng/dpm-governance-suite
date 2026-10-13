<?php
require_once __DIR__ . '/Controller.class.php';
require_once __DIR__ . '/../models/Dokumen.php';

class DashboardController extends Controller {
    private $pdo;
    private $dokumenModel;

    public function __construct($pdo) {
        $this->pdo          = $pdo;
        $this->dokumenModel = new Dokumen($pdo);
    }

    public function client() {
        $search = $_GET['search'] ?? '';

        $currentPath = $_GET['path'] ?? '';
        $pathSegments = array_filter(explode('/', $currentPath));
        $level = count($pathSegments);

        $folders = [];
        $files = [];
        $category = '';
        $year = '';

        if (!empty($search)) {
            // Bypass folder navigation on search
            $files = $this->dokumenModel->semua(['search' => $search]);
            $level = -1; // special search level
        } else {
            if ($level === 0) {
                // Level 0: list of categories
                $folders = $this->dokumenModel->semuaJenis();
            } elseif ($level === 1) {
                // Level 1: list of years for category
                $category = array_values($pathSegments)[0];
                $folders = $this->dokumenModel->tahunKategori($category);
            } else {
                // Level 2+: list of laws in category/year
                $category = array_values($pathSegments)[0];
                $year = array_values($pathSegments)[1];
                $files = $this->dokumenModel->semua(['jenis' => $category, 'tahun' => $year]);
            }
        }

        // Determine active document
        $activeDoc = null;
        $activeRelations = null;
        $activeId = intval($_GET['id'] ?? 0);

        if ($activeId > 0) {
            $activeDoc = $this->dokumenModel->findById($activeId);
        } else {
            // Find pinned first, if none exist load the latest
            $activeDoc = $this->dokumenModel->findPinned();
            if (!$activeDoc) {
                $activeDoc = $this->dokumenModel->findLatest();
            }
        }

        if ($activeDoc) {
            $activeRelations = $this->dokumenModel->getRelations($activeDoc['id']);
        }

        $this->loadView('dashboard/client', [
            'folders'         => $folders,
            'files'           => $files,
            'level'           => $level,
            'pathSegments'    => array_values($pathSegments),
            'currentPath'     => $currentPath,
            'activeDoc'       => $activeDoc,
            'activeRelations' => $activeRelations,
            'search'          => $search
        ]);
    }

    public function admin() {
        $this->requireAdmin();

        $filters = [
            'search' => $_GET['search'] ?? '',
            'jenis'  => $_GET['jenis'] ?? '',
            'tahun'  => $_GET['tahun'] ?? '',
        ];

        $documents = $this->dokumenModel->semua($filters);
        $categories = $this->dokumenModel->semuaKategori();
        $csrfToken = $this->generateCsrfToken();

        $this->loadView('dashboard/admin', [
            'documents'  => $documents,
            'categories' => $categories,
            'filters'    => $filters,
            'csrfToken'  => $csrfToken
        ]);
    }
}
