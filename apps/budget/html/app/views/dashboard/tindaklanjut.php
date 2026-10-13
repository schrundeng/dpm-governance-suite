<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tindak Lanjut — WebDPM</title>
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
        .info-grid { display: grid; grid-template-columns: 150px 1fr; gap: 0.5rem 1rem; font-size: 0.875rem; }
        .info-grid .label { color: #6b7280; }
        .info-grid .value { color: #1f2937; font-weight: 500; }
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-in-review { background: #fef3c7; color: #92400e; }
        .preview-frame { width: 100%; height: 500px; border: 1px solid #e5e7eb; border-radius: 6px; margin-top: 0.5rem; }
        label { display: block; font-size: 0.875rem; color: #374151; margin-bottom: 4px; margin-top: 1rem; }
        select, textarea, input[type=file] { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; background: #fff; }
        textarea { height: 100px; resize: vertical; }
        .hint { font-size: 0.75rem; color: #9ca3af; margin-top: 3px; }
        .btn-row { display: flex; gap: 1rem; margin-top: 1.5rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.6rem 1.5rem; border-radius: 6px; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; text-align: center; }
        .btn-submit { background: #1e3a5f; color: #fff; }
        .btn-submit:hover { background: #1e40af; }
        .btn-cancel { background: #e5e7eb; color: #374151; }
        .error { background: #fee2e2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        #catatan-group, #file-group { display: none; }

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
        <h3>Informasi Pengajuan</h3>
        <div class="info-grid">
            <span class="label">ID Pengajuan</span>
            <span class="value"><?= htmlspecialchars($dokumen['id_pengajuan']) ?></span>
            <span class="label">Nama</span>
            <span class="value"><?= htmlspecialchars($dokumen['nama_user']) ?></span>
            <span class="label">Komisi</span>
            <span class="value"><?= htmlspecialchars($dokumen['komisi']) ?></span>
            <span class="label">Judul</span>
            <span class="value"><?= htmlspecialchars($dokumen['judul']) ?></span>
            <span class="label">Jenis</span>
            <span class="value"><?= htmlspecialchars($dokumen['jenis']) ?></span>
            <span class="label">Waktu Dikirim</span>
            <span class="value"><?= date('d M Y, H:i', strtotime($dokumen['created_at'])) ?></span>
            <span class="label">Status</span>
            <span class="value"><span class="badge badge-in-review">In-Review</span></span>
            <?php if ($dokumen['keterangan']): ?>
            <span class="label">Keterangan</span>
            <span class="value"><?= htmlspecialchars($dokumen['keterangan']) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Preview File -->
    <div class="card">
        <h3>Preview & Download File</h3>
        <?php
        $ext = strtolower(pathinfo($dokumen['nama_file'], PATHINFO_EXTENSION));
        $fileUrl = '/uploads/' . $dokumen['nama_file'];
        ?>
        <?php if ($ext === 'pdf'): ?>
            <iframe src="<?= $fileUrl ?>" class="preview-frame"></iframe>
        <?php elseif (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
            <img src="<?= $fileUrl ?>" style="max-width:100%;border-radius:6px;margin-top:0.5rem;">
        <?php endif; ?>
        <div style="margin-top:1rem;">
            <a href="/dokumen/download?id=<?= $dokumen['id'] ?>" class="btn btn-cancel" style="font-size:0.85rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Download File Original
            </a>
        </div>
    </div>

    <!-- Form Tindak Lanjut -->
    <div class="card">
        <h3>Keputusan</h3>
        <form method="POST" action="/approval/tindaklanjut?id=<?= $dokumen['id'] ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <label>Status Keputusan</label>
            <select name="status" id="status-select" required onchange="toggleCatatan(this.value)">
                <option value="">Pilih Keputusan</option>
                <option value="diterima">Diterima</option>
                <option value="ditolak">Ditolak</option>
            </select>

            <div id="catatan-group">
                <label>Catatan Revisi <span style="color:#b91c1c">*</span></label>
                <textarea name="catatan" placeholder="Tuliskan alasan penolakan atau catatan revisi..."></textarea>
            </div>

            <div id="file-group">
                <label>Upload File Yang Sudah Di Review (Apabila diterima)</label>
                <input type="file" name="file_feedback" accept=".pdf,.jpg,.jpeg,.png">
                <p class="hint">Format: PDF, JPG, PNG. Maksimal 10MB.</p>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-submit">Kirim Keputusan</button>
                <a href="/dashboard/admin" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleCatatan(status) {
    const catatanGroup = document.getElementById('catatan-group');
    const fileGroup = document.getElementById('file-group');
    const catatanInput = catatanGroup.querySelector('textarea');
    const fileInput = fileGroup.querySelector('input[type="file"]');
    
    if (status === 'diterima') {
        catatanGroup.style.display = 'none';
        catatanInput.required = false;
        catatanInput.value = '';
        
        fileGroup.style.display = 'block';
    } else if (status === 'ditolak') {
        catatanGroup.style.display = 'block';
        catatanInput.required = true;
        
        fileGroup.style.display = 'none';
        fileInput.value = '';
    } else {
        catatanGroup.style.display = 'none';
        catatanInput.required = false;
        fileGroup.style.display = 'none';
        fileInput.value = '';
    }
}
</script>
</body>
</html>