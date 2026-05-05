<?php
session_start();
// Obtener la lista de jugadores unidos a una sesión especifica

// solo admin
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit;
}
// bbdd
require_once __DIR__ . '/../config/conexion_admin.php';
header('Content-Type: application/json');

// Obtener el ID de la sesión desde URL
$sesion_id = intval($_GET['sesion_id'] ?? 0);

// Consultar los nombres y avatares de los jugadores de esta sesion
$result = $db->query("SELECT nombre, avatar FROM jugadores WHERE sesion_id = $sesion_id ORDER BY created_at ASC");

$jugadores = [];
while ($j = $result->fetchArray(SQLITE3_ASSOC)) {
    $jugadores[] = $j;
}

// Devolver los datos en formato JSON
echo json_encode(['success' => true, 'total' => count($jugadores), 'jugadores' => $jugadores]);