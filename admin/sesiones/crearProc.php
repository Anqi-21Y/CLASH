<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';

$pin          = trim($_POST['pin']          ?? '');
$categoria_id = intval($_POST['categoria_id'] ?? 0);
$admin_id     = intval($_SESSION['admin_id']);

// desactivo foreign keys para evitar bloqueos en el insert
$db->exec("PRAGMA foreign_keys = OFF");

$stmt = $db->prepare("
    INSERT INTO sesiones (pin, categoria_id, admin_id, estado)
    VALUES (:pin, :cat, :admin, 'esperando')
");
$stmt->bindValue(':pin',   $pin,          SQLITE3_TEXT);
$stmt->bindValue(':cat',   $categoria_id, SQLITE3_INTEGER);
$stmt->bindValue(':admin', $admin_id,     SQLITE3_INTEGER);

$ok = $stmt->execute();

if (!$ok) {
    die("Error al crear partida: " . $db->lastErrorMsg());
}

$sesion_id = $db->lastInsertRowID();

if ($sesion_id <= 0) {
    die("Error: sesion_id inválido después del INSERT. Error DB: " . $db->lastErrorMsg());
}

$db->exec("PRAGMA foreign_keys = ON");
$db->close();

header("Location: /CLASH/admin/sesiones/vista.php?id=$sesion_id");
exit;