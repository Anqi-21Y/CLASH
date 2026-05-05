<?php
// iniciamos la sesion para que el servidor recuerde el idioma en toda la web
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// detectamos si el usuario quiere cambiar de idioma por la URL ejm ?lang=en
if (isset($_GET['lang'])) {
    $allowed_langs = ['es', 'ca', 'zh'];
    if (in_array($_GET['lang'], $allowed_langs)) {
        $_SESSION['lang'] = $_GET['lang'];
    }
}

// si no hay idioma en la sesion ponemos español por defecto
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'es';
}

$idioma_actual = $_SESSION['lang'];

//  constantes generales del juego 
// tiempo en segundos que tiene cada jugador para responder
define('TIEMPO_PREGUNTA', 17);

// # de preguntas por partida
define('NUM_PREGUNTAS', 4);

// puntos segun velocidad de respuesta
define('PUNTOS_RAPIDO', 100);
define('PUNTOS_MEDIO', 70);
define('PUNTOS_LENTO', 40);
define('PUNTOS_FALLO', 0);

// idiomas disponibles en el juego[
define('IDIOMAS', ['es', 'ca', 'zh']);
?>