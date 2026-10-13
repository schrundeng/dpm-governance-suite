<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen — WebDPM</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; min-height: 100vh; padding: 2rem; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 0.25rem; color: #1f2937; }
        .subtitle { font-size: 0.85rem; color: #6b7280; margin-bottom: 1.5rem; }
        label { display: block; font-size: 0.875rem; color: #374151; margin-bottom: 4px; margin-top: 0.75rem; }
        input, select, textarea { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; background: #fff; }
        textarea { height: 80px; resize: vertical; }
        .hint { font-size: 0.75rem; color: #9ca3af; margin-top: 3px; }
        button { margin-top: 1.5rem; width: 100%; padding: 0.6rem; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { background: #fee2e2; color: #b91c1c; padding: 0.5rem 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
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

        @media (max-width: 600px) {
            body { padding: 1rem; }
            .card { padding: 1.25rem; }
        }
    </style>
</head>
<body>
<div class="container">
    <a href="/dashboard/client" class="back"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>Kembali ke Dashboard</a>
    <div class="card">
        <h2>Upload Dokumen</h2>
        <p class="subtitle">Ajukan dokumen RAB atau LPJ kamu</p>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/dokumen/upload" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

            <label>Judul Dokumen</label>
            <input type="text" name="judul" required autofocus placeholder="contoh: RAB Kegiatan Seminar 2026">

            <label>Jenis Dokumen</label>
            <select name="jenis" required>
                <option value="">— Pilih Jenis —</option>
                <option value="RAB">RAB</option>
                <option value="LPJ">LPJ</option>
            </select>

            <label>File Dokumen</label>
            <input type="file" name="dokumen" accept=".pdf,.jpg,.jpeg,.png" required>
            <p class="hint">Format: PDF, JPG, PNG. Maksimal 10MB.</p>

            <label>Keterangan (opsional)</label>
            <textarea name="keterangan" placeholder="Tambahkan keterangan jika diperlukan..."></textarea>

            <button type="submit">Kirim Pengajuan</button>
        </form>
    </div>
</div>

</body>
</html>