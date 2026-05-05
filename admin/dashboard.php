<?php
session_start();

// Panel principal (Dashboard)

// admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}

// bbdd
require_once __DIR__ . '/config/conexion_admin.php';
$titulo_admin = 'Dashboard';
require_once __DIR__ . '/includes/header_admin.php';

// determina a que idioma mostrar 
$idioma = $_SESSION['lang'] ?? 'es';
$campo_categoria = "c.nombre_" . $idioma;

// Consultar si hay alguna sesión activa (esperando o en juego)
$sesionActiva = $db->query("SELECT s.*, $campo_categoria AS categoria FROM sesiones s JOIN categorias c ON c.id = s.categoria_id
    WHERE s.estado IN ('esperando','en_juego') ORDER BY s.created_at DESC LIMIT 1")->fetchArray(SQLITE3_ASSOC);
?>

<div class="admin-topbar">
    <div class="admin-page-sub">Bienvenida, <?= htmlspecialchars($_SESSION['admin_usuario']) ?> ⚡</div>
</div>

<p class="dashboard-label">Partida activa</p>

<?php if ($sesionActiva): ?>
<div class="dashboard-activa">
    <div class="dashboard-activa-left">
        <span class="dashboard-pin-label">PIN</span>
        <div class="admin-pin-display"><?= htmlspecialchars($sesionActiva['pin']) ?></div>
        <div class="admin-actions-row">
            <span class="badge badge-<?= $sesionActiva['estado'] ?>"><?= $sesionActiva['estado'] ?></span>
            <span class="dashboard-cat"><?= htmlspecialchars($sesionActiva['categoria']) ?></span>
        </div>
    </div>
    <a href="/CLASH/admin/sesiones/vista.php?id=<?= $sesionActiva['id'] ?>"
       class="btn-admin btn-admin-pink">
        Ver partida en vivo →
    </a>
</div>

<?php else: ?>
<div class="dashboard-vacia">
    <p>No hay ninguna partida en curso</p>
    <a href="/CLASH/admin/sesiones/crear.php" class="btn-admin btn-admin-dark">
        + Nueva partida
    </a>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer_admin.php'; ?>