<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /inner-work/clash/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';
$titulo_admin = 'Categorías';
require_once __DIR__ . '/../includes/header_admin.php';

$categorias = $db->query("SELECT * FROM categorias ORDER BY id ASC");

$retos_por_cat = [];
$retos = $db->query("SELECT * FROM retos ORDER BY categoria_id, id ASC");
while ($r = $retos->fetchArray(SQLITE3_ASSOC)) {
    $retos_por_cat[$r['categoria_id']][] = $r;
}
?>

<div class="admin-topbar">
    <span class="admin-page-sub">Gestiona las categorías y sus retos</span>
    <a href="/inner-work/clash/admin/categorias/crearCategoria.php" class="btn-admin btn-admin-pink">+ Nueva categoría</a>
</div>

<div class="cat-cards-grid">
    <?php while ($cat = $categorias->fetchArray(SQLITE3_ASSOC)): ?>
    <?php $retos_cat = $retos_por_cat[$cat['id']] ?? []; ?>
    <div class="cat-card">
        <div class="cat-card-header">
            <div>
                <div class="cat-card-nombre"><?= htmlspecialchars($cat['nombre_es']) ?></div>
                <div class="cat-card-count"><?= count($retos_cat) ?> retos</div>
            </div>
            <div class="admin-actions-row">
                <a href="/inner-work/clash/admin/retos/crearReto.php?cat=<?= $cat['id'] ?>"
                   class="btn-admin btn-admin-reto">+ Reto</a>
                <a href="/inner-work/clash/admin/categorias/eliminarCategoriaProc.php?id=<?= $cat['id'] ?>"
                   class="cat-card-eliminar"
                   onclick="return confirm('¿Eliminar esta categoría y todos sus retos?')">eliminar</a>
            </div>
        </div>

        <div class="cat-card-body">
            <?php if (empty($retos_cat)): ?>
                <p class="cat-empty">No hay retos todavía</p>
            <?php else: ?>
                <?php foreach ($retos_cat as $r): ?>
                <div class="cat-reto-row" id="reto-<?= $r['id'] ?>">
                    <div class="cat-reto-top">
                        <span class="cat-reto-tipo"><?= $r['tipo'] ?></span>
                        <span class="cat-reto-respuesta">
                            <?php
                            $op = 'op' . $r['opcion_correcta'] . '_es';
                            echo htmlspecialchars($r[$op]);
                            ?>
                        </span>
                        <div class="cat-reto-acciones">
                            <button class="cat-btn-editar" onclick="toggleEditar(<?= $r['id'] ?>)">editar</button>
                            <a href="/inner-work/clash/admin/retos/bajaRetoProc.php?id=<?= $r['id'] ?>"
                               class="cat-reto-eliminar"
                               onclick="return confirm('¿Eliminar este reto?')">eliminar</a>
                        </div>
                    </div>
                    <div class="cat-reto-edit-panel" id="edit-<?= $r['id'] ?>" style="display:none">
                        <div class="admin-form-group">
                            <label>Emojis / Contenido</label>
                            <input type="text" id="emojis-<?= $r['id'] ?>" value="<?= htmlspecialchars($r['emojis'] ?? '') ?>">
                        </div>
                        <div class="admin-form-group">
                            <label>Opciones</label>
                            <div class="admin-input-stack">
                                <input type="text" id="op1-<?= $r['id'] ?>" value="<?= htmlspecialchars($r['op1_es']) ?>">
                                <input type="text" id="op2-<?= $r['id'] ?>" value="<?= htmlspecialchars($r['op2_es']) ?>">
                                <input type="text" id="op3-<?= $r['id'] ?>" value="<?= htmlspecialchars($r['op3_es']) ?>">
                                <input type="text" id="op4-<?= $r['id'] ?>" value="<?= htmlspecialchars($r['op4_es']) ?>">
                            </div>
                        </div>
                        <div class="admin-form-group">
                            <label>Respuesta correcta</label>
                            <select id="correcta-<?= $r['id'] ?>">
                                <option value="1" <?= $r['opcion_correcta'] == 1 ? 'selected' : '' ?>>Opción 1</option>
                                <option value="2" <?= $r['opcion_correcta'] == 2 ? 'selected' : '' ?>>Opción 2</option>
                                <option value="3" <?= $r['opcion_correcta'] == 3 ? 'selected' : '' ?>>Opción 3</option>
                                <option value="4" <?= $r['opcion_correcta'] == 4 ? 'selected' : '' ?>>Opción 4</option>
                            </select>
                        </div>
                        <div class="cat-edit-actions">
                            <button class="btn-admin btn-admin-ghost" onclick="toggleEditar(<?= $r['id'] ?>)">Cancelar</button>
                            <button class="btn-admin btn-admin-pink" onclick="guardarReto(<?= $r['id'] ?>)">Guardar cambios</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<script>
function toggleEditar(id) {
    const panel = document.getElementById('edit-' + id);
    const btn   = document.querySelector('#reto-' + id + ' .cat-btn-editar');
    const abierto = panel.style.display !== 'none';
    panel.style.display = abierto ? 'none' : 'block';
    btn.classList.toggle('activo', !abierto);
    btn.textContent = abierto ? 'editar' : 'editar ▲';
}

function guardarReto(id) {
    const datos = {
        id:              id,
        emojis:          document.getElementById('emojis-' + id).value,
        op1_es:          document.getElementById('op1-' + id).value,
        op2_es:          document.getElementById('op2-' + id).value,
        op3_es:          document.getElementById('op3-' + id).value,
        op4_es:          document.getElementById('op4-' + id).value,
        opcion_correcta: document.getElementById('correcta-' + id).value,
    };

    fetch('/inner-work/clash/admin/retos/editarRetoProc.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            toggleEditar(id);
            // actualizo el texto visible de la respuesta correcta
            const op = document.getElementById('op' + datos.opcion_correcta + '-' + id).value;
            document.querySelector('#reto-' + id + ' .cat-reto-respuesta').textContent = op;
        } else {
            alert('Error al guardar');
        }
    })
    .catch(() => alert('Error de conexión'));
}
</script>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>