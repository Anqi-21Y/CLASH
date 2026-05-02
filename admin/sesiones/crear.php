<?php
session_start();

// Configurar una nueva partida generando un PIN único y seleccionando una categoria

// admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}

// bbdd
require_once __DIR__ . '/../config/conexion_admin.php';

// Genera un PIN de 6 que no exista en la base de datos
do {
    $pin = strval(random_int(100000, 999999));
    $existe = $db->querySingle("SELECT id FROM sesiones WHERE pin = '$pin'");
} while ($existe);

// Obtener categorías y el numero de retos que tiene cada una
$categorias = [];
$res = $db->query("SELECT c.*, COUNT(r.id) as num_retos FROM categorias c LEFT JOIN retos r ON r.categoria_id = c.id GROUP BY c.id ORDER BY c.id");

while ($cat = $res->fetchArray(SQLITE3_ASSOC)) {
    $categorias[] = $cat;
}

$titulo_admin = 'Nueva partida';
require_once __DIR__ . '/../includes/header_admin.php';
?>

<div class="admin-topbar">
    <span class="admin-page-sub">Nueva partida</span>
    <a href="/CLASH/admin/sesiones/gestion.php" class="btn-admin btn-admin-ghost">← Cancelar</a>
</div>

<div class="pin-box">
    <p class="pin-box-label">PIN generado</p>
    <div class="admin-pin-display"><?= $pin ?></div>
    <p class="pin-box-hint">Compártelo cuando todos estén listos</p>
</div>

<form action="/CLASH/admin/sesiones/crearProc.php" method="POST">
    <input type="hidden" name="pin" value="<?= $pin ?>">
    <input type="hidden" name="categoria_id" id="categoria_id_input" value="<?= $categorias[0]['id'] ?>">

    <p class="crear-cat-label">Elige la categoría</p>
    <div class="crear-cat-grid">
        <?php foreach ($categorias as $i => $cat): ?>
        <div class="crear-cat-option <?= $i === 0 ? 'seleccionada' : '' ?>"
             onclick="seleccionarCat(this, <?= $cat['id'] ?>)">
            <div class="crear-cat-nombre"><?= htmlspecialchars($cat['nombre_es']) ?></div>
            <div class="crear-cat-count"><?= $cat['num_retos'] ?> retos</div>
        </div>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="btn-admin btn-admin-pink crear-btn">Crear partida →</button>
</form>

<script>
// Cambiar la categoria seleccionada
function seleccionarCat(el, id) {
    // Quitar la clase 'seleccionada' de todas las opciones
    document.querySelectorAll('.crear-cat-option').forEach(c => c.classList.remove('seleccionada'));
    el.classList.add('seleccionada');
    // Actualizar el valor del input oculto
    document.getElementById('categoria_id_input').value = id;
}
</script>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>