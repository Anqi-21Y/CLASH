// gestiono el menu overlay
function toggleMenu() {
    var overlay = document.getElementById('menuOverlay');
    var widget  = document.getElementById('qr-widget-btn');
    overlay.classList.toggle('abierto');
    document.body.classList.toggle('sin-scroll');
    if (widget) {
        widget.style.display = overlay.classList.contains('abierto') ? 'none' : 'block';
    }
}

//  barra animada superior 
// lee los msjs de CLASH_T_JS definido en idiomas.js
// si por algun motivo idiomas.js no carga usamos el fallback en español
var _msgs = (typeof CLASH_T_JS !== 'undefined' && CLASH_T_JS.topbar_msgs)
    ? CLASH_T_JS.topbar_msgs
    : [
        'Adivina emojis y gana el premio',
        'Disponible en español, catalán y chino',
        'Responde en menos de 30 segundos',
        'El más rápido gana más puntos',
        'Películas, canciones, famosos y modo sorpresa',
        'Haz click aquí para unirte a la partida',
    ];

// duplicamos para que el scroll sea continuo sin saltos
var mensajes = _msgs.concat(_msgs);

// creo los spans del track
var track = document.getElementById('top-bar-track');
mensajes.forEach(function (msg) {
    var span = document.createElement('span');
    span.className = 'top-bar-item';
    span.textContent = msg;
    track.appendChild(span);
});

// click en la barra abre el overlay del QR
document.getElementById('top-bar-track').addEventListener('click', function (e) {
    e.stopPropagation();
    document.getElementById('qr-overlay').classList.add('abierto');
    document.body.classList.add('sin-scroll');
});