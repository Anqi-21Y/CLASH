<?php
/*
 * api/session/jugadores.php
 * Devuelve la lista de jugadores conectados a una sesión.
 * Es pública — la usan los móviles de los jugadores en la sala de espera.
 */
require __DIR__ . '/../../config/conexion.php';
header('Content-Type: application/json');

$sesion_id = intval($_GET['sesion_id'] ?? 0);

if ($sesion_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'sesion_id inválido']);
    exit;
}

$result = $db->query("
    SELECT nombre, avatar FROM jugadores
    WHERE sesion_id = $sesion_id
    ORDER BY created_at ASC
");

$jugadores = [];
while ($j = $result->fetchArray(SQLITE3_ASSOC)) {
    $jugadores[] = $j;
}

echo json_encode([
    'success'   => true,
    'total'     => count($jugadores),
    'jugadores' => $jugadores,
]);

$db->close();