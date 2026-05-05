<?php
session_start();
// crear un reto

// solo admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}

// bbdd
require_once __DIR__ . '/../config/conexion_admin.php';

// Obtener categorias para el selector
$cat_preseleccionada = intval($_GET['cat'] ?? 0);

$idioma = $_SESSION['lang'] ?? 'es';
$idiomas_validos = ['es', 'ca', 'zh'];

if (!in_array($idioma, $idiomas_validos)) {
    $idioma = 'es';
}

$campo_categoria = "nombre_" . $idioma;

$categorias = $db->query("SELECT * FROM categorias ORDER BY $campo_categoria ASC");

$titulo_admin = 'Nuevo reto';
require_once __DIR__ . '/../includes/header_admin.php';
?>

<div class="admin-topbar">
    <span class="admin-page-sub">Nuevo reto</span>
    <a href="gestionRetos.php" class="btn-admin btn-admin-ghost">← Cancelar</a>
</div>

<div class="admin-form-container">
    <form action="crearRetoProc.php" method="POST">

        <div class="reto-form-grid">
            <div class="admin-form-group">
                <label>Categoría</label>
                <select name="categoria_id" required>
                    <?php while ($cat = $categorias->fetchArray(SQLITE3_ASSOC)): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] === $cat_preseleccionada ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat[$campo_categoria]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="admin-form-group">
                <label>Tipo de reto</label>
                <select name="tipo" required>
                    <option value="emoji">Emoji</option>
                    <option value="imagen">Imagen</option>
                    <option value="video">Vídeo</option>
                    <option value="audio">Audio</option>
                    <option value="codigo">Código</option>
                </select>
            </div>
        </div>

        <div class="admin-form-group">
            <label>Emojis / Contenido del reto</label>
            <input type="text" name="emojis" placeholder="Ej: 🦁👑">
        </div>

        <div class="admin-form-group">
            <label>Opciones de respuesta</label>
            <div class="admin-input-stack">
                <input type="text" name="op1_es" placeholder="Opción 1" required>
                <input type="text" name="op2_es" placeholder="Opción 2" required>
                <input type="text" name="op3_es" placeholder="Opción 3" required>
                <input type="text" name="op4_es" placeholder="Opción 4" required>
            </div>
        </div>

        <div class="admin-form-group">
            <label>Respuesta correcta</label>
            <select name="opcion_correcta">
                <option value="1">Opción 1</option>
                <option value="2">Opción 2</option>
                <option value="3">Opción 3</option>
                <option value="4">Opción 4</option>
            </select>
        </div>

        <button type="submit" class="btn-admin btn-admin-pink">Guardar reto →</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>