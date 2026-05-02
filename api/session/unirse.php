<?php
// Validar el PIN de la sala y registrar al jugador en la sesion correcta

require __DIR__ . '/../../config/conexion.php';

header('Content-Type: application/json');

// solo POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $input = json_decode(file_get_contents('php://input'), true);

    $nombre = isset($input['nombre']) ? trim($input['nombre']) : '';
    $avatar = isset($input['avatar']) ? trim($input['avatar']) : '😀';
    $idioma = isset($input['idioma']) ? trim($input['idioma']) : 'es';
    $pin_room = isset($input['pin']) ? trim($input['pin']) : '';

    // Validacion de campos obligatorios
    if (empty($nombre) || empty($pin_room)) {
        http_response_code(400);
        echo json_encode(['error' => 'Nombre y PIN son obligatorios']);
        exit;
    }

    // Existe la sala y esta abierta
    $stmt = $db->prepare("SELECT id FROM sesiones WHERE pin = :pin AND estado = 'esperando' LIMIT 1");
    $stmt->bindValue(':pin', $pin_room, SQLITE3_TEXT);
    $res = $stmt->execute();
    $sesion = $res->fetchArray(SQLITE3_ASSOC);

    if (!$sesion) {
        // La sala no existe o ya empezó (estado != 'esperando')
        http_response_code(404);
        echo json_encode(['error' => 'La sesión no existe o ya ha comenzado']);
        exit;
    }

    $sesion_id = $sesion['id'];

    //Crear jugador y vincularlo a la sesion
    $stmt = $db->prepare("INSERT INTO jugadores (nombre, avatar, idioma, sesion_id) VALUES (:nom, :ava, :idi, :sid)");

    $stmt->bindValue(':nom', $nombre, SQLITE3_TEXT);
    $stmt->bindValue(':ava', $avatar, SQLITE3_TEXT);
    $stmt->bindValue(':idi', $idioma, SQLITE3_TEXT);
    $stmt->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);

    if ($stmt->execute()) {
        $jugador_id = $db->lastInsertRowID();
        
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'jugador_id' => $jugador_id,
            'sesion_id'  => $sesion_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al registrar el jugador']);
    }

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

$db->close();