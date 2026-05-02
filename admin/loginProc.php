<?php
session_start();

// procesamiendo de login

require_once __DIR__ . '/config/conexion_admin.php';

$usuario  = trim($_POST['usuario']  ?? '');
$password = trim($_POST['password'] ?? '');

$stmt = $db->prepare("SELECT * FROM admins WHERE usuario = :usuario");
$stmt->bindValue(':usuario', $usuario, SQLITE3_TEXT);
$admin = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if ($admin && password_verify($password, $admin['password_hash'])) {
    $_SESSION['admin_id']      = $admin['id'];
    $_SESSION['admin_usuario'] = $admin['usuario'];
    $db->close();
    header('Location: /CLASH/admin/dashboard.php');
    exit;
} else {
    $db->close();
    header('Location: /CLASH/admin/login.php?error=1');
    exit;
}