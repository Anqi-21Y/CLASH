<?php
// incluimos la configuracion y el diccionario de idiomas
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/idiomas.php';

$titulo    = $t['inicio']; // usa la traduccion del diccionario
$css_pagina = ['hero.css', 'como-jugar.css', 'categorias.css', 'footer.css'];
$js_pagina  = ['landing.js'];

include 'includes/header.php';
?>

<!-- panel 1 — hero -->
<div class="panel hero-panel" id="inicio">
    <div class="hero-contenido">
        <span class="hero-badge"><?= $t['hero_badge'] ?></span>
        <h1 class="hero-titulo">CLASH</h1>
        <h2 class="hero-sub"><?= $t['hero_subtitulo'] ?></h2>
        <div class="hero-botones">
            <a href="/CLASH/pages/inicio.php" class="btn-principal"><?= $t['jugar_ahora'] ?></a>
        </div>
        <div class="hero-live">
            <span class="dot-live"></span>
            <span id="jugadores-live">0</span> <?= $t['jugadores_reg'] ?>
        </div>
    </div>
</div>

<!-- panel 2 — como jugar -->
<div class="panel" id="como-jugar">
    <div class="panel-fill" id="cj-panel">

        <!-- cabecera: titulo verde limon + subtitulo -->
        <div class="cj-header">
            <h2 class="cj-titulo-panel"><?= $t['como_jugar_tit'] ?></h2>
            <p class="cj-subtitulo-panel"><?= $t['como_jugar_sub'] ?></p>
        </div>

        <!-- card: flechas dentro a izquierda y derecha -->
        <div class="cj-card" id="cj-card">
            <div class="cj-bg-base" id="cj-bg-base"></div>
            <div class="cj-bg-wipe" id="cj-bg-wipe"></div>

            <button class="cj-flecha" id="cj-prev" onclick="cambiarPaso(-1)" aria-label="Paso anterior">&#8592;</button>

            <div class="cj-contenido" id="cj-contenido">
                <p class="cj-num" id="cj-num">01</p>
                <h3 class="cj-titulo" id="cj-titulo"><?= $t['paso1_tit'] ?></h3>
                <p class="cj-texto" id="cj-texto"><?= $t['paso1_txt'] ?></p>
            </div>

            <button class="cj-flecha" id="cj-next" onclick="cambiarPaso(1)" aria-label="Siguiente paso">&#8594;</button>
        </div>

        <!-- dots indicadores -->
        <div class="cj-dots" id="cj-dots">
            <span class="cj-dot activo" onclick="irPaso(0)"></span>
            <span class="cj-dot" onclick="irPaso(1)"></span>
            <span class="cj-dot" onclick="irPaso(2)"></span>
            <span class="cj-dot" onclick="irPaso(3)"></span>
        </div>

    </div>
</div>
<div class="spacer"></div>

<!-- panel 3 — categorias -->
<div class="panel" id="categorias">
    <div class="panel-fill" style="background: #ff9022; ">

        <div class="cat-header">
            <h2 class="cj-titulo-panel"><?= $t['cat_tit'] ?></h2>
            <p class="cj-subtitulo-panel"><?= $t['cat_sub'] ?></p>
        </div>

        <div class="categorias-slider">
            <div class="categorias-grid" id="cat-track">
                <div class="categoria-card">
                    <img class="categoria-img" src="/CLASH/assets/img/cat1.png" alt="Peliculas">
                    <div class="categoria-overlay">
                        <h2><?= $t['cat1_tit'] ?></h2>
                        <p><?= $t['cat1_txt'] ?></p>
                    </div>
                </div>
                <div class="categoria-card">
                    <img class="categoria-img" src="/CLASH/assets/img/cat2.png" alt="Canciones">
                    <div class="categoria-overlay">
                        <h2><?= $t['cat2_tit'] ?></h2>
                        <p><?= $t['cat2_txt'] ?></p>
                    </div>
                </div>
                <div class="categoria-card">
                    <img class="categoria-img" src="/CLASH/assets/img/cat3.png" alt="Famosos">
                    <div class="categoria-overlay">
                        <h2><?= $t['cat3_tit'] ?></h2>
                        <p><?= $t['cat3_txt'] ?></p>
                    </div>
                </div>
                <div class="categoria-card">
                    <img class="categoria-img" src="/CLASH/assets/img/cat4.png" alt="Modo sorpresa">
                    <div class="categoria-overlay">
                        <h2><?= $t['cat4_tit'] ?></h2>
                        <p><?= $t['cat4_txt'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="cat-flechas">
            <button class="cat-flecha" id="cat-prev" onclick="catSlide(-1)" aria-label="Anterior">&#8592;</button>
            <button class="cat-flecha" id="cat-next" onclick="catSlide(1)"  aria-label="Siguiente">&#8594;</button>
        </div>

    </div>
</div>
<div class="spacer"></div>

<?php include 'includes/footer.php'; ?>