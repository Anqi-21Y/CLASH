<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';
$titulo_admin = 'Jugadores';
require_once __DIR__ . '/../includes/header_admin.php';

$jugadores = $db->query("
    SELECT j.*, s.pin, c.nombre_es AS categoria
    FROM jugadores j
    LEFT JOIN sesiones s ON s.id = j.sesion_id
    LEFT JOIN categorias c ON c.id = s.categoria_id
    ORDER BY j.created_at DESC
");
?>

<div class="admin-topbar">
    <span class="admin-page-sub">Todos los jugadores registrados</span>
</div>

<div class="admin-table-wrap">
    <table class="admin-table jugadores-table">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Nombre</th>
                <th>PIN</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($j = $jugadores->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td><img src="/CLASH/assets/img/<?= htmlspecialchars($j['avatar']) ?>.png" class="tabla-avatar-img" alt="avatar"></td>
                <td><?= htmlspecialchars($j['nombre']) ?></td>
                <td><?= $j['pin'] ? htmlspecialchars($j['pin']) : '—' ?></td>
                <td><?= $j['categoria'] ? htmlspecialchars($j['categoria']) : '—' ?></td>
                <td>
                    <div class="admin-table-actions">
                        <a href="/CLASH/admin/jugadores/bajaJugadorProc.php?id=<?= $j['id'] ?>"
                           class="btn-admin btn-admin-danger"
                           onclick="return confirm('¿Eliminar este jugador?')">Eliminar</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>