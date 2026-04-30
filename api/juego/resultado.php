<?php
require __DIR__ . '/../../config/conexion.php';
header('Content-Type: application/json');

$jugador_id = intval($_GET['jugador_id'] ?? 0);
$sesion_id  = intval($_GET['sesion_id'] ?? 0);
$reto_id    = intval($_GET['reto_id'] ?? 0); // ID de la pregunta actual
$type       = $_GET['type'] ?? 'parcial';    // 'parcial' 

if (!$jugador_id || !$sesion_id) {
    echo json_encode(['error' => 'Faltan datos']); exit;
}


// 1. Se otorgarán puntos por esta pregunta (solo es necesario si type == 'parcial').
$puntos_esta_pregunta = 0;
if ($type == 'parcial' && $reto_id > 0) {
    $puntos_esta_pregunta = $db->querySingle("
        SELECT puntos FROM respuestas 
        WHERE jugador_id = $jugador_id AND sesion_id = $sesion_id AND reto_id = $reto_id
    ") ?: 0;
}

// 2. Puntuación total acumulada (calculada en tiempo real a partir de la tabla de respuestas)
$puntos_totales = $db->querySingle("
    SELECT SUM(puntos) FROM respuestas 
    WHERE jugador_id = $jugador_id AND sesion_id = $sesion_id
") ?: 0;

// 3. Cálculo de la clasificación en tiempo real
// Calcula cuántas personas tienen una puntuación total superior a la mía y, a continuación, suma 1.
$ranking = $db->querySingle("
    SELECT COUNT(*) + 1 FROM (
        SELECT SUM(puntos) as score FROM respuestas 
        WHERE sesion_id = $sesion_id GROUP BY jugador_id
    ) WHERE score > $puntos_totales
");

// --- Datos devueltos ---

if ($type == 'parcial') {
    // Comentarios de las fases 1 y 2
    echo json_encode([
        'status' => 'success',
        'puntos_ganados' => $puntos_esta_pregunta, // ¿Cuántos puntos obtuviste en esta pregunta?
        'puntos_acumulados' => $puntos_totales,    // ¿Cuánto te llevaste en total?
        'posicion_actual' => $ranking              // ¿Cuál es la clasificación actual?
    ]);
} else {
    $podio = [];

    $res = $db->query("
        SELECT 
            j.nombre,
            j.avatar,
            SUM(r.puntos) as puntos_total
        FROM respuestas r
        JOIN jugadores j ON r.jugador_id = j.id
        WHERE r.sesion_id = $sesion_id
        GROUP BY r.jugador_id
        ORDER BY puntos_total DESC
        LIMIT 5
    ");

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