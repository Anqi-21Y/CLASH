<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /inner-work/clash/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // ELIMINANDO
    $db->exec("DELETE FROM resultados WHERE sesion_id = $id");
    
    $db->exec("DELETE FROM respuestas WHERE sesion_id = $id");
    
    $db->exec("DELETE FROM jugadores WHERE sesion_id = $id");
    
    $db->exec("DELETE FROM sesiones WHERE id = $id");
}

$db->close();

header('Location: /inner-work/clash/admin/sesiones/gestion.php');
exit;