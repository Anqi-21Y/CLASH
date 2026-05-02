<?php
// Obtener el siguiente reto disponible para un jugador especifico en su idioma

set_error_handler(function($errno, $errstr) {
    http_response_code(500);
    echo json_encode(['error' => $errstr]);
    exit;
});

require __DIR__ . '/../../config/conexion.php';
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$sesion_id  = isset($_GET['sesion_id'])  ? intval($_GET['sesion_id'])  : 0;
$jugador_id = isset($_GET['jugador_id']) ? intval($_GET['jugador_id']) : 0;

// Validar el idioma del jugador
$idioma_raw = isset($_GET['idioma']) ? trim($_GET['idioma']) : 'es';
$idioma = in_array($idioma_raw, ['es', 'ca', 'zh']) ? $idioma_raw : 'es';

if ($sesion_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'sesion_id inválido o no enviado']);
    exit;
}

// Encuentra la primera pregunta que el jugador aún no ha respondido en la categoria actual
if ($jugador_id > 0) {

    // obtengo la categoría de esta sesión
    $stmt = $db->prepare("SELECT categoria_id FROM sesiones WHERE id = :sesion_id");
    $stmt->bindValue(':sesion_id', $sesion_id, SQLITE3_INTEGER);
    $sesion = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if (!$sesion) {
        http_response_code(404);
        echo json_encode(['error' => 'Sesión no encontrada']);
        exit;
    }

    // Buscar el primer reto NO respondido por este jugador
    $stmt = $db->prepare("SELECT id FROM retos WHERE categoria_id = :cat AND id NOT IN ( SELECT reto_id FROM respuestas WHERE sesion_id  = :sid AND jugador_id = :jid )ORDER BY id ASC LIMIT 1");

    $stmt->bindValue(':cat', intval($sesion['categoria_id']), SQLITE3_INTEGER);
    $stmt->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);
    $stmt->bindValue(':jid', $jugador_id, SQLITE3_INTEGER);
    $row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    $reto_id = $row ? $row['id'] : null;

} else {
    // modo compatibilidad
    $stmt = $db->prepare("SELECT reto_actual FROM sesiones WHERE id = :sesion_id");
    $stmt->bindValue(':sesion_id', $sesion_id, SQLITE3_INTEGER);
    $row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    $reto_id = $row ? $row['reto_actual'] : null;
}

if (!$reto_id) {
    http_response_code(404);
    echo json_encode(['error' => 'No hay un reto activo en esta sesión']);
    exit;
}

// cargo los detalles del reto en el idioma del jugador
$stmt = $db->prepare("SELECT id, tipo, emojis, media_url, pregunta_{$idioma} AS pregunta,
        op1_{$idioma} AS opcion1,
        op2_{$idioma} AS opcion2,
        op3_{$idioma} AS opcion3, 
        op4_{$idioma} AS opcion4, 
        dificultad FROM retos WHERE id = :reto_id");

$stmt->bindValue(':reto_id', $reto_id, SQLITE3_INTEGER);
$reto = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if (!$reto) {
    http_response_code(404);
    echo json_encode(['error' => 'Reto no encontrado']);
    exit;
}

// barajo las opciones para que no siempre salga en el mismo orden
$opciones = [
    ['id' => 1, 'texto' => $reto['opcion1']],
    ['id' => 2, 'texto' => $reto['opcion2']],
    ['id' => 3, 'texto' => $reto['opcion3']],
    ['id' => 4, 'texto' => $reto['opcion4']],
];
shuffle($opciones);

// Enviar respuesta JSON
echo json_encode([
    'success' => true,
    'reto_id' => $reto['id'],
    'tipo' => $reto['tipo'],
    'emojis' => $reto['emojis'],
    'media_url' => $reto['media_url'],
    'pregunta' => $reto['pregunta'],
    'opciones' => $opciones,
    'dificultad' => $reto['dificultad'],
]);

$db->close();