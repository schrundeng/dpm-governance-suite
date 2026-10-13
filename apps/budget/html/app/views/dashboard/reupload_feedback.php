<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update File Feedback — WebDPM</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; min-height: 100vh; }
        .navbar { background: #1e3a5f; color: #fff; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 1.1rem; }
        .navbar-right { display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; }
        .navbar a { color: #fff; text-decoration: none; font-size: 0.875rem; }
        .container { max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        .back {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 1.5rem;
            font-weight: 500;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .back:hover {
            background: #f9fafb;
            border-color: #c5cacc;
            color: #111827;
        }
        .back svg {
            display: block;
            margin-right: 0.5rem;
        }
        .card { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
        .card h3 { font-size: 1rem; color: #1f2937; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb; }
        .info-grid { display: grid; grid-template-columns: 150px 1fr; gap: 0.5rem 1rem; font-size: 0.875rem; margin-bottom: 1.5rem; }
        .info-grid .label { color: #6b7280; }
        .info-grid .value { color: #1f2937; font-weight: 500; }
        .preview-frame { width: 100%; height: 400px; border: 1px solid #e5e7eb; border-radius: 6px; }
        label { display: block; font-size: 0.875rem; color: #374151; margin-bottom: 4px; margin-top: 1rem; }
        input[type=file] { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; }
        .hint { font-size: 0.75rem; color: #9ca3af; margin-top: 3px; }
        .btn-row { display: flex; gap: 1rem; margin-top: 1.5rem; }
        .btn { padding: 0.6rem 1.5rem; border-radius: 6px; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; }
        .btn-submit { background: #1e3a5f; color: #fff; }
        .btn-submit:hover { background: #1e40af; }
        .btn-cancel { background: #e5e7eb; color: #374151; }
        .error { background: #fee2e2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .warning-box { background: #fef3c7; border: 1px solid #fcd34d; border-radius: 6px; padding: 0.75rem 1rem; font-size: 0.8rem; color: #92400e; margin-bottom: 1rem; }

        @media (max-width: 768px) {
            .navbar { padding: 1rem; flex-direction: column; gap: 0.5rem; text-align: center; }
            .container { margin: 1rem auto; }
            .info-grid { grid-template-columns: 1fr; gap: 0.25rem; }
            .info-grid .label { font-weight: bold; margin-top: 0.25rem; }
            .btn-row { flex-direction: column; }
            .btn { width: 100%; }
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
    <a href="/dashboard/admin" class="back"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>Kembali ke Dashboard</a>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Info Dokumen -->
    <div class="card">
        <h3>Informasi Dokumen</h3>
        <div class="info-grid">
            <span class="label">ID Pengajuan</span>
            <span class="value"><?= htmlspecialchars($dokumen['id_pengajuan']) ?></span>
            <span class="label">Judul</span>
            <span class="value"><?= htmlspecialchars($dokumen['judul']) ?></span>
            <span class="label">Pengirim</span>
            <span class="value"><?= htmlspecialchars($dokumen['nama_user']) ?> (<?= htmlspecialchars($dokumen['komisi']) ?>)</span>
        </div>
    </div>

    <!-- Preview File Feedback Sekarang -->
    <?php if (!empty($dokumen['file_feedback'])): ?>
    <div class="card">
        <h3>File Feedback Saat Ini</h3>
        <?php
        $ext     = strtolower(pathinfo($dokumen['file_feedback'], PATHINFO_EXTENSION));
        $fileUrl = '/uploads/' . $dokumen['file_feedback'];
        ?>
        <?php if ($ext === 'pdf'): ?>
            <iframe src="<?= $fileUrl ?>" class="preview-frame"></iframe>
        <?php elseif (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
            <img src="<?= $fileUrl ?>" style="max-width:100%;border-radius:6px;">
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Form Upload Baru -->
    <div class="card">
        <h3>Upload File Feedback Baru</h3>
        <div class="warning-box" style="display:flex;align-items:center;gap:0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>File feedback lama akan digantikan oleh file baru. Client akan mendapat notifikasi email.</span>
        </div>
        <form method="POST" action="/approval/reuploadFeedback?id=<?= $dokumen['id'] ?>" enctype="multipart/form-data">
            <label>Pilih File Baru</label>
            <input type="file" name="file_feedback" accept=".pdf,.jpg,.jpeg,.png" required>
            <p class="hint">Format: PDF, JPG, PNG. Maksimal 10MB.</p>

            <div class="btn-row">
                <button type="submit" class="btn btn-submit">Upload File Baru</button>
                <a href="/dashboard/admin" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>