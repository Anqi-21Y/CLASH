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

// puntos segun velocidad de respuesta - si responde entre el segundo 1 y 3 gana 100 puntos
define('PUNTOS_RAPIDO', 100);
// si responde entre el segundo 4 y 6 gana 70 puntos
define('PUNTOS_MEDIO', 70);
// si responde entre el segundo 7 y 9 gana 40 puntos
define('PUNTOS_LENTO', 40);
// si falla o no responde gana 0 puntos
define('PUNTOS_FALLO', 0);

// idiomas disponibles en el juego
define('IDIOMAS', ['es', 'ca', 'zh']);
?>