<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #f9fafb; 
            color: #1f2937;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            padding: 1rem; 
        }
        .login-container { 
            width: 100%; 
            max-width: 420px; 
            padding: 2.5rem; 
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .header-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header-logo h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }
        .header-logo p {
            font-size: 0.875rem;
            color: #6b7280;
        }
        label { 
            display: block; 
            font-size: 0.875rem; 
            font-weight: 500;
            color: #374151; 
            margin-bottom: 6px; 
        }
        input { 
            width: 100%; 
            padding: 0.75rem 1rem; 
            border: 1px solid #d1d5db; 
            border-radius: 8px; 
            font-family: inherit;
            font-size: 0.95rem; 
            margin-bottom: 1.25rem; 
            transition: all 0.2s ease;
            outline: none;
        }
        input:focus {
            border-color: #ca8a04;
            box-shadow: 0 0 0 3px rgba(202, 138, 4, 0.15);
        }
        button { 
            width: 100%; 
            padding: 0.8rem; 
            background: #ca8a04; 
            color: #ffffff; 
            font-family: inherit;
            font-weight: 600;
            border: none; 
            border-radius: 8px; 
            font-size: 0.95rem; 
            cursor: pointer; 
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        button:hover { 
            background: #a16207; 
        }
        .error { 
            background: #fee2e2; 
            color: #b91c1c; 
            padding: 0.75rem 1rem; 
            border-radius: 8px; 
            margin-bottom: 1.25rem; 
            font-size: 0.875rem; 
            border-left: 4px solid #ef4444;
        }
        .success { 
            background: #ecfdf5; 
            color: #065f46; 
            padding: 0.75rem 1rem; 
            border-radius: 8px; 
            margin-bottom: 1.25rem; 
            font-size: 0.875rem; 
            border-left: 4px solid #10b981;
        }
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
        }
        .back-link a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            color: #ca8a04;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s ease;
        }
        .back-link a:hover {
            color: #a16207;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="header-logo">
        <h2>DPM BALeg</h2>
        <p>Repositori Undang-Undang</p>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form method="POST" action="/auth/login">
        <label for="email">Email Admin</label>
        <input type="email" id="email" name="email" required autofocus>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Masuk</button>
    </form>

    <div class="back-link">
        <a href="/">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:2px; width:0.95rem; height:0.95rem;"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Kembali ke Portal Publik
        </a>
    </div>
</div>
</body>
</html>
