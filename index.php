<?php
$titulo    = 'Inicio';
$css_pagina = ['hero.css', 'como-jugar.css', 'categorias.css', 'footer.css'];
$js_pagina  = ['landing.js'];
include 'includes/header.php';
?>



<!-- panel 1 — hero -->
<div class="panel hero-panel" id="inicio">
    <div class="hero-contenido">
        <span class="hero-badge">Demuestra lo que sabes. Menos de 30 segundos.</span>
        <h1 class="hero-titulo">CLASH</h1>
        <h2 class="hero-sub">Reconoce películas, canciones y famosos. Compite en tiempo real. Que gane el mejor.</h2>
        <div class="hero-botones">
            <a href="/CLASH/pages/inicio.php" class="btn-principal">Jugar ahora</a>
        </div>
        <div class="hero-live">
            <span class="dot-live"></span>
            <span id="jugadores-live">0</span>Jugadores registrados
        </div>
    </div>
</div>


<!-- panel 2 — como jugar -->
<div class="panel" id="como-jugar">
    <div class="panel-fill" id="cj-panel">

        <!-- cabecera: título verde limón + subtítulo -->
        <div class="cj-header">
            <h2 class="cj-titulo-panel">ASÍ SE JUEGA</h2>
            <p class="cj-subtitulo-panel">Cuatro pasos simples para que en 30 segundos ya estés compitiendo contra todos.</p>
        </div>

        <!-- card: flechas dentro, a izquierda y derecha -->
        <div class="cj-card" id="cj-card">
            <div class="cj-bg-base" id="cj-bg-base"></div>
            <div class="cj-bg-wipe" id="cj-bg-wipe"></div>

            <button class="cj-flecha" id="cj-prev" onclick="cambiarPaso(-1)" aria-label="Paso anterior">&#8592;</button>

            <div class="cj-contenido" id="cj-contenido">
                <p class="cj-num" id="cj-num">01</p>
                <h3 class="cj-titulo" id="cj-titulo">Escanea y regístrate</h3>
                <p class="cj-texto" id="cj-texto">Escanea el QR con tu móvil, escribe tu nombre, elige tu avatar y escribe el PIN que el presentador mostrará en pantalla.</p>
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
            <h2 class="cj-titulo-panel">AQUÍ EMPIEZA TODO</h2>
            <p class="cj-subtitulo-panel">Películas, canciones, famosos y sorpresas. Demuestra en cuál eres imbatible.</p>
        </div>

        <div class="categorias-slider">
            <div class="categorias-grid" id="cat-track">
                <div class="categoria-card">
                    <img class="categoria-img" src="/CLASH/assets/img/cat1.png" alt="Peliculas">
                    <div class="categoria-overlay">
                        <h2>Películas</h2>
                        <p>Adivina la peli sin una sola letra.</p>
                    </div>
                </div>
                <div class="categoria-card">
                    <img class="categoria-img" src="/CLASH/assets/img/cat2.png" alt="Canciones">
                    <div class="categoria-overlay">
                        <h2>Canciones</h2>
                        <p>Adivina la canción sin escucharla.</p>
                    </div>
                </div>
                <div class="categoria-card">
                    <img class="categoria-img" src="/CLASH/assets/img/cat3.png" alt="Famosos">
                    <div class="categoria-overlay">
                        <h2>Famosos</h2>
                        <p>Adivina quién es sin que te digan su nombre.</p>
                    </div>
                </div>
                <div class="categoria-card">
                    <img class="categoria-img" src="/CLASH/assets/img/cat4.png" alt="Modo sorpresa">
                    <div class="categoria-overlay">
                        <h2>Modo sorpresa</h2>
                        <p>Audio, vídeo, imágenes y más. Sin saber qué viene.</p>
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