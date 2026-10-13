<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Undang-Undang — DPM Baleg</title>
    <script>
        if (localStorage.getItem('dark-mode') === 'true') {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
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
            --bg-sidebar: #fafafa;
            --bg-hover: #f3f4f6;
            --bg-active: #e5e7eb;
        }

        html.dark-mode {
            --border-color: #374151;
            --text-dark: #f9fafb;
            --text-muted: #9ca3af;
            --bg-light: #111827;
            --bg-sidebar: #1f2937;
            --light-yellow: #3e3215;
            --bg-hover: #374151;
            --bg-active: #4b5563;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-dark);
            background-color: var(--bg-light);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Top Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-light);
            z-index: 10;
        }

        header .logo-area h1 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.025em;
        }

        header .logo-area p {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        header .header-actions a {
            display: inline-flex;
            align-items: center;
            padding: 0.4rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        header .header-actions a:hover {
            background: var(--bg-hover);
            border-color: var(--border-color);
        }

        /* Main Workspace Container */
        .workspace {
            display: flex;
            flex: 1;
            overflow: hidden;
            position: relative;
        }

        /* Left Sidebar: Document Navigation List */
        .sidebar {
            width: 360px;
            min-width: 240px;
            max-width: 75vw;
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
        }

        body.resizing-active,
        .workspace.resizing-active {
            user-select: none !important;
            cursor: col-resize !important;
        }

        body.resizing-active iframe,
        .workspace.resizing-active iframe {
            pointer-events: none !important;
        }

        /* Sidebar Search Panel */
        .search-panel {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-light);
        }

        .search-form {
            display: flex;
            gap: 0.5rem;
        }

        .search-form input {
            flex: 1;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.15s ease;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        .search-form input::placeholder {
            color: var(--text-muted);
        }

        .search-form input:focus {
            border-color: var(--primary-yellow);
        }

        .search-form button, .btn-clear {
            padding: 0.5rem 0.75rem;
            background: var(--primary-yellow);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .search-form button:hover {
            background: #a16207;
        }

        .btn-clear {
            background: var(--bg-hover);
            color: var(--text-dark);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            flex-shrink: 0;
            transition: all 0.15s ease;
        }

        .btn-clear:hover {
            background: var(--bg-active);
            color: var(--text-dark);
        }

        /* Breadcrumbs navigation */
        .breadcrumbs-container {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.65rem 1rem;
            background: var(--bg-hover);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            overflow-x: auto;
            white-space: nowrap;
        }

        .breadcrumbs-container::-webkit-scrollbar {
            height: 2px;
        }

        .breadcrumbs-container a {
            color: var(--primary-yellow);
            text-decoration: none;
            font-weight: 600;
        }

        .breadcrumbs-container a:hover {
            text-decoration: underline;
        }

        .breadcrumb-separator {
            color: var(--text-muted);
        }

        .breadcrumb-current {
            color: var(--text-dark);
            font-weight: 600;
        }

        .btn-sidebar-back {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: var(--bg-hover);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-sidebar-back:hover {
            background: var(--light-yellow);
            color: var(--primary-yellow);
        }

        .btn-sidebar-back svg {
            width: 0.9rem;
            height: 0.9rem;
            stroke-width: 2.5px;
        }

        /* Scrollable Law List & Folders */
        .law-list {
            flex: 1;
            overflow-y: auto;
        }

        /* Folder Items */
        .folder-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
            color: inherit;
            transition: background 0.15s ease;
            cursor: pointer;
        }

        .folder-item:hover {
            background: var(--bg-hover);
        }

        .folder-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-yellow);
        }

        .folder-icon svg {
            width: 1.35rem;
            height: 1.35rem;
        }

        .folder-info {
            flex: 1;
        }

        .folder-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .folder-desc {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .folder-arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .folder-arrow svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        .folder-item:hover .folder-arrow {
            color: var(--primary-yellow);
            transform: translateX(3px);
        }

        /* File Items */
        .law-item {
            display: block;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
            color: inherit;
            transition: background 0.15s ease;
        }

        .law-item:hover {
            background: var(--bg-hover);
        }

        .law-item.active {
            background: var(--light-yellow);
            border-left: 4px solid var(--primary-yellow);
            padding-left: 1rem;
        }

        .law-meta-tag {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--primary-yellow);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .pin-badge {
            background: var(--primary-yellow);
            color: #fff;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 0.6rem;
            text-transform: uppercase;
            font-weight: 800;
        }

        .law-title-short {
            font-size: 0.875rem;
            font-weight: 600;
            line-height: 1.25rem;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .law-about-short {
            font-size: 0.75rem;
            color: var(--text-muted);
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .empty-list {
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        /* Draggable Divider Handle */
        .divider {
            width: 4px;
            background-color: var(--border-color);
            cursor: col-resize;
            transition: background-color 0.15s ease;
            position: relative;
            z-index: 10;
            flex-shrink: 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: -4px;
            right: -4px;
            cursor: col-resize;
        }

        .divider:hover, .divider.dragging {
            background-color: var(--primary-yellow);
        }

        /* Right Panel: Document Viewer */
        .viewer-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: var(--bg-hover);
            overflow: hidden;
            position: relative;
        }

        /* Viewer Action Bar */
        .viewer-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background: var(--bg-light);
            border-bottom: 1px solid var(--border-color);
        }

        .active-law-info h2 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.15rem;
        }

        .active-law-info p {
            font-size: 0.75rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 450px;
        }

        .viewer-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-ctrl {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-ctrl:hover {
            background: var(--bg-hover);
            border-color: var(--text-muted);
        }

        .btn-ctrl svg {
            width: 0.95rem;
            height: 0.95rem;
            stroke-width: 2.25px;
        }

        .btn-ctrl-primary {
            background: var(--primary-yellow);
            color: #ffffff;
            border-color: var(--primary-yellow);
        }

        .btn-ctrl-primary:hover {
            background: #a16207;
            border-color: #a16207;
        }

        .btn-mobile-back {
            display: none;
        }

        /* PDF Viewer Container */
        .pdf-container {
            flex: 1;
            position: relative;
            background: #525659;
            overflow: hidden;
        }

        .pdf-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Inverted Mode (Dark Mode for PDFs) */
        html.dark-mode #pdf-container iframe {
            filter: invert(0.9) hue-rotate(180deg);
        }

        html.dark-mode .pdf-container {
            background: #1f2937;
        }

        /* Metadata Popover Drawer */
        .meta-drawer {
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            width: 320px;
            background: var(--bg-light);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 80;
            display: none;
        }

        .meta-drawer.show {
            display: block;
        }

        .meta-drawer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .meta-drawer-header h3 {
            font-size: 0.875rem;
            font-weight: 700;
        }

        .meta-drawer-header button {
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

        .meta-drawer-header button:hover {
            background: var(--bg-hover);
            color: var(--text-dark);
        }

        .meta-drawer-header button svg {
            width: 1.1rem;
            height: 1.1rem;
            stroke-width: 2.25px;
        }

        .meta-row {
            margin-bottom: 0.75rem;
            font-size: 0.8rem;
        }

        .meta-label {
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .meta-value {
            color: var(--text-dark);
            line-height: 1.1rem;
        }

        .relation-link {
            display: inline-block;
            background: var(--bg-hover);
            padding: 2px 6px;
            border-radius: 4px;
            text-decoration: none;
            color: var(--primary-yellow);
            font-weight: 600;
            font-size: 0.75rem;
            margin-top: 2px;
            transition: background 0.15s ease;
        }

        .relation-link:hover {
            background: var(--light-yellow);
        }

        /* Fullscreen Mode override */
        .workspace.fullscreen-active .sidebar,
        .workspace.fullscreen-active .divider {
            display: none !important;
        }

        .workspace.fullscreen-active .viewer-bar {
            padding: 0.5rem 1rem;
        }

        /* Welcome panel if no PDF active */
        .welcome-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: var(--bg-light);
            padding: 2rem;
            text-align: center;
        }

        .welcome-panel svg {
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .welcome-panel h3 {
            font-size: 1.2rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .welcome-panel p {
            font-size: 0.875rem;
            color: var(--text-muted);
            max-width: 320px;
        }

        /* Responsive Styles (Mobile & Tablets) */
        @media (max-width: 768px) {
            .divider {
                display: none !important;
            }

            .sidebar {
                width: 100% !important;
                max-width: 100% !important;
            }

            .viewer-panel {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 20;
                display: none;
            }

            .workspace.show-reader .viewer-panel {
                display: flex;
            }

            .btn-mobile-back {
                display: inline-flex !important;
            }

            .meta-drawer {
                position: fixed;
                width: calc(100% - 40px);
                left: 20px;
                top: 60px;
                transform: none;
            }

            .active-law-info p {
                max-width: 180px;
            }

            .search-panel {
                padding: 0.75rem;
            }

            .search-form input {
                font-size: 0.8rem;
                padding: 0.4rem 0.6rem;
            }

            .search-form button {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }

            header {
                padding: 0.6rem 1rem;
            }

            header .logo-area h1 {
                font-size: 0.95rem;
            }

            header .logo-area p {
                font-size: 0.7rem;
            }

            .viewer-bar {
                padding: 0.5rem 0.75rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .viewer-controls {
                flex-wrap: wrap;
                gap: 0.4rem;
            }

            .btn-ctrl {
                padding: 0.35rem 0.6rem;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>

    <!-- Top Header Navigation -->
    <header>
        <div class="logo-area">
            <h1>BALeg DPM</h1>
            <p>Portal Publik Repositori Undang-Undang</p>
        </div>
        <div class="header-actions">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="/dashboard/admin">Dashboard Admin</a>
            <?php else: ?>
                <a href="/auth/login">Login Admin</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Workspace -->
    <div class="workspace" id="workspace">
        
        <!-- Left Panel: File Navigation Sidebar -->
        <div class="sidebar" id="sidebar">
            
            <!-- Search bar -->
            <div class="search-panel">
                <form class="search-form" method="GET" action="/">
                    <input type="text" name="search" placeholder="Cari nomor, tahun, tentang..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit">Cari</button>
                    <?php if (!empty($search) || !empty($currentPath)): ?>
                        <a href="/?path=" class="btn-clear" title="Reset pencarian">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Breadcrumbs Navigation -->
            <div class="breadcrumbs-container">
                <a href="/?path=">Beranda</a>
                <?php if ($level === -1): ?>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">Hasil Pencarian: "<?= htmlspecialchars($search) ?>"</span>
                <?php else: ?>
                    <?php
                    $accumulatedPath = '';
                    foreach ($pathSegments as $index => $seg): 
                        $accumulatedPath .= ($index === 0 ? '' : '/') . $seg;
                    ?>
                        <span class="breadcrumb-separator">/</span>
                        <?php if ($index === count($pathSegments) - 1): ?>
                            <span class="breadcrumb-current"><?= htmlspecialchars($seg) ?></span>
                        <?php else: ?>
                            <a href="/?path=<?= urlencode($accumulatedPath) ?>"><?= htmlspecialchars($seg) ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Go Back Button (One Level) -->
            <?php if ($level > 0 || $level === -1): ?>
                <?php
                $backPath = '';
                if ($level > 0) {
                    $parentSegments = $pathSegments;
                    array_pop($parentSegments);
                    $backPath = implode('/', $parentSegments);
                }
                ?>
                <a href="/?path=<?= urlencode($backPath) ?>" class="btn-sidebar-back">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Kembali ke tingkat sebelumnya
                </a>
            <?php endif; ?>

            <!-- Scrollable list (Folders or Files) -->
            <div class="law-list">
                <?php if ($level === 0): ?>
                    <!-- Level 0: Categories Folders -->
                    <?php if (empty($folders)): ?>
                        <div class="empty-list">Tidak ada kategori peraturan tersedia</div>
                    <?php else: ?>
                        <?php foreach ($folders as $cat): ?>
                            <a href="/?path=<?= urlencode($cat) ?>" class="folder-item">
                                <span class="folder-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                                </span>
                                <div class="folder-info">
                                    <div class="folder-title"><?= htmlspecialchars($cat) ?></div>
                                    <div class="folder-desc">Folder Dokumen Peraturan <?= htmlspecialchars($cat) ?></div>
                                </div>
                                <span class="folder-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php elseif ($level === 1): ?>
                    <!-- Level 1: Years Folders -->
                    <?php if (empty($folders)): ?>
                        <div class="empty-list">Tidak ada tahun peraturan dalam kategori ini</div>
                    <?php else: ?>
                        <?php foreach ($folders as $yr): ?>
                            <a href="/?path=<?= urlencode($pathSegments[0] . '/' . $yr) ?>" class="folder-item">
                                <span class="folder-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                                </span>
                                <div class="folder-info">
                                    <div class="folder-title">Tahun <?= htmlspecialchars($yr) ?></div>
                                    <div class="folder-desc">Kumpulan Peraturan Tahun <?= htmlspecialchars($yr) ?></div>
                                </div>
                                <span class="folder-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Level 2/Search: Files List -->
                    <?php if (empty($files)): ?>
                        <div class="empty-list">Tidak ada dokumen peraturan dalam folder ini</div>
                    <?php else: ?>
                        <?php foreach ($files as $doc): ?>
                            <?php 
                                $isActiveItem = ($activeDoc && $activeDoc['id'] == $doc['id']);
                                $docUrl = '/?path=' . urlencode($currentPath) . '&id=' . $doc['id'];
                                if (!empty($search)) $docUrl .= '&search=' . urlencode($search);
                            ?>
                            <a href="<?= $docUrl ?>" class="law-item <?= $isActiveItem ? 'active' : '' ?>" 
                               data-id="<?= $doc['id'] ?>"
                               data-filename="<?= htmlspecialchars($doc['nama_file']) ?>"
                               data-title-short="<?= htmlspecialchars($doc['jenis'] . ' No. ' . $doc['nomor'] . ' Th. ' . $doc['tahun']) ?>"
                               data-tentang="<?= htmlspecialchars($doc['tentang']) ?>">
                                <div class="law-meta-tag">
                                    <span><?= htmlspecialchars($doc['jenis']) ?> No. <?= htmlspecialchars($doc['nomor']) ?> / <?= htmlspecialchars($doc['tahun']) ?></span>
                                    <?php if ($doc['is_pinned']): ?>
                                        <span class="pin-badge">Pinned</span>
                                    <?php endif; ?>
                                </div>
                                <div class="law-title-short"><?= htmlspecialchars($doc['jenis']) ?> No. <?= htmlspecialchars($doc['nomor']) ?> Tahun <?= htmlspecialchars($doc['tahun']) ?></div>
                                <div class="law-about-short"><?= htmlspecialchars($doc['tentang']) ?></div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Draggable Resizing Divider -->
        <div class="divider" id="divider"></div>

        <!-- Right Panel: Document Viewer -->
        <?php if ($activeDoc): ?>
            <div class="viewer-panel" id="viewer-panel">
                
                <!-- Action / Detail Bar -->
                <div class="viewer-bar">
                    <div class="viewer-controls" style="gap: 0.75rem;">
                        <button class="btn-ctrl btn-mobile-back" onclick="closeReader()">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg> Kembali
                        </button>
                        <div class="active-law-info">
                            <h2 id="active-title"><?= htmlspecialchars($activeDoc['jenis']) ?> No. <?= htmlspecialchars($activeDoc['nomor']) ?> Tahun <?= htmlspecialchars($activeDoc['tahun']) ?></h2>
                            <p id="active-subtitle"><?= htmlspecialchars($activeDoc['tentang']) ?></p>
                        </div>
                        <!-- Info Drawer Toggler Wrapper -->
                        <div style="position: relative; display: inline-block;">
                            <button class="btn-ctrl" onclick="toggleMetaDrawer()">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg> Detail Dokumen
                            </button>
                            <div class="meta-drawer" id="meta-drawer">
                                 <div class="meta-drawer-header">
                                     <h3>Detail Informasi</h3>
                                     <button onclick="toggleMetaDrawer()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg></button>
                                 </div>
                                 
                                 <div class="meta-row">
                                     <div class="meta-label">Jenis Peraturan</div>
                                     <div class="meta-value" id="drawer-jenis"><?= htmlspecialchars($activeDoc['jenis']) ?></div>
                                 </div>
                                 <div class="meta-row">
                                     <div class="meta-label">Nomor & Tahun</div>
                                     <div class="meta-value" id="drawer-nomor"><?= htmlspecialchars($activeDoc['nomor']) ?> / <?= htmlspecialchars($activeDoc['tahun']) ?></div>
                                 </div>
                                 <div class="meta-row">
                                     <div class="meta-label">Tentang</div>
                                     <div class="meta-value" id="drawer-tentang"><?= htmlspecialchars($activeDoc['tentang']) ?></div>
                                 </div>
                                 <div class="meta-row" id="drawer-keterangan-row" style="<?= empty($activeDoc['keterangan']) ? 'display: none;' : '' ?>">
                                     <div class="meta-label">Keterangan Tambahan</div>
                                     <div class="meta-value" id="drawer-keterangan"><?= htmlspecialchars($activeDoc['keterangan'] ?? '') ?></div>
                                 </div>

                                 <!-- Hubungan Undang-Undang (Relations) -->
                                 <div id="drawer-relations-container" style="<?= ($activeRelations && (!empty($activeRelations['outgoing']) || !empty($activeRelations['incoming']))) ? '' : 'display: none;' ?> margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid var(--border-color);">
                                     <h4 style="font-size: 0.8rem; font-weight: 700; margin-bottom: 0.5rem;">Dokumen Terkait</h4>
                                     <div id="drawer-relations-list">
                                         <?php if ($activeRelations): ?>
                                             <!-- Relasi Keluar (Dokumen ini mengubah/mencabut yang lain) -->
                                             <?php foreach ($activeRelations['outgoing'] as $rel): ?>
                                                 <div class="meta-row style-outgoing" style="margin-bottom: 0.5rem;">
                                                     <div class="meta-label" style="text-transform: capitalize; color: #b45309;"><?= htmlspecialchars($rel['tipe_relasi']) ?>:</div>
                                                     <a href="/?path=<?= urlencode($rel['jenis'] . '/' . $rel['tahun']) ?>&id=<?= $rel['id'] ?>" class="relation-link"
                                                        data-id="<?= $rel['id'] ?>"
                                                        data-filename="<?= htmlspecialchars($rel['nama_file']) ?>"
                                                        data-title-short="<?= htmlspecialchars($rel['jenis'] . ' No. ' . $rel['nomor'] . ' Th. ' . $rel['tahun']) ?>"
                                                        data-tentang="<?= htmlspecialchars($rel['tentang']) ?>">
                                                          <?= htmlspecialchars($rel['jenis']) ?> No. <?= htmlspecialchars($rel['nomor']) ?>/<?= htmlspecialchars($rel['tahun']) ?>
                                                     </a>
                                                 </div>
                                             <?php endforeach; ?>

                                             <!-- Relasi Masuk (Dokumen lain mengubah/mencabut dokumen ini) -->
                                             <?php foreach ($activeRelations['incoming'] as $rel): ?>
                                                 <div class="meta-row style-incoming" style="margin-bottom: 0.5rem;">
                                                     <div class="meta-label" style="text-transform: capitalize; color: #15803d;">
                                                         <?= ($rel['tipe_relasi'] === 'mengubah') ? 'Diubah oleh' : 'Dicabut oleh' ?>:
                                                     </div>
                                                     <a href="/?path=<?= urlencode($rel['jenis'] . '/' . $rel['tahun']) ?>&id=<?= $rel['id'] ?>" class="relation-link"
                                                        data-id="<?= $rel['id'] ?>"
                                                        data-filename="<?= htmlspecialchars($rel['nama_file']) ?>"
                                                        data-title-short="<?= htmlspecialchars($rel['jenis'] . ' No. ' . $rel['nomor'] . ' Th. ' . $rel['tahun']) ?>"
                                                        data-tentang="<?= htmlspecialchars($rel['tentang']) ?>">
                                                          <?= htmlspecialchars($rel['jenis']) ?> No. <?= htmlspecialchars($rel['nomor']) ?>/<?= htmlspecialchars($rel['tahun']) ?>
                                                     </a>
                                                 </div>
                                             <?php endforeach; ?>
                                         <?php endif; ?>
                                     </div>
                                 </div>
                            </div>
                        </div>
                    </div>

                    <div class="viewer-controls">
                        <!-- Night Mode Toggle -->
                        <button class="btn-ctrl" id="btn-night" onclick="toggleNightMode()">
                            <span class="btn-icon-wrapper" style="display: inline-flex; align-items: center; justify-content: center;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg></span>
                            <span class="btn-text">Mode Kontras</span>
                        </button>

                        <!-- Fullscreen Toggle -->
                        <button class="btn-ctrl" onclick="toggleFullscreen()">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg> Fullscreen
                        </button>

                        <!-- Direct Download -->
                        <a href="/dokumen/download?id=<?= $activeDoc['id'] ?>" class="btn-ctrl btn-ctrl-primary" id="btn-download">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Download PDF
                        </a>
                    </div>
                </div>

                <!-- PDF Viewer Iframe (PDF.js for smooth performance across Chrome/Edge/Firefox) -->
                <div class="pdf-container" id="pdf-container">
                    <iframe src="/pdfjs/web/viewer.html?file=<?= urlencode('/uploads/' . $activeDoc['nama_file']) ?>" id="pdf-iframe"></iframe>
                </div>

            </div>
        <?php else: ?>
            <div class="welcome-panel">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/><line x1="9" y1="11" x2="10" y2="11"/><line x1="9" y1="19" x2="13" y2="19"/></svg>
                <h3>Selamat Datang</h3>
                <p>Belum ada dokumen undang-undang yang diunggah, atau pencarian Anda tidak mencocokkan dokumen mana pun.</p>
            </div>
        <?php endif; ?>

    </div>

    <script>
        // DRAGGABLE DIVIDER LOGIC
        let isResizing = false;
        const workspaceEl = document.getElementById('workspace');
        const sidebarEl = document.getElementById('sidebar');

        // Restore saved sidebar width
        const savedWidth = localStorage.getItem('baleg-sidebar-width');
        if (savedWidth && sidebarEl && window.innerWidth > 768) {
            const initialWidth = Math.min(Math.max(parseInt(savedWidth, 10), 240), window.innerWidth - 320);
            sidebarEl.style.width = initialWidth + 'px';
        }

        document.addEventListener('mousedown', (e) => {
            const divider = e.target.closest('#divider');
            if (divider) {
                isResizing = true;
                e.preventDefault();
                divider.classList.add('dragging');
                document.body.classList.add('resizing-active');
                if (workspaceEl) workspaceEl.classList.add('resizing-active');
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (!isResizing || !sidebarEl) return;
            const containerLeft = workspaceEl ? workspaceEl.getBoundingClientRect().left : 0;
            const newWidth = e.clientX - containerLeft;
            const minWidth = 240;
            const maxWidth = Math.max(minWidth, (workspaceEl ? workspaceEl.clientWidth : window.innerWidth) - 320);
            const clampedWidth = Math.min(Math.max(newWidth, minWidth), maxWidth);
            sidebarEl.style.width = clampedWidth + 'px';
        });

        document.addEventListener('mouseup', () => {
            if (isResizing) {
                isResizing = false;
                const divider = document.getElementById('divider');
                if (divider) divider.classList.remove('dragging');
                document.body.classList.remove('resizing-active');
                if (workspaceEl) workspaceEl.classList.remove('resizing-active');
                if (sidebarEl) {
                    localStorage.setItem('baleg-sidebar-width', parseInt(sidebarEl.style.width, 10));
                }
            }
        });

        // VIEW UTILITY ACTIONS
        function toggleMetaDrawer() {
            const drawer = document.getElementById('meta-drawer');
            if (drawer) drawer.classList.toggle('show');
        }

        function toggleNightMode() {
            document.documentElement.classList.toggle('dark-mode');
            const isNight = document.documentElement.classList.contains('dark-mode');
            localStorage.setItem('dark-mode', isNight ? 'true' : 'false');
            updateNightModeUI(isNight);
        }

        function updateNightModeUI(isNight) {
            const btn = document.getElementById('btn-night');
            if (btn) {
                const iconWrapper = btn.querySelector('.btn-icon-wrapper');
                const textSpan = btn.querySelector('.btn-text');
                
                if (isNight) {
                    textSpan.textContent = "Mode Normal";
                    iconWrapper.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>`;
                } else {
                    textSpan.textContent = "Mode Kontras";
                    iconWrapper.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>`;
                }
            }
        }

        function toggleFullscreen() {
            const ws = document.getElementById('workspace');
            if (ws) {
                ws.classList.toggle('fullscreen-active');
            }
        }

        function closeReader() {
            const ws = document.getElementById('workspace');
            if (ws) ws.classList.remove('show-reader');
        }

        // SPA NAVIGATION SYSTEM
        function navigateSPA(url, updateViewer = false, shouldPushState = true) {
            fetch(url)
                .then(res => {
                    if (!res.ok) throw new Error("HTTP error " + res.status);
                    return res.text();
                })
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // 1. Update Sidebar
                    const newSidebar = doc.getElementById('sidebar');
                    const currentSidebar = document.getElementById('sidebar');
                    if (newSidebar && currentSidebar) {
                        currentSidebar.innerHTML = newSidebar.innerHTML;
                    }

                    // 2. Update Viewer Panel
                    const newViewer = doc.getElementById('viewer-panel');
                    const currentViewer = document.getElementById('viewer-panel');
                    const workspace = document.getElementById('workspace');

                    if (newViewer) {
                        if (currentViewer) {
                            if (updateViewer) {
                                // Sync key textual content
                                const newTitle = doc.getElementById('active-title');
                                const currentTitle = document.getElementById('active-title');
                                if (newTitle && currentTitle) currentTitle.textContent = newTitle.textContent;

                                const newSubtitle = doc.getElementById('active-subtitle');
                                const currentSubtitle = document.getElementById('active-subtitle');
                                if (newSubtitle && currentSubtitle) currentSubtitle.textContent = newSubtitle.textContent;

                                const newDownload = doc.getElementById('btn-download');
                                const currentDownload = document.getElementById('btn-download');
                                if (newDownload && currentDownload) currentDownload.href = newDownload.href;

                                const newDrawer = doc.getElementById('meta-drawer');
                                const currentDrawer = document.getElementById('meta-drawer');
                                if (newDrawer && currentDrawer) currentDrawer.innerHTML = newDrawer.innerHTML;

                                // Sync keterangan display status
                                const newKetRow = doc.getElementById('drawer-keterangan-row');
                                const currentKetRow = document.getElementById('drawer-keterangan-row');
                                if (currentKetRow) {
                                    currentKetRow.style.display = newKetRow ? newKetRow.style.display : 'none';
                                }

                                // Sync relations container display status
                                const newRelContainer = doc.getElementById('drawer-relations-container');
                                const currentRelContainer = document.getElementById('drawer-relations-container');
                                if (currentRelContainer) {
                                    currentRelContainer.style.display = newRelContainer ? newRelContainer.style.display : 'none';
                                }

                                // Prevent redundant reload if PDF file has not changed
                                const newIframe = doc.getElementById('pdf-iframe');
                                const currentIframe = document.getElementById('pdf-iframe');
                                if (newIframe && currentIframe) {
                                    const newSrc = newIframe.getAttribute('src');
                                    const currentSrc = currentIframe.getAttribute('src');
                                    if (newSrc !== currentSrc) {
                                        currentIframe.src = newSrc;
                                    }
                                }
                            }
                        } else {
                            // If welcome panel is active, replace with viewer panel
                            const welcomePanel = workspace.querySelector('.welcome-panel');
                            if (welcomePanel) welcomePanel.remove();

                            // Re-append divider and viewer panel
                            const div = doc.getElementById('divider');
                            const dividerClone = div ? div.cloneNode(true) : document.createElement('div');
                            dividerClone.id = 'divider';
                            dividerClone.className = 'divider';
                            workspace.appendChild(dividerClone);

                            const viewerClone = newViewer.cloneNode(true);
                            workspace.appendChild(viewerClone);
                        }

                        // Handle mobile view responsive behavior
                        if (updateViewer && window.innerWidth <= 768) {
                            workspace.classList.add('show-reader');
                        }
                    } else {
                        // Switch to welcome panel if appropriate
                        const newWelcome = doc.querySelector('.welcome-panel');
                        if (newWelcome) {
                            if (currentViewer) currentViewer.remove();
                            const currentDivider = document.getElementById('divider');
                            if (currentDivider) currentDivider.remove();
                            
                            const welcomePanel = workspace.querySelector('.welcome-panel');
                            if (!welcomePanel) {
                                workspace.appendChild(newWelcome.cloneNode(true));
                            }
                        }
                    }

                    // Push address bar state
                    if (shouldPushState) {
                        window.history.pushState({}, '', url);
                    }

                    // Re-sync UI night mode styling elements
                    const isNight = document.documentElement.classList.contains('dark-mode');
                    updateNightModeUI(isNight);
                })
                .catch(err => console.error("SPA Navigation Error:", err));
        }

        // DOM EVENT DELEGATION
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('id') && window.innerWidth <= 768) {
                const ws = document.getElementById('workspace');
                if (ws) ws.classList.add('show-reader');
            }
            
            // Sync night mode button on load
            const isNight = document.documentElement.classList.contains('dark-mode');
            updateNightModeUI(isNight);

            // Intercept all SPA anchor click events
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (link) {
                    const href = link.getAttribute('href');
                    if (href && (href.startsWith('/') || href.startsWith('?'))) {
                        // Skip system and administrative routes
                        if (href.startsWith('/auth/logout') || href.startsWith('/dashboard/admin') || href.startsWith('/auth/login') || href.startsWith('/admin/')) {
                            return;
                        }

                        e.preventDefault();

                        // Determine if viewer panel needs to be updated (selecting a specific document)
                        const urlObj = new URL(link.href);
                        const hasId = urlObj.searchParams.has('id');
                        const isDocSelect = link.closest('.law-item, .relation-link');

                        navigateSPA(href, hasId || isDocSelect);
                    }
                }
            });

            // Intercept search form submission events
            document.addEventListener('submit', (e) => {
                const form = e.target.closest('.search-form');
                if (form) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const searchParams = new URLSearchParams(formData);
                    const action = form.getAttribute('action') || '/';
                    const targetUrl = action + '?' + searchParams.toString();
                    navigateSPA(targetUrl, false);
                }
            });

            // Handle back/forward history navigation popstate events
            window.addEventListener('popstate', () => {
                const urlObj = new URL(window.location.href);
                const hasId = urlObj.searchParams.has('id');
                navigateSPA(window.location.href, hasId, false);
            });
        });
    </script>

</body>
</html>
