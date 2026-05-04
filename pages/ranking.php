<?php
// incluimos la configuracion y el diccionario de idiomas
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/idiomas.php';
?>
<!DOCTYPE html>
<html lang="<?= $idioma_actual ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/CLASH/assets/img/favicon.png" type="image/png">
    <title>Clash — <?= $t['ranking_tit'] ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/juego.css">
    <link rel="stylesheet" href="../assets/css/ranking.css">
</head>
<body class="juego-body juego-game">

    <!-- nav principal del ranking -->
    <header class="juego-header">
        <h1>CLASH</h1>
        <span class="ranking-header-sub"><?= $t['ranking_final'] ?></span>
    </header>

    <!-- pantalla de carga mientras se procesan puntos -->
    <div class="ranking-marco" id="seccion-cargando">
        <p class="ranking-cargando-txt"><?= $t['calculando_res'] ?></p>
    </div>

    <!-- cuadro top 3 para el podio visual -->
    <div class="ranking-marco" id="seccion-podio" style="display:none;">

        <div class="ranking-felicitaciones">
            <p class="ranking-felicitaciones-sub"><?= $t['felicitaciones'] ?>!</p>
            <p class="ranking-felicitaciones-nombre" id="ganador-nombre"></p>
        </div>

        <div id="podio">
            <!-- segundo puesto -->
            <div class="podio-puesto" id="puesto-2">
                <img class="podio-avatar" id="avatar-2" src="" alt="avatar">
                <div class="podio-nombre" id="nombre-2"></div>
                <div class="podio-barra segundo">
                    <span class="podio-barra-num">2</span>
                    <span class="podio-barra-pts" id="puntos-2"></span>
                </div>
            </div>
            <!-- primer puesto -->
            <div class="podio-puesto" id="puesto-1">
                <img class="podio-avatar podio-avatar-1" id="avatar-1" src="" alt="avatar">
                <div class="podio-nombre" id="nombre-1"></div>
                <div class="podio-barra primero">
                    <span class="podio-barra-num">1</span>
                    <span class="podio-barra-pts" id="puntos-1"></span>
                </div>
            </div>
            <!-- tercer puesto -->
            <div class="podio-puesto" id="puesto-3">
                <img class="podio-avatar" id="avatar-3" src="" alt="avatar">
                <div class="podio-nombre" id="nombre-3"></div>
                <div class="podio-barra tercero">
                    <span class="podio-barra-num">3</span>
                    <span class="podio-barra-pts" id="puntos-3"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- cuadro lista resto (4 en adelante) -->
    <div class="ranking-marco ranking-marco-lista" id="seccion-lista" style="display:none;">
        <p class="ranking-lista-label"><?= $t['clasificacion_completa'] ?></p>
        <div id="lista-ranking"></div>
    </div>

    <!-- ranking global tras espera de seguridad -->
    <div class="ranking-marco" id="seccion-global" style="display:none;">
        <div class="ranking-global-header">
            <p class="ranking-lista-label"><?= $t['ranking_historico'] ?></p>
        </div>
        <div id="lista-global"></div>
        <button onclick="window.location.href='inicio.php'" class="btn-volver"><?= $t['volver_inicio'] ?></button>
    </div>

    <!-- pasamos constantes de php a javascript -->
    <script>
        window.NUM_PREGUNTAS = <?= NUM_PREGUNTAS ?>;
        window.SESION_ID = new URLSearchParams(window.location.search).get('sesion_id')
            || localStorage.getItem('sesion_id');
    </script>
    <script src="../assets/js/ranking.js"></script>

</body>
</html>