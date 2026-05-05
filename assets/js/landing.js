//  Como se juega — slider con wipe horizontal 
// Los textos de los 4 pasos vienen de CLASH_T_JS definido en idiomas.js

var pasos = (typeof CLASH_T_JS !== 'undefined' && CLASH_T_JS.pasos)
    ? CLASH_T_JS.pasos
    : [
        { num: '01', titulo: 'Escanea y regístrate',  texto: 'Escanea el QR con tu móvil, escribe tu nombre, elige tu avatar y escribe el PIN que el presentador mostrará en pantalla.',  color: '#FFAAEA' },
        { num: '02', titulo: 'Espera en sala',         texto: 'Verás a los demás jugadores conectarse en tiempo real. Cuando el presentador lo decida, la partida arranca sola.',            color: '#acadff' },
        { num: '03', titulo: '¡Responde rápido!',      texto: 'Tienes menos de 30 segundos para adivinar la respuesta correcta. Cuanto antes respondas, más puntos consigues.',             color: '#fff4a3' },
        { num: '04', titulo: 'Sube al podio',          texto: 'Al terminar todas las rondas verás el ranking final en tu móvil. ¿Serás el más rápido y certero?',                          color: '#d2ffeb' },
    ];

var pasoActual  = 0;
var cj_animando = false;

// init: aplica color base al primer paso
(function () {
    var base = document.getElementById('cj-bg-base');
    if (base) base.style.background = pasos[0].color;
    var prev = document.getElementById('cj-prev');
    if (prev) prev.disabled = true;
})();

// animacion de cambio de color (wipe)
// dir > 0 va hacia la izquierda
// dir < 0 va hacia la derecha

function _wipeColor(nuevoPaso, dir) {
    var base  = document.getElementById('cj-bg-base');
    var wipe  = document.getElementById('cj-bg-wipe');
    var durMs = 420;

    // 1. posiciona el wipe fuera de pantalla en la direccion correcta
    wipe.style.transition  = 'none';
    wipe.style.background  = pasos[nuevoPaso].color;
    wipe.style.transform   = dir > 0 ? 'translateX(100%)' : 'translateX(-100%)';

    // 2. fuerza reflow para que la GPU registre el estado inicial
    void wipe.offsetWidth;

    // 3. lanza la transicion hacia el centro (cubre toda la card)
    wipe.style.transition = 'transform ' + durMs + 'ms cubic-bezier(0.65, 0, 0.35, 1)';
    wipe.style.transform  = 'translateX(0)';

    // 4. cuando termina: fija el nuevo color en base y resetea wipe (invisible)
    setTimeout(function () {
        base.style.background  = pasos[nuevoPaso].color;
        wipe.style.transition  = 'none';
        wipe.style.transform   = dir > 0 ? 'translateX(100%)' : 'translateX(-100%)';
        cj_animando = false;
    }, durMs);
}

// cambia el texto con pequeño fade
function _actualizarTexto(nuevoPaso) {
    var contenido = document.getElementById('cj-contenido');
    contenido.style.opacity   = '0';
    contenido.style.transform = 'translateX(' + (0) + 'px)';

    setTimeout(function () {
        document.getElementById('cj-num').textContent    = pasos[nuevoPaso].num;
        document.getElementById('cj-titulo').textContent = pasos[nuevoPaso].titulo;
        document.getElementById('cj-texto').textContent  = pasos[nuevoPaso].texto;
        contenido.style.opacity   = '1';
        contenido.style.transform = 'translateX(0)';
    }, 200);
}

// actualizar los puntos (dots)
function _actualizarDots(idx) {
    document.querySelectorAll('.cj-dot').forEach(function (d, i) {
        d.classList.toggle('activo', i === idx);
    });
}

// cambiar paso con botones
function cambiarPaso(dir) {
    var nuevo = pasoActual + dir;
    if (nuevo < 0 || nuevo >= pasos.length || cj_animando) return;
    cj_animando = true;
    pasoActual  = nuevo;

    _wipeColor(pasoActual, dir);
    _actualizarTexto(pasoActual);
    _actualizarDots(pasoActual);

    document.getElementById('cj-prev').disabled = (pasoActual === 0);
    document.getElementById('cj-next').disabled = (pasoActual === pasos.length - 1);
}

function irPaso(indice) {
    if (indice === pasoActual || cj_animando) return;
    cambiarPaso(indice > pasoActual ? 1 : -1);
}

// slider infinito de categorias

(function () {
    var track   = document.getElementById('cat-track');
    var btnPrev = document.getElementById('cat-prev');
    var btnNext = document.getElementById('cat-next');
    if (!track || !btnPrev || !btnNext) return;

    var origCards = Array.from(track.querySelectorAll('.categoria-card'));
    var total     = origCards.length;

    // clonar tarjetas al final (para ir hacia delante en loop)
    origCards.forEach(function (card) {
        track.appendChild(card.cloneNode(true));
    });

    // clonar tarjetas al inicio (para ir hacia atras en loop)
    var fragmento = document.createDocumentFragment();
    origCards.forEach(function (card) {
        fragmento.appendChild(card.cloneNode(true));
    });
    track.insertBefore(fragmento, track.firstChild);

    // empezamos en el primer card original
    var catIdx  = total;
    var animando = false;

    function getCardWidth() {
        return track.children[0].offsetWidth + 20; /* 20px = gap */
    }

    // mover el slider
    function mover(conAnimacion) {
        track.style.transition = conAnimacion
            ? 'transform 0.5s cubic-bezier(0.65, 0, 0.35, 1)'
            : 'none';
        track.style.transform = 'translateX(-' + (catIdx * getCardWidth()) + 'px)';
    }

    // posicion inicial sin animacion
    mover(false);

    track.addEventListener('transitionend', function () {
        // llegamos a los clones del final saltar silenciosamente a los originales
        if (catIdx >= total * 2) {
            catIdx -= total;
            mover(false);
        }
        // llegamos a los clones del inicio  saltar silenciosamente al final de los originales
        if (catIdx < total) {
            catIdx += total;
            mover(false);
        }
        animando = false;
    });

    window.catSlide = function (dir) {
        if (animando) return;
        animando = true;
        catIdx  += dir;
        mover(true);
    };

    window.addEventListener('resize', function () {
        mover(false);
    });

    // sin disabled — el loop es infinito en ambas direcciones
    btnPrev.disabled = false;
    btnNext.disabled = false;
})();

// cargar estadisticas del juego

async function cargarEstadisticas() {
    try {
        var resposta = await fetch('api/obtener_ranking.php');
        if (!resposta.ok) throw new Error('error');
        var datos = await resposta.json();
        if (datos.success) {
            animarNumero('stat-jugadores', datos.total_jugadores);
            var totalRespuestas = datos.ranking.reduce(function(t, j) { return t + j.aciertos; }, 0);
            animarNumero('stat-respuestas', totalRespuestas);
            var el = document.getElementById('jugadores-live');
            if (el) el.textContent = datos.total_jugadores;
        }
    } catch (e) {
        console.error('error al cargar estadisticas:', e);
    }
}

// animar numero (contador)
function animarNumero(id, valorFinal) {
    var elemento = document.getElementById(id);
    if (!elemento) return;
    var valorActual = 0;
    var paso = Math.ceil(valorFinal / 40) || 1;
    var intervalo = setInterval(function () {
        valorActual += paso;
        if (valorActual >= valorFinal) { valorActual = valorFinal; clearInterval(intervalo); }
        elemento.textContent = valorActual;
    }, 40);
}

cargarEstadisticas();

// boton para ir a jugar

var btnUnirse = document.getElementById('btn-unirse');
if (btnUnirse) {
    btnUnirse.addEventListener('click', function (e) {
        e.preventDefault();
        window.location.href = 'pages/registro.php';
    });
}

// ocultar widget cuando haces scroll
var widget = document.getElementById('qr-widget-btn');
if (widget) {
    window.addEventListener('scroll', function () {
        if (window.scrollY > 100) {
            widget.style.right = '-200px';
        } else {
            widget.style.right = '32px';
        }
    });
}