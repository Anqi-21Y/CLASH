<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';

$id = intval($_GET['id'] ?? 0);

$db->exec("DELETE FROM respuestas WHERE jugador_id = $id");
$db->exec("DELETE FROM resultados WHERE jugador_id = $id");
$db->exec("DELETE FROM jugadores WHERE id = $id");
$db->close();

header('Location: /CLASH/admin/jugadores/gestionJugadores.php');
exit;