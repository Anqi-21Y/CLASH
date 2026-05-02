<?php
// Gestionar el ranking final de una sesion y el ranking global por categoria.
require __DIR__ . '/../../config/conexion.php';
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Obtener el ID de la sesión actual
    $sesion_id = isset($_GET['sesion_id']) ? intval($_GET['sesion_id']) : 0;
    $categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

    // --- RANKING GLOBAL POR CATEGORIA ---
    if ($sesion_id === 0 && $categoria_id > 0) {
        $stmt = $db->prepare("SELECT j.nombre, j.avatar, SUM(r.puntos) AS puntos_total, SUM(r.es_correcta) AS aciertos
            FROM jugadores j JOIN respuestas r ON j.id = r.jugador_id JOIN sesiones s ON r.sesion_id = s.id
            WHERE s.categoria_id = :cat_id GROUP BY j.nombre, j.avatar ORDER BY puntos_total DESC LIMIT 10");

        $stmt->bindValue(':cat_id', $categoria_id, SQLITE3_INTEGER);
        $res = $stmt->execute();
        
        $global_ranking = [];
        while ($f = $res->fetchArray(SQLITE3_ASSOC)) {
            $global_ranking[] = $f;
        }

        echo json_encode([
            'success' => true,
            'tipo' => 'global',
            'ranking' => $global_ranking
        ]);
        exit; 
    }

    // --- RANKING DE SESION ---

    // Esto evita el problema de las inserciones duplicadas
    $check = $db->querySingle("SELECT COUNT(*) FROM resultados WHERE sesion_id = $sesion_id");

    if ($check == 0) {
        // Calcular e insertar los resultados finales
        $stmt = $db->prepare("SELECT j.id, j.nombre, j.avatar, SUM(r.puntos) AS puntos_total, SUM(r.es_correcta) AS aciertos FROM jugadores j
            JOIN respuestas r ON j.id = r.jugador_id WHERE r.sesion_id = :sesion_id GROUP BY j.id ORDER BY puntos_total DESC");

        $stmt->bindValue(':sesion_id', $sesion_id, SQLITE3_INTEGER);
        $resultats = $stmt->execute();

        $posicion = 1;
        while ($fila = $resultats->fetchArray(SQLITE3_ASSOC)) {
            // Guardar en la tabla de resultados
            $ins = $db->prepare("INSERT INTO resultados (jugador_id, sesion_id, puntos_total, aciertos, posicion) VALUES (:jid, :sid, :pts, :act, :pos)");

            $ins->bindValue(':jid', $fila['id'], SQLITE3_INTEGER);
            $ins->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);
            $ins->bindValue(':pts', $fila['puntos_total'], SQLITE3_INTEGER);
            $ins->bindValue(':act', $fila['aciertos'], SQLITE3_INTEGER);
            $ins->bindValue(':pos', $posicion, SQLITE3_INTEGER);
            $ins->execute();
            $posicion++;
        }
    }

    // 3. Lee los datos de la tabla de resultados y devuélvelos a la interfaz de usuario.
    $final_ranking = [];
    $stmt = $db->prepare("SELECT r.*, j.nombre, j.avatar FROM resultados r JOIN jugadores j ON r.jugador_id = j.id WHERE r.sesion_id = :sid ORDER BY r.posicion ASC");
    
    $stmt->bindValue(':sid', $sesion_id, SQLITE3_INTEGER);
    $res = $stmt->execute();

    while ($f = $res->fetchArray(SQLITE3_ASSOC)) {
        $final_ranking[] = $f;
    }

    if (count($final_ranking) > 0) {
        // obtengo la categoria de la sesión para que el front pueda usarla en el ranking global
        $cat = $db->querySingle("SELECT categoria_id FROM sesiones WHERE id = $sesion_id");
        echo json_encode([
            'success'      => true,
            'tipo'         => 'sesion',
            'ranking'      => $final_ranking,
            'categoria_id' => $cat,
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'No hay resultados aun']);
    }

} else {
    http_response_code(405);
    echo json_encode(['error' => 'Metodo no permitido']);
}

$db->close();