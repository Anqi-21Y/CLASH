<?php
// conecto con la base de datos sqlite3 (se crea sola si no existe)
$db = new SQLite3(__DIR__ . '/../database/clash.db');

$db->exec("
CREATE TABLE IF NOT EXISTS sesiones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pin TEXT NOT NULL UNIQUE,
    categoria_id INTEGER NOT NULL,
    estado TEXT NOT NULL DEFAULT 'esperando'
        CHECK(estado IN ('esperando','en_juego','terminada')),
    reto_actual INTEGER NOT NULL DEFAULT 0,
    admin_id INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    inicio_timestamp DATETIME
)
");

// creo la tabla de jugadores si no existe
$db->exec("
CREATE TABLE IF NOT EXISTS jugadores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    avatar TEXT NOT NULL,
    idioma TEXT NOT NULL CHECK(idioma IN ('es','ca','zh')),
    sesion_id INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_seen DATETIME,
    FOREIGN KEY (sesion_id) REFERENCES sesiones(id)
)
");

// creo la tabla de categorias si no existe
$db->exec("
    CREATE TABLE IF NOT EXISTS categorias (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre_es TEXT NOT NULL,
        nombre_ca TEXT NOT NULL,
        nombre_zh TEXT NOT NULL,
        icono TEXT NOT NULL
    )
");

// creo la tabla de retos si no existe
$db->exec("
    CREATE TABLE IF NOT EXISTS retos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        categoria_id INTEGER NOT NULL,
        tipo TEXT NOT NULL CHECK(tipo IN ('emoji','imagen','video','audio','codigo')),
        emojis TEXT,
        media_url TEXT,
        pregunta_es TEXT,
        pregunta_ca TEXT,
        pregunta_zh TEXT,
        opcion_correcta INTEGER NOT NULL CHECK(opcion_correcta IN (1,2,3,4)),
        op1_es TEXT NOT NULL, op1_ca TEXT NOT NULL, op1_zh TEXT NOT NULL,
        op2_es TEXT NOT NULL, op2_ca TEXT NOT NULL, op2_zh TEXT NOT NULL,
        op3_es TEXT NOT NULL, op3_ca TEXT NOT NULL, op3_zh TEXT NOT NULL,
        op4_es TEXT NOT NULL, op4_ca TEXT NOT NULL, op4_zh TEXT NOT NULL,
        dificultad TEXT NOT NULL CHECK(dificultad IN ('facil','medio','dificil')),
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
    )
");

// creo la tabla de respuestas si no existe
$db->exec("
CREATE TABLE IF NOT EXISTS respuestas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    jugador_id INTEGER NOT NULL,
    reto_id INTEGER NOT NULL,
    opcion_elegida INTEGER NOT NULL CHECK(opcion_elegida IN (1,2,3,4)),
    es_correcta INTEGER NOT NULL CHECK(es_correcta IN (0,1)),
    tiempo_ms INTEGER NOT NULL,
    puntos INTEGER NOT NULL,
    sesion_id INTEGER,
    FOREIGN KEY (jugador_id) REFERENCES jugadores(id),
    FOREIGN KEY (reto_id) REFERENCES retos(id)
)
");

// creo la tabla de resultados si no existe
$db->exec("
CREATE TABLE IF NOT EXISTS resultados (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    jugador_id INTEGER NOT NULL,
    puntos_total INTEGER NOT NULL,
    aciertos INTEGER NOT NULL,
    posicion INTEGER NOT NULL,
    sesion_id INTEGER,
    FOREIGN KEY (jugador_id) REFERENCES jugadores(id)
)
");

// El profe lo explica en el D02 — require incluye el contenido de otro archivo dentro del tuyo. La diferencia con include es que si el archivo no existe, require lanza un error grave y para la ejecución, mientras que include sigue aunque falle.
// En Clash lo usarás así. Por ejemplo en api/registrar_jugador.php al principio del archivo escribirías:
// php<?php
// incluyo la conexion a la base de datos
// require '../config/conexion.php';
