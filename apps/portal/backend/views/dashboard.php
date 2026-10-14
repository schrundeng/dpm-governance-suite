<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — DPM UB 2026</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; min-height: 100vh; color: #1f2937; }
        
        /* Navbar */
        .navbar {
            background: #1e3a5f;
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .navbar h1 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #fbbf24;
            display: flex;
            align-items: center;
        }
        .brand-short {
            display: none;
        }
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            font-size: 0.875rem;
        }
        .user-greeting {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.9rem;
        }
        .user-greeting b {
            color: #ffffff;
        }
        .nav-portal {
            color: #e2e8f0;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.35rem 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            transition: all 0.15s ease;
        }
        .nav-portal:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            text-decoration: none;
        }
        .portal-text-short {
            display: none;
        }
        .btn-logout {
            padding: 0.4rem 1rem;
            background: #fee2e2;
            color: #b91c1c !important;
            border-radius: 6px;
            font-weight: 600 !important;
            font-size: 0.85rem;
            text-decoration: none !important;
            transition: background 0.15s;
        }
        .btn-logout:hover {
            background: #fca5a5;
        }

        .container { max-width: 100%; margin: 2rem auto; padding: 0 2rem; }
        
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .top-bar h2 { font-size: 1.2rem; color: #1f2937; }

        /* Stats Cards */
        .stats { display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .stat-card { background: #fff; padding: 1rem 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); flex: 1; min-width: 180px; }
        .stat-card .num { font-size: 1.8rem; font-weight: 700; color: #1e3a5f; }
        .stat-card .label { font-size: 0.8rem; color: #6b7280; margin-top: 2px; }

        /* Filter Panel */
        .filters { background: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap; box-shadow: 0 1px 3px rgba(0,0,0,0.1); align-items: center; }
        .filters select, .filters input { padding: 0.4rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; background: #fff; color: #374151; }
        .filters label { font-size: 0.875rem; color: #374151; display: flex; align-items: center; gap: 0.4rem; }
        .filters button { padding: 0.4rem 0.75rem; background: #1e3a5f; color: #fff; border: none; border-radius: 6px; font-size: 0.875rem; cursor: pointer; }
        .filters button:hover { background: #1e40af; }
        .filters a { padding: 0.4rem 0.75rem; font-size: 0.875rem; color: #6b7280; text-decoration: none; }
        .filters a:hover { text-decoration: underline; }

        /* Table Card */
        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow-x: auto; margin-bottom: 2rem; }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        th { background: #f9fafb; padding: 0.75rem 1rem; text-align: left; font-size: 0.8rem; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
        td { padding: 0.875rem 1rem; font-size: 0.875rem; color: #374151; border-bottom: 1px solid #f3f4f6; vertical-align: top; word-break: break-word; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9fafb; }
        
        .empty { text-align: center; padding: 3rem; color: #9ca3af; }
        .student-info { display: flex; flex-direction: column; gap: 0.2rem; }
        .student-name { font-weight: 600; color: #111827; }
        .student-nim { color: #4b5563; font-size: 0.8rem; }
        
        .category-badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; background: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; }
        .aspiration-title { font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; }
        .aspiration-text { color: #4b5563; font-size: 0.85rem; line-height: 1.4; white-space: pre-wrap; }

        /* Pagination Styling matching WebDPM */
        .pagination-container { display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #fff; border-top: 1px solid #e5e7eb; }
        .pagination-left { display: flex; align-items: center; gap: 1rem; }
        .pagination-info { font-size: 0.875rem; color: #6b7280; }
        .pagination-size { font-size: 0.875rem; color: #4b5563; }
        .pagination-size select { padding: 0.25rem 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; margin-left: 0.25rem; background: #fff; color: #374151; }
        .pagination-buttons { display: flex; align-items: center; gap: 0.25rem; }
        .pagination-btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.35rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #374151; font-size: 0.875rem; cursor: pointer; height: 32px; min-width: 32px; transition: all 0.2s ease; }
        .pagination-btn:hover { background: #f9fafb; }
        .pagination-btn.active { background: #1e3a5f; color: #fff; border-color: #1e3a5f; }
        .pagination-btn.disabled { background: #f3f4f6; color: #9ca3af; cursor: not-allowed; border-color: #e5e7eb; }

        @media (max-width: 768px) {
            .navbar {
                padding: 0.75rem 1rem;
                flex-direction: row;
                flex-wrap: nowrap;
                gap: 0.5rem;
                align-items: center;
                text-align: left;
            }
            .navbar h1 {
                font-size: 0.95rem;
                white-space: nowrap;
                flex-shrink: 0;
            }
            .navbar-right {
                gap: 0.6rem;
                flex-shrink: 0;
                align-items: center;
            }
            .user-greeting {
                display: none; /* Sembunyikan "Halo, admin" di mobile sama seperti Baleg */
            }
            .portal-text-full {
                display: none;
            }
            .portal-text-short {
                display: inline;
                font-size: 0.825rem;
            }
            .nav-portal {
                padding: 0.3rem 0.65rem;
            }
            .btn-logout {
                padding: 0.3rem 0.7rem;
                font-size: 0.775rem !important;
            }
            .container {
                margin: 1rem auto;
                padding: 0 0.75rem;
            }
            .top-bar {
                margin-bottom: 1rem;
            }
            .top-bar h2 {
                font-size: 1.15rem;
            }
            .stats {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
            .stat-card {
                min-width: unset;
                padding: 0.75rem;
            }
            .stat-card:first-child {
                grid-column: span 2;
            }
            .stat-card .num {
                font-size: 1.3rem;
            }
            .stat-card .label {
                font-size: 0.75rem;
            }
            .filters {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
                padding: 0.75rem;
            }
            .filters select, .filters input, .filters button, .filters a {
                width: 100%;
                text-align: center;
                margin-left: 0 !important;
            }
            .pagination-container {
                flex-direction: column;
                gap: 1rem;
                align-items: center;
                text-align: center;
            }
            .pagination-left {
                flex-direction: column;
                gap: 0.5rem;
            }
            .pagination-buttons {
                justify-content: center;
                width: 100%;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 420px) {
            .navbar {
                padding: 0.65rem 0.75rem;
            }
            .navbar h1 {
                font-size: 0.875rem;
            }
            .brand-full {
                display: none;
            }
            .brand-short {
                display: inline;
            }
            .navbar-right {
                gap: 0.45rem;
            }
            .nav-portal {
                padding: 0.25rem 0.5rem;
            }
            .portal-text-short {
                font-size: 0.8rem;
            }
            .btn-logout {
                padding: 0.25rem 0.55rem;
                font-size: 0.75rem !important;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <h1>
        <span class="brand-full">DPM UB 2026 — Admin Portal</span>
        <span class="brand-short">DPM Admin</span>
    </h1>
    <div class="navbar-right">
        <span class="user-greeting">Halo, <b><?= htmlspecialchars($_SESSION['admin_user']) ?></b></span>
        <a href="http://localhost:3000" target="_blank" class="nav-portal">
            <span class="portal-text-full">Lihat Portal</span>
            <span class="portal-text-short">Portal</span>
        </a>
        <a href="/admin/logout" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
    </div>
</nav>

<div class="container">
    <div class="top-bar">
        <h2>Daftar Aspirasi Mahasiswa</h2>
    </div>

    <!-- Stats Section grouped by Category -->
    <?php
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM aspirasi");
    $totalCount = (int)$stmtTotal->fetchColumn();

    $stmtAkademik = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE kategori = 'Akademik'");
    $akademikCount = (int)$stmtAkademik->fetchColumn();

    $stmtFasilitas = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE kategori = 'Fasilitas'");
    $fasilitasCount = (int)$stmtFasilitas->fetchColumn();

    $stmtKebijakan = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE kategori = 'Kebijakan'");
    $kebijakanCount = (int)$stmtKebijakan->fetchColumn();

    $stmtKesejahteraan = $pdo->query("SELECT COUNT(*) FROM aspirasi WHERE kategori = 'Kesejahteraan'");
    $kesejahteraanCount = (int)$stmtKesejahteraan->fetchColumn();
    ?>
    <div class="stats">
        <div class="stat-card">
            <div class="num"><?= $totalCount ?></div>
            <div class="label">Total Aspirasi</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color: #2563eb;"><?= $akademikCount ?></div>
            <div class="label">Akademik</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color: #d97706;"><?= $fasilitasCount ?></div>
            <div class="label">Fasilitas</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color: #059669;"><?= $kebijakanCount ?></div>
            <div class="label">Kebijakan</div>
        </div>
        <div class="stat-card">
            <div class="num" style="color: #7c3aed;"><?= $kesejahteraanCount ?></div>
            <div class="label">Kesejahteraan</div>
        </div>
    </div>

    <!-- Filter & Sort & Search matching WebDPM style -->
    <form method="GET" action="/admin/dashboard" class="filters">
        <label>Kategori:
            <select name="kategori" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="Akademik" <?= ($_GET['kategori'] ?? '') === 'Akademik' ? 'selected' : '' ?>>Akademik</option>
                <option value="Fasilitas" <?= ($_GET['kategori'] ?? '') === 'Fasilitas' ? 'selected' : '' ?>>Fasilitas</option>
                <option value="Kebijakan" <?= ($_GET['kategori'] ?? '') === 'Kebijakan' ? 'selected' : '' ?>>Kebijakan</option>
                <option value="Kesejahteraan" <?= ($_GET['kategori'] ?? '') === 'Kesejahteraan' ? 'selected' : '' ?>>Kesejahteraan</option>
                <option value="Lainnya" <?= ($_GET['kategori'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </label>

        <label>Urutkan:
            <select id="sortSelect">
                <option value="terbaru">Terbaru</option>
                <option value="terlama">Terlama</option>
                <option value="nama_asc">Nama (A-Z)</option>
                <option value="nama_desc">Nama (Z-A)</option>
                <option value="kategori">Kategori</option>
            </select>
        </label>

        <button type="submit" style="display:none;">Filter</button>
        <a href="/admin/dashboard">Reset</a>

        <input type="text" id="tableSearch" placeholder="Cari Nama, NIM, Judul, Kategori..." style="margin-left: auto;" onkeydown="if(event.key === 'Enter') event.preventDefault();">
    </form>

    <!-- Table Listing -->
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 20%">Mahasiswa</th>
                    <th style="width: 15%">Fakultas</th>
                    <th style="width: 15%">Kategori</th>
                    <th style="width: 30%">Aspirasi</th>
                    <th style="width: 15%">Waktu Dikirim</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($aspirasiList)): ?>
                <tr><td colspan="6" class="empty">Tidak ada aspirasi mahasiswa.</td></tr>
            <?php else: ?>
                <?php foreach ($aspirasiList as $index => $aspirasi): ?>
                <tr data-nama="<?= htmlspecialchars(strtolower($aspirasi['nama'])) ?>" 
                    data-kategori="<?= htmlspecialchars(strtolower($aspirasi['kategori'])) ?>" 
                    data-created-at="<?= htmlspecialchars($aspirasi['created_at']) ?>">
                    <td><?= $index + 1 ?></td>
                    <td>
                        <div class="student-info">
                            <span class="student-name"><?= htmlspecialchars($aspirasi['nama']) ?></span>
                            <span class="student-nim">NIM: <?= htmlspecialchars($aspirasi['nim']) ?></span>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($aspirasi['fakultas']) ?></td>
                    <td>
                        <span class="category-badge"><?= htmlspecialchars($aspirasi['kategori']) ?></span>
                    </td>
                    <td>
                        <div class="aspiration-title"><?= htmlspecialchars($aspirasi['judul']) ?></div>
                        <div class="aspiration-text"><?= htmlspecialchars($aspirasi['isi']) ?></div>
                    </td>
                    <td><?= date('d M Y, H:i', strtotime($aspirasi['created_at'])) ?></td>
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

    let itemsPerPage = 10;
    let currentPage = 1;
    let filteredRows = [...rows];

    // Create pagination container
    const card = table.closest('.card');
    const paginationContainer = document.createElement('div');
    paginationContainer.className = 'pagination-container';
    card.appendChild(paginationContainer);

    const searchInput = document.getElementById('tableSearch');
    const sortSelect = document.getElementById('sortSelect');

    function sortRows() {
        const sortBy = sortSelect.value;
        filteredRows.sort((a, b) => {
            if (sortBy === 'terbaru') {
                return new Date(b.getAttribute('data-created-at')) - new Date(a.getAttribute('data-created-at'));
            }
            if (sortBy === 'terlama') {
                return new Date(a.getAttribute('data-created-at')) - new Date(b.getAttribute('data-created-at'));
            }
            if (sortBy === 'nama_asc') {
                return a.getAttribute('data-nama').localeCompare(b.getAttribute('data-nama'));
            }
            if (sortBy === 'nama_desc') {
                return b.getAttribute('data-nama').localeCompare(a.getAttribute('data-nama'));
            }
            if (sortBy === 'kategori') {
                return a.getAttribute('data-kategori').localeCompare(b.getAttribute('data-kategori'));
            }
            return 0;
        });
    }

    function updateTable() {
        // Sort first
        sortRows();

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
            // Update No column
            const noCell = row.cells[0];
            if (noCell) {
                noCell.textContent = start + index + 1;
            }
            // Append back to tbody in sorted order
            tbody.appendChild(row);
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
        prevBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><polyline points="15 18 9 12 15 6"></polyline></svg>';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            currentPage--;
            updateTable();
        });
        buttonsDiv.appendChild(prevBtn);

        // Page Number Buttons
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = i;
                updateTable();
            });
            buttonsDiv.appendChild(pageBtn);
        }

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.className = `pagination-btn ${currentPage === totalPages ? 'disabled' : ''}`;
        nextBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><polyline points="9 18 15 12 9 6"></polyline></svg>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
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
                const cells = Array.from(row.cells).slice(1); // Skip No cell
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

    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            currentPage = 1;
            updateTable();
        });
    }

    updateTable();
});
</script>

<script>
function handleLogout(e) {
    e.preventDefault();
    if (confirm('Apakah Anda yakin ingin logout?')) {
        window.location.href = '/admin/logout';
    }
}
</script>

</body>
</html>
