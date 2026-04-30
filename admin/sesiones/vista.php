<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /inner-work/clash/admin/login.php');
    exit;
}
require_once __DIR__ . '/../config/conexion_admin.php';

$id = intval($_GET['id'] ?? 0);

$stmt = $db->prepare("
    SELECT s.*, c.nombre_es AS categoria
    FROM sesiones s
    JOIN categorias c ON c.id = s.categoria_id
    WHERE s.id = :id
");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$sesion = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if (!$sesion) {
    header('Location: /inner-work/clash/admin/sesiones/gestion.php');
    exit;
}

$resultados = [];
if ($sesion['estado'] === 'terminada') {
    $res = $db->query("
        SELECT j.id as jugador_id, j.nombre, j.avatar,
               SUM(r.puntos) as puntos_total,
               SUM(CASE WHEN r.es_correcta = 1 THEN 1 ELSE 0 END) as aciertos
        FROM respuestas r
        JOIN jugadores j ON j.id = r.jugador_id
        WHERE r.sesion_id = $id
        GROUP BY r.jugador_id
        ORDER BY puntos_total DESC
        LIMIT 10
    ");
    $pos = 1;
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $row['posicion'] = $pos++;
        $resultados[] = $row;
    }
}

$titulo_admin = 'Partida ' . $sesion['pin'];
require_once __DIR__ . '/../includes/header_admin.php';
?>

<div class="admin-topbar">
    <div>
        <div class="vista-cat"><?= htmlspecialchars($sesion['categoria']) ?></div>
        <div class="admin-actions-row vista-estado-row">
            <span class="badge badge-<?= $sesion['estado'] ?>"><?= $sesion['estado'] ?></span>
            <?php if ($sesion['estado'] !== 'terminada'): ?>
            <span class="badge vista-badge-jug"><span id="total-jugadores">0</span> jugadores</span>
            <?php endif; ?>
        </div>
    </div>
    <a href="/inner-work/clash/admin/sesiones/gestion.php" class="btn-admin btn-admin-ghost">← Volver</a>
</div>

<?php if ($sesion['estado'] !== 'terminada'): ?>

<!-- PIN -->
<div class="pin-box">
    <p class="pin-box-label">Comparte este PIN</p>
    <div class="admin-pin-display"><?= $sesion['pin'] ?></div>
</div>

<!-- jugadores en sala -->
<div class="vista-jugadores-wrap">
    <span class="vista-jugadores-label">En sala</span>
    <div id="vista-avatares" class="vista-avatares-grid"></div>
</div>

<!-- botón acción -->
<div class="vista-accion">
    <?php if ($sesion['estado'] === 'esperando'): ?>
        <a href="/inner-work/clash/admin/sesiones/iniciarProc.php?id=<?= $id ?>"
           class="btn-admin btn-admin-green"
           onclick="if(!confirm('¿Iniciar la partida ahora?')) return false; clearInterval(window._poller); return true;">▶ Iniciar partida</a>
    <?php elseif ($sesion['estado'] === 'en_juego'): ?>
        <a href="/inner-work/clash/admin/sesiones/terminarProc.php?id=<?= $id ?>"
           class="btn-admin btn-admin-orange"
           onclick="return confirm('¿Terminar la partida?')">⏹ Terminar partida</a>
    <?php endif; ?>
</div>

<script>
function actualizarJugadores(jugadores) {
    document.getElementById('total-jugadores').textContent = jugadores.length;
    const grid = document.getElementById('vista-avatares');
    grid.innerHTML = '';
    jugadores.forEach(j => {
        const div = document.createElement('div');
        div.className = 'vista-jugador-card';
        div.innerHTML = `
            <img src="/inner-work/clash/assets/img/${j.avatar}.png" class="vista-jugador-avatar" alt="avatar">
            <span class="vista-jugador-nombre">${j.nombre}</span>
        `;
        grid.appendChild(div);
    });
}

window._poller = setInterval(async () => {
    try {
        const res = await fetch(`/inner-work/clash/api/session/jugadores.php?sesion_id=<?= $id ?>`);
        const data = await res.json();
        if (data.success) actualizarJugadores(data.jugadores);
    } catch(e) {}
}, 2000);

// carga inicial
fetch(`/inner-work/clash/api/session/jugadores.php?sesion_id=<?= $id ?>`)
    .then(r => r.json())
    .then(data => { if (data.success) actualizarJugadores(data.jugadores); });
</script>

<?php endif; ?>

<?php if ($sesion['estado'] === 'terminada' && $resultados): ?>
<p class="admin-section-sub">Podio final</p>
<div class="admin-podio">
    <?php
    $orden = [1, 0, 2]; // 2º, 1º, 3º
    foreach ($orden as $i):
        if (!isset(array_slice($resultados, 0, 3)[$i])) continue;
        $r = array_slice($resultados, 0, 3)[$i];
        $pos = $r['posicion'];
        $clase = $pos === 1 ? 'primero' : ($pos === 2 ? 'segundo' : 'tercero');
    ?>
    <div class="admin-puesto">
        <img src="/inner-work/clash/assets/img/<?= htmlspecialchars($r['avatar']) ?>.png" class="admin-puesto-avatar" alt="avatar">
        <div class="admin-puesto-nombre"><?= htmlspecialchars($r['nombre']) ?></div>
        <div class="admin-puesto-puntos"><?= $r['puntos_total'] ?> pts</div>
        <div class="admin-barra <?= $clase ?>"><span class="admin-barra-num"><?= $pos ?></span></div>
    </div>
    <?php endforeach; ?>
</div>
<?php if (count($resultados) > 3): ?>
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead><tr><th>#</th><th>Jugador</th><th>Puntos</th><th>Aciertos</th></tr></thead>
        <tbody>
            <?php foreach ($resultados as $r): ?>
            <tr>
                <td><?= $r['posicion'] ?>º</td>
                <td><img src="/inner-work/clash/assets/img/<?= htmlspecialchars($r['avatar']) ?>.png" class="tabla-avatar-img" alt="avatar"> <?= htmlspecialchars($r['nombre']) ?></td>
                <td><?= $r['puntos_total'] ?></td>
                <td><?= $r['aciertos'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer_admin.php'; ?>