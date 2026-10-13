<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../../app/controllers/AuthController.class.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi — WebDPM</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 1.5rem 1rem; }
        .card { background: #fff; padding: 2rem; border-radius: 8px; width: 100%; max-width: 420px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 0.25rem; font-size: 1.4rem; color: #1f2937; }
        .subtitle { font-size: 0.85rem; color: #6b7280; margin-bottom: 1.5rem; }
        label { display: block; font-size: 0.875rem; color: #374151; margin-bottom: 4px; }
        input, select { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; margin-bottom: 1rem; background: #fff; }
        button { width: 100%; padding: 0.6rem; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { background: #fee2e2; color: #b91c1c; padding: 0.5rem 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .link { text-align: center; margin-top: 1rem; font-size: 0.875rem; }
        .link a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h2>Daftar Akun</h2>
    <p class="subtitle">Buat akun untuk mengajukan dokumen RAB & LPJ</p>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="/auth/register">
        <input type="hidden" name="role" value="user">

        <label>Nama Lengkap</label>
        <input type="text" name="nama" required autofocus>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Komisi</label>
        <select name="komisi" required>
            <option value="">— Pilih Komisi —</option>
            <?php foreach (AuthController::$komisiList as $k): ?>
                <option value="<?= htmlspecialchars($k) ?>"><?= htmlspecialchars($k) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Konfirmasi Password</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Daftar</button>
    </form>

    <div class="link">
        Sudah punya akun? <a href="/auth/login">Login</a>
    </div>
</div>
</body>
</html>