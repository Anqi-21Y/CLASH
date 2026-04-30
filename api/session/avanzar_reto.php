<?php
// fix: devuelve siguiente_reto correctamente y actualiza sesiones para compatibilidad con radar
require __DIR__ . '/../../config/conexion.php';
require __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input          = json_decode(file_get_contents('php://input'), true);
$sesion_id      = intval($input['sesion_id']      ?? 0);
$jugador_id     = intval($input['jugador_id']     ?? 0);
$reto_id_actual = intval($input['reto_id_actual'] ?? 0);

if ($sesion_id <= 0 || $jugador_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit;
}

// obtengo la categoría de la sesión
$sesion = $db->querySingle(
    "SELECT categoria_id FROM sesiones WHERE id = $sesion_id", true
);
if (!$sesion) {
    http_response_code(404);
    echo json_encode(['error' => 'Sesión no encontrada']);
    exit;
}
$categoria_id = intval($sesion['categoria_id']);

// cuántas preguntas distintas ha respondido YA este jugador
$retos_jugados = $db->querySingle("
    SELECT COUNT(DISTINCT reto_id) FROM respuestas
    WHERE sesion_id = $sesion_id AND jugador_id = $jugador_id
");

// este jugador ya terminó todas sus preguntas
if ($retos_jugados >= NUM_PREGUNTAS) {

    // compruebo si TODOS los jugadores de la sesión han terminado
    $total = $db->querySingle(
        "SELECT COUNT(*) FROM jugadores WHERE sesion_id = $sesion_id"
    );
    $terminados = $db->querySingle("
        SELECT COUNT(*) FROM (
            SELECT jugador_id FROM respuestas
            WHERE sesion_id = $sesion_id
            GROUP BY jugador_id
            HAVING COUNT(DISTINCT reto_id) >= " . NUM_PREGUNTAS . "
        )
    ");

    if ($terminados >= $total) {
        $db->exec("UPDATE sesiones SET estado = 'terminada' WHERE id = $sesion_id");
        echo json_encode(['success' => true, 'estado' => 'terminada']);
    } else {
        echo json_encode(['success' => true, 'estado' => 'esperando_final']);
    }
    $db->close();
    exit;
}

// busco la siguiente pregunta que este jugador NO haya respondido aún
$stmt = $db->prepare("
    SELECT id FROM retos
    WHERE categoria_id = :cat
      AND id NOT IN (
          SELECT reto_id FROM respuestas
          WHERE sesion_id  = :sid
            AND jugador_id = :jid
      )
    ORDER BY id ASC
    LIMIT 1
");
$stmt->bindValue(':cat', $categoria_id, SQLITE3_INTEGER);
$stmt->bindValue(':sid', $sesion_id,    SQLITE3_INTEGER);
$stmt->bindValue(':jid', $jugador_id,   SQLITE3_INTEGER);
$row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if ($row) {
    $siguiente_id = intval($row['id']);

    // fix: actualizamos reto_actual en sesiones para que el radar también funcione como fallback.
    // En modo libre cada jugador lleva su propio ritmo, pero mantener este campo sincronizado
    // evita que el radar de otros jugadores quede ciego si obtener_pregunta falla con jugador_id.
    // El valor aquí es el del jugador más avanzado (last-write-wins), lo cual es inofensivo
    // porque obtener_pregunta.php ya filtra por progreso personal cuando recibe jugador_id.
    $db->exec("UPDATE sesiones SET reto_actual = $siguiente_id WHERE id = $sesion_id");

    echo json_encode([
        'success'        => true,
        'siguiente_reto' => $siguiente_id,   // fix: el frontend usa este valor directamente
    ]);
} else {
    echo json_encode(['success' => true, 'estado' => 'esperando_final']);
}

$db->close();