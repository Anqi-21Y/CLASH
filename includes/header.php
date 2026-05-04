<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../config/idiomas.php';

// buscamos la sesion activa esperando o en_juego mas reciente
$res  = $db->query("SELECT pin FROM sesiones WHERE estado != 'terminada' ORDER BY id DESC LIMIT 1");
$fila = $res->fetchArray(SQLITE3_ASSOC);
$pin  = $fila['pin'] ?? null;
$db->close();

// obtener nombre de cloudflare
$PUBLIC_URL = "https://knelt-ramrod-clapped.ngrok-free.dev";

// base URL
$base_url = $PUBLIC_URL . "/CLASH";

// si hay sesion activa el QR lleva directo a inicio.php?pin=XXXX
$qr_url = $pin
    ? $base_url . "/pages/inicio.php?pin=" . urlencode($pin)
    : $base_url . "/pages/inicio.php";
?>
<!-- inicio de la estructura html -->
<!DOCTYPE html>
<html lang="<?= $idioma_actual ?>">
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

<!-- marquesina superior y boton menu -->
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

<!-- barra de navegacion principal -->
<nav id="nav-principal">
    <ul class="nav-links">
        <li><a href="/CLASH/index.php#inicio"><?= $t['inicio'] ?></a></li>
        <li><a href="/CLASH/index.php#como-jugar"><?= $t['como_jugar_tit'] ?></a></li>
        <li><a href="/CLASH/index.php#categorias"><?= $t['cat_tit'] ?></a></li>
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
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/></svg>
            </a>
        </div>

        <div class="nav-idiomas">
            <!-- selectores de idioma -->
            <a href="?lang=ca" class="nav-lang-btn <?= $idioma_actual == 'ca' ? 'activo' : '' ?>">CA</a>
            <a href="?lang=es" class="nav-lang-btn <?= $idioma_actual == 'es' ? 'activo' : '' ?>">ES</a>
            <a href="?lang=zh" class="nav-lang-btn <?= $idioma_actual == 'zh' ? 'activo' : '' ?>">ZH</a>
        </div>
    </div>
</nav>

<!-- overlay del menu hamburguesa -->
<div class="menu-overlay" id="menuOverlay">
    <button class="menu-cerrar" onclick="toggleMenu()">✕</button>
    <div class="menu-izquierda">
        <a href="/CLASH/index.php#inicio"     class="menu-link" onclick="toggleMenu()"><?= $t['inicio'] ?></a>
        <a href="/CLASH/index.php#como-jugar" class="menu-link" onclick="toggleMenu()"><?= $t['como_jugar_tit'] ?></a>
        <a href="/CLASH/index.php#categorias" class="menu-link" onclick="toggleMenu()"><?= $t['cat_tit'] ?></a>
        <a href="/CLASH/pages/sobre-nosotras.php" class="menu-link" onclick="toggleMenu()"><?= $t['sobre_nosotras'] ?></a>
        <a href="/CLASH/pages/inicio.php" class="btn-menu" onclick="toggleMenu()"><?= $t['jugar_ahora'] ?> →</a>
        <div class="menu-marca">CLASH</div>
    </div>
    <div class="menu-derecha"></div>
</div>

<!-- overlay del codigo qr -->
<div class="qr-overlay" id="qr-overlay">
    <div class="qr-overlay-contenido">
        <button class="qr-overlay-cerrar" id="qr-overlay-cerrar">✕</button>
        <p class="qr-overlay-msg"><?= $t['escanea_unete'] ?></p>
        <div class="qr-overlay-codigo" id="qr-overlay-codigo"></div>
        <?php if ($pin): ?>
            <p style="font-size:1.4rem;font-weight:bold;margin-top:1rem;">PIN: <?= htmlspecialchars($pin) ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- widget del qr para el index -->
<?php if (basename($_SERVER['PHP_SELF']) === 'index.php'): ?>
<div class="qr-widget" id="qr-widget-btn">
    <div class="qr-widget-inner">
        <div class="qr-widget-burbuja"><?= $t['unete_burbuja'] ?></div>
        <img class="qr-widget-img" src="/CLASH/assets/img/qr2.png" alt="QR">
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

    document.getElementById('qr-widget-btn')?.addEventListener('click', function () {
        document.getElementById('qr-overlay').classList.add('abierto');
        document.body.classList.add('sin-scroll');
    });

    document.getElementById('qr-overlay-cerrar').addEventListener('click', function () {
        document.getElementById('qr-overlay').classList.remove('abierto');
        document.body.classList.remove('sin-scroll');
    });

    function toggleMenu() {
        document.getElementById('nav-principal').classList.toggle('menu-abierto');
        document.getElementById('menuOverlay').classList.toggle('abierto');
    }

    const idiomaGuardado = '<?= $idioma_actual ?>';
    localStorage.setItem('idioma_clash', idiomaGuardado);
</script>
</body>
</html>