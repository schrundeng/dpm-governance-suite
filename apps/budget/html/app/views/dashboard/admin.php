<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — WebDPM</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; min-height: 100vh; }
        .navbar { background: #1e3a5f; color: #fff; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 1.1rem; }
        .navbar-right { display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; }
        .navbar a { color: #fff; text-decoration: none; }
        .container { max-width: 100%; margin: 2rem auto; padding: 0 2rem; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .top-bar h2 { font-size: 1.2rem; color: #1f2937; }
        .filters { background: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; box-shadow: 0 1px 3px rgba(0,0,0,0.1); align-items: center; }
        .filters select { padding: 0.4rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; }
        .filters label { font-size: 0.875rem; color: #374151; display: flex; align-items: center; gap: 0.4rem; }
        .filters button { padding: 0.4rem 0.75rem; background: #1e3a5f; color: #fff; border: none; border-radius: 6px; font-size: 0.875rem; cursor: pointer; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th { background: #f9fafb; padding: 0.75rem 1rem; text-align: left; font-size: 0.8rem; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
        td { padding: 0.875rem 1rem; font-size: 0.875rem; color: #374151; border-bottom: 1px solid #f3f4f6; vertical-align: middle; word-break: break-word; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9fafb; }
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-dikirim   { background: #dbeafe; color: #1d4ed8; }
        .badge-in-review { background: #fef3c7; color: #92400e; }
        .badge-diterima  { background: #d1fae5; color: #065f46; }
        .badge-ditolak   { background: #fee2e2; color: #b91c1c; }
        .btn { padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.8rem; text-decoration: none; cursor: pointer; border: none; display: inline-block; }
        .btn-review  { background: #fef3c7; color: #92400e; }
        .btn-tindak  { background: #1e3a5f; color: #fff; }
        .btn-tindak:hover { background: #1e40af; }
        .btn-disabled { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; }
        .empty { text-align: center; padding: 3rem; color: #9ca3af; }
        .success { background: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .error { background: #fee2e2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .stats { display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .stat-card { background: #fff; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); flex: 1; min-width: 150px; }
        .stat-card .num { font-size: 1.8rem; font-weight: 700; color: #1e3a5f; }
        .stat-card .label { font-size: 0.8rem; color: #6b7280; margin-top: 2px; }

        /* Pagination & Search styling */
        .search-box input {
            padding: 0.4rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            min-width: 250px;
        }
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #fff;
            border-top: 1px solid #e5e7eb;
        }
        .pagination-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .pagination-info {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .pagination-size {
            font-size: 0.875rem;
            color: #4b5563;
        }
        .pagination-size select {
            padding: 0.25rem 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            margin-left: 0.25rem;
            background: #fff;
            color: #374151;
        }
        .pagination-buttons {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .pagination-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
            color: #374151;
            font-size: 0.875rem;
            cursor: pointer;
            height: 32px;
            min-width: 32px;
            transition: all 0.2s ease;
        }
        .pagination-btn:hover {
            background: #f9fafb;
        }
        .pagination-btn.active {
            background: #1e3a5f;
            color: #fff;
            border-color: #1e3a5f;
        }
        .pagination-btn.disabled {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
            border-color: #e5e7eb;
        }

        /* Icon Button Styles */
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-review-icon {
            background: #fef3c7;
            color: #d97706;
            border-color: #fde68a;
        }
        .btn-review-icon:hover {
            background: #fde68a;
            color: #92400e;
        }
        .btn-tindak-icon {
            background: #eff6ff;
            color: #2563eb;
            border-color: #dbeafe;
        }
        .btn-tindak-icon:hover {
            background: #dbeafe;
            color: #1e40af;
        }
        .btn-detail-icon {
            background: #f3f4f6;
            color: #4b5563;
            border-color: #e5e7eb;
        }
        .btn-detail-icon:hover {
            background: #e5e7eb;
            color: #1f2937;
        }
        .btn-update-icon {
            background: #ecfdf5;
            color: #059669;
            border-color: #d1fae5;
        }
        .btn-update-icon:hover {
            background: #d1fae5;
            color: #065f46;
        }
        .btn-archive-icon {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }
        .btn-archive-icon:hover {
            background: #fecaca;
            color: #991b1b;
        }
        .btn-disabled-icon {
            background: #f3f4f6;
            color: #9ca3af;
            border-color: #e5e7eb;
            cursor: not-allowed;
            pointer-events: none;
        }
        .btn-danger-light {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-danger-light:hover {
            background: #fecaca;
            color: #991b1b;
            border-color: #fca5a5;
        }

        /* Tooltip using data-tooltip */
        [data-tooltip] {
            position: relative;
        }
        [data-tooltip]::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #1f2937;
            color: #fff;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0.2s;
            z-index: 100;
            pointer-events: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        [data-tooltip]::after {
            content: "";
            position: absolute;
            bottom: 115%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: #1f2937 transparent transparent transparent;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0.2s;
            z-index: 100;
            pointer-events: none;
        }
        [data-tooltip]:hover::before,
        [data-tooltip]:hover::after {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 768px) {
            .navbar { padding: 1rem; flex-direction: column; gap: 0.5rem; text-align: center; }
            .navbar-right { justify-content: center; }
            .container { margin: 1rem auto; }
            .top-bar { flex-direction: column; align-items: stretch; gap: 1rem; text-align: center; }
            .filters { flex-direction: column; align-items: stretch; }
            .filters select, .filters button, .filters input, .filters a { width: 100%; text-align: center; margin-left: 0 !important; }
            .pagination-container { flex-direction: column; gap: 1rem; align-items: center; text-align: center; }
            .pagination-left { flex-direction: column; gap: 0.5rem; }
            .pagination-buttons { justify-content: center; width: 100%; flex-wrap: wrap; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <h1>WebDPM — Admin</h1>
    <div class="navbar-right">
        <a href="/auth/profile" style="display:inline-flex;align-items:center;gap:0.35rem;text-decoration:none;color:#fff;font-weight:500;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <?= htmlspecialchars($_SESSION['user_nama']) ?>
        </a>
        <a href="/auth/logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
    </div>
</nav>

<div class="container">
    <div class="top-bar">
        <h2>Dashboard Pengajuan</h2>
        <a href="/approval/arsip" class="btn-danger-light">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg> Arsip Kadaluarsa
        </a>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Stats -->
    <?php
    $totalDikirim  = count(array_filter($dokumens, fn($d) => $d['status'] === 'dikirim'));
    $totalReview   = count(array_filter($dokumens, fn($d) => $d['status'] === 'in-review'));
    $totalDiterima = count(array_filter($dokumens, fn($d) => $d['status'] === 'diterima'));
    $totalDitolak  = count(array_filter($dokumens, fn($d) => $d['status'] === 'ditolak'));
    ?>
    <div class="stats">
        <div class="stat-card">
            <div class="num"><?= count($dokumens) ?></div>
            <div class="label">Total Pengajuan</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#1d4ed8"><?= $totalDikirim ?></div>
            <div class="label">Baru Masuk</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#92400e"><?= $totalReview ?></div>
            <div class="label">In Review</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#065f46"><?= $totalDiterima ?></div>
            <div class="label">Diterima</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color:#b91c1c"><?= $totalDitolak ?></div>
            <div class="label">Ditolak</div>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" action="/dashboard/admin" class="filters">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="dikirim"   <?= ($_GET['status'] ?? '') === 'dikirim'   ? 'selected' : '' ?>>Dikirim</option>
            <option value="in-review" <?= ($_GET['status'] ?? '') === 'in-review' ? 'selected' : '' ?>>In Review</option>
            <option value="diterima"  <?= ($_GET['status'] ?? '') === 'diterima'  ? 'selected' : '' ?>>Diterima</option>
            <option value="ditolak"   <?= ($_GET['status'] ?? '') === 'ditolak'   ? 'selected' : '' ?>>Ditolak</option>
        </select>
        <select name="komisi">
            <option value="">Semua Komisi</option>
            <?php
            require_once __DIR__ . '/../../../app/controllers/AuthController.class.php';
            foreach (AuthController::$komisiList as $k):
            ?>
                <option value="<?= htmlspecialchars($k) ?>" <?= ($_GET['komisi'] ?? '') === $k ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="jenis">
            <option value="">Semua Jenis</option>
            <option value="RAB" <?= ($_GET['jenis'] ?? '') === 'RAB' ? 'selected' : '' ?>>RAB</option>
            <option value="LPJ" <?= ($_GET['jenis'] ?? '') === 'LPJ' ? 'selected' : '' ?>>LPJ</option>
        </select>
        <button type="submit">Filter</button>
        
        <a href="/dashboard/admin" style="padding:0.4rem 0.75rem;font-size:0.875rem;color:#6b7280;text-decoration:none;">Reset</a>

        <input type="text" id="tableSearch" placeholder="Cari ID, Nama, Judul, Komisi..." style="padding:0.4rem 0.75rem;border:1px solid #d1d5db;border-radius:6px;font-size:0.875rem;min-width:250px;margin-left:auto;" onkeydown="if(event.key === 'Enter') event.preventDefault();">
    </form>

    <!-- Tabel -->
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Pengajuan</th>
                    <th>Nama</th>
                    <th>Komisi</th>
                    <th>Judul</th>
                    <th>Jenis</th>
                    <th>Waktu Dikirim</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($dokumens)): ?>
                <tr><td colspan="9" class="empty">Tidak ada pengajuan.</td></tr>
            <?php else: ?>
                <?php foreach ($dokumens as $i => $d): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($d['id_pengajuan']) ?></td>
                    <td><?= htmlspecialchars($d['nama_user']) ?></td>
                    <td><?= htmlspecialchars($d['komisi']) ?></td>
                    <td><?= htmlspecialchars($d['judul']) ?></td>
                    <td><?= htmlspecialchars($d['jenis']) ?></td>
                    <td><?= date('d M Y, H:i', strtotime($d['created_at'])) ?></td>
                    <td>
                        <span class="badge badge-<?= $d['status'] ?>">
                            <?= ucfirst($d['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
                            <?php if ($d['status'] === 'dikirim'): ?>
                                <a href="/approval/review?id=<?= $d['id'] ?>" class="btn-icon btn-review-icon" data-tooltip="Set In-Review">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                </a>
                                <span class="btn-icon btn-disabled-icon" data-tooltip="Tindak Lanjut (Belum Tersedia)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </span>
                            <?php elseif ($d['status'] === 'in-review'): ?>
                                <a href="/approval/tindaklanjut?id=<?= $d['id'] ?>" class="btn-icon btn-tindak-icon" data-tooltip="Tindak Lanjut">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </a>
                            <?php elseif ($d['status'] === 'diterima'): ?>
                                <a href="/dokumen/detail?id=<?= $d['id'] ?>" class="btn-icon btn-detail-icon" data-tooltip="Detail Dokumen">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="/approval/reuploadFeedback?id=<?= $d['id'] ?>" class="btn-icon btn-update-icon" data-tooltip="Update File Feedback">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                </a>
                                <?php if (strtotime($d['created_at']) <= strtotime('-2 weeks')): ?>
                                    <a href="/approval/archive?id=<?= $d['id'] ?>" class="btn-icon btn-archive-icon" data-tooltip="Arsipkan" onclick="return confirm('Arsipkan dokumen ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><line x1="10" x2="14" y1="12" y2="12"/></svg>
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="/dokumen/detail?id=<?= $d['id'] ?>" class="btn-icon btn-detail-icon" data-tooltip="Detail Dokumen">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <?php if (strtotime($d['created_at']) <= strtotime('-2 weeks')): ?>
                                    <a href="/approval/archive?id=<?= $d['id'] ?>" class="btn-icon btn-archive-icon" data-tooltip="Arsipkan" onclick="return confirm('Arsipkan dokumen ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><line x1="10" x2="14" y1="12" y2="12"/></svg>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('table');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    if (rows.length === 0 || rows[0].querySelector('.empty')) return;

    let itemsPerPage = 5;
    let currentPage = 1;
    let filteredRows = [...rows];

    // Create pagination container
    const card = table.closest('.card');
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'pagination-container';
    card.appendChild(paginationContainer);

    const searchInput = document.getElementById('tableSearch');

    function updateTable() {
        const totalItems = filteredRows.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        // Hide all rows
        rows.forEach(row => row.style.display = 'none');

        // Show matching rows for current page
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        
        filteredRows.slice(start, end).forEach((row, index) => {
            row.style.display = '';
            const noCell = row.cells[0];
            if (noCell) {
                noCell.textContent = start + index + 1;
            }
        });

        renderPagination(totalPages, totalItems, start + 1, Math.min(end, totalItems));
    }

    function renderPagination(totalPages, totalItems, startItem, endItem) {
        paginationContainer.innerHTML = '';
        if (totalItems === 0) {
            paginationContainer.innerHTML = '<span class="pagination-info">Menampilkan 0-0 dari 0 data</span>';
            return;
        }

        const leftDiv = document.createElement('div');
        leftDiv.className = 'pagination-left';

        const info = document.createElement('span');
        info.className = 'pagination-info';
        info.innerHTML = `Menampilkan <b>${startItem}-${endItem}</b> dari <b>${totalItems}</b> data`;
        leftDiv.appendChild(info);

        // Entries size selector
        const sizeDiv = document.createElement('div');
        sizeDiv.className = 'pagination-size';
        sizeDiv.innerHTML = 'Tampilkan: ' +
            `<select id="pageSizeSelect">` +
                `<option value="5" ${itemsPerPage === 5 ? 'selected' : ''}>5</option>` +
                `<option value="10" ${itemsPerPage === 10 ? 'selected' : ''}>10</option>` +
                `<option value="15" ${itemsPerPage === 15 ? 'selected' : ''}>15</option>` +
                `<option value="20" ${itemsPerPage === 20 ? 'selected' : ''}>20</option>` +
            `</select> data`;
        
        leftDiv.appendChild(sizeDiv);
        paginationContainer.appendChild(leftDiv);

        const buttonsDiv = document.createElement('div');
        buttonsDiv.className = 'pagination-buttons';

        // Prev Button
        const prevBtn = document.createElement('button');
        prevBtn.className = `pagination-btn ${currentPage === 1 ? 'disabled' : ''}`;
        prevBtn.setAttribute('data-tooltip', 'Sebelumnya');
        prevBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><polyline points="15 18 9 12 15 6"></polyline></svg>';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', () => {
            currentPage--;
            updateTable();
        });
        buttonsDiv.appendChild(prevBtn);

        // Page Number Buttons
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentPage = i;
                updateTable();
            });
            buttonsDiv.appendChild(pageBtn);
        }

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.className = `pagination-btn ${currentPage === totalPages ? 'disabled' : ''}`;
        nextBtn.setAttribute('data-tooltip', 'Berikutnya');
        nextBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><polyline points="9 18 15 12 9 6"></polyline></svg>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', () => {
            currentPage++;
            updateTable();
        });
        buttonsDiv.appendChild(nextBtn);

        paginationContainer.appendChild(buttonsDiv);

        // Listen for itemsPerPage change
        document.getElementById('pageSizeSelect').addEventListener('change', function() {
            itemsPerPage = parseInt(this.value);
            currentPage = 1;
            updateTable();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            filteredRows = rows.filter(row => {
                const cells = Array.from(row.cells).slice(1, -1); // Skip No and Actions
                return cells.some(cell => cell.textContent.toLowerCase().includes(query));
            });

            const existingEmpty = tbody.querySelector('.search-empty');
            if (filteredRows.length === 0) {
                if (!existingEmpty) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.className = 'search-empty';
                    emptyRow.innerHTML = `<td colspan="${table.rows[0].cells.length}" class="empty">Pencarian "${this.value}" tidak ditemukan.</td>`;
                    tbody.appendChild(emptyRow);
                }
            } else if (existingEmpty) {
                existingEmpty.remove();
            }

            currentPage = 1;
            updateTable();
        });
    }

    updateTable();
});
</script>

    </div>
</div>
</body>
</html>