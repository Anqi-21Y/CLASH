<?php
require '../config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/inner-work/clash/assets/img/favicon.png" type="image/png">
    <title>Clash - Sala de Espera</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@900&family=DM+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/juego.css">
</head>
<body class="waiting-body">

    <header class="juego-header">
        <h1>CLASH</h1>
    </header>

    <main class="waiting-main">

        <p class="waiting-label">Sala de espera</p>

        <div id="waiting-avatares" class="waiting-avatares-grid"></div>

        <p class="waiting-sub">El admin dará el inicio cuando todos estén listos</p>

    </main>

    <script>
        const sesionId      = localStorage.getItem('sesion_id');
        const jugadorAvatar = localStorage.getItem('jugador_avatar');
        const jugadorNombre = localStorage.getItem('jugador_nombre');

        function actualizarJugadores(jugadores) {
            const grid = document.getElementById('waiting-avatares');
            grid.innerHTML = '';
            jugadores.forEach(j => {
                const div = document.createElement('div');
                div.className = 'waiting-jugador-card';
                div.innerHTML = `
                    <img src="../assets/img/${j.avatar}.png" class="waiting-jugador-avatar" alt="avatar">
                    <span class="waiting-jugador-nombre">${j.nombre}</span>
                `;
                grid.appendChild(div);
            });
        }

        const radar = setInterval(async () => {
            try {
                const [resEstado, resJugadores] = await Promise.all([
                    fetch(`../api/session/estado.php?sesion_id=${sesionId}`),
                    fetch(`../api/session/jugadores.php?sesion_id=${sesionId}`)
                ]);

                const estado    = await resEstado.json();
                const jugadores = await resJugadores.json();

                if (jugadores.success) actualizarJugadores(jugadores.jugadores);

                if (estado.estado === 'en_juego') {
                    clearInterval(radar);
                    window.location.href = 'juego.php';
                }
            } catch (error) {
                console.error("Error al sincronizar:", error);
            }
        }, 2000);

        fetch(`../api/session/jugadores.php?sesion_id=${sesionId}`)
            .then(r => r.json())
            .then(data => { if (data.success) actualizarJugadores(data.jugadores); });
    </script>

</body>
</html>