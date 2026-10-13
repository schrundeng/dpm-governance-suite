<?php 
if (session_status() === PHP_SESSION_NONE) session_start(); 
$isEdit = isset($dokumen) && !empty($dokumen['id']);
$titleText = $isEdit ? 'Ubah Dokumen Peraturan' : 'Unggah Peraturan Baru';
$actionUrl = $isEdit ? '/admin/document/edit?id=' . $dokumen['id'] : '/admin/document/create';

// Load old input data if validation failed
$oldData = $_SESSION['form_data'] ?? null;
unset($_SESSION['form_data']);

$valJenis = $oldData['jenis'] ?? ($isEdit ? $dokumen['jenis'] : '');
$valNomor = $oldData['nomor'] ?? ($isEdit ? $dokumen['nomor'] : '');
$valTahun = $oldData['tahun'] ?? ($isEdit ? $dokumen['tahun'] : date('Y'));
$valTentang = $oldData['tentang'] ?? ($isEdit ? $dokumen['tentang'] : '');
$valKeterangan = $oldData['keterangan'] ?? ($isEdit ? $dokumen['keterangan'] : '');
$valIsPinned = isset($oldData['is_pinned']) ? !empty($oldData['is_pinned']) : ($isEdit && $dokumen['is_pinned']);

// Find currently pinned document
$pinnedDoc = null;
if (isset($allDocs)) {
    foreach ($allDocs as $d) {
        if ($d['is_pinned'] == 1) {
            $pinnedDoc = $d;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titleText ?> — Admin</title>
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
            --bg-page: #f9fafb;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-page);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
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
            font-size: 1.15rem;
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
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .navbar-links a:hover {
            color: var(--primary-yellow);
        }

        .btn-logout {
            padding: 0.35rem 0.85rem;
            background: #fee2e2;
            color: #b91c1c !important;
            border-radius: 6px;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
        }

        .btn-logout:hover {
            background: #fca5a5;
        }

        /* Main Content Container */
        .main-content {
            flex: 1;
            max-width: 780px;
            width: 100%;
            margin: 2rem auto;
            padding: 0 1.25rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            transition: color 0.15s ease;
        }

        .btn-back:hover {
            color: var(--primary-yellow);
        }

        .btn-back svg {
            width: 1rem;
            height: 1rem;
        }

        .form-container {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .form-header {
            margin-bottom: 1.75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--border-color);
        }

        .form-header h2 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #111827;
        }

        .form-header p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 0.85rem 1rem;
            border-radius: 8px;
            border-left: 4px solid #ef4444;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-row {
            display: flex;
            gap: 1.25rem;
        }

        .form-row .form-group {
            flex: 1;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            color: var(--text-dark);
            outline: none;
            transition: all 0.15s ease;
            background: #ffffff;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 3px rgba(202, 138, 4, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 85px;
            line-height: 1.45;
        }

        /* Checkbox Pinned */
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            padding: 0.75rem 1rem;
            background: #f9fafb;
            border: 1px solid var(--border-color);
            border-radius: 8px;
        }

        .checkbox-group input {
            width: 17px;
            height: 17px;
            cursor: pointer;
            accent-color: var(--primary-yellow);
            margin-top: 2px;
            flex-shrink: 0;
        }

        .checkbox-group label {
            margin-bottom: 0;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .pinned-hint {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 4px;
            font-size: 0.775rem;
            color: var(--text-muted);
            font-weight: normal;
        }

        .pinned-hint svg {
            color: var(--primary-yellow);
            flex-shrink: 0;
        }

        /* Custom File Upload Box */
        .file-upload-wrapper {
            position: relative;
        }

        .file-upload-wrapper input[type="file"] {
            position: absolute;
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            z-index: -1;
        }

        .file-upload-box {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.9rem 1.15rem;
            border: 1.5px dashed #d1d5db;
            border-radius: 8px;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-upload-box:hover {
            border-color: var(--primary-yellow);
            background: #fefce8;
        }

        .file-upload-box svg {
            color: var(--primary-yellow);
            flex-shrink: 0;
            width: 22px;
            height: 22px;
        }

        .file-upload-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .file-upload-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-upload-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .btn-browse {
            padding: 0.4rem 0.85rem;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-dark);
            flex-shrink: 0;
            transition: background 0.15s ease;
        }

        .file-upload-box:hover .btn-browse {
            background: #f3f4f6;
        }

        /* Relations Area */
        .relations-container {
            background: #fafafa;
            padding: 1.25rem;
            border: 1px dashed var(--border-color);
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .relations-container > label {
            margin-bottom: 2px;
        }

        .relation-row {
            display: flex;
            gap: 0.65rem;
            align-items: center;
            margin-bottom: 0.65rem;
        }

        .relation-row select {
            padding: 0.55rem 0.75rem;
            font-size: 0.825rem;
        }

        .btn-add-rel {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.9rem;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.15s ease;
            margin-top: 0.5rem;
        }

        .btn-add-rel:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .btn-del-rel {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
            border-radius: 6px;
            padding: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .btn-del-rel:hover {
            background: #fca5a5;
        }

        /* Buttons footer */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.75rem;
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border-color);
        }

        .btn-cancel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.25rem;
            background: #ffffff;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-cancel:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.5rem;
            background: var(--primary-yellow);
            color: #111827;
            border: 1px solid var(--primary-yellow);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-submit:hover {
            background: #a16207;
            border-color: #a16207;
            color: #ffffff;
        }

        .portal-text-short {
            display: none;
        }

        @media (max-width: 640px) {
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
                gap: 0.5rem;
                flex-shrink: 0;
                align-items: center;
            }

            .navbar-links a {
                font-size: 0.8rem;
            }

            .portal-text-full {
                display: none;
            }

            .portal-text-short {
                display: inline;
                font-size: 0.8rem;
            }

            .btn-logout {
                padding: 0.25rem 0.55rem;
                font-size: 0.75rem !important;
            }

            .form-row { flex-direction: column; gap: 0; }
            .form-container { padding: 1.25rem; }
            .form-actions { flex-direction: column-reverse; width: 100%; gap: 0.5rem; }
            .btn-cancel, .btn-submit { width: 100%; text-align: center; justify-content: center; height: 42px; }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 0.65rem 0.75rem;
            }

            .navbar h1 {
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar">
        <h1>DPM BALeg — Admin</h1>
        <div class="navbar-links">
            <a href="/dashboard/admin">
                <span class="portal-text-full">Dashboard Admin</span>
                <span class="portal-text-short">Dashboard</span>
            </a>
            <a href="/auth/logout" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Keluar</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        
        <a href="<?= $isEdit ? '/admin/document/detail?id=' . $dokumen['id'] : '/dashboard/admin' ?>" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke <?= $isEdit ? 'Detail Dokumen' : 'Dashboard' ?>
        </a>

        <div class="form-container">
            
            <div class="form-header">
                <h2><?= $titleText ?></h2>
                <p>Masukkan metadata peraturan perundang-undangan dan lampirkan dokumen PDF terkait.</p>
            </div>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="<?= $actionUrl ?>" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

                <!-- Jenis Peraturan -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="jenis">Jenis Peraturan</label>
                        <select id="jenis" name="jenis" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['nama']) ?>" <?= ($valJenis === $cat['nama']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['nama']) ?></option>
                            <?php endforeach; ?>
                            <option value="Lainnya" <?= ($valJenis !== '' && !in_array($valJenis, array_column($categories, 'nama'))) ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="custom-jenis-group" style="display: none;">
                        <label for="custom_jenis">Sebutkan Jenis Lainnya</label>
                        <input type="text" id="custom_jenis" placeholder="Contoh: Perda">
                    </div>
                </div>

                <!-- Nomor & Tahun -->
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label for="nomor">Nomor Peraturan</label>
                        <input type="text" id="nomor" name="nomor" placeholder="Contoh: 04/DPM/UN/2026" value="<?= htmlspecialchars($valNomor) ?>" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label for="tahun">Tahun</label>
                        <input type="number" id="tahun" name="tahun" placeholder="Contoh: 2026" min="1900" max="2100" value="<?= htmlspecialchars($valTahun) ?>" required>
                    </div>
                </div>

                <!-- Tentang -->
                <div class="form-group">
                    <label for="tentang">Tentang / Judul Peraturan</label>
                    <textarea id="tentang" name="tentang" placeholder="Tuliskan tentang peraturan..." required><?= htmlspecialchars($valTentang) ?></textarea>
                </div>

                <!-- Keterangan -->
                <div class="form-group">
                    <label for="keterangan">Keterangan Tambahan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" placeholder="Catatan tambahan..."><?= htmlspecialchars($valKeterangan) ?></textarea>
                </div>

                <!-- Is Pinned -->
                <div class="form-group">
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_pinned" name="is_pinned" value="1" <?= $valIsPinned ? 'checked' : '' ?>>
                        <label for="is_pinned">
                            Jadikan dokumen default utama saat website pertama kali dibuka (Pinned)
                            <?php if ($pinnedDoc && (!$isEdit || !$valIsPinned)): ?>
                                <span class="pinned-hint">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><line x1="12" y1="17" x2="12" y2="22"/><path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24Z"/></svg>
                                    Saat ini terpin: <strong><?= htmlspecialchars($pinnedDoc['jenis'] . ' No. ' . $pinnedDoc['nomor'] . ' Th. ' . $pinnedDoc['tahun']) ?></strong> (mencentang ini akan memindahkan pin)
                                </span>
                            <?php endif; ?>
                        </label>
                    </div>
                </div>

                <!-- File Upload (Polished Drag & Browse Box) -->
                <div class="form-group">
                    <label for="dokumen">File PDF Peraturan <?= $isEdit ? '<span style="font-weight:normal; color:var(--text-muted);">(Kosongkan jika tidak ingin diubah)</span>' : '' ?></label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="dokumen" name="dokumen" accept="application/pdf" <?= $isEdit ? '' : 'required' ?> onchange="handleFileChange(this)">
                        <label for="dokumen" class="file-upload-box">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="12" y2="12"/><line x1="15" y1="15" x2="12" y2="12"/></svg>
                            <div class="file-upload-text">
                                <span class="file-upload-title" id="file-name-display"><?= $isEdit ? htmlspecialchars($dokumen['nama_asli']) : 'Pilih dokumen PDF atau seret berkas ke sini' ?></span>
                                <span class="file-upload-desc">Format PDF resmi. Ukuran maksimal 20MB.</span>
                            </div>
                            <span class="btn-browse">Pilih Berkas</span>
                        </label>
                    </div>
                </div>

                <!-- Hubungan Dokumen (Relations) -->
                <div class="relations-container">
                    <label>Hubungan Peraturan Terkait</label>
                    <p style="font-size: 0.775rem; color: var(--text-muted); margin-bottom: 0.85rem;">Tentukan apakah peraturan ini mengubah atau mencabut peraturan lain yang sudah diunggah sebelumnya.</p>
                    
                    <div id="relations-list">
                        <!-- Dynamic relation rows injected here -->
                    </div>

                    <button type="button" class="btn-add-rel" onclick="addRelationRow()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                        Tambah Hubungan Dokumen
                    </button>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="<?= $isEdit ? '/admin/document/detail?id=' . $dokumen['id'] : '/dashboard/admin' ?>" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit"><?= $isEdit ? 'Simpan Perubahan' : 'Unggah Dokumen' ?></button>
                </div>
            </form>

        </div>

    </div>

    <!-- Pilihan Dokumen Lain (untuk dropdown relasi) -->
    <script>
        const allAvailableDocs = [
            <?php foreach ($allDocs as $doc): ?>
                {
                    id: <?= $doc['id'] ?>,
                    label: "<?= htmlspecialchars($doc['jenis'] . ' No. ' . $doc['nomor'] . ' Th. ' . $doc['tahun'] . ' - ' . addslashes(substr($doc['tentang'], 0, 50))) ?>..."
                },
            <?php endforeach; ?>
        ];

        let relationCount = 0;

        function handleFileChange(input) {
            const display = document.getElementById('file-name-display');
            if (input.files && input.files[0]) {
                display.textContent = input.files[0].name;
            }
        }

        // Custom Jenis Toggle
        const jenisSelect = document.getElementById('jenis');
        const customJenisGroup = document.getElementById('custom-jenis-group');
        const customJenisInput = document.getElementById('custom_jenis');

        function toggleCustomJenis() {
            if (jenisSelect.value === 'Lainnya') {
                customJenisGroup.style.display = 'block';
                customJenisInput.required = true;
                customJenisInput.name = 'jenis';
                jenisSelect.removeAttribute('name');
            } else {
                customJenisGroup.style.display = 'none';
                customJenisInput.required = false;
                customJenisInput.removeAttribute('name');
                jenisSelect.name = 'jenis';
            }
        }

        jenisSelect.addEventListener('change', toggleCustomJenis);
        
        window.addEventListener('DOMContentLoaded', () => {
            toggleCustomJenis();
            
            <?php if ($valJenis !== '' && !in_array($valJenis, array_column($categories, 'nama'))): ?>
                jenisSelect.value = 'Lainnya';
                customJenisGroup.style.display = 'block';
                customJenisInput.value = "<?= htmlspecialchars($valJenis) ?>";
                customJenisInput.name = 'jenis';
                jenisSelect.removeAttribute('name');
            <?php endif; ?>

            // Memuat relasi
            <?php 
            $relOutgoing = [];
            if ($oldData && isset($oldData['relations'])) {
                foreach ($oldData['relations'] as $r) {
                    if (!empty($r['related_id'])) {
                        $relOutgoing[] = [
                            'id' => intval($r['related_id']),
                            'tipe_relasi' => $r['tipe_relasi']
                        ];
                    }
                }
            } elseif ($isEdit && !empty($currentRelations['outgoing'])) {
                foreach ($currentRelations['outgoing'] as $rel) {
                    $relOutgoing[] = [
                        'id' => $rel['id'],
                        'tipe_relasi' => $rel['tipe_relasi']
                    ];
                }
            }
            ?>
            <?php foreach ($relOutgoing as $rel): ?>
                addRelationRow(<?= $rel['id'] ?>, "<?= htmlspecialchars($rel['tipe_relasi']) ?>");
            <?php endforeach; ?>
        });

        function addRelationRow(selectedDocId = null, selectedType = null) {
            if (allAvailableDocs.length === 0) {
                alert("Tidak ada dokumen lain di repositori untuk dihubungkan.");
                return;
            }

            const list = document.getElementById('relations-list');
            const row = document.createElement('div');
            row.className = 'relation-row';
            row.id = 'relation-row-' + relationCount;

            let docSelectHtml = `<select name="relations[${relationCount}][related_id]" required style="flex: 2;">`;
            docSelectHtml += `<option value="">-- Pilih Dokumen --</option>`;
            allAvailableDocs.forEach(doc => {
                const isSelected = selectedDocId && doc.id == selectedDocId ? 'selected' : '';
                docSelectHtml += `<option value="${doc.id}" ${isSelected}>${doc.label}</option>`;
            });
            docSelectHtml += `</select>`;

            let typeSelectHtml = `<select name="relations[${relationCount}][tipe_relasi]" required style="flex: 1;">`;
            typeSelectHtml += `<option value="mengubah" ${selectedType === 'mengubah' ? 'selected' : ''}>Mengubah</option>`;
            typeSelectHtml += `<option value="mencabut" ${selectedType === 'mencabut' ? 'selected' : ''}>Mencabut</option>`;
            docSelectHtml += `</select>`;

            const delBtnHtml = `<button type="button" class="btn-del-rel" onclick="removeRelationRow(${relationCount})"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; display: block;"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg></button>`;

            row.innerHTML = docSelectHtml + typeSelectHtml + delBtnHtml;
            list.appendChild(row);

            relationCount++;
        }

        function removeRelationRow(id) {
            const row = document.getElementById('relation-row-' + id);
            if (row) row.remove();
        }
    </script>

</body>
</html>
