<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../controllers/AuthController.class.php';
$navbarBg = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '#1e3a5f' : '#1e40af';
$backUrl = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' ? '/dashboard/admin' : '/dashboard/client';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — WebDPM</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f3f4f6; min-height: 100vh; }
        .navbar { background: <?= $navbarBg ?>; color: #fff; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 1.1rem; }
        .navbar a { color: #fff; text-decoration: none; font-size: 0.875rem; }
        .container { max-width: 600px; margin: 2rem auto; padding: 0 1rem; }
        
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
        .card h3 { font-size: 1rem; color: #1f2937; margin-bottom: 1.25rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb; }
        
        label { display: block; font-size: 0.875rem; color: #374151; margin-bottom: 4px; margin-top: 1rem; font-weight: 500; }
        label:first-of-type { margin-top: 0; }
        
        input, select { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem; background: #fff; color: #374151; transition: border-color 0.15s ease; }
        input:focus, select:focus { outline: none; border-color: <?= $navbarBg ?>; }
        input[readonly], input[disabled] { background: #f9fafb; color: #6b7280; cursor: not-allowed; }
        
        .hint { font-size: 0.75rem; color: #9ca3af; margin-top: 3px; }
        
        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.6rem;
            background: <?= $navbarBg ?>;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s ease;
            margin-top: 1.5rem;
        }
        .btn-submit:hover { opacity: 0.9; }
        
        .success { background: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; border: 1px solid #a7f3d0; }
        .error { background: #fee2e2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; border: 1px solid #fecaca; }
        
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; margin-top: 4px; }
        .badge-admin { background: #fee2e2; color: #b91c1c; }
        .badge-user { background: #dbeafe; color: #1d4ed8; }
        
        @media (max-width: 768px) {
            .navbar { padding: 1rem; flex-direction: column; gap: 0.5rem; text-align: center; }
            .container { margin: 1rem auto; }
            .btn-submit { width: 100%; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <h1>WebDPM — Profil Saya</h1>
    <a href="/auth/logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</a>
</nav>

<div class="container">
    <a href="<?= $backUrl ?>" class="back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Dashboard
    </a>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <h3>Detail Profil</h3>
        <form method="POST" action="/auth/profile">
            <label>Email</label>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly disabled>
            <p class="hint">Email tidak dapat diubah.</p>

            <label>Peran (Role)</label>
            <div>
                <span class="badge badge-<?= htmlspecialchars($user['role']) ?>">
                    <?= htmlspecialchars($user['role']) ?>
                </span>
            </div>

            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>

            <?php if ($user['role'] !== 'admin'): ?>
                <label>Komisi</label>
                <input type="text" value="<?= htmlspecialchars($user['komisi']) ?>" readonly disabled>
                <p class="hint">Komisi tidak dapat diubah.</p>
            <?php endif; ?>

            <h3 style="margin-top: 2rem;">Ubah Password (Opsional)</h3>

            <label>Password Baru</label>
            <input type="password" name="password" placeholder="Masukkan password baru jika ingin diubah">
            <p class="hint">Kosongkan jika tidak ingin mengubah password.</p>

            <label>Konfirmasi Password Baru</label>
            <input type="password" name="confirm_password" placeholder="Ulangi password baru">

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</div>

</body>
</html>
