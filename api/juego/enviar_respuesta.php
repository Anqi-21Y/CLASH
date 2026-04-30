<?php
// enviar_respuesta.php

set_error_handler(function($errno, $errstr) {
    http_response_code(500);
    echo json_encode(['error' => $errstr]);
    exit;
});

require __DIR__ . '/../../config/conexion.php';
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['jugador_id'], $input['reto_id'], $input['opcion_elegida'], $input['tiempo_ms'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan datos obligatorios']);
    exit;
}

$jugador_id     = intval($input['jugador_id']);
$reto_id        = intval($input['reto_id']);
$opcion_elegida = intval($input['opcion_elegida']); // 0 = timeout, 1-4 = opción elegida
$tiempo_ms      = intval($input['tiempo_ms']);
$sesion_id      = intval($input['sesion_id'] ?? 0);

// Validación: permitimos 0 (timeout) y 1-4 (opciones válidas)
if ($opcion_elegida < 0 || $opcion_elegida > 4) {
    http_response_code(400);
    echo json_encode(['error' => 'Opción no válida']);
    exit;
}

// Evitar respuesta duplicada para el mismo jugador/reto/sesión
$yaRespondio = $db->querySingle("
    SELECT id FROM respuestas
    WHERE jugador_id = $jugador_id AND reto_id = $reto_id AND sesion_id = $sesion_id
");
if ($yaRespondio) {
    // Ya existe — devolvemos éxito sin duplicar
    http_response_code(200);
    echo json_encode(['success' => true, 'es_correcta' => 0, 'puntos' => 0, 'duplicado' => true]);
    $db->close();
    exit;
}

// Compruebo la respuesta correcta del reto

$stmt = $db->prepare("SELECT opcion_correcta FROM retos WHERE id = :reto_id");
$stmt->bindValue(':reto_id', $reto_id, SQLITE3_INTEGER);
$reto = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if (!$reto) {
    http_response_code(404);
    echo json_encode(['error' => 'Reto no encontrado']);
    exit;
}

// Calculo puntos
if ($opcion_elegida === 0) {
    // Timeout: no eligió nada
    $es_correcta    = 0;
    $puntos         = 0;
    $opcion_guardar = null; // guardaremos NULL en BD para no violar el CHECK(1,2,3,4)
} else {
    $es_correcta    = ($opcion_elegida == $reto['opcion_correcta']) ? 1 : 0;
    $opcion_guardar = $opcion_elegida;

    if ($es_correcta) {
        $segundos = $tiempo_ms / 1000;
        if ($segundos <= 3)     $puntos = PUNTOS_RAPIDO;
        elseif ($segundos <= 6) $puntos = PUNTOS_MEDIO;
        else                    $puntos = PUNTOS_LENTO;
    } else {
        $puntos = PUNTOS_FALLO;
    }
}

// Guardo la respuesta

if ($opcion_guardar === null) {
    $stmt = $db->prepare("
        INSERT INTO respuestas (jugador_id, reto_id, opcion_elegida, es_correcta, tiempo_ms, puntos, sesion_id)
        VALUES (:jugador_id, :reto_id, NULL, :es_correcta, :tiempo_ms, :puntos, :sesion_id)
    ");
} else {
    $stmt = $db->prepare("
        INSERT INTO respuestas (jugador_id, reto_id, opcion_elegida, es_correcta, tiempo_ms, puntos, sesion_id)
        VALUES (:jugador_id, :reto_id, :opcion_elegida, :es_correcta, :tiempo_ms, :puntos, :sesion_id)
    ");
    $stmt->bindValue(':opcion_elegida', $opcion_guardar, SQLITE3_INTEGER);
}

$stmt->bindValue(':jugador_id',  $jugador_id,  SQLITE3_INTEGER);
$stmt->bindValue(':reto_id',     $reto_id,     SQLITE3_INTEGER);
$stmt->bindValue(':es_correcta', $es_correcta, SQLITE3_INTEGER);
$stmt->bindValue(':tiempo_ms',   $tiempo_ms,   SQLITE3_INTEGER);
$stmt->bindValue(':puntos',      $puntos,      SQLITE3_INTEGER);
$stmt->bindValue(':sesion_id',   $sesion_id,   SQLITE3_INTEGER);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar la respuesta']);
    $db->close();
    exit;
}

// compruebo si todos los jugadores han respondido todas las preguntas
// si es así, termino la partida automáticamente
$total_jugadores = $db->querySingle("
    SELECT COUNT(*) FROM jugadores WHERE sesion_id = $sesion_id
");

$total_respuestas = $db->querySingle("
    SELECT COUNT(DISTINCT jugador_id) FROM respuestas
    WHERE sesion_id = $sesion_id
    AND jugador_id IN (
        SELECT id FROM jugadores WHERE sesion_id = $sesion_id
    )
    GROUP BY jugador_id HAVING COUNT(DISTINCT reto_id) >= " . NUM_PREGUNTAS . "
");

// si todos los jugadores han respondido NUM_PREGUNTAS preguntas → termino
$jugadores_terminados = $db->querySingle("
    SELECT COUNT(*) FROM (
        SELECT jugador_id FROM respuestas
        WHERE sesion_id = $sesion_id
        GROUP BY jugador_id
        HAVING COUNT(DISTINCT reto_id) >= " . NUM_PREGUNTAS . "
    )
");

$partida_terminada = false;
if ($jugadores_terminados >= $total_jugadores && $total_jugadores > 0) {
    $db->exec("UPDATE sesiones SET estado = 'terminada' WHERE id = $sesion_id AND estado = 'en_juego'");
    $partida_terminada = true;
}

$db->close();

http_response_code(201);
echo json_encode([
    'success'           => true,
    'es_correcta'       => $es_correcta,
    'puntos'            => $puntos,
    'partida_terminada' => $partida_terminada,
]);