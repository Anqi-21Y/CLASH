<?php
require '../config/config.php';
require '../config/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clash — Unirse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=DM+Sans:wght@400;500;700;800&family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/juego.css">
    <link rel="stylesheet" href="../assets/css/inicio.css">
</head>
<body class="juego-body">

    <header class="juego-header">
        <a href="/inner-work/clash/index.php" class="header-logo">CLASH</a>
        <span class="header-sub">Elige tu perfil</span>
    </header>

    <main class="juego-main">

        <section class="registro-seccion" id="seccion-nombre">
            <h2>Tu nombre</h2>
            <input type="text" id="input-nombre" maxlength="20" placeholder="Como quieres que te llamen..."/>
        </section>

        <section class="registro-seccion" id="seccion-avatar">
            <h2>Elige tu avatar</h2>
            <div class="avatares-grid">
                <?php for ($i = 1; $i <= 28; $i++): ?>
                <button class="btn-avatar <?= $i === 1 ? 'activo' : '' ?>" data-avatar="avatar<?= $i ?>">
                    <img src="../assets/img/avatar<?= $i ?>.png" alt="Avatar <?= $i ?>">
                </button>
                <?php endfor; ?>
            </div>
        </section>

        <section class="registro-seccion" id="seccion-pin-manual">
            <h2>Código del PIN</h2>
            <input type="text" id="input-pin-manual" placeholder="Escribe el PIN aquí">
        </section>

        <section id="seccion-entrar">
            <button id="btn-entrar">Entrar al juego</button>
            <p id="mensaje-error" class="oculto"></p>
            <p id="mensaje-espera" class="oculto">Registrado. Espera a que empiece la partida...</p>
        </section>

    </main>

    <script src="../assets/js/registro.js"></script>
</body>
</html>