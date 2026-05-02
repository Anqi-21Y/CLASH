<?php
// reiniciar la sesion sin borrar datos antiguos
// sirve para empezar una nueva partida

require __DIR__ . '/../config/conexion.php';

// Indicamos respuesta JSON
header('Content-Type: application/json');

// Obtenemos el ID de la sesión desde la URL
$sesion_id = isset($_GET['sesion_id']) ? intval($_GET['sesion_id']) : 0;

if ($sesion_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Falta el ID de la sesión.']);
    exit;
}

try {
    // empezar transaccion
    $db->exec('BEGIN TRANSACTION');

    // resetear estado de la sesion (vuelve al lobby)
    $stmt = $db->prepare("UPDATE sesiones SET estado = 'esperando', reto_actual = 0 WHERE id = :sid");

    $stmt->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);
    $stmt->execute();

    // quitar jugadores de la sesion (para que no aparezcan)
    $db->exec("UPDATE jugadores SET sesion_id = 0 WHERE sesion_id = $sesion_id");

    // guardar cambios
    $db->exec('COMMIT');

    echo json_encode([
        'success' => true,
        'message' => 'Sesión reiniciada. Los datos históricos han sido preservados para el Ranking Global.'
    ]);

} catch (Exception $e) {
    $db->exec('ROLLBACK');
    echo json_encode([
        'success' => false,
        'message' => 'Error al limpiar sesión: ' . $e->getMessage()
    ]);
}

$db->close();