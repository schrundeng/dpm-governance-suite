<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — WebDPM</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 1rem; }
        .card { background: #fff; padding: 2rem; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { margin-bottom: 0.25rem; font-size: 1.4rem; color: #1f2937; }
        .subtitle { font-size: 0.85rem; color: #6b7280; margin-bottom: 1.5rem; }
        label { display: block; font-size: 0.875rem; color: #374151; margin-bottom: 4px; }
        input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; margin-bottom: 1rem; }
        button { width: 100%; padding: 0.6rem; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { background: #fee2e2; color: #b91c1c; padding: 0.5rem 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .success { background: #d1fae5; color: #065f46; padding: 0.5rem 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.875rem; }
        .link { text-align: center; margin-top: 1rem; font-size: 0.875rem; }
        .link a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h2>Login WebDPM Banggar</h2>
    <p class="subtitle">Sistem Pengajuan Dokumen</p>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="POST" action="/auth/login">
        <label>Email</label>
        <input type="email" name="email" required autofocus>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Masuk</button>
    </form>

    <div class="link">
        Belum punya akun? <a href="/auth/register">Daftar disini</a>
    </div>
</div>
</body>
</html>