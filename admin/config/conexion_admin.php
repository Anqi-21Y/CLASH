<?php
// BBDD configurar y crear las tablas

ini_set('display_errors', 1);
error_reporting(E_ALL);
// reutilizo la conexion que ya existe
require_once __DIR__ . '/../../config/conexion.php';

// activo foreign keys
$db->exec("PRAGMA foreign_keys = ON");

// tabla admins — nueva
$db->exec("CREATE TABLE IF NOT EXISTS admins ( id INTEGER PRIMARY KEY AUTOINCREMENT, usuario TEXT NOT NULL UNIQUE, email TEXT NOT NULL UNIQUE, password_hash TEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)");

// tabla sesiones — nueva
$db->exec("CREATE TABLE IF NOT EXISTS sesiones ( id INTEGER PRIMARY KEY AUTOINCREMENT, pin TEXT NOT NULL UNIQUE, categoria_id INTEGER NOT NULL, estado TEXT NOT NULL DEFAULT 'esperando' CHECK(estado IN ('esperando','en_juego','terminada')),
        reto_actual INTEGER NOT NULL DEFAULT 0, inicio_timestamp DATETIME, admin_id INTEGER NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (categoria_id) REFERENCES categorias(id), FOREIGN KEY (admin_id) REFERENCES admins(id))");

// añado sesion_id a jugadores si no existe
$cols = $db->query("PRAGMA table_info(jugadores)");
$tieneSesion = false;

while ($col = $cols->fetchArray(SQLITE3_ASSOC)) {
    if ($col['name'] === 'sesion_id') { $tieneSesion = true; break; }
}

if (!$tieneSesion) {
    $db->exec("ALTER TABLE jugadores ADD COLUMN sesion_id INTEGER REFERENCES sesiones(id)");
}

// añado sesion_id a respuestas si no existe
$cols = $db->query("PRAGMA table_info(respuestas)");
$tieneSesion = false;

while ($col = $cols->fetchArray(SQLITE3_ASSOC)) {
    if ($col['name'] === 'sesion_id') { $tieneSesion = true; break; }
}

if (!$tieneSesion) {
    $db->exec("ALTER TABLE respuestas ADD COLUMN sesion_id INTEGER REFERENCES sesiones(id)");
}

// añado sesion_id a resultados si no existe
$cols = $db->query("PRAGMA table_info(resultados)");
$tieneSesion = false;

while ($col = $cols->fetchArray(SQLITE3_ASSOC)) {
    if ($col['name'] === 'sesion_id') { $tieneSesion = true; break; }
}

if (!$tieneSesion) {
    $db->exec("ALTER TABLE resultados ADD COLUMN sesion_id INTEGER REFERENCES sesiones(id)");
}

// admin por defecto si la tabla esta vacia
// usuario: admin  |  contraseña: clash2026
$check = $db->query("SELECT COUNT(*) as total FROM admins");
$row   = $check->fetchArray(SQLITE3_ASSOC);

if ($row['total'] == 0) {
    $hash = password_hash('clash2026', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO admins (usuario, email, password_hash) VALUES ('admin', 'admin@clash.com', :hash)");

    $stmt->bindValue(':hash', $hash, SQLITE3_TEXT);
    $stmt->execute();
}

// Si el usuario no ha iniciado sesión y no se encuentra en la pagina de inicio de sesion, redirijalo directamente (intercepción de seguridad).
$paginaActual = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['admin_id']) && $paginaActual != 'login.php' && $paginaActual != 'loginProc.php') {
    header('Location: /CLASH/admin/login.php');
    exit();
}