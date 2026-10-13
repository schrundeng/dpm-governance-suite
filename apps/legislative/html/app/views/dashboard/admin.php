<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — Repositori Undang-Undang</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        :root {
            --primary-yellow: #ca8a04;
            --light-yellow: #fef9c3;
            --border-color: #e5e7eb;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --bg-light: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            background: #ffffff;
        }

        .navbar h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
        }

        .navbar-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .navbar-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.15s;
        }

        .navbar-links a:hover {
            color: var(--primary-yellow);
        }

        .btn-logout {
            padding: 0.4rem 1rem;
            background: #fee2e2;
            color: #b91c1c !important;
            border-radius: 6px;
            font-weight: 600 !important;
        }

        .btn-logout:hover {
            background: #fca5a5;
        }

        .portal-text-short {
            display: none;
        }

        /* Container */
        .container {
            max-width: 1200px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .dashboard-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
        }

        .header-actions {
            display: flex;
            gap: 0.65rem;
            align-items: center;
        }

        .btn-primary, .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0 1.15rem;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8125rem !important;
            font-weight: 600;
            line-height: 1;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            box-sizing: border-box;
            white-space: nowrap;
            height: 38px;
        }

        .btn-primary {
            background: var(--primary-yellow);
            color: #ffffff;
            border: 1px solid var(--primary-yellow);
        }

        .btn-primary:hover {
            background: #a16207;
            border-color: #a16207;
            color: #ffffff;
        }

        .btn-secondary {
            background: #ffffff;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        /* Alerts */
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        /* Table Card */
        .card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            margin-bottom: 2rem;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            display: block;
        }

        table {
            width: 100%;
            min-width: 820px;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.875rem;
        }

        th {
            background: #fafafa;
            padding: 0.85rem 1rem;
            font-weight: 600;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            color: var(--text-dark);
        }

        .col-jenis {
            min-width: 140px;
            white-space: nowrap;
        }

        .col-nomor {
            min-width: 70px;
            white-space: nowrap;
        }

        .col-tahun {
            min-width: 70px;
            white-space: nowrap;
        }

        .col-tentang {
            min-width: 260px;
            max-width: 380px;
            white-space: normal;
            line-height: 1.4;
            word-break: break-word;
        }

        .col-file {
            min-width: 130px;
            max-width: 160px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .col-aksi {
            width: 120px;
            min-width: 120px;
            text-align: center;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge-pinned {
            background: var(--light-yellow);
            color: var(--primary-yellow);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .action-links {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .btn-action svg {
            width: 1.05rem;
            height: 1.05rem;
        }
        .btn-action-detail:hover {
            background: #f3f4f6;
            color: var(--primary-yellow);
            border-color: #d1d5db;
        }
        .btn-action-edit:hover {
            background: #fdfaf2;
            color: #d97706;
            border-color: #f59e0b;
        }
        .btn-action-delete {
            color: #dc2626;
        }
        .btn-action-delete:hover {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fca5a5;
        }

        /* Empty state */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: var(--text-muted);
        }

        @media (max-width: 1024px) {
            .container {
                padding: 0 1rem;
            }

            table {
                font-size: 0.8rem;
            }

            th, td {
                padding: 0.75rem 0.75rem;
            }

            .stats {
                gap: 0.75rem;
            }

            .stat-card {
                padding: 0.75rem 1rem;
                min-width: 120px;
            }

            .stat-card .num {
                font-size: 1.4rem;
            }
        }

        /* Mobile (max 768px) */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.75rem 1rem;
                flex-wrap: nowrap;
                gap: 0.5rem;
                align-items: center;
            }

            .navbar h1 {
                font-size: 0.95rem;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .navbar-links {
                gap: 0.6rem;
                flex-shrink: 0;
                align-items: center;
            }

            .user-greeting {
                display: none; /* sembunyikan "Halo, Nama" di mobile */
            }

            .portal-text-full {
                display: none;
            }

            .portal-text-short {
                display: inline;
                font-size: 0.825rem;
            }

            .btn-logout {
                padding: 0.3rem 0.7rem;
                font-size: 0.775rem !important;
            }

            .container {
                margin: 1rem auto;
                padding: 0 0.75rem;
            }

            .dashboard-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .dashboard-header h2 {
                font-size: 1.15rem;
            }

            .header-actions {
                flex-direction: column;
                width: 100%;
                gap: 0.6rem;
            }

            .header-actions .btn-primary,
            .header-actions .btn-secondary {
                width: 100%;
                height: 42px;
                font-size: 0.825rem !important;
                padding: 0 1rem;
                justify-content: center;
                box-sizing: border-box;
            }

            /* Stats jadi 2 kolom di mobile */
            .stats {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .stat-card {
                min-width: unset;
                padding: 0.75rem;
            }

            .stat-card .num {
                font-size: 1.3rem;
            }

            .stat-card .label {
                font-size: 0.75rem;
            }

            /* Filter jadi column di mobile */
            .filters {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
                padding: 0.75rem;
            }

            .filters select,
            .filters button {
                width: 100%;
                padding: 0.6rem 0.75rem;
                font-size: 0.875rem;
            }

            .filters label {
                font-size: 0.875rem;
            }

            /* Tabel scrollable horizontal */
            .card {
                border-radius: 8px;
                overflow: hidden;
            }

            table {
                min-width: 820px;
                font-size: 0.8125rem;
            }

            th, td {
                padding: 0.65rem 0.85rem;
            }

            /* Alert */
            .success, .error {
                font-size: 0.8rem;
                padding: 0.6rem 0.75rem;
            }
        }

        /* Small mobile (max 480px) */
        @media (max-width: 480px) {
            .navbar {
                padding: 0.65rem 0.75rem;
            }

            .navbar h1 {
                font-size: 0.875rem;
            }

            .navbar-links {
                gap: 0.45rem;
            }

            .portal-text-short {
                font-size: 0.8rem;
            }

            .btn-logout {
                padding: 0.25rem 0.55rem;
                font-size: 0.75rem !important;
            }

            .dashboard-header h2 {
                font-size: 1.05rem;
            }

            table {
                min-width: 820px;
                font-size: 0.8rem;
            }

            th, td {
                padding: 0.6rem 0.75rem;
            }
        }

        .btn-modal-close {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .btn-modal-close:hover {
            background: #f3f4f6;
            color: var(--text-dark);
        }
    </style>
</head>
<body>

    <!-- Admin Navigation -->
    <nav class="navbar">
        <h1>DPM BALeg — Admin</h1>
        <div class="navbar-links">
            <span class="user-greeting" style="font-size: 0.9rem; color: var(--text-muted);">Halo, <b><?= htmlspecialchars($_SESSION['user_nama']) ?></b></span>
            <a href="/" target="_blank">
                <span class="portal-text-full">Lihat Portal Publik</span>
                <span class="portal-text-short">Portal</span>
            </a>
            <a href="/auth/logout" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Keluar</a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert-error" style="background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; border-left: 4px solid #ef4444; margin-bottom: 1.5rem; font-size: 0.9rem;"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="dashboard-header">
            <div>
                <h2>Daftar Undang-Undang & Peraturan</h2>
                <p style="font-size: 0.875rem; color: var(--text-muted); margin-top: 0.25rem;">Kelola dokumen peraturan perundang-undangan disini.</p>
            </div>
            <div class="header-actions">
                <button class="btn-secondary" onclick="openCategoryModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:4px; width:0.95rem; height:0.95rem; color:var(--primary-yellow);"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg> Kelola Jenis Peraturan
                </button>
                <a href="/admin/document/create" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:4px; width:0.95rem; height:0.95rem;"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg> Tambah Dokumen Baru
                </a>
            </div>
        </div>

        <!-- Document Grid Card (Full Width) -->
        <div class="card">
            <?php if (empty($documents)): ?>
                <div class="empty-state">
                    <p style="margin-bottom: 1rem;">Belum ada dokumen yang diunggah.</p>
                    <a href="/admin/document/create" class="btn-primary">Mulai Unggah Dokumen</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th class="col-jenis">Jenis Peraturan</th>
                                <th class="col-nomor">Nomor</th>
                                <th class="col-tahun">Tahun</th>
                                <th class="col-tentang">Tentang</th>
                                <th class="col-file">File PDF</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td class="col-jenis" style="font-weight: 700;">
                                        <?= htmlspecialchars($doc['jenis']) ?>
                                        <?php if ($doc['is_pinned']): ?>
                                            <span class="badge-pinned" title="Default Home" style="display: inline-flex; align-items: center; gap: 0.2rem; padding: 2px 6px; font-size: 0.65rem; margin-left: 6px; vertical-align: middle;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:0.8rem; height:0.8rem; stroke-width:3px; color: var(--primary-yellow);"><line x1="12" x2="12" y1="17" y2="22"/><path d="M5 17h14v-1.76a2 2 0 0 0-.44-1.24l-2.33-2.91a8.4 8.4 0 0 1-1.23-4.57V4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v2.52a8.4 8.4 0 0 1-1.23 4.57L5.44 14a2 2 0 0 0-.44 1.24Z"/></svg> Pinned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="col-nomor"><?= htmlspecialchars($doc['nomor']) ?></td>
                                    <td class="col-tahun"><?= htmlspecialchars($doc['tahun']) ?></td>
                                    <td class="col-tentang"><?= htmlspecialchars($doc['tentang']) ?></td>
                                    <td class="col-file" title="<?= htmlspecialchars($doc['nama_asli']) ?>"><?= htmlspecialchars($doc['nama_asli']) ?></td>
                                    <td class="col-aksi">
                                        <div class="action-links" style="justify-content: center;">
                                            <a href="/admin/document/detail?id=<?= $doc['id'] ?>" class="btn-action btn-action-detail" title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                            <a href="/admin/document/edit?id=<?= $doc['id'] ?>" class="btn-action btn-action-edit" title="Ubah">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                            </a>
                                            <a href="/admin/document/delete?id=<?= $doc['id'] ?>" 
                                               class="btn-action btn-action-delete" 
                                               title="Hapus"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus peraturan ini? File di disk juga akan dihapus secara permanen.')">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Category Management Modal -->
    <div id="category-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.4); z-index: 100; justify-content: center; align-items: center;">
        <div style="background: #ffffff; padding: 2rem; border-radius: 12px; width: 100%; max-width: 450px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); border: 1px solid var(--border-color); position: relative; font-family: inherit;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-dark);">Kelola Jenis Peraturan</h3>
                <button onclick="closeCategoryModal()" class="btn-modal-close"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" style="width:1.1rem; height:1.1rem;"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg></button>
            </div>
            
            <!-- Category list -->
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem; max-height: 200px; overflow-y: auto; margin-bottom: 1.5rem; padding-right: 0.5rem;">
                <?php if (empty($categories)): ?>
                    <li style="font-size: 0.85rem; color: var(--text-muted); text-align: center; padding: 1rem 0;">Belum ada jenis peraturan</li>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <li style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; padding: 0.4rem 0; border-bottom: 1px solid #f3f4f6;">
                            <span><b><?= htmlspecialchars($cat['nama']) ?></b></span>
                            <a href="/admin/category/delete?id=<?= $cat['id'] ?>" 
                               class="btn-action btn-action-delete"
                               title="Hapus"
                               style="width: 28px; height: 28px;"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus jenis peraturan ini? Pengaruh ke dokumen yang sudah ada hanya tidak akan muncul dalam opsi dropdown.')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 0.95rem; height: 0.95rem;"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <!-- Add Category Form -->
            <form action="/admin/category/create" method="POST" style="display: flex; flex-direction: column; gap: 0.75rem; border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <label for="new_cat" style="font-size: 0.8rem; font-weight: 600; color: var(--text-dark);">Tambah Jenis Peraturan baru</label>
                <input type="text" id="new_cat" name="nama" placeholder="Contoh: Perda" required style="padding: 0.6rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.85rem; outline: none; font-family: inherit; width: 100%;">
                <button type="submit" class="btn-primary" style="padding: 0.5rem; font-size: 0.85rem; display: flex; justify-content: center; width: 100%;">Tambah Jenis</button>
            </form>
        </div>
    </div>

    <script>
        function openCategoryModal() {
            document.getElementById('category-modal').style.display = 'flex';
        }
        function closeCategoryModal() {
            document.getElementById('category-modal').style.display = 'none';
        }
    </script>

</body>
</html>
