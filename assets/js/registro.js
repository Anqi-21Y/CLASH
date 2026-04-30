// defino las variables del formulario de registro
let idiomaSeleccionado = 'es';
let avatarSeleccionado = 'avatar1';

// gestiono la seleccion de idioma
document.querySelectorAll('.btn-idioma').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.btn-idioma').forEach(b => b.classList.remove('activo'));
        btn.classList.add('activo');
        idiomaSeleccionado = btn.dataset.idioma;
    });
});

// gestiono la seleccion de avatar
document.querySelectorAll('.btn-avatar').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.btn-avatar').forEach(b => b.classList.remove('activo'));
        btn.classList.add('activo');
        avatarSeleccionado = btn.dataset.avatar;
    });
});

// gestiono el boton de entrar al juego
document.getElementById('btn-entrar').addEventListener('click', async () => {

    const nombre = document.getElementById('input-nombre').value.trim();
    const error = document.getElementById('mensaje-error');
    error.classList.add('oculto');

    // verifico que el nombre no este vacio
    if (nombre === '') {
        error.textContent = 'Por favor escribe tu nombre';
        error.classList.remove('oculto');
        return;
    }


const pinManualInput = document.getElementById('input-pin-manual');
const pin = pinManualInput ? pinManualInput.value.trim() : '';


if (nombre === '') {
    error.textContent = 'Por favor escribe tu nombre';
    error.classList.remove('oculto');
    return;
}

if (pin === '') {
    error.textContent = 'Por favor escribe el PIN de la partida';
    error.classList.remove('oculto');
    return;
}


try {
    const resposta = await fetch('../api/session/unirse.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            nombre: nombre,
            avatar: avatarSeleccionado,
            idioma: idiomaSeleccionado,
            pin: pin 
        })
    });

    if (!resposta.ok) throw new Error('PIN no válido o error en registro');

    const resultado = await resposta.json();

    // guardo los datos del jugador
    localStorage.setItem('jugador_id', resultado.jugador_id);
    localStorage.setItem('sesion_id', resultado.sesion_id);
    localStorage.setItem('jugador_nombre', nombre);
    localStorage.setItem('jugador_avatar', avatarSeleccionado);
    localStorage.setItem('jugador_idioma', idiomaSeleccionado);

    console.log("SESSION ID FINAL:", resultado.sesion_id);

    // redirijo a la sala de espera
    window.location.href = 'waiting.php';

    } catch (error) {
        console.error('error:', error);
        document.getElementById('mensaje-error').textContent = 'algo salio mal, intentalo de nuevo';
        document.getElementById('mensaje-error').classList.remove('oculto');
    }
});