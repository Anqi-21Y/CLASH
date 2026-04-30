// ranking.js — lógica de la pantalla de ranking final

const NUM_PREGUNTAS = window.NUM_PREGUNTAS || 4;
const SESION_ID     = new URLSearchParams(window.location.search).get('sesion_id')
    || localStorage.getItem('sesion_id');

let datosRanking = null;
const medallas   = ['🥇', '🥈', '🥉'];

cargarRanking();

async function cargarRanking() {
    try {
        const res = await fetch(`../api/ranking/obtener_ranking.php?sesion_id=${SESION_ID}`);
        if (!res.ok) throw new Error('error');

        datosRanking = await res.json();

        if (datosRanking.success && datosRanking.ranking.length > 0) {
            mostrarPodio(datosRanking.ranking);
            mostrarLista(datosRanking.ranking);

            document.getElementById('seccion-cargando').style.display  = 'none';
            document.getElementById('seccion-podio').style.display     = 'block';

            // Solo mostrar lista si hay jugadores fuera del podio
            if (datosRanking.ranking.length > 3) {
                document.getElementById('seccion-lista').style.display = 'block';
            }

            // Tras 15s mostrar ranking histórico global
            setTimeout(mostrarRankingGlobal, 15000);

        } else {
            document.querySelector('#seccion-cargando .ranking-cargando-txt').textContent =
                'No hay resultados disponibles';
        }

    } catch (e) {
        console.error(e);
    }
}

function mostrarPodio(ranking) {
    // Nombre del ganador en el título de felicitaciones
    if (ranking[0]) {
        document.getElementById('ganador-nombre').textContent = ranking[0].nombre;
    }

    // Rellenar puestos 1, 2 y 3 (el orden visual es 2-1-3)
    [1, 2, 3].forEach(pos => {
        const j = ranking[pos - 1];
        if (!j) return;
        document.getElementById(`avatar-${pos}`).src =
            `../assets/img/${j.avatar}.png`;
        document.getElementById(`nombre-${pos}`).textContent = j.nombre;
        document.getElementById(`puntos-${pos}`).textContent = j.puntos_total + ' pts';
    });
}

function mostrarLista(ranking) {
    // Solo jugadores fuera del podio (posición 4 en adelante)
    const resto = ranking.filter(j => j.posicion > 3);
    if (resto.length === 0) return;

    const lista = document.getElementById('lista-ranking');
    lista.innerHTML = '';

    resto.forEach(j => {
        const fila = document.createElement('div');
        fila.className = 'fila-ranking';
        fila.innerHTML = `
            <span class="ranking-posicion">${j.posicion}</span>
            <img src="../assets/img/${j.avatar}.png" class="ranking-avatar" alt="avatar">
            <span class="ranking-nombre">${j.nombre}</span>
            <span class="ranking-aciertos">${j.aciertos}/${NUM_PREGUNTAS}</span>
            <span class="ranking-puntos">${j.puntos_total} pts</span>
        `;
        lista.appendChild(fila);
    });
}

async function mostrarRankingGlobal() {
    if (!datosRanking) return;
    const CATEGORIA_ID = datosRanking.categoria_id ?? 1;

    try {
        const res = await fetch(
            `../api/ranking/obtener_ranking.php?categoria_id=${CATEGORIA_ID}`
        );
        if (!res.ok) throw new Error('error');
        const datos = await res.json();

        if (datos.success) {
            document.getElementById('seccion-podio').style.display = 'none';
            document.getElementById('seccion-lista').style.display = 'none';

            const seccionGlobal = document.getElementById('seccion-global');
            seccionGlobal.style.display = 'block';

            const lista = document.getElementById('lista-global');
            lista.innerHTML = '';

            datos.ranking.forEach((j, i) => {
                const fila = document.createElement('div');
                fila.className = 'fila-ranking';
                const pos = i < 3 ? medallas[i] : i + 1;
                fila.innerHTML = `
                    <span class="ranking-posicion">${pos}</span>
                    <img src="../assets/img/${j.avatar}.png" class="ranking-avatar" alt="avatar">
                    <span class="ranking-nombre">${j.nombre}</span>
                    <span class="ranking-puntos">${j.puntos_total} pts</span>
                `;
                lista.appendChild(fila);
            });
        }
    } catch (e) {
        console.error(e);
    }
}