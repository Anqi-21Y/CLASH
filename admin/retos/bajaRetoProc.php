<?php
session_start();
// eliminar retos y sus respuestos
// slo admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}

// BBDD
require_once __DIR__ . '/../config/conexion_admin.php';

// obtener id de ses
$id = intval($_GET['id'] ?? 0);

$db->exec("DELETE FROM respuestas WHERE reto_id = $id");
$db->exec("DELETE FROM retos WHERE id = $id");
$db->close();

header('Location: /CLASH/admin/retos/gestionRetos.php');
exit;