<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /inner-work/clash/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $db->exec("PRAGMA foreign_keys = OFF");

    // elimino respuestas de los retos de esta categoría
    $db->exec("DELETE FROM respuestas WHERE reto_id IN (SELECT id FROM retos WHERE categoria_id = $id)");
    // elimino los retos
    $db->exec("DELETE FROM retos WHERE categoria_id = $id");
    // elimino la categoría
    $db->exec("DELETE FROM categorias WHERE id = $id");

    $db->exec("PRAGMA foreign_keys = ON");
}

$db->close();
header('Location: /inner-work/clash/admin/retos/gestionRetos.php');
exit;