// juego.js — implementar logica principal del juego multijugador en modo libre

const CATEGORIA_HINTS = CLASH_T_JS.hints || {};
const TXT_RES = CLASH_T_JS.resultados || {};
// DATOS DEL JUGADOR

const jugadorId     = parseInt(localStorage.getItem('jugador_id'));
const jugadorNombre = localStorage.getItem('jugador_nombre');
const jugadorAvatar = (localStorage.getItem('jugador_avatar') || '').trim();
const jugadorIdioma = localStorage.getItem('idioma_clash') || 'es';
const sesionId      = typeof SESION_ID !== 'undefined'
    ? parseInt(SESION_ID)
    : parseInt(localStorage.getItem('sesion_id'));

let numeroPreguntaActual = 0;
let cargandoPregunta = false;
let intentosFallidos = 0;
const MAX_INTENTOS = 5;

if (!sesionId || !jugadorId) {
    console.error('ERROR: sesion_id o jugador_id no encontrado en localStorage.');
}

// Avatar y nombre en header
const avatarEl = document.getElementById('jugador-avatar');
if (jugadorAvatar && jugadorAvatar.startsWith('avatar')) {
    avatarEl.innerHTML = `<img src="../assets/img/${jugadorAvatar}.png" alt="avatar"
        style="width:30px;height:30px;border-radius:50%;border:2px solid #fff;">`;
} else {
    avatarEl.textContent = jugadorAvatar;
}
document.getElementById('jugador-nombre').textContent = jugadorNombre;


// ESTADO GLOBAL

let retoId = null;
let tiempoInicio = null;
let respondido = false;
let estadoActual = 'esperando';
let radarArranqueActivo = true;

mostrarSeccion('seccion-espera');


// RADAR DE ARRANQUE

const radarArranque = setInterval(async () => {
    if (!radarArranqueActivo) return;
    try {
        const res  = await fetch(`../api/session/estado.php?sesion_id=${sesionId}`);
        if (!res.ok) return;
        const data = await res.json();

        if (data.estado === 'en_juego' && estadoActual === 'esperando') {
            estadoActual        = 'jugando';
            radarArranqueActivo = false;
            cargarPregunta();
        }

        if (data.estado === 'terminada' && estadoActual !== 'terminada') {
            estadoActual = 'terminada';
            clearInterval(radarArranque);
            mostrarGranFinal();
        }

    } catch (e) {
        console.error('Error en radar de arranque:', e);
    }
}, 2000);


// CARGAR PREGUNTA

async function cargarPregunta() {
    if (cargandoPregunta) return;

    // Nunca superar el total de preguntas
    if (numeroPreguntaActual >= NUM_PREGUNTAS) {
        finalizarJugador();
        return;
    }

    cargandoPregunta = true;
    respondido       = false;
    bloquearOpciones(true);

    document.querySelectorAll('.btn-opcion').forEach(btn => {
        btn.classList.remove('correcta', 'incorrecta');
        btn.blur();
    });

    try {
        const res = await fetch(
            `../api/juego/obtener_pregunta.php?idioma=${jugadorIdioma}&sesion_id=${sesionId}&jugador_id=${jugadorId}`
        );
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const datos = await res.json();

        if (!datos.success) {
            cargandoPregunta = false;
            finalizarJugador();
            return;
        }

        // Incrementar solo al recibir pregunta correctamente
        numeroPreguntaActual++;
        intentosFallidos = 0;

        // Mostrar numero — nunca supera NUM_PREGUNTAS
        const numMostrar = Math.min(numeroPreguntaActual, NUM_PREGUNTAS);
        document.getElementById('numero-pregunta').textContent = numMostrar;

        // Barra de progreso
        const fill = document.getElementById('progreso-fill');
        if (fill) fill.style.width = Math.round((numMostrar / NUM_PREGUNTAS) * 100) + '%';

        // Hint de categoría
        const catId  = typeof CATEGORIA_ID !== 'undefined' ? CATEGORIA_ID : 4;
        const hintEl = document.getElementById('hint-texto');
        if (hintEl) hintEl.textContent = CATEGORIA_HINTS[catId] || '';

        mostrarSeccion('seccion-pregunta');
        mostrarTipo(datos);

        // Rellenar texto de cada opción en su span interior
        datos.opciones.forEach((opcion, i) => {
            const btn = document.getElementById(`opcion-${i + 1}`);
            const textoEl = document.getElementById(`texto-opcion-${i + 1}`);
            if (btn) btn.dataset.opcion  = opcion.id;
            if (textoEl) textoEl.textContent = opcion.texto;
        });

        retoId       = datos.reto_id;
        tiempoInicio = Date.now();

        bloquearOpciones(false);
        setTimeout(() => document.querySelectorAll('.btn-opcion').forEach(b => b.blur()), 50);

        // timer.js actualiza #tiempo-restante automáticamente (descendente)
        iniciarContador(TIEMPO_PREGUNTA, () => {
            if (!respondido) enviarRespuesta(0);
        });

    } catch (e) {
        console.error('Error cargando pregunta:', e);
        intentosFallidos++;
        cargandoPregunta = false;

        if (intentosFallidos < MAX_INTENTOS) {
            setTimeout(cargarPregunta, 2000);
        } else {
            finalizarJugador();
        }
        return;
    }

    cargandoPregunta = false;
}


// 5. LISTENERS OPCIONES

document.querySelectorAll('.btn-opcion').forEach(btn => {
    btn.addEventListener('click', () => {
        if (respondido || btn.disabled) return;
        btn.blur();
        enviarRespuesta(parseInt(btn.dataset.opcion));
    });
});


// 6. ENVIAR RESPUESTA

async function enviarRespuesta(opcion) {
    if (respondido) return;
    respondido = true;
    pararContador();
    bloquearOpciones(true);

    const tiempoMs = opcion > 0
        ? (Date.now() - tiempoInicio)
        : (TIEMPO_PREGUNTA * 1000);

    const payload = {
        jugador_id: jugadorId,
        sesion_id: sesionId,
        reto_id: parseInt(retoId),
        opcion_elegida: opcion,
        tiempo_ms: tiempoMs,
    };

    try {
        const res = await fetch('../api/juego/enviar_respuesta.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload),
        });

        if (!res.ok) { mostrarResultadoLocal(opcion, null); return; }

        const resultado = await res.json();
        if (!resultado.success) { mostrarResultadoLocal(opcion, null); return; }

        let ranking = null;
        try {
            const resRanking = await fetch(
                `../api/juego/resultado.php?type=parcial&jugador_id=${jugadorId}&sesion_id=${sesionId}&reto_id=${retoId}`
            );
            ranking = await resRanking.json();
        } catch (e) { /* silencioso */ }

        mostrarResultadoLocal(opcion, resultado, ranking);

    } catch (e) {
        console.error('Error en enviarRespuesta:', e);
        mostrarResultadoLocal(opcion, null);
    }
}


// 7. PANTALLA DE RESULTADO

function mostrarResultadoLocal(opcion, resultado, ranking) {
    mostrarSeccion('seccion-resultado');

    const icono = document.getElementById('resultado-icono');
    const texto = document.getElementById('resultado-texto');

    if (opcion === 0) {
        icono.textContent = '⏰';
        texto.textContent = TXT_RES.tiempo_agotado || 'Tiempo agotado';
    } else if (resultado && resultado.es_correcta) {
        icono.textContent = '✅';
        texto.textContent = TXT_RES.correcto || '¡Correcto!';
    } else {
        icono.textContent = '❌';
        texto.textContent = TXT_RES.incorrecto || 'Incorrecto';
    }

    const elGanados = document.getElementById('puntos-ganados');
    const elAcumulados = document.getElementById('puntos-acumulados');
    const elPosicion = document.getElementById('posicion-actual');
    const elEspera = document.getElementById('resultado-espera');

    if (elGanados) elGanados.textContent =
        `+${ranking?.puntos_ganados ?? resultado?.puntos ?? 0} pts`;

    if (elAcumulados) elAcumulados.textContent =
        `${TXT_RES.total || 'Total'}: ${ranking?.puntos_acumulados ?? 0} pts`;

    if (elPosicion) elPosicion.textContent =
        `${TXT_RES.ranking || 'Ranking'}: #${ranking?.posicion_actual ?? '-'}`;

    let cuenta = 3;

    if (elEspera) {
        elEspera.textContent =
            (TXT_RES.siguiente || 'Siguiente pregunta en {s}s...')
            .replace('{s}', cuenta);
    }

    if (window._timerResumen) clearInterval(window._timerResumen);

    window._timerResumen = setInterval(() => {
        cuenta--;

        if (elEspera) {
            elEspera.textContent = cuenta > 0
                ? (TXT_RES.siguiente || 'Siguiente pregunta en {s}s...')
                    .replace('{s}', cuenta)
                : (TXT_RES.cargando || 'Cargando...');
        }

        if (cuenta <= 0) {
            clearInterval(window._timerResumen);
            avanzarAlSiguiente();
        }

    }, 1000);
}


// 8. AVANZAR AL SIGUIENTE

async function avanzarAlSiguiente() {
    try {
        const res = await fetch('../api/session/avanzar_reto.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                sesion_id: sesionId,
                jugador_id: jugadorId,
                reto_id_actual: parseInt(retoId),
            }),
        });
        const data = await res.json();

        if (data.estado === 'terminada') {
            estadoActual = 'terminada';
            clearInterval(radarArranque);
            mostrarGranFinal();
        } else if (data.estado === 'esperando_final') {
            finalizarJugador();
        } else {
            cargarPregunta();
        }

    } catch (e) {
        console.error('Error en avanzarAlSiguiente:', e);
        cargarPregunta();
    }
}


// 9. JUGADOR TERMINÓ — espera fin global

function finalizarJugador() {
    mostrarSeccion('seccion-resultado');

    const els = {
        espera: document.getElementById('resultado-espera'),
        ganados: document.getElementById('puntos-ganados'),
        acumulados: document.getElementById('puntos-acumulados'),
        posicion: document.getElementById('posicion-actual'),
        icono: document.getElementById('resultado-icono'),
        texto: document.getElementById('resultado-texto'),
    };

    if (els.icono) els.icono.textContent = '🏁';
    if (els.texto) {
        els.texto.textContent =
            TXT_RES.fin_partida || '¡Todas respondidas!';
    }

    if (els.ganados) els.ganados.textContent = '';
    if (els.acumulados) els.acumulados.textContent = '';
    if (els.posicion) els.posicion.textContent = '';
    if (els.espera) {
        els.espera.textContent =
            TXT_RES.esperando_jugadores || 'Esperando a los demás...';
    }

    const radarFin = setInterval(async () => {
        try {
            const res  = await fetch(`../api/session/estado.php?sesion_id=${sesionId}`);
            const data = await res.json();
            if (data.estado === 'terminada') {
                clearInterval(radarFin);
                clearInterval(radarArranque);
                mostrarGranFinal();
            }
        } catch (e) { /* silencioso */ }
    }, 2000);
}


// 10. PANTALLA FINAL → redirige a ranking.php

function mostrarGranFinal() {
    clearInterval(radarArranque);
    mostrarSeccion('seccion-fin');
    setTimeout(() => {
        window.location.href = `ranking.php?sesion_id=${sesionId}`;
    }, 2000);
}


// 11. RENDERIZAR TIPO DE PREGUNTA

function mostrarTipo(datos) {
    ['emoji','imagen','video','audio','codigo','pregunta'].forEach(b => {
        document.getElementById(`bloque-${b}`)?.classList.add('oculto');
    });

    switch (datos.tipo) {
        case 'emoji':
            document.getElementById('texto-emojis').textContent = datos.emojis;
            document.getElementById('bloque-emoji').classList.remove('oculto');
            break;
        case 'imagen':
            document.getElementById('imagen-reto').src = '../' + datos.media_url;
            document.getElementById('bloque-imagen').classList.remove('oculto');
            document.getElementById('texto-pregunta').textContent = datos.pregunta;
            document.getElementById('bloque-pregunta').classList.remove('oculto');
            break;
        case 'video':
            document.getElementById('video-source').src = '../' + datos.media_url;
            document.getElementById('video-reto').load();
            document.getElementById('bloque-video').classList.remove('oculto');
            document.getElementById('texto-pregunta').textContent = datos.pregunta;
            document.getElementById('bloque-pregunta').classList.remove('oculto');
            break;
        case 'audio':
            document.getElementById('audio-source').src = '../' + datos.media_url;
            document.getElementById('audio-reto').load();
            document.getElementById('bloque-audio').classList.remove('oculto');
            document.getElementById('texto-pregunta').textContent = datos.pregunta;
            document.getElementById('bloque-pregunta').classList.remove('oculto');
            break;
        case 'codigo':
            document.getElementById('texto-codigo').textContent = datos.emojis;
            document.getElementById('bloque-codigo').classList.remove('oculto');
            document.getElementById('texto-pregunta').textContent = datos.pregunta;
            document.getElementById('bloque-pregunta').classList.remove('oculto');
            break;
    }
}


// 12. UTILIDADES

function mostrarSeccion(id) {
    ['seccion-espera','seccion-pregunta','seccion-resultado','seccion-fin'].forEach(s => {
        document.getElementById(s)?.classList.add('oculto');
    });
    document.getElementById(id)?.classList.remove('oculto');
}

function bloquearOpciones(bloquear) {
    document.querySelectorAll('.btn-opcion').forEach(btn => {
        btn.disabled = bloquear;
    });
}