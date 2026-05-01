<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';

$id = intval($_GET['id'] ?? 0);

// 1. Recuperar el ID de categoría para esta sesión
$sesionInfo = $db->query("SELECT categoria_id FROM sesiones WHERE id = $id")->fetchArray(SQLITE3_ASSOC);
$cat_id = $sesionInfo['categoria_id'];

// 2. Cambio principal: Seleccionar aleatoriamente 1 ID de pregunta de esta categoría
// Usando la función RANDOM() de SQLite
$primeraPregunta = $db->querySingle("
    SELECT id FROM retos 
    WHERE categoria_id = $cat_id 
    ORDER BY RANDOM() 
    LIMIT 1
");

// 3. Registra la hora actual como punto de inicio de la cuenta regresiva.
$ahora = date('Y-m-d H:i:s');

// 4. update
$stmt = $db->prepare("
    UPDATE sesiones 
    SET estado = 'en_juego', 
        reto_actual = :reto, 
        inicio_timestamp = :ahora 
    WHERE id = :id AND estado = 'esperando'
");
$stmt->bindValue(':reto', $primeraPregunta, SQLITE3_INTEGER);
$stmt->bindValue(':ahora', $ahora, SQLITE3_TEXT);
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->execute();

$db->close();

header("Location: /CLASH/admin/sesiones/vista.php?id=$id");
exit;