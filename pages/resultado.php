<?php
// archivo para devolver el resultado despues de responder
// calcula puntos, total y posicion del jugador


// Incluyo la conexión como indica el profe en D05
require __DIR__ . '/../../config/conexion.php';

// devolver en formato json
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Recojo parámetros de la URL
    $jugador_id = isset($_GET['jugador_id']) ? intval($_GET['jugador_id']) : 0;
    $sesion_id  = isset($_GET['sesion_id'])  ? intval($_GET['sesion_id'])  : 0;
    $reto_id    = isset($_GET['reto_id'])    ? intval($_GET['reto_id'])    : 0;

    if ($jugador_id === 0 || $sesion_id === 0 || $reto_id === 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Parámetros incompletos']);
        exit;
    }

    // 1. Consulto el resultado de la última respuesta enviada (D05 query)
    $stmt = $db->prepare("
        SELECT puntos, es_correcta 
        FROM respuestas 
        WHERE jugador_id = :jid AND reto_id = :rid AND sesion_id = :sid
    ");
    $stmt->bindValue(':jid', $jugador_id, SQLITE3_INTEGER);
    $stmt->bindValue(':rid', $reto_id, SQLITE3_INTEGER);
    $stmt->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);
    $res = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    // 2. Calculo el total acumulado de este jugador en esta sesión
    $puntos_acumulados = $db->querySingle("
        SELECT SUM(puntos) 
        FROM respuestas 
        WHERE jugador_id = $jugador_id AND sesion_id = $sesion_id
    ");

    // 3. Calculo la POSICIÓN REAL en el ranking (D05 lógica de competición)
    // Cuento cuántos jugadores tienen más puntos que yo + 1
    $posicion_actual = $db->querySingle("
        SELECT COUNT(*) + 1 FROM (
            SELECT SUM(puntos) as total 
            FROM respuestas 
            WHERE sesion_id = $sesion_id 
            GROUP BY jugador_id
            HAVING total > " . ($puntos_acumulados ?? 0) . "
        )
    ");

    // Devuelvo el éxito con toda la información para el UI
    echo json_encode([
        'success'           => true,
        'puntos_ganados'    => $res['puntos'] ?? 0,
        'es_correcta'       => (bool)($res['es_correcta'] ?? false),
        'puntos_acumulados' => $puntos_acumulados ?? 0,
        'posicion_actual'   => $posicion_actual ?? 1
    ]);

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}

// Cierro conexión (D05)
$db->close();