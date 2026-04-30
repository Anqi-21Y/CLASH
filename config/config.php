<?php

// defino las constantes generales del juego clash

// tiempo en segundos que tiene cada jugador para responder
define('TIEMPO_PREGUNTA', 17);

// numero de preguntas por partida
define('NUM_PREGUNTAS', 4);

// puntos segun velocidad de respuesta
// si responde entre el segundo 1 y 3 gana 100 puntos
define('PUNTOS_RAPIDO', 100);
// si responde entre el segundo 4 y 6 gana 70 puntos
define('PUNTOS_MEDIO', 70);
// si responde entre el segundo 7 y 9 gana 40 puntos
define('PUNTOS_LENTO', 40);
// si falla o no responde gana 0 puntos
define('PUNTOS_FALLO', 0);

// idiomas disponibles en el juego
define('IDIOMAS', ['es', 'ca', 'zh']);

// Usamos define() exactamente como enseña el profe en D02 para las constantes. 
// La ventaja es que si decides cambiar el tiempo de respuesta de 9 a 12 segundos, lo cambias aquí y se actualiza en todo el proyecto automáticamente.