<?php
session_start();
// Backend : eliminar una categoria y todos sus retos y respuesta

// SOLO ADMIN 
if (!isset($_SESSION['admin_id'])) {
    header('Location: /CLASH/admin/login.php');
    exit;
}

// BBDD
require_once __DIR__ . '/../config/conexion_admin.php';

// obtener el id de la categoria desde URL
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // desactivar foreginKey para borrar en cascada manualmente
    $db->exec("PRAGMA foreign_keys = OFF");

    // elimino respuestas de los retos de esta categoria
    $db->exec("DELETE FROM respuestas WHERE reto_id IN (SELECT id FROM retos WHERE categoria_id = $id)");
   
    // elimino los retos
    $db->exec("DELETE FROM retos WHERE categoria_id = $id");
   
    // elimino la categoria
    $db->exec("DELETE FROM categorias WHERE id = $id");

    $db->exec("PRAGMA foreign_keys = ON");
}

$db->close();

// volver a la gestion
header('Location: /CLASH/admin/retos/gestionRetos.php');
exit;