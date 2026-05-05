<?php
session_start();
require_once __DIR__ . '/../config/idiomas.php';

// si ya está logueado redirige al dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?= $idioma_actual ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Clash</title>
    <link rel="stylesheet" href="/CLASH/admin/assets/css/admin.css">
    <link rel="icon" href="/CLASH/assets/img/favicon.png" type="image/png">
</head>
<body class="admin-login-body">
    <div class="admin-login-box">
        <div class="admin-login-logo">CLASH</div>
        <div class="admin-login-sub"><?= $t['acceso_admin'] ?></div>
        <?php if (isset($_GET['error'])): ?>
            <div class="admin-error"><?= $t['error_credenciales'] ?></div>
        <?php endif; ?>
        <form action="/CLASH/admin/loginProc.php" method="POST">
            <div class="admin-form-group">
                <label for="usuario"><?= $t['label_usuario'] ?></label>
                <input type="text" id="usuario" name="usuario" placeholder="admin" required autofocus>
            </div>
            <div class="admin-form-group">
                <label for="password"><?= $t['label_contrasena'] ?></label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-admin btn-admin-dark btn-admin-full">
                <?= $t['btn_entrar_admin'] ?>
            </button>
        </form>
    </div>
</body>
</html>