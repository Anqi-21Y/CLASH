<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_es = trim($_POST['nombre_es'] ?? '');

    if ($nombre_es) {
        $db->exec("PRAGMA foreign_keys = OFF");

        $stmt = $db->prepare("
            INSERT INTO categorias (nombre_es, nombre_ca, nombre_zh, icono)
            VALUES (:es, :ca, :zh, '')
        ");
        $stmt->bindValue(':es', $nombre_es, SQLITE3_TEXT);
        $stmt->bindValue(':ca', $nombre_es, SQLITE3_TEXT);
        $stmt->bindValue(':zh', $nombre_es, SQLITE3_TEXT);
        $stmt->execute();

        $nueva_id = $db->lastInsertRowID();
        $db->exec("PRAGMA foreign_keys = ON");
        $db->close();

        // redirige a crear reto con la nueva categoría preseleccionada
        header("Location: /CLASH/admin/retos/crearReto.php?cat=$nueva_id");
        exit;
    }
}

$db->close();
header('Location: /CLASH/admin/retos/gestionRetos.php');
exit;