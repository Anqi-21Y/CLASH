<?php
session_start();
// Iniciar la partida

// solo admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}
// bbdd
require_once __DIR__ . '/../config/conexion_admin.php';

$id = intval($_GET['id'] ?? 0);

// Obtener la categoria de esta sesion
$sesionInfo = $db->query("SELECT categoria_id FROM sesiones WHERE id = $id")->fetchArray(SQLITE3_ASSOC);
$cat_id = $sesionInfo['categoria_id'];

// Seleccionar aleatoriamente la primera pregunta
$primeraPregunta = $db->querySingle("SELECT id FROM retos WHERE categoria_id = $cat_id ORDER BY RANDOM() LIMIT 1");

// Registrar la hora de inicio para la cuenta regresiva
$ahora = date('Y-m-d H:i:s');

// Actualizar el estado de la sesion a 'en_juego'
$stmt = $db->prepare("UPDATE sesiones SET estado = 'en_juego', reto_actual = :reto, inicio_timestamp = :ahora WHERE id = :id AND estado = 'esperando'");

$stmt->bindValue(':reto', $primeraPregunta, SQLITE3_INTEGER);
$stmt->bindValue(':ahora', $ahora, SQLITE3_TEXT);
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->execute();

$db->close();

// Volver a la vista de la sesión
header("Location: /CLASH/admin/sesiones/vista.php?id=$id");
exit;