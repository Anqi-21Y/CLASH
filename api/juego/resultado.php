<?php
// Proporcionar el feedback de puntuación y ranking en tiempo real al jugador

require __DIR__ . '/../../config/conexion.php';
header('Content-Type: application/json');

$jugador_id = intval($_GET['jugador_id'] ?? 0);
$sesion_id  = intval($_GET['sesion_id'] ?? 0);
$reto_id = intval($_GET['reto_id'] ?? 0); // ID de la pregunta actual
$type = $_GET['type'] ?? 'parcial';    // 'parcial' 

if (!$jugador_id || !$sesion_id) {
    echo json_encode(['error' => 'Faltan datos']); exit;
}


// Obtener puntos de la pregunta actual
$puntos_esta_pregunta = 0;
if ($type == 'parcial' && $reto_id > 0) {
    $puntos_esta_pregunta = $db->querySingle("
        SELECT puntos FROM respuestas 
        WHERE jugador_id = $jugador_id AND sesion_id = $sesion_id AND reto_id = $reto_id
    ") ?: 0;
}

// 2. Calcular puntuación total acumulada
$puntos_totales = $db->querySingle("
    SELECT SUM(puntos) FROM respuestas 
    WHERE jugador_id = $jugador_id AND sesion_id = $sesion_id
") ?: 0;

// Calculo el Ranking en tiempo real
$ranking = $db->querySingle("SELECT COUNT(*) + 1 FROM (SELECT SUM(puntos) as score FROM respuestas WHERE sesion_id = $sesion_id GROUP BY jugador_id ) WHERE score > $puntos_totales");

//Respuesta JSON

if ($type == 'parcial') {
    // Comentarios de las fases 1 y 2
    echo json_encode([
        'status' => 'success',
        'puntos_ganados' => $puntos_esta_pregunta, // nota de pregunta
        'puntos_acumulados' => $puntos_totales,    // nota final
        'posicion_actual' => $ranking              // ranking
    ]);
} else {
    // Respuesta para el final del juego
    $podio = [];

    $res = $db->query("SELECT j.nombre, j.avatar, SUM(r.puntos) as puntos_total FROM respuestas r 
    JOIN jugadores j ON r.jugador_id = j.id WHERE r.sesion_id = $sesion_id GROUP BY r.jugador_id ORDER BY puntos_total DESC LIMIT 5");

    $pos = 1;
    while($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $row['posicion'] = $pos++;
        $podio[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'puntos_finales' => $puntos_totales,
        'tu_posicion' => $ranking,
        'leaderboard' => $podio
    ]);
}

$db->close();