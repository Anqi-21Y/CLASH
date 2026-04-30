<?php
// Expulsar jugadores. 
// Los administradores deberían poder eliminar a los jugadores con un solo clic si usan nombres ofensivos.

require __DIR__ . '/../../config/conexion.php';
header('Content-Type: application/json');

// La verificación debe ser una solicitud POST e incluir el ID del jugador.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $jugador_id = isset($input['jugador_id']) ? intval($input['jugador_id']) : 0;

    if ($jugador_id > 0) {
        $stmt = $db->prepare("DELETE FROM jugadores WHERE id = :id");
        $stmt->bindValue(':id', $jugador_id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Jugador expulsado']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'No se pudo expulsar']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'ID no valido']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo no permitido']);
}
$db->close();