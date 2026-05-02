<?php
// pagina para mostrar el QR de acceso al juego
// usa la sesion actual y muestra el pin

require '../config/config.php';
require '../config/conexion.php';

// Recogemos el sesion_id de la URL
$sesion_id = isset($_GET['sesion_id']) ? intval($_GET['sesion_id']) : 0;

if ($sesion_id === 0) {
    $res = $db->query("SELECT id FROM sesiones WHERE estado = 'esperando' LIMIT 1");
    $fila = $res->fetchArray(SQLITE3_ASSOC);
    if ($fila) {
        $sesion_id = $fila['id'];
    }
}

// CAMBIO: ahora buscamos también el PIN real de esta sesion
$stmt = $db->prepare("SELECT pin FROM sesiones WHERE id = :id");
$stmt->bindValue(':id', $sesion_id, SQLITE3_INTEGER);
$res2 = $stmt->execute();
$fila2 = $res2->fetchArray(SQLITE3_ASSOC);
$pin = $fila2['pin'] ?? 'N/A';

$db->close();

if ($sesion_id === 0) {
    die("Error: No hay ninguna sesión en espera. Por favor, crea una partida en el Admin primero.");
}

// nombre de ngrok
$PUBLIC_URL = "https://knelt-ramrod-clapped.ngrok-free.dev";

// base URL
$base_url = $PUBLIC_URL . "/CLASH";

// final URL
$url_registro = $base_url . "/pages/inicio.php?pin=" . urlencode($pin);

// Usamos una API externa gratuita (Google Charts o QRServer) para generar el QR rápidamente
$qr_api_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url_registro);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Juego - QR</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <style>
        /* Estilos específicos para que el QR se vea increíble en el proyector */
        .qr-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            display: inline-block;
        }
        .qr-frame {
            background-color: #f4f4f4;
            padding: 40px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .instrucciones {
            margin-top: 2rem;
            font-size: 2.5rem;
            color: #333;
            font-weight: bold;
        }
        .pin-destacado {
            font-size: 4rem;
            color: #e74c3c;
            margin: 1rem 0;
        }
    </style>
</head>
<body style="margin:0; overflow:hidden;">

<div class="qr-frame">

    <div class="qr-container">
        <img src="<?php echo $qr_api_url; ?>" alt="QR" style="width: 350px; height: 350px;">
    </div>

    <div class="instrucciones">
        <p>Escanea para unirte</p>
        <div class="pin-destacado">PIN: <?php echo htmlspecialchars($pin); ?></div>
    </div>

</div>

</body>
</html>