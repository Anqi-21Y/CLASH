<?php
//El pistoletazo de salida. 
// Una vez que todos los jugadores estén listos en la sala de espera, 
// el administrador pulsa este botón para cambiar el estado a en_juego, 
// y solo entonces los teléfonos de los jugadores empezarán a mostrar la primera pregunta.

require __DIR__ . '/../../config/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $sesion_id = isset($input['sesion_id']) ? intval($input['sesion_id']) : 0;

    if ($sesion_id > 0) {
        // Actualiza el estado y establece la pregunta actual como la primera pregunta
        //  (suponiendo que el ID comienza desde 1, o puedes realizar consultas dinámicas).
        $stmt = $db->prepare("
            UPDATE sesiones 
            SET estado = 'en_juego', reto_actual = 1 
            WHERE id = :id
        ");
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