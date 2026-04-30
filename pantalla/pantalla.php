<?php
/**
 * ARCHIVO: pages/pantalla.php
 * ACCIÓN: Pantalla principal de proyección (público).
 * FUNCIONALIDAD: Muestra los retos, el PIN de acceso y controla el ritmo visual del juego.
 */

require '../config/config.php';
require '../config/conexion.php';

// Obtenemos el ID de la sesión por URL (ej: pantalla.php?sesion_id=1)
$sesion_id = isset($_GET['sesion_id']) ? intval($_GET['sesion_id']) : 0;

if ($sesion_id === 0) {
    $res = $db->query("SELECT id FROM sesiones WHERE estado != 'terminada' ORDER BY id DESC LIMIT 1");
    $fila = $res->fetchArray(SQLITE3_ASSOC);
    
    if ($fila) {
        $sesion_id = $fila['id'];
    }
}

$db->close();

if ($sesion_id === 0) {
    die("Error: ID de sesión no especificado para la pantalla principal.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clash - Pantalla Principal</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/pantalla.css"> </head>
<body class="pantalla-body">

    <header class="pantalla-header">
        <div class="logo">⚡ Clash</div>
        <div class="pin-info">
            <span>Únete en: <strong>clash.test</strong></span>
            <span class="pin-code">N: <?php echo $sesion_id; ?></span>
        </div>
    </header>

    <main id="pantalla-main">
        
        <section id="pantalla-lobby">
            <div class="lobby-layout">
                <div class="qr-zone">
                    <iframe src="qr_display.php?sesion_id=<?php echo $sesion_id; ?>" frameborder="0" id="qr-iframe"></iframe>
                </div>
                
                <div class="players-zone">
                    <h1>¡Escanea para jugar!</h1>
                    <div id="contador-jugadores">0 jugadores unidos</div>
                    <div id="lista-jugadores-nombres">
                        </div>
                </div>
            </div>
        </section>

        <section id="pantalla-reto" class="oculto">
            <div id="pantalla-temporizador">
                <svg>
                    <circle r="50" cx="60" cy="60"></circle>
                </svg>
                <span id="segundos">0</span>
            </div>

            <h2 id="pantalla-pregunta-texto">Cargando reto...</h2>

            <div id="pantalla-contenido-multimedia">
                </div>
            
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
        // Pasamos variables de PHP a JS
        const SESION_ID = <?php echo $sesion_id; ?>;
        const TIEMPO_PREGUNTA = <?php echo TIEMPO_PREGUNTA; ?>;
        
    </script>
    
    <script src="../assets/js/pantalla.js"></script>

</body>
</html>