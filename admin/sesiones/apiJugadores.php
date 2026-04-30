<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';
header('Content-Type: application/json');

$sesion_id = intval($_GET['sesion_id'] ?? 0);

$result = $db->query("
    SELECT nombre, avatar FROM jugadores
    WHERE sesion_id = $sesion_id
    ORDER BY created_at ASC
");

$jugadores = [];
while ($j = $result->fetchArray(SQLITE3_ASSOC)) {
    $jugadores[] = $j;
}

echo json_encode(['total' => count($jugadores), 'jugadores' => $jugadores]);