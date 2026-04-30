<?php
// Parada de emergencia. 
// Por ejemplo, cuando se acabe el tiempo o quieras terminar el juego antes de tiempo, cambia forzosamente el estado a terminado.

require __DIR__ . '/../../config/conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $sesion_id = isset($input['sesion_id']) ? intval($input['sesion_id']) : 0;

    if ($sesion_id > 0) {
        $stmt = $db->prepare("UPDATE sesiones SET estado = 'terminada' WHERE id = :id");
        $stmt->bindValue(':id', $sesion_id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            // Aquí también puedes llamar a la lógica de cálculo de la clasificación o redirigir la interfaz a la tabla de clasificación.
            echo json_encode(['success' => true, 'message' => 'Sesion finalizada']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al finalizar']);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo no permitido']);
}
$db->close();