<?php
// Consultar el estado de la sesion y el progreso individual del jugador en tiempo real
require __DIR__ . '/../../config/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sesion_id  = isset($_GET['sesion_id'])  ? intval($_GET['sesion_id'])  : 0;
    $jugador_id = isset($_GET['jugador_id']) ? intval($_GET['jugador_id']) : 0;

    if ($sesion_id === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'ID de sesión no válido']);
        exit;
    }

    // Obtener estado general de la sesion
    $stmt = $db->prepare("SELECT estado, reto_actual, categoria_id FROM sesiones WHERE id = :sid");
    $stmt->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);
    $data = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if (!$data) {
        http_response_code(404);
        echo json_encode(['error' => 'Sesión no encontrada']);
        $db->close();
        exit;
    }

    $reto_para_jugador = $data['reto_actual']; // fallback: valor global

    // Si viene jugador_id, calculamos su siguiente pregunta personal
    if ($jugador_id > 0 && $data['estado'] === 'en_juego') {
        $stmt2 = $db->prepare("SELECT id FROM retos WHERE categoria_id = :cat
            AND id NOT IN (SELECT reto_id FROM respuestas WHERE sesion_id = :sid 
            AND jugador_id = :jid ) ORDER BY id ASC LIMIT 1");

        $stmt2->bindValue(':cat', intval($data['categoria_id']), SQLITE3_INTEGER);
        $stmt2->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);
        $stmt2->bindValue(':jid', $jugador_id, SQLITE3_INTEGER);
        $row = $stmt2->execute()->fetchArray(SQLITE3_ASSOC);

        // Si no quedan preguntas para este jugador, devolvemos null
        $reto_para_jugador = $row ? $row['id'] : null;
    }

    // Respuesta final
    echo json_encode([
        'success' => true,
        'estado' => $data['estado'],
        'reto_actual' => $reto_para_jugador
    ]);

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

$db->close();