<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';

$sesiones = $db->query("
    SELECT s.*, c.nombre_es AS categoria
    FROM sesiones s
    JOIN categorias c ON c.id = s.categoria_id
    ORDER BY s.created_at DESC
");

$titulo_admin = 'Sesiones';
require_once __DIR__ . '/../includes/header_admin.php';
?>

<div class="admin-topbar">
    <span class="admin-page-sub">Historial de todas las partidas</span>
    <a href="/CLASH/admin/sesiones/crear.php" class="btn-admin btn-admin-pink">+ Nueva partida</a>
</div>

<div class="admin-table-wrap">
    <table class="admin-table gestion-table">
        <thead>
            <tr>
                <th>PIN</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($s = $sesiones->fetchArray(SQLITE3_ASSOC)): ?>
            <tr class="<?= in_array($s['estado'], ['esperando','en_juego']) ? 'sesion-activa' : '' ?>">
                <td><strong><?= htmlspecialchars($s['pin']) ?></strong></td>
                <td><?= htmlspecialchars($s['categoria']) ?></td>
                <td><span class="badge badge-<?= $s['estado'] ?>"><?= $s['estado'] ?></span></td>
                <td>
                    <div class="admin-table-actions">
                        <a href="/CLASH/admin/sesiones/vista.php?id=<?= $s['id'] ?>"
                           class="btn-admin btn-admin-ghost">Ver →</a>
                        <a href="/CLASH/admin/sesiones/eliminarProc.php?id=<?= $s['id'] ?>"
                           class="btn-admin btn-admin-danger"
                           onclick="return confirm('¿Eliminar esta sesión y todos sus datos?')">Eliminar</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>