<?php 
if (session_status() === PHP_SESSION_NONE) session_start(); 
$navbarBg = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '#1e3a5f' : '#1e40af';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dokumen — WebDPM</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; min-height: 100vh; }
        .navbar { background: <?= $navbarBg ?>; color: #fff; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 1.1rem; }
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
        .info-grid { display: grid; grid-template-columns: 150px 1fr; gap: 0.5rem 1rem; font-size: 0.875rem; }
        .info-grid .label { color: #6b7280; }
        .info-grid .value { color: #1f2937; font-weight: 500; }
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-dikirim   { background: #dbeafe; color: #1d4ed8; }
        .badge-in-review { background: #fef3c7; color: #92400e; }
        .badge-diterima  { background: #d1fae5; color: #065f46; }
        .badge-ditolak   { background: #fee2e2; color: #b91c1c; }
        .preview-frame { width: 100%; height: 500px; border: 1px solid #e5e7eb; border-radius: 6px; margin-top: 0.5rem; }
        .catatan-box { background: #fee2e2; border-left: 4px solid #b91c1c; padding: 1rem; border-radius: 6px; font-size: 0.875rem; color: #7f1d1d; margin-top: 0.5rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.5rem 1.2rem; border-radius: 6px; font-size: 0.875rem; text-decoration: none; margin-top: 1rem; margin-right: 0.5rem; text-align: center; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: #e5e7eb; color: #374151; }

        @media (max-width: 768px) {
            .navbar { padding: 1rem; flex-direction: column; gap: 0.5rem; text-align: center; }
            .container { margin: 1rem auto; }
            .info-grid { grid-template-columns: 1fr; gap: 0.25rem; }
            .info-grid .label { font-weight: bold; margin-top: 0.25rem; }
            .btn { width: 100%; margin-right: 0; margin-top: 0.5rem; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <h1>WebDPM</h1>
    <a href="/auth/logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
</nav>

<div class="container">
    <?php
    $backUrl = $_SESSION['user_role'] === 'admin' ? '/dashboard/admin' : '/dashboard/client';
    ?>
    <a href="<?= $backUrl ?>" class="back"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>Kembali ke Dashboard</a>

    <!-- Info Dokumen -->
    <div class="card">
        <h3>Informasi Dokumen</h3>
        <div class="info-grid">
            <span class="label">ID Pengajuan</span>
            <span class="value"><?= htmlspecialchars($dokumen['id_pengajuan']) ?></span>
            <span class="label">Judul</span>
            <span class="value"><?= htmlspecialchars($dokumen['judul']) ?></span>
            <span class="label">Jenis</span>
            <span class="value"><?= htmlspecialchars($dokumen['jenis']) ?></span>
            <span class="label">Pengirim</span>
            <span class="value"><?= htmlspecialchars($dokumen['nama_user']) ?></span>
            <span class="label">Komisi</span>
            <span class="value"><?= htmlspecialchars($dokumen['komisi']) ?></span>
            <span class="label">Waktu Dikirim</span>
            <span class="value"><?= date('d M Y, H:i', strtotime($dokumen['created_at'])) ?></span>
            <span class="label">Status</span>
            <span class="value">
                <span class="badge badge-<?= $dokumen['status'] ?>">
                    <?= ucfirst($dokumen['status']) ?>
                </span>
            </span>
            <?php if ($dokumen['keterangan']): ?>
            <span class="label">Keterangan</span>
            <span class="value"><?= htmlspecialchars($dokumen['keterangan']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Catatan Revisi (jika ditolak) -->
    <?php if ($dokumen['status'] === 'ditolak' && $dokumen['catatan_revisi']): ?>
    <div class="card">
        <h3>Catatan Revisi</h3>
        <div class="catatan-box">
            <?= nl2br(htmlspecialchars($dokumen['catatan_revisi'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Preview & Download (jika diterima) -->
<!-- Preview & Download (jika diterima) -->
<?php if ($dokumen['status'] === 'diterima'): ?>
<div class="card">
    <h3>File Dokumen</h3>
    <?php
    // Kalau ada file feedback (sudah ditandatangani), tampilkan itu sebagai preview utama
    $tampilFeedback = !empty($dokumen['file_feedback']);
    $previewFile    = $tampilFeedback ? $dokumen['file_feedback'] : $dokumen['nama_file'];
    $ext            = strtolower(pathinfo($previewFile, PATHINFO_EXTENSION));
    $fileUrl        = '/uploads/' . $previewFile;
    ?>

    <?php if ($tampilFeedback): ?>
        <p style="font-size:0.8rem;color:#065f46;background:#d1fae5;padding:0.5rem 0.75rem;border-radius:6px;margin-bottom:0.75rem;">
            File berikut merupakan dokumen resmi yang telah ditandatangani oleh admin
        </p>
    <?php endif; ?>

    <?php if ($ext === 'pdf'): ?>
        <iframe src="<?= $fileUrl ?>" class="preview-frame"></iframe>
    <?php elseif (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
        <img src="<?= $fileUrl ?>" style="max-width:100%;border-radius:6px;margin-top:0.5rem;">
    <?php endif; ?>

    <div style="margin-top:1rem;display:flex;gap:0.75rem;flex-wrap:wrap;">
        <?php if ($tampilFeedback): ?>
            <a href="/dokumen/download?id=<?= $dokumen['id'] ?>&tipe=feedback" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Download File Resmi (Sudah Ditandatangani)
            </a>
            <a href="/dokumen/download?id=<?= $dokumen['id'] ?>&tipe=original" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Download File Original
            </a>
        <?php else: ?>
            <a href="/dokumen/download?id=<?= $dokumen['id'] ?>" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Download File
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
</body>
</html>