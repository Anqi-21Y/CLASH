<?php
// Determinar el progreso del jugador y decidir si debe ir al siguiente reto o finalizar.

require __DIR__ . '/../../config/conexion.php';
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$sesion_id = intval($input['sesion_id'] ?? 0);
$jugador_id = intval($input['jugador_id'] ?? 0);
$reto_id_actual = intval($input['reto_id_actual'] ?? 0);

if ($sesion_id <= 0 || $jugador_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

// obtengo la categoria de la sesion
$sesion = $db->querySingle("SELECT categoria_id FROM sesiones WHERE id = $sesion_id", true);

if (!$sesion) {
    http_response_code(404);
    echo json_encode(['error' => 'Sesión no encontrada']);
    exit;
}
$categoria_id = intval($sesion['categoria_id']);

// Contar cuántas preguntas ha respondido ya este jugador
$retos_jugados = $db->querySingle("SELECT COUNT(DISTINCT reto_id) FROM respuestas WHERE sesion_id = $sesion_id AND jugador_id = $jugador_id");

// este jugador ya terminó todas sus preguntas
if ($retos_jugados >= NUM_PREGUNTAS) {

    // Comprobar si TODOS han terminado para cerrar la sesio
    $total = $db->querySingle("SELECT COUNT(*) FROM jugadores WHERE sesion_id = $sesion_id");

    $terminados = $db->querySingle("SELECT COUNT(*) FROM (SELECT jugador_id FROM respuestas WHERE sesion_id = $sesion_id GROUP BY jugador_id HAVING COUNT(DISTINCT reto_id) >= " . NUM_PREGUNTAS . ")");

    if ($terminados >= $total) {
        $db->exec("UPDATE sesiones SET estado = 'terminada' WHERE id = $sesion_id");
        echo json_encode(['success' => true, 'estado' => 'terminada']);
    } else {
        echo json_encode(['success' => true, 'estado' => 'esperando_final']);
    }
    $db->close();
    exit;
}

// Buscar la siguiente pregunta disponible
$stmt = $db->prepare("SELECT id FROM retos WHERE categoria_id = :cat AND id NOT IN (SELECT reto_id FROM respuestas WHERE sesion_id  = :sid AND jugador_id = :jid )ORDER BY id ASC LIMIT 1");

$stmt->bindValue(':cat', $categoria_id, SQLITE3_INTEGER);
$stmt->bindValue(':sid', $sesion_id,    SQLITE3_INTEGER);
$stmt->bindValue(':jid', $jugador_id,   SQLITE3_INTEGER);
$row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if ($row) {
    $siguiente_id = intval($row['id']);

    // Sincronizar el reto_actual de la sesion para compatibilidad
    $db->exec("UPDATE sesiones SET reto_actual = $siguiente_id WHERE id = $sesion_id");

    echo json_encode([
        'success'        => true,
        'siguiente_reto' => $siguiente_id,
    ]);
} else {
    echo json_encode(['success' => true, 'estado' => 'esperando_final']);
}

$db->close();