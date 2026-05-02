<?php

//Cambia el estado de la sesion a 'en_juego' via API

require __DIR__ . '/../../config/conexion.php';
header('Content-Type: application/json');

// Verificar que la petición sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $sesion_id = isset($input['sesion_id']) ? intval($input['sesion_id']) : 0;

    if ($sesion_id > 0) {
        // Actualizar el estado de la sesion
        $stmt = $db->prepare("UPDATE sesiones SET estado = 'en_juego', reto_actual = 1 WHERE id = :id");
        $stmt->bindValue(':id', $sesion_id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Juego iniciado']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al iniciar']);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo no permitido']);
}
$db->close();