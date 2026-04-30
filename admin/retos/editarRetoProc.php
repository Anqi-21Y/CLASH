<?php
session_start();
if (!isset($_SESSION['admin_id'])) { exit; }
require_once __DIR__ . '/../config/conexion_admin.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$id    = intval($input['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false]);
    exit;
}

$stmt = $db->prepare("
    UPDATE retos SET
        emojis          = :emojis,
        op1_es = :o1, op1_ca = :o1, op1_zh = :o1,
        op2_es = :o2, op2_ca = :o2, op2_zh = :o2,
        op3_es = :o3, op3_ca = :o3, op3_zh = :o3,
        op4_es = :o4, op4_ca = :o4, op4_zh = :o4,
        opcion_correcta = :correcta
    WHERE id = :id
");
$stmt->bindValue(':emojis',   $input['emojis'],          SQLITE3_TEXT);
$stmt->bindValue(':o1',       $input['op1_es'],          SQLITE3_TEXT);
$stmt->bindValue(':o2',       $input['op2_es'],          SQLITE3_TEXT);
$stmt->bindValue(':o3',       $input['op3_es'],          SQLITE3_TEXT);
$stmt->bindValue(':o4',       $input['op4_es'],          SQLITE3_TEXT);
$stmt->bindValue(':correcta', $input['opcion_correcta'], SQLITE3_INTEGER);
$stmt->bindValue(':id',       $id,                       SQLITE3_INTEGER);
$stmt->execute();

$db->close();
echo json_encode(['success' => true]);