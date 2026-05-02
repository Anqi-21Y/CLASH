<?php
session_start();
// eliminar un jugador  y sus datos

// solo admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}

// conectar bbdd
require_once __DIR__ . '/../config/conexion_admin.php';

// obtener el id de jugador de url
$id = intval($_GET['id'] ?? 0);

// eliminar respuestas del judagor
$db->exec("DELETE FROM respuestas WHERE jugador_id = $id");

// eliminar reslutados de judagor
$db->exec("DELETE FROM resultados WHERE jugador_id = $id");

// eliminar jugador
$db->exec("DELETE FROM jugadores WHERE id = $id");
$db->close();

// volver a gestion
header('Location: /CLASH/admin/jugadores/gestionJugadores.php');
exit;