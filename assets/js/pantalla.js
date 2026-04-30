/**
 * ARCHIVO: assets/js/pantalla.js
 * ACCIÓN: Controlador lógico de la pantalla de proyección.
 * FUNCIONALIDAD: Sincronización en tiempo real mediante polling (Radar).
 */

let currentRetoId = 0;
let estadoJuego   = 'esperando';
let timerInterval = null;

async function radarPantalla() {
    try {
        const res = await fetch(`../api/session/estado.php?sesion_id=${SESION_ID}`);
        if (!res.ok) throw new Error("Error en la red");

        const data = await res.json();

        if (data.estado !== estadoJuego) {
            estadoJuego = data.estado;
            gestionarCambioEstado(data);
        }

        if (estadoJuego === 'en_juego' && data.reto_actual !== currentRetoId) {
            currentRetoId = data.reto_actual;
            cargarRetoEnPantalla(currentRetoId);
        }

        if (estadoJuego === 'esperando') {
            actualizarListaJugadores();
        }

        if (estadoJuego === 'terminada') {
            setTimeout(() => {
                window.location.href = `/inner-work/clash/pages/ranking.php?sesion_id=${SESION_ID}`;
            }, 3000);
        }

    } catch (error) {
        console.error("Error en radarPantalla:", error);
    }
}

function gestionarCambioEstado(data) {
    const secLobby  = document.getElementById('pantalla-lobby');
    const secReto   = document.getElementById('pantalla-reto');
    const secFin    = document.getElementById('pantalla-intermedia');

    [secLobby, secReto, secFin].forEach(s => { if (s) s.classList.add('oculto'); });

    if (estadoJuego === 'esperando' && secLobby)      secLobby.classList.remove('oculto');
    else if (estadoJuego === 'en_juego' && secReto)   secReto.classList.remove('oculto');
    else if (estadoJuego === 'terminada' && secFin)   secFin.classList.remove('oculto');
}

async function cargarRetoEnPantalla(retoId) {
    try {
        const res = await fetch(`../api/juego/obtener_pregunta.php?sesion_id=${SESION_ID}&idioma=es`);
        const reto = await res.json();

        document.getElementById('pantalla-pregunta-texto').textContent = reto.pregunta || '';

        const contenedorMedia = document.getElementById('pantalla-contenido-multimedia');
        contenedorMedia.innerHTML = '';

        // campo correcto es 'emojis', no 'contenido'
        if (reto.tipo === 'emoji') {
            contenedorMedia.innerHTML = `<div class="pantalla-emoji">${reto.emojis}</div>`;
        } else if (reto.tipo === 'imagen') {
            contenedorMedia.innerHTML = `<img src="../assets/img/retos/${reto.media_url}" class="pantalla-img" alt="imagen">`;
        } else if (reto.tipo === 'video') {
            contenedorMedia.innerHTML = `<video src="../${reto.media_url}" autoplay muted class="pantalla-video"></video>`;
        } else if (reto.tipo === 'audio') {
            contenedorMedia.innerHTML = `<audio src="../${reto.media_url}" autoplay controls class="pantalla-audio"></audio>`;
        } else if (reto.tipo === 'codigo') {
            contenedorMedia.innerHTML = `<pre class="pantalla-code"><code>${reto.emojis}</code></pre>`;
        }

        const tiempoSegundos = typeof TIEMPO_PREGUNTA !== 'undefined' ? TIEMPO_PREGUNTA : 9;
        iniciarContadorPantalla(tiempoSegundos);

    } catch (error) {
        console.error("Error al cargar reto:", error);
    }
}

function iniciarContadorPantalla(segundos) {
    if (timerInterval) clearInterval(timerInterval);

    let restante = segundos;
    const display = document.getElementById('segundos');
    if (!display) return;
    display.textContent = restante;

    timerInterval = setInterval(() => {
        restante--;
        display.textContent = restante;
        if (restante <= 0) clearInterval(timerInterval);
    }, 1000);
}

async function actualizarListaJugadores() {
    try {
        const res = await fetch(`../api/jugador/listar.php?sesion_id=${SESION_ID}`);
        const data = await res.json();

        const contador = document.getElementById('contador-jugadores');
        const lista = document.getElementById('lista-jugadores-nombres');

        // actualizar numero de usuario
        if (contador) {
            contador.textContent = `${data.length} jugadores unidos`;
        }
        
        // norrar
        if (lista) {
            lista.innerHTML = '';
        }

        // si no hay usuario
        if (data.length === 0) {
            if (lista) {
                lista.innerHTML = '<p>Esperando jugadores...</p>';
            }
            return;
        }

        // Reproductor de renderizado
        data.forEach(j => {
            if (lista) {
                lista.innerHTML += `
                    <div class="jugador-item">
                        <img src="../assets/img/${j.avatar}.png" class="jugador-avatar" alt="avatar">
                        <span>${j.nombre}</span>
                    </div>
                `;
            }
        });

    } catch (e) {
        console.error("Error lista jugadores:", e);
    }
}

setInterval(radarPantalla, 1500);