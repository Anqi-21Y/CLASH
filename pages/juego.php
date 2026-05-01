<?php
session_start();
require '../config/config.php';

$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 4;
if ($categoria_id < 1 || $categoria_id > 4) $categoria_id = 4;

$categoria_nombres = [
    1 => 'Películas',
    2 => 'Canciones',
    3 => 'Famosos',
    4 => 'Modo Sorpresa',
];
$categoria_nombre = $categoria_nombres[$categoria_id] ?? 'Clash';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/inner-work/clash/assets/img/favicon.png" type="image/png">
    <title>Clash - Juego</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/juego.css">
</head>
<!-- juego-game da el fondo #9FA0FF solo a esta página, no afecta inicio.php -->
<body class="juego-body juego-game">

    <!-- NAV — full width, fuera del marco -->
    <header class="juego-header">
        <h1>CLASH</h1>
        <div class="jugador-info">
            <span id="jugador-avatar"></span>
            <span id="jugador-nombre"></span>
        </div>
    </header>

    <!-- CATEGORÍA — full width, sin fondo -->
    <div class="juego-categoria-nombre"><?= htmlspecialchars($categoria_nombre) ?></div>

    <!-- MARCO MÓVIL — centrado, no full width -->
    <div class="juego-marco">
        <main class="juego-main">

            <!-- ESPERA INICIAL -->
            <section id="seccion-espera">
                <h2>Esperando la siguiente pregunta...</h2>
                <p>¡Prepárate!</p>
            </section>

            <!-- PREGUNTA -->
            <section id="seccion-pregunta" class="oculto">

                <!-- barra progreso + pregunta X de Y + timer -->
                <div class="juego-progreso">
                    <div class="progreso-barra-wrap">
                        <div class="progreso-barra-fill" id="progreso-fill"></div>
                    </div>
                    <div class="progreso-info">
                        <span class="progreso-label">Pregunta <span id="numero-pregunta">1</span> de <?= NUM_PREGUNTAS ?></span>
                        <span class="progreso-timer">⏱ <span id="tiempo-restante"><?= TIEMPO_PREGUNTA ?></span>s</span>
                    </div>
                </div>

                <!-- tipo emoji — cuadro blanco con hint + emojis -->
                <div id="bloque-emoji" class="oculto">
                    <div id="emojis">
                        <p class="juego-hint" id="hint-texto"></p>
                        <p id="texto-emojis"></p>
                    </div>
                </div>

                <!-- tipo imagen -->
                <div id="bloque-imagen" class="oculto">
                    <div id="contenedor-imagen">
                        <img id="imagen-reto" src="" alt="imagen del reto">
                    </div>
                </div>

                <!-- tipo video -->
                <div id="bloque-video" class="oculto">
                    <div id="contenedor-video">
                        <video id="video-reto" controls autoplay muted loop>
                            <source id="video-source" src="" type="video/mp4">
                        </video>
                    </div>
                </div>

                <!-- tipo audio -->
                <div id="bloque-audio" class="oculto">
                    <div id="contenedor-audio">
                        <p id="audio-label">Escucha y adivina</p>
                        <audio id="audio-reto" controls autoplay>
                            <source id="audio-source" src="" type="audio/mp3">
                        </audio>
                    </div>
                </div>

                <!-- tipo codigo -->
                <div id="bloque-codigo" class="oculto">
                    <div id="contenedor-codigo">
                        <pre id="texto-codigo"></pre>
                    </div>
                </div>

                <!-- pregunta de texto -->
                <div id="bloque-pregunta" class="oculto">
                    <p id="texto-pregunta"></p>
                </div>

                <!-- opciones A B C D -->
                <div id="opciones">
                    <button class="btn-opcion" data-opcion="1" id="opcion-1">
                        <span class="btn-opcion-letra">A</span>
                        <span class="btn-opcion-texto" id="texto-opcion-1"></span>
                    </button>
                    <button class="btn-opcion" data-opcion="2" id="opcion-2">
                        <span class="btn-opcion-letra">B</span>
                        <span class="btn-opcion-texto" id="texto-opcion-2"></span>
                    </button>
                    <button class="btn-opcion" data-opcion="3" id="opcion-3">
                        <span class="btn-opcion-letra">C</span>
                        <span class="btn-opcion-texto" id="texto-opcion-3"></span>
                    </button>
                    <button class="btn-opcion" data-opcion="4" id="opcion-4">
                        <span class="btn-opcion-letra">D</span>
                        <span class="btn-opcion-texto" id="texto-opcion-4"></span>
                    </button>
                </div>

            </section>

            <!-- RESULTADO -->
            <section id="seccion-resultado" class="oculto">
                <div id="resultado-icono"></div>
                <h2 id="resultado-texto"></h2>
                <p id="puntos-ganados"></p>
                <p id="puntos-acumulados"></p>
                <p id="posicion-actual"></p>
                <p id="resultado-espera">Esperando la siguiente pregunta...</p>
            </section>

            <!-- FIN -->
            <section id="seccion-fin" class="oculto">
                <h2>¡Partida terminada!</h2>
                <p id="fin-espera" style="font-size:11px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:rgba(26,26,26,0.55);">Cargando ranking...</p>
            </section>

        </main>
    </div>

    <script>
        const NUM_PREGUNTAS   = <?= NUM_PREGUNTAS ?>;
        const TIEMPO_PREGUNTA = <?= TIEMPO_PREGUNTA ?>;
        const CATEGORIA_ID    = <?= $categoria_id ?>;
        const SESION_ID       = localStorage.getItem('sesion_id');
    </script>
    <script src="../assets/js/timer.js"></script>
    <script src="../assets/js/juego.js"></script>

</body>
</html>