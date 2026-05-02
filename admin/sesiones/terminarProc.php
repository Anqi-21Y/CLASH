<?php
session_start();

// Finalizar la partida, calcular el ranking y guardar los resultados

if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';

$id = intval($_GET['id'] ?? 0);

// marco la sesión como terminada
$stmt = $db->prepare("UPDATE sesiones SET estado = 'terminada' WHERE id = :id AND estado = 'en_juego'");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->execute();

// guardo el podio final en la tabla de resultados
$res = $db->query("SELECT jugador_id, COUNT(*) as aciertos, SUM(puntos) as total_puntos FROM respuestas WHERE sesion_id = $id AND es_correcta = 1 GROUP BY jugador_id ORDER BY total_puntos DESC, aciertos DESC");

// Guardar el ranking final en la tabla de resultados
$posicion = 1;
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $ins = $db->prepare("INSERT INTO resultados (sesion_id, jugador_id, puntos_total, aciertos, posicion) VALUES (:sid, :jid, :pts, :act, :pos)");

    $ins->bindValue(':sid', $id, SQLITE3_INTEGER);
    $ins->bindValue(':jid', $row['jugador_id'], SQLITE3_INTEGER);
    $ins->bindValue(':pts', $row['total_puntos'], SQLITE3_INTEGER);
    $ins->bindValue(':act', $row['aciertos'],   SQLITE3_INTEGER);
    $ins->bindValue(':pos', $posicion, SQLITE3_INTEGER);
    $ins->execute();
    $posicion++;
}

$db->close();
header("Location: /CLASH/admin/sesiones/vista.php?id=$id");
exit;