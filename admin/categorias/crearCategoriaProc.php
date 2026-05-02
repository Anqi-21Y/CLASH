<?php
session_start();

// Backend: Guardar la nueva categoria en la BBDD

// asegurar  ser admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}

// conectar a bbdd
require_once __DIR__ . '/../config/conexion_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_es = trim($_POST['nombre_es'] ?? '');

    if ($nombre_es) {
        // deasacticar foregin key temporalmente
        $db->exec("PRAGMA foreign_keys = OFF");

        // prepara la consulta para insertar la categoria
        $stmt = $db->prepare("INSERT INTO categorias (nombre_es, nombre_ca, nombre_zh, icono) VALUES (:es, :ca, :zh, '')");

        $stmt->bindValue(':es', $nombre_es, SQLITE3_TEXT);
        $stmt->bindValue(':ca', $nombre_es, SQLITE3_TEXT);
        $stmt->bindValue(':zh', $nombre_es, SQLITE3_TEXT);
        $stmt->execute();

        // obtener id para nueva categoria
        $nueva_id = $db->lastInsertRowID();
        $db->exec("PRAGMA foreign_keys = ON");
        $db->close();

        // redirige a crear reto con la nueva categoría preseleccionada
        header("Location: /CLASH/admin/retos/crearReto.php?cat=$nueva_id");
        exit;
    }
}
// si algo fallo, vlover a la gestion de retos
$db->close();
header('Location: /CLASH/admin/retos/gestionRetos.php');
exit;