<?php
require '../config/config.php';
require '../config/conexion.php';

$sesion_id = isset($_GET['sesion_id']) ? intval($_GET['sesion_id']) : 0;

if ($sesion_id === 0) {
    $res = $db->query("SELECT id, pin FROM sesiones WHERE estado != 'terminada' ORDER BY id DESC LIMIT 1");
    $fila = $res->fetchArray(SQLITE3_ASSOC);
    if ($fila) { $sesion_id = $fila['id']; $pin = $fila['pin']; }
} else {
    $res = $db->query("SELECT pin FROM sesiones WHERE id = $sesion_id LIMIT 1");
    $fila = $res->fetchArray(SQLITE3_ASSOC);
    $pin = $fila['pin'] ?? '';
}

$db->close();
if ($sesion_id === 0) die("Error: No hay ninguna sesión activa.");
$PUBLIC_URL = "https://knelt-ramrod-clapped.ngrok-free.dev";
$base_url = $PUBLIC_URL . "/CLASH";

$qr_url = $base_url . "/pages/inicio.php?pin=" . urlencode($pin);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/CLASH/assets/img/favicon.png" type="image/png">
    <title>Clash — Pantalla Principal</title>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/pantalla.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body class="pantalla-body">

    <header class="pantalla-header">
        <div class="logo">CLASH</div>
    </header>

    <main id="pantalla-main">

        <section id="pantalla-lobby">
            <div class="lobby-layout">

                <!-- LADO IZQUIERDO: QR -->
                <div class="qr-zone">
                    <span class="qr-label">Escanea y únete</span>
                    <div class="qr-box" id="qr-codigo"></div>
                    <span class="qr-pin">PIN: <?php echo htmlspecialchars($pin); ?></span>
                </div>

                <!-- LADO DERECHO: Jugadores -->
                <div class="players-zone">
                    <p class="players-sala-label">Sala de espera</p>
                    <div id="contador-jugadores">0 jugadores unidos</div>
                    <div id="lista-jugadores-nombres"></div>
                    <p class="players-espera-label">El admin dará el inicio cuando todos estén listos</p>
                </div>

            </div>
        </section>

        <section id="pantalla-reto" class="oculto">
            <div id="pantalla-temporizador">
                <svg><circle r="50" cx="60" cy="60"></circle></svg>
                <span id="segundos">0</span>
            </div>
            <h2 id="pantalla-pregunta-texto">Cargando reto...</h2>
            <div id="pantalla-contenido-multimedia"></div>
            <div id="pantalla-contador-respuestas">
                <span id="num-respuestas">0</span> respuestas recibidas
            </div>
        </section>

        <section id="pantalla-intermedia" class="oculto">
            <h2>¡Tiempo terminado!</h2>
            <p>Mira tu móvil para ver tu posición.</p>
        </section>

    </main>

    <script>
        const SESION_ID       = <?php echo $sesion_id; ?>;
        const TIEMPO_PREGUNTA = <?php echo TIEMPO_PREGUNTA; ?>;

        new QRCode(document.getElementById('qr-codigo'), {
            text: '<?php echo $qr_url; ?>',
            width: 440,
            height: 440,
            colorDark: '#1A1A1A',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
    <script src="../assets/js/pantalla.js"></script>

</body>
</html>