<?php

// Registrar un nuevo jugador en la base de datos tras unirse a una sesión

// BBDD
require __DIR__ . '/../../config/conexion.php';

// la configuracion general del juego
require __DIR__ . '/../../config/config.php';

// indico que la respuesta sera siempre json como indica el profe en d11
header('Content-Type: application/json');

// verifico que la peticion sea POST como indica el profe en d11 y d12
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // leo el json que envia javascript desde el movil del jugador
    $input = json_decode(file_get_contents('php://input'), true);

    // verifico que lleguen todos los datos necesarios
    if (
        isset($input['nombre']) &&
        isset($input['avatar']) &&
        isset($input['idioma']) &&
        isset($input['sesion_id']) &&
        in_array($input['idioma'], IDIOMAS)
    ) {

        // limpio el nombre para evitar datos sucios
        $nombre = trim($input['nombre']);
        $avatar = trim($input['avatar']);
        $idioma = trim($input['idioma']);
        $sesion_id = intval($input['sesion_id']);

        // verifico que el nombre no este vacio despues de limpiar
        if (empty($nombre)) {
            http_response_code(400);
            echo json_encode(['error' => 'el nombre no puede estar vacio']);
            exit;
        }

        // preparo la consulta con prepare y bindvalue como indica el profe en d06 y d12
        $stmt = $db->prepare("
            INSERT INTO jugadores (nombre, avatar, idioma, sesion_id)
            VALUES (:nombre, :avatar, :idioma, :sesion_id)
        ");

        $stmt->bindValue(':nombre', $nombre, SQLITE3_TEXT);
        $stmt->bindValue(':avatar', $avatar, SQLITE3_TEXT);
        $stmt->bindValue(':idioma', $idioma, SQLITE3_TEXT);
        $stmt->bindValue(':sesion_id', $sesion_id, SQLITE3_INTEGER);

        // ejecuto la consulta y devuelvo el resultado
        if ($stmt->execute()) {
            // obtengo el id del jugador recien insertado
            $id = $db->lastInsertRowID();
            http_response_code(201);
            echo json_encode([
                'success' => 'jugador registrado correctamente',
                'jugador_id' => $id,
                'sesion_id' => $sesion_id,
                'nombre' => $nombre,
                'avatar' => $avatar,
                'idioma' => $idioma
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'error al registrar el jugador']);
        }

    } else {
        // faltan datos obligatorios
        http_response_code(400);
        echo json_encode(['error' => 'faltan datos obligatorios']);
    }

} else {
    // el metodo no es post
    http_response_code(405);
    echo json_encode(['error' => 'metodo no permitido']);
}

// cierro la conexion con la base de datos como indica el profe en d05
$db->close();
