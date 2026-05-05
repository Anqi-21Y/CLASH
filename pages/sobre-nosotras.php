<?php
$titulo     = $t['sobre_nosotras'];
$css_pagina = ['sobre.css', 'footer.css'];
include '../includes/header.php';
?>

<main class="sobre-main">

    <!-- HERO -->
    <section class="sobre-hero">
        <h1 class="sobre-titulo" id="titulo-color"><?= $t['conocenos'] ?></h1>
        <p class="sobre-sub"><?= $t['sobre_sub'] ?></p>
    </section>

    <!-- ANQI -->
    <div class="sobre-persona sobre-persona-anqi">
        <div class="sobre-persona-info">
            <span class="sobre-label"><?= $t['anqi_label'] ?></span>
            <span class="sobre-rol"><?= $t['anqi_rol'] ?></span>
            <h3 class="sobre-nombre">Anqi Yang</h3>
            <p><?= $t['anqi_bio'] ?></p>
        </div>
        <div class="sobre-persona-foto">
            <img class="sobre-img-rotativa" id="img-anqi"
                 src="/CLASH/assets/img/anqi1.png"
                 data-imgs='["/CLASH/assets/img/anqi1.png","/CLASH/assets/img/anqi2.png","/CLASH/assets/img/anqi3.png"]'
                 alt="Anqi Yang">
        </div>
    </div>

    <!-- ONDA SEPARADORA -->
    <div class="sobre-onda">
        <svg viewBox="0 0 1440 200" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,80 C120,160 240,0 360,80 C480,160 600,0 720,80 C840,160 960,0 1080,80 C1200,160 1320,0 1440,80 L1440,200 L0,200 Z" fill=" #ff9022"/>
        </svg>
    </div>

    <!-- ANA -->
    <div class="sobre-persona sobre-persona-ana">
        <div class="sobre-persona-foto">
            <img class="sobre-img-rotativa" id="img-ana"
                 src="/CLASH/assets/img/ana1.png"
                 data-imgs='["/CLASH/assets/img/ana1.png","/CLASH/assets/img/ana2.png","/CLASH/assets/img/ana3.png"]'
                 alt="Jhoana Martínez">
        </div>
        <div class="sobre-persona-info">
            <span class="sobre-rol"><?= $t['ana_rol'] ?></span>
            <h3 class="sobre-nombre">Jhoana Martínez</h3>
            <p><?= $t['ana_bio'] ?></p>
        </div>
    </div>

    <!-- LA IDEA -->
    <section class="sobre-idea">
        <span class="sobre-label"><?= $t['idea_label'] ?></span>
        <h2 class="sobre-h2"><?= $t['idea_h2'] ?></h2>
        <p><?= $t['idea_p1'] ?></p>
        <p><?= $t['idea_p2'] ?></p>
        <a href="/CLASH/index.php" class="sobre-btn"><?= $t['ver_proyecto'] ?></a>
    </section>

</main>

<script>
// titulo con cambio de color suave
var colores = ['#eafa71', '#ff9022', '#ff70f3', '#DAFFEF', '#1f6f5b'];
var indiceColor = 0;
var titulo = document.getElementById('titulo-color');
setInterval(function() {
    indiceColor = (indiceColor + 1) % colores.length;
    titulo.style.color = colores[indiceColor];
}, 2000);

// imagenes rotativas con fade 
document.querySelectorAll('.sobre-img-rotativa').forEach(function(img) {
    var imgs = JSON.parse(img.getAttribute('data-imgs'));
    var indice = 0;
    setInterval(function() {
        indice = (indice + 1) % imgs.length;
        img.style.opacity = '0';
        setTimeout(function() {
            img.src = imgs[indice];
            img.style.opacity = '1';
        }, 400);
    }, 2000);
});
</script>

<?php include '../includes/footer.php'; ?>