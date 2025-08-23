<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <style>
    body{font-family:system-ui,Arial,sans-serif;background:#f6f7fb;margin:0}
    .bar{background:#111827;color:#fff;padding:14px 18px}
    .wrap{max-width:900px;margin:24px auto;background:#fff;padding:24px;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.08)}
    a.btn{display:inline-block;padding:10px 14px;background:#ef4444;color:#fff;border-radius:10px;text-decoration:none}
    .muted{color:#666}
  </style>
</head>
<body>
  <div class="bar"><strong>Chakanoks</strong></div>
  <div class="wrap">
    <h1>Dashboard</h1>
    <p class="muted">Welcome, <strong><?= esc($email ?? 'user') ?></strong> (role: <strong><?= esc($role ?? 'n/a') ?></strong>)</p>
    <p><a class="btn" href="<?= site_url('/logout') ?>">Log out</a></p>
  </div>
</body>
</html>
