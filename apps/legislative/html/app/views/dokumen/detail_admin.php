<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau Dokumen — Admin</title>
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
            overflow: hidden;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            background: #ffffff;
            flex-shrink: 0;
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

        /* Split Layout */
        .workspace {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Left Info Panel (40%) */
        .info-panel {
            width: 400px;
            border-right: 1px solid var(--border-color);
            padding: 2rem;
            overflow-y: auto;
            background: #fafafa;
            display: flex;
            flex-direction: column;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-dark);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            transition: color 0.15s;
        }

        .btn-back:hover {
            color: var(--primary-yellow);
        }

        .doc-header {
            margin-bottom: 1.5rem;
        }

        .doc-tag {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-yellow);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .doc-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            line-height: 1.6rem;
        }

        .meta-list {
            list-style: none;
            margin-bottom: 2rem;
        }

        .meta-item {
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }

        .meta-label {
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .meta-value {
            line-height: 1.25rem;
        }

        .badge-pinned {
            display: inline-block;
            background: var(--light-yellow);
            color: var(--primary-yellow);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Action Buttons */
        .actions-block {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: auto;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.7rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-yellow {
            background: var(--primary-yellow);
            color: #111827;
            border: none;
        }

        .btn-yellow:hover {
            background: #ca8a04;
            color: #ffffff;
        }

        .btn-outline {
            background: #ffffff;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-danger {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        .btn-danger:hover {
            background: #fca5a5;
        }

        /* Right PDF Preview (60%) */
        .preview-panel {
            flex: 1;
            background: #525659;
            position: relative;
        }

        .preview-panel iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .relation-link {
            display: inline-block;
            background: #ffffff;
            border: 1px solid var(--border-color);
            padding: 3px 8px;
            border-radius: 4px;
            text-decoration: none;
            color: var(--primary-yellow);
            font-weight: 600;
            font-size: 0.75rem;
            margin-top: 4px;
            transition: background 0.15s;
        }

        .relation-link:hover {
            background: var(--light-yellow);
        }

        .back-text-short {
            display: none;
        }

        @media (max-width: 768px) {
            body {
                overflow-y: auto;
                height: auto;
                min-height: 100vh;
            }

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
                font-size: 0.825rem;
                flex-shrink: 0;
                align-items: center;
            }

            .user-greeting {
                display: none;
            }

            .back-text-full {
                display: none;
            }

            .back-text-short {
                display: inline;
                font-size: 0.825rem;
            }

            .workspace {
                flex-direction: column;
                overflow: visible;
                height: auto;
            }

            .info-panel {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
                padding: 1.25rem 1rem;
                overflow: visible;
            }

            .preview-panel {
                width: 100%;
                height: 75vh;
                min-height: 480px;
                flex: none;
            }

            .actions-block {
                margin-top: 1.5rem;
            }
        }

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

            .back-text-short {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

    <!-- Admin Navigation -->
    <nav class="navbar">
        <h1>DPM BALeg — Admin</h1>
        <div class="navbar-links">
            <span class="user-greeting" style="font-size: 0.9rem; color: var(--text-muted);">Halo, <b><?= htmlspecialchars($_SESSION['user_nama']) ?></b></span>
            <a href="/dashboard/admin">
                <span class="back-text-full">Kembali ke Dashboard</span>
                <span class="back-text-short">Dashboard</span>
            </a>
            <a href="/auth/logout" style="color: #dc2626; font-weight: 600;" onclick="return confirm('Apakah Anda yakin?')">Keluar</a>
        </div>
    </nav>

    <!-- Workspace Split -->
    <div class="workspace">
        
        <!-- Left Info Panel -->
        <div class="info-panel">
            <a href="/dashboard/admin" class="btn-back"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:1rem; height:1rem; display:inline-block; vertical-align:middle; margin-right:4px;"><polyline points="15 18 9 12 15 6"/></svg> Kembali ke Dashboard</a>
            
            <div class="doc-header">
                <div class="doc-tag"><?= htmlspecialchars($dokumen['jenis']) ?> No. <?= htmlspecialchars($dokumen['nomor']) ?> / <?= htmlspecialchars($dokumen['tahun']) ?></div>
                <h2 class="doc-title">UU No. <?= htmlspecialchars($dokumen['nomor']) ?> Tahun <?= htmlspecialchars($dokumen['tahun']) ?></h2>
            </div>

            <ul class="meta-list">
                <li class="meta-item">
                    <div class="meta-label">Tentang / Judul</div>
                    <div class="meta-value" style="font-weight: 600;"><?= htmlspecialchars($dokumen['tentang']) ?></div>
                </li>
                <?php if ($dokumen['keterangan']): ?>
                    <li class="meta-item">
                        <div class="meta-label">Keterangan Tambahan</div>
                        <div class="meta-value"><?= htmlspecialchars($dokumen['keterangan']) ?></div>
                    </li>
                <?php endif; ?>
                <li class="meta-item">
                    <div class="meta-label">File di Server</div>
                    <div class="meta-value" style="font-family: monospace; font-size: 0.8rem; word-break: break-all;"><?= htmlspecialchars($dokumen['nama_file']) ?></div>
                </li>
                <li class="meta-item">
                    <div class="meta-label">Nama Asli File</div>
                    <div class="meta-value" style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($dokumen['nama_asli']) ?></div>
                </li>
                <li class="meta-item">
                    <div class="meta-label">Tanggal Unggah</div>
                    <div class="meta-value"><?= date('d M Y, H:i', strtotime($dokumen['created_at'])) ?></div>
                </li>
                <li class="meta-item">
                    <div class="meta-label">Status Pinned</div>
                    <div class="meta-value">
                        <?php if ($dokumen['is_pinned']): ?>
                            <span class="badge-pinned">Default Utama (Home)</span>
                        <?php else: ?>
                            <span style="color: var(--text-muted); font-size: 0.85rem;">Tidak Pinned</span>
                        <?php endif; ?>
                    </div>
                </li>

                <!-- Hubungan Peraturan Terkait -->
                <?php if (!empty($relations['outgoing']) || !empty($relations['incoming'])): ?>
                    <li class="meta-item" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                        <div class="meta-label" style="color: #111827; font-weight: 700; margin-bottom: 8px;">Peraturan Terkait</div>
                        
                        <!-- Mengubah/Mencabut -->
                        <?php foreach ($relations['outgoing'] as $rel): ?>
                            <div style="margin-bottom: 0.5rem;">
                                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #b45309;"><?= htmlspecialchars($rel['tipe_relasi']) ?>:</span><br>
                                <a href="/admin/document/detail?id=<?= $rel['id'] ?>" class="relation-link">
                                    <?= htmlspecialchars($rel['jenis']) ?> No. <?= htmlspecialchars($rel['nomor']) ?>/<?= htmlspecialchars($rel['tahun']) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>

                        <!-- Diubah/Dicabut oleh -->
                        <?php foreach ($relations['incoming'] as $rel): ?>
                            <div style="margin-bottom: 0.5rem;">
                                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #15803d;">
                                    <?= ($rel['tipe_relasi'] === 'mengubah') ? 'Diubah oleh' : 'Dicabut oleh' ?>:
                                </span><br>
                                <a href="/admin/document/detail?id=<?= $rel['id'] ?>" class="relation-link">
                                    <?= htmlspecialchars($rel['jenis']) ?> No. <?= htmlspecialchars($rel['nomor']) ?>/<?= htmlspecialchars($rel['tahun']) ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="actions-block">
                <a href="/dokumen/download?id=<?= $dokumen['id'] ?>" class="btn btn-yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:1.1rem; height:1.1rem; display:inline-block; vertical-align:middle; margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Download PDF
                </a>
                <a href="/admin/document/edit?id=<?= $dokumen['id'] ?>" class="btn btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:1.1rem; height:1.1rem; display:inline-block; vertical-align:middle; margin-right:4px;"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg> Ubah Metadata
                </a>
                <a href="/admin/document/delete?id=<?= $dokumen['id'] ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Apakah Anda yakin ingin menghapus peraturan ini secara permanen?')">
                    Hapus Peraturan
                </a>
            </div>
        </div>

        <!-- Right PDF Preview Panel (PDF.js for smooth performance across Chrome/Edge/Firefox) -->
        <div class="preview-panel">
            <iframe src="/pdfjs/web/viewer.html?file=<?= urlencode('/uploads/' . $dokumen['nama_file']) ?>"></iframe>
        </div>

    </div>

</body>
</html>
