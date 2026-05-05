<?php
session_start();
// bck: guardar el nuevo reto en la base de datos

// solo admin
if (!isset($_SESSION['admin_id'])) { exit; }
require_once __DIR__ . '/../config/conexion_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preparar la consulta SQL para insertar el reto con todos sus campos
    $stmt = $db->prepare("INSERT INTO retos (
    categoria_id, tipo, emojis,
    op1_es, op1_ca, op1_zh,
    op2_es, op2_ca, op2_zh,
    op3_es, op3_ca, op3_zh,
    op4_es, op4_ca, op4_zh,
    opcion_correcta, dificultad
        ) VALUES (
    :cat, :tipo, :emoj,
    :o1es, :o1ca, :o1zh,
    :o2es, :o2ca, :o2zh,
    :o3es, :o3ca, :o3zh,
    :o4es, :o4ca, :o4zh,
    :corr, :dif)");

    // Vincular los valores del formulario a la consulta
    $stmt->bindValue(':cat',  $_POST['categoria_id'],SQLITE3_INTEGER);
    $stmt->bindValue(':tipo', $_POST['tipo'],SQLITE3_TEXT);
    $stmt->bindValue(':emoj', $_POST['emojis'],SQLITE3_TEXT);

    // Opciones en varios idiomas (Español, Catalán, Chino)
    $stmt->bindValue(':o1es', $_POST['op1_es'],SQLITE3_TEXT);
    $stmt->bindValue(':o1ca', $_POST['op1_ca'],SQLITE3_TEXT);
    $stmt->bindValue(':o1zh', $_POST['op1_zh'],SQLITE3_TEXT);

    $stmt->bindValue(':o2es', $_POST['op2_es'],SQLITE3_TEXT);
    $stmt->bindValue(':o2ca', $_POST['op2_ca'],SQLITE3_TEXT);
    $stmt->bindValue(':o2zh', $_POST['op2_zh'],SQLITE3_TEXT);

    $stmt->bindValue(':o3es', $_POST['op3_es'],SQLITE3_TEXT);
    $stmt->bindValue(':o3ca', $_POST['op3_ca'],SQLITE3_TEXT);
    $stmt->bindValue(':o3zh', $_POST['op3_zh'],SQLITE3_TEXT);

    $stmt->bindValue(':o4es', $_POST['op4_es'],SQLITE3_TEXT);
    $stmt->bindValue(':o4ca', $_POST['op4_ca'],SQLITE3_TEXT);
    $stmt->bindValue(':o4zh', $_POST['op4_zh'],SQLITE3_TEXT);

    $stmt->bindValue(':corr', $_POST['opcion_correcta'],  SQLITE3_INTEGER);
    $stmt->bindValue(':dif',  $_POST['dificultad'],       SQLITE3_TEXT);

    $stmt->execute();
}

header('Location: gestionRetos.php');
exit;