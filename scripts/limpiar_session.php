<?php
/**
 * ARCHIVO: api/admin/limpiar_session.php
 * ACCIÓN: Reinicio de sesión activa sin pérdida de datos históricos.
 * FUNCIONALIDAD: 
 * 1. Cambia el estado de la sesión a 'esperando'.
 * 2. Desvincula a los jugadores actuales para que el lobby empiece de cero.
 * 3. NO elimina las respuestas (para permitir el Ranking Global por categoría).
 */

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
    // Iniciamos transacción para asegurar integridad
    $db->exec('BEGIN TRANSACTION');

    /**
     * LOGICA: 
     * Para que el Ranking Global funcione, no podemos borrar los registros de 'respuestas'.
     * Pero para que la pantalla vuelva al Lobby (0 personas), vamos a:
     * 1. Marcar a los jugadores de esta sesión como "inactivos" o simplemente
     * cambiar el estado de la sesión para que nadie pueda usar el PIN antiguo.
     */

    // 1. Resetear el estado de la sesión
    // Esto hace que pantalla.php vuelva a mostrar el QR y el Lobby vacío
    $stmt = $db->prepare("
        UPDATE sesiones 
        SET estado = 'esperando', 
            reto_actual = 0 
        WHERE id = :sid
    ");
    $stmt->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);
    $stmt->execute();

    // 2. (Opcional) Si quieres que la lista de nombres del Lobby se vacíe:
    // Desvinculamos a los jugadores de esta sesión (ponemos su sesion_id a NULL o 0)
    // Así sus puntos anteriores se guardan pero ya no aparecen en el "ahora"
    $db->exec("UPDATE jugadores SET sesion_id = 0 WHERE sesion_id = $sesion_id");

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