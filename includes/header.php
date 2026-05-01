<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/conexion.php';

// Buscar la sesión activa (esperando o en_juego) más reciente
$res  = $db->query("SELECT pin FROM sesiones WHERE estado != 'terminada' ORDER BY id DESC LIMIT 1");
$fila = $res->fetchArray(SQLITE3_ASSOC);
$pin  = $fila['pin'] ?? null;
$db->close();

// obtener nombre de cloudflare
$PUBLIC_URL = "https://knelt-ramrod-clapped.ngrok-free.dev";

// base URL
$base_url = $PUBLIC_URL . "/CLASH";

// Si hay sesión activa, el QR lleva directo a inicio.php?pin=XXXX
// Si no hay ninguna, el QR lleva a inicio.php sin pin (comportamiento anterior)
$qr_url = $pin
    ? $base_url . "/pages/inicio.php?pin=" . urlencode($pin)
    : $base_url . "/pages/inicio.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? htmlspecialchars($titulo) . ' — Clash' : 'Clash — El juego de emojis' ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="icon" href="/CLASH/assets/img/favicon.png" type="image/png">
    <link rel="stylesheet" href="/CLASH/assets/css/main.css">
    <link rel="stylesheet" href="/CLASH/assets/css/topbar.css">
    <link rel="stylesheet" href="/CLASH/assets/css/nav.css">

    <?php if (isset($css_pagina)): ?>
        <?php foreach ((array)$css_pagina as $css): ?>
            <link rel="stylesheet" href="/CLASH/assets/css/<?= htmlspecialchars($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body>

<div class="top-bar" id="top-bar">
    <div class="top-bar-izquierda">
        <div class="top-bar-track" id="top-bar-track"></div>
    </div>
    <button class="top-bar-hamburger" onclick="toggleMenu()" aria-label="menu">
        <span></span>
        <span></span>
        <span></span>
    </button>
</div>

<nav id="nav-principal">
    <ul class="nav-links">
        <li><a href="/CLASH/index.php#inicio">Inicio</a></li>
        <li><a href="/CLASH/index.php#como-jugar">Como jugar</a></li>
        <li><a href="/CLASH/index.php#categorias">Categorias</a></li>
    </ul>

    <a href="/CLASH/index.php" class="nav-logo">CLASH</a>

    <div class="nav-right">
        <div class="nav-iconos">

            <svg class="nav-icono" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                <line x1="9" y1="9" x2="9.01" y2="9"/>
                <line x1="15" y1="9" x2="15.01" y2="9"/>
            </svg>

            <a href="#" class="nav-icono" aria-label="TikTok">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/>
                </svg>
            </a>

            <a href="#" class="nav-icono" aria-label="Pinterest">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 0 1 .083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/>
                </svg>
            </a>

        </div>

        <div class="nav-idiomas">
            <button class="nav-lang-btn" data-idioma="ca" onclick="cambiarIdioma('ca')">CA</button>
            <button class="nav-lang-btn" data-idioma="es" onclick="cambiarIdioma('es')">ES</button>
            <button class="nav-lang-btn" data-idioma="zh" onclick="cambiarIdioma('zh')">ZH</button>
        </div>
    </div>
</nav>

<div class="menu-overlay" id="menuOverlay">
    <button class="menu-cerrar" onclick="toggleMenu()">✕</button>
    <div class="menu-izquierda">
        <a href="/CLASH/index.php#inicio"     class="menu-link" onclick="toggleMenu()">Inicio</a>
        <a href="/CLASH/index.php#como-jugar" class="menu-link" onclick="toggleMenu()">Como jugar</a>
        <a href="/CLASH/index.php#categorias" class="menu-link" onclick="toggleMenu()">Categorias</a>
        <a href="/CLASH/pages/sobre-nosotras.php" class="menu-link" onclick="toggleMenu()">Sobre nosotras</a>
        <a href="/CLASH/pages/inicio.php" class="btn-menu" onclick="toggleMenu()">Jugar ahora →</a>
        <div class="menu-marca">CLASH</div>
    </div>
    <div class="menu-derecha"></div>
</div>

<!-- overlay QR -->
<div class="qr-overlay" id="qr-overlay">
    <div class="qr-overlay-contenido">
        <button class="qr-overlay-cerrar" id="qr-overlay-cerrar">✕</button>
        <p class="qr-overlay-msg">Escanea y únete</p>
        <div class="qr-overlay-codigo" id="qr-overlay-codigo"></div>
        <?php if ($pin): ?>
            <p style="font-size:1.4rem;font-weight:bold;margin-top:1rem;">PIN: <?= htmlspecialchars($pin) ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- widget esquina -->
<?php if (basename($_SERVER['PHP_SELF']) === 'index.php'): ?>
<div class="qr-widget" id="qr-widget-btn">
    <div class="qr-widget-inner">
        <div class="qr-widget-burbuja">¿Te unes al juego?<br>¡Haz click!</div>
        <img class="qr-widget-img" src="/CLASH/assets/img/qr2.png" alt="Únete al juego">
    </div>
</div>
<?php endif; ?>

<script>
    const QR_URL = '<?= $qr_url ?>';

    new QRCode(document.getElementById('qr-overlay-codigo'), {
        text: QR_URL,
        width: 240,
        height: 240,
        colorDark: '#1A1A1A',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });

    document.getElementById('qr-widget-btn').addEventListener('click', function () {
        document.getElementById('qr-overlay').classList.add('abierto');
        document.body.classList.add('sin-scroll');
    });

    document.getElementById('qr-overlay-cerrar').addEventListener('click', function () {
        document.getElementById('qr-overlay').classList.remove('abierto');
        document.body.classList.remove('sin-scroll');
    });

    document.getElementById('qr-overlay').addEventListener('click', function (e) {
        if (e.target === this) {
            this.classList.remove('abierto');
            document.body.classList.remove('sin-scroll');
        }
    });

    function cambiarIdioma(idioma) {
        localStorage.setItem('idioma_clash', idioma);
        document.querySelectorAll('.nav-lang-btn').forEach(btn => {
            btn.classList.toggle('activo', btn.dataset.idioma === idioma);
        });
    }

    const idiomaGuardado = localStorage.getItem('idioma_clash') || 'es';
    document.querySelectorAll('.nav-lang-btn').forEach(btn => {
        btn.classList.toggle('activo', btn.dataset.idioma === idiomaGuardado);
    });
</script>