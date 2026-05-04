<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../config/idiomas.php';
?>
<!-- incluimos la configuracion, conexion y el diccionario de idiomas actualizado -->
<!DOCTYPE html>
<html lang="<?= $idioma_actual ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/CLASH/assets/img/favicon.png" type="image/png">
    <title>Clash — <?= $t['inicio_unirse'] ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=DM+Sans:wght@400;500;700;800&family=Unbounded:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/juego.css">
    <link rel="stylesheet" href="../assets/css/inicio.css">
</head>
<body class="juego-body">

    <header class="juego-header">
        <a href="/CLASH/index.php" class="header-logo">CLASH</a>
        <span class="header-sub"><?= $t['elige_perfil'] ?></span>
    </header>

    <main class="juego-main">

        <section class="registro-seccion" id="seccion-nombre">
            <h2><?= $t['tu_nombre'] ?></h2>
            <input type="text" id="input-nombre" maxlength="20" placeholder="<?= $t['placeholder_nombre'] ?>"/>
        </section>

        <section class="registro-seccion" id="seccion-avatar">
            <h2><?= $t['elige_avatar'] ?></h2>
            <div class="avatares-grid">
                <!-- generamos los 28 avatares mediante un bucle -->
                <?php for ($i = 1; $i <= 28; $i++): ?>
                <button class="btn-avatar <?= $i === 1 ? 'activo' : '' ?>" data-avatar="avatar<?= $i ?>">
                    <img src="../assets/img/avatar<?= $i ?>.png" alt="Avatar <?= $i ?>">
                </button>
                <?php endfor; ?>
            </div>
        </section>

        <section class="registro-seccion" id="seccion-pin-manual">
            <h2><?= $t['codigo_pin'] ?></h2>
            <input type="text" id="input-pin-manual" placeholder="<?= $t['placeholder_pin'] ?>">
        </section>

        <section id="seccion-entrar">
            <button id="btn-entrar"><?= $t['btn_entrar'] ?></button>
            <p id="mensaje-error" class="oculto"></p>
            <p id="mensaje-espera" class="oculto"><?= $t['mensaje_espera'] ?></p>
        </section>

    </main>

    <!-- cargamos el script de logica para el registro de jugadores -->
    <script src="../assets/js/registro.js"></script>
</body>
</html>