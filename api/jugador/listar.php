<?php
require __DIR__ . '/../../config/conexion.php';

header('Content-Type: application/json');

$sesion_id = isset($_GET['sesion_id']) ? intval($_GET['sesion_id']) : 0;

if ($sesion_id <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = $db->prepare("SELECT nombre, avatar FROM jugadores WHERE sesion_id = :sid");
$stmt->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);

$res = $stmt->execute();

$jugadores = [];

while ($fila = $res->fetchArray(SQLITE3_ASSOC)) {
    $jugadores[] = $fila;
}

echo json_encode($jugadores);

$db->close();