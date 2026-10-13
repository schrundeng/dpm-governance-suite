<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Kadaluarsa — WebDPM</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; min-height: 100vh; }
        .navbar { background: #1e3a5f; color: #fff; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 1.1rem; }
        .navbar a { color: #fff; text-decoration: none; font-size: 0.875rem; }
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .top-bar h2 { font-size: 1.2rem; color: #1f2937; }
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
        .card { background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th { background: #f9fafb; padding: 0.75rem 1rem; text-align: left; font-size: 0.8rem; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
        td { padding: 0.875rem 1rem; font-size: 0.875rem; color: #374151; border-bottom: 1px solid #f3f4f6; vertical-align: middle; word-break: break-word; }
        tr:last-child td { border-bottom: none; }
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .badge-diterima { background: #d1fae5; color: #065f46; }
        .badge-ditolak  { background: #fee2e2; color: #b91c1c; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem; padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.8rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s ease; }
        .btn-danger  { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-danger-outline { background: #fff; color: #dc2626; border: 1px solid #dc2626; }
        .btn-danger-outline:hover { background: #fee2e2; }
        .navbar-right { display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; }
        .empty { text-align: center; padding: 3rem; color: #9ca3af; }
        .success { background: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .info-box { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; color: #78350f; }

        @media (max-width: 768px) {
            .navbar { padding: 1rem; flex-direction: column; gap: 0.5rem; text-align: center; }
            .container { margin: 1rem auto; }
            .top-bar { flex-direction: column; align-items: stretch; gap: 1rem; text-align: center; }
            .top-bar form { width: 100%; }
            .top-bar form button { width: 100%; text-align: center; }
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

    <div class="top-bar">
        <h2>Arsip Dokumen Kadaluarsa</h2>
        <?php if (!empty($dokumens)): ?>
        <form method="POST" action="/approval/arsip"
              onsubmit="return confirm('Hapus semua <?= count($dokumens) ?> dokumen kadaluarsa? Tindakan ini tidak bisa dibatalkan.')">
            <input type="hidden" name="aksi" value="hapus_semua">
            <button type="submit" class="btn btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg> Hapus Semua (<?= count($dokumens) ?>)
            </button>
        </form>
        <?php endif; ?>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="info-box">
        Menampilkan dokumen yang telah <b>diarsipkan secara manual</b> oleh Admin.
        Dokumen yang dihapus secara permanen tidak dapat dikembalikan.
    </div>

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
                    <th>Tanggal Kirim</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($dokumens)): ?>
                <tr><td colspan="9" class="empty">Tidak ada dokumen kadaluarsa.</td></tr>
            <?php else: ?>
                <?php foreach ($dokumens as $i => $d): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($d['id_pengajuan']) ?></td>
                    <td><?= htmlspecialchars($d['nama_user']) ?></td>
                    <td><?= htmlspecialchars($d['komisi']) ?></td>
                    <td><?= htmlspecialchars($d['judul']) ?></td>
                    <td><?= htmlspecialchars($d['jenis']) ?></td>
                    <td><?= date('d M Y', strtotime($d['created_at'])) ?></td>
                    <td>
                        <span class="badge badge-<?= $d['status'] ?>">
                            <?= ucfirst($d['status']) ?>
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.5rem;align-items:center;">
                            <a href="/approval/restore?id=<?= $d['id'] ?>" class="btn" style="background:#d1fae5;color:#065f46;text-decoration:none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><polyline points="3 3 3 8 8 8"/></svg> Pulihkan
                            </a>
                            <form method="POST" action="/approval/arsip"
                                  onsubmit="return confirm('Hapus dokumen ini? Tindakan tidak bisa dibatalkan.')" style="margin:0;">
                                <input type="hidden" name="aksi" value="hapus_satu">
                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                <button type="submit" class="btn btn-danger-outline">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>