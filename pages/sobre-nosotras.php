<?php
$titulo     = 'Sobre nosotras';
$css_pagina = ['sobre.css', 'footer.css'];
include '../includes/header.php';
?>

<main class="sobre-main">
    <!-- HERO -->
    <section class="sobre-hero">
        <h1 class="sobre-titulo" id="titulo-color">CONÓCENOS</h1>
        <p class="sobre-sub">Nunca habíamos jugado a ningún juego. Y aun así decidimos hacer uno como proyecto final de carrera.</p>
    </section>

    <!-- ANQI -->
    <div class="sobre-persona sobre-persona-anqi">
        <div class="sobre-persona-info">
            <span class="sobre-label">El equipo</span>
            <span class="sobre-rol">Backend · Lógica del juego</span>
            <h3 class="sobre-nombre">Anqi Yang</h3>
            <p>Soy estudiante de DAW y me especializo en desarrollo web. Nací en Zhejiang, China, y ahora vivo en España — una experiencia que me ha dado la oportunidad de conocer culturas distintas y crecer tanto personal como profesionalmente.
            En este proyecto fui responsable del backend — diseñé la base de datos, desarrollé las APIs y me aseguré de que toda la lógica del juego funcionara correctamente. 
            También realicé pruebas para garantizar que el sistema fuera estable y fluido para los jugadores.
            Trabajo con Java, PHP, JavaScript, Python, HTML, CSS, MySQL y SQLite. Me gusta trabajar con orden y resolver problemas con cabeza fría. Fuera del código, me encanta hacer postres y los animales me apasionan </p>
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
            <span class="sobre-rol">Frontend · Diseño · Backend</span>
            <h3 class="sobre-nombre">Jhoana Martínez</h3>
            <p>Estudiante de DAW, y este proyecto ha sido la experiencia más intensa y más bonita de mi carrera hasta ahora. 
                Cuando empezamos, no tenía ni idea de todo lo que iba a aprender — y eso es exactamente lo que más valoro. 
                Empecé tocando backend, fui descubriendo el frontend, y acabé dándome cuenta de que lo que más me apasiona es que las cosas se vean bien, se sientan bien y cuenten algo.
                He aprendido a trabajar en equipo de verdad, a resolver problemas que no sabía ni que existían, y a no rendirme cuando algo no funciona a la primera. 
                Clash nació de cero, con muchísimas horas, muchísimas dudas y muchísimas ganas. 
                Y ahora que estas aquí, solo espero que lo disfrutes tanto como yo disfruté construyéndolo. Aún me queda mucho por aprender, y no puedo esperar a hacerlo.
            </p>
        </div>
    </div>

    <!-- LA IDEA -->
    <section class="sobre-idea">
        <span class="sobre-label">La idea</span>
        <h2 class="sobre-h2">¿CÓMO NACIÓ NUESTRO PROYECTO?</h2>
        <p>Clash nació como proyecto final del Grado Superior de Desarrollo de Aplicaciones Web. 
            La idea de hacer un juego nos pareció un reto desde el primer momento — y eso fue exactamente lo que buscábamos. 
            Nunca habíamos construido nada así, pero lo afrontamos con muchas ganas y mucho trabajo en equipo.</p>
        <p>Ha sido un proceso de aprendizaje real, de los que te cambian. Backend, Frontend, Diseño, Lógica de juego... todo desde cero. 
            Y ahora que está terminado, solo nos queda una cosa por decir: esperamos que lo disfruten tanto como nosotras disfrutamos haciéndolo.</p>
        <a href="/CLASH/index.php" class="sobre-btn">Ver el proyecto →</a>
    </section>

</main>

<script>
// ── Título con cambio de color suave ─────────────────────────────
var colores = ['#eafa71', '#ff9022', '#ff70f3', '#DAFFEF', '#1f6f5b'];
var indiceColor = 0;
var titulo = document.getElementById('titulo-color');
setInterval(function() {
    indiceColor = (indiceColor + 1) % colores.length;
    titulo.style.color = colores[indiceColor];
}, 2000);

// ── Imágenes rotativas con fade ──────────────────────────────────
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