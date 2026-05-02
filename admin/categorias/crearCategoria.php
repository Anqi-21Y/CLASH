<?php
session_start();

// Backend : crear una nueva pagina de categoria

// asegurar  ser admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}

// conectar a bbdd
require_once __DIR__ . '/../config/conexion_admin.php';
$titulo_admin = 'Nueva categoría';

// header
require_once __DIR__ . '/../includes/header_admin.php';
?>

<div class="admin-topbar">
    <span class="admin-page-sub">Nueva categoría</span>
    <a href="/CLASH/admin/retos/gestionRetos.php" class="btn-admin btn-admin-ghost">← Cancelar</a>
</div>

<div class="crear-steps">
    <div class="crear-step activo">
        <span class="crear-step-num">1</span>
        <span>Categoría</span>
    </div>
    <div class="crear-step-line"></div>
    <div class="crear-step">
        <span class="crear-step-num">2</span>
        <span>Añadir retos</span>
    </div>
</div>

<div class="admin-form-container">
    <form action="/CLASH/admin/categorias/crearCategoriaProc.php" method="POST">
        <div class="admin-form-group">
            <label>Nombre de la categoría</label>
            <input type="text" name="nombre_es" placeholder="Ej: Deportes" required>
        </div>
        <button type="submit" class="btn-admin btn-admin-pink">Crear y añadir retos →</button>
    </form>
</div>

<!--Footer-->
<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>