<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo_admin) ? htmlspecialchars($titulo_admin) . ' — Admin Clash' : 'Admin — Clash' ?></title>
    <link rel="stylesheet" href="/CLASH/admin/assets/css/admin.css">
    <link rel="icon" href="/CLASH/assets/img/favicon.png" type="image/png"> 
</head>
<body>
<div class="admin-layout">

    <aside class="admin-sidebar">

        <div class="admin-sidebar-head">
            <a href="/CLASH/index.php" class="admin-sidebar-logo">CLASH</a>
            <div class="admin-sidebar-badge">Panel Admin</div>
        </div>

        <nav class="admin-sidebar-nav">
            <a href="/CLASH/admin/dashboard.php"
               class="admin-nav-link <?= $pagina_actual === 'dashboard.php' ? 'activo' : '' ?>">
                Dashboard
            </a>
            <a href="/CLASH/admin/sesiones/gestion.php"
               class="admin-nav-link <?= in_array($pagina_actual, ['gestion.php','crear.php','vista.php']) ? 'activo' : '' ?>">
                Sesiones
            </a>
            <a href="/CLASH/admin/jugadores/gestionJugadores.php"
               class="admin-nav-link <?= $pagina_actual === 'gestionJugadores.php' ? 'activo' : '' ?>">
                Jugadores
            </a>
            <a href="/CLASH/admin/retos/gestionRetos.php"
               class="admin-nav-link <?= in_array($pagina_actual, ['gestionRetos.php','crearReto.php']) ? 'activo' : '' ?>">
                Categorías
            </a>
        </nav>

        <div class="admin-sidebar-foot">
            <a href="/CLASH/admin/logout.php" class="btn-admin btn-admin-ghost btn-admin-full">
                Cerrar sesión
            </a>
        </div>

    </aside>

    <main class="admin-main">