// el contador de 9 segundos:



// gestiono el contador de tiempo de cada pregunta

let intervalo = null;

// inicio el contador con el tiempo definido en config.php
function iniciarContador(segundos, callback) {
    let tiempoRestante = segundos;
    document.getElementById('tiempo-restante').textContent = tiempoRestante;

    intervalo = setInterval(() => {
        tiempoRestante--;
        document.getElementById('tiempo-restante').textContent = tiempoRestante;

        // cuando llega a 0 paro el contador y llamo al callback
        if (tiempoRestante <= 0) {
            clearInterval(intervalo);
            callback();
        }
    }, 1000);
}

// paro el contador si el jugador responde antes de que acabe
function pararContador() {
    clearInterval(intervalo);
}