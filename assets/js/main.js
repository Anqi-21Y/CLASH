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

// gestiono la barra animada superior con los mensajes
const mensajes = [
    'Adivina emojis y gana el premio',
    'Disponible en español, catalan y chino',
    'Responde en 9 segundos',
    'El mas rápido gana mas puntos',
    'Peliculas, canciones, famosos y modo sorpresa',
    'Haz click aqui para unirte a la partida',
    'Adivina emojis y gana el premio',
    'Disponible en español, catalan y chino',
    'Responde en 9 segundos',
    'El mas rápido gana mas puntos',
    'Peliculas, canciones, famosos y modo sorpresa',
];

// creo el track de la barra animada
const track = document.getElementById('top-bar-track');
mensajes.forEach(msg => {
    const span = document.createElement('span');
    span.className = 'top-bar-item';
    span.textContent = msg;
    track.appendChild(span);
});

// abro el modal del qr al hacer click en la zona de texto de la barra
document.getElementById('top-bar-track').addEventListener('click', (e) => {
    e.stopPropagation();
    document.getElementById('qr-modal').classList.add('abierto');
    document.body.classList.add('sin-scroll');
});

// cierro el modal del qr al hacer click en el boton de cerrar
document.getElementById('qr-modal-cerrar').addEventListener('click', () => {
    document.getElementById('qr-modal').classList.remove('abierto');
    document.body.classList.remove('sin-scroll');
});

// cierro el modal del qr al hacer click fuera del contenido
document.getElementById('qr-modal').addEventListener('click', e => {
    if (e.target === document.getElementById('qr-modal')) {
        document.getElementById('qr-modal').classList.remove('abierto');
        document.body.classList.remove('sin-scroll');
    }
});