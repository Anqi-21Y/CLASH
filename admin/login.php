<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header('Location: /inner-work/clash/admin/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Clash</title>
    <link rel="stylesheet" href="/inner-work/clash/admin/assets/css/admin.css">
    <link rel="icon" href="/inner-work/clash/assets/img/favicon.png" type="image/png">
</head>
<body class="admin-login-body">
    <div class="admin-login-box">
        <div class="admin-login-logo">CLASH</div>
        <div class="admin-login-sub">Acceso admin</div>
        <?php if (isset($_GET['error'])): ?>
            <div class="admin-error">Usuario o contraseña incorrectos</div>
        <?php endif; ?>
        <form action="/inner-work/clash/admin/loginProc.php" method="POST">
            <div class="admin-form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" placeholder="admin" required autofocus>
            </div>
            <div class="admin-form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-admin btn-admin-dark btn-admin-full">
                Entrar
            </button>
        </form>
    </div>
</body>
</html>