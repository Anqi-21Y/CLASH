<?php
// incluyo la conexion a la base de datos
require __DIR__ . '/../config/conexion.php';
// inserto las 4 categorias
$categorias = [
    [1, 'Peliculas',     'Pellicules',  '电影',   '🎬'],
    [2, 'Canciones',     'Cançons',     '歌曲',   '🎵'],
    [3, 'Famosos',       'Famosos',     '名人',   '⭐'],
    [4, 'Modo sorpresa', 'Mode sorpresa','惊喜模式','🎲'],
];

foreach ($categorias as $cat) {
    $stmt = $db->prepare("
        INSERT OR IGNORE INTO categorias (id, nombre_es, nombre_ca, nombre_zh, icono)
        VALUES (:id, :nombre_es, :nombre_ca, :nombre_zh, :icono)
    ");
    $stmt->bindValue(':id',        $cat[0], SQLITE3_INTEGER);
    $stmt->bindValue(':nombre_es', $cat[1], SQLITE3_TEXT);
    $stmt->bindValue(':nombre_ca', $cat[2], SQLITE3_TEXT);
    $stmt->bindValue(':nombre_zh', $cat[3], SQLITE3_TEXT);
    $stmt->bindValue(':icono',     $cat[4], SQLITE3_TEXT);
    $stmt->execute();
}

echo "categorias insertadas<br>";

// inserto los retos
$retos = [

    // ══════════════════════════════
    // categoria 1 — peliculas
    // ══════════════════════════════
    [
        'categoria_id'   => 1,
        'tipo'           => 'emoji',
        'emojis'         => '🦸‍♂️💥⏳',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Endgame',        'op1_ca' => 'Endgame',        'op1_zh' => '终局之战',
        'op2_es' => 'Infinity War',   'op2_ca' => 'Infinity War',   'op2_zh' => '无限战争',
        'op3_es' => 'Thor',           'op3_ca' => 'Thor',           'op3_zh' => '雷神',
        'op4_es' => 'Iron Man',       'op4_ca' => 'Iron Man',       'op4_zh' => '钢铁侠',
        'dificultad'     => 'medio',
    ],
    [
        'categoria_id'   => 1,
        'tipo'           => 'emoji',
        'emojis'         => '🏎️🔫🏁',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Fast & Furious', 'op1_ca' => 'Fast & Furious', 'op1_zh' => '速度与激情',
        'op2_es' => 'Need for Speed', 'op2_ca' => 'Need for Speed', 'op2_zh' => '极速狂飙',
        'op3_es' => 'Gran Turismo',   'op3_ca' => 'Gran Turismo',   'op3_zh' => '跑车浪漫旅',
        'op4_es' => 'Top Gun',        'op4_ca' => 'Top Gun',        'op4_zh' => '壮志凌云',
        'dificultad'     => 'facil',
    ],
    [
        'categoria_id'   => 1,
        'tipo'           => 'emoji',
        'emojis'         => '🤠🥾🐍',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Toy Story',      'op1_ca' => 'Toy Story',      'op1_zh' => '玩具总动员',
        'op2_es' => 'Indiana Jones',  'op2_ca' => 'Indiana Jones',  'op2_zh' => '夺宝奇兵',
        'op3_es' => 'Shrek',          'op3_ca' => 'Shrek',          'op3_zh' => '史莱克',
        'op4_es' => 'Monsters Inc',   'op4_ca' => 'Monsters Inc',   'op4_zh' => '怪兽公司',
        'dificultad'     => 'medio',
    ],
    [
        'categoria_id'   => 1,
        'tipo'           => 'emoji',
        'emojis'         => '🚀👨🏻‍🚀🌌',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Interstellar',   'op1_ca' => 'Interstellar',   'op1_zh' => '星际穿越',
        'op2_es' => 'Gravity',        'op2_ca' => 'Gravity',        'op2_zh' => '地心引力',
        'op3_es' => 'The Martian',    'op3_ca' => 'The Martian',    'op3_zh' => '火星救援',
        'op4_es' => 'Apollo 13',      'op4_ca' => 'Apollo 13',      'op4_zh' => '阿波罗13号',
        'dificultad'     => 'dificil',
    ],

    // ══════════════════════════════
    // categoria 2 — canciones
    // ══════════════════════════════
    [
        'categoria_id'   => 2,
        'tipo'           => 'emoji',
        'emojis'         => '🦈🐰🌊',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Aqui llego tu tiburon', 'op1_ca' => 'Aqui va arribar el teu tauró', 'op1_zh' => '你的鲨鱼来了',
        'op2_es' => 'Yonaguni',              'op2_ca' => 'Yonaguni',                     'op2_zh' => '与那国岛',
        'op3_es' => 'Titi me pregunto',      'op3_ca' => 'Titi em va preguntar',         'op3_zh' => '提提问我',
        'op4_es' => 'Dakiti',                'op4_ca' => 'Dakiti',                       'op4_zh' => '达基提',
        'dificultad'     => 'medio',
    ],
    [
        'categoria_id'   => 2,
        'tipo'           => 'emoji',
        'emojis'         => '🌧️👩🏼‍🦰💔',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Amargura',                  'op1_ca' => 'Amargor',                   'op1_zh' => '苦涩',
        'op2_es' => 'Mientras me curo del cora', 'op2_ca' => 'Mentre em curo el cor',     'op2_zh' => '治愈我的心',
        'op3_es' => 'Provenza',                  'op3_ca' => 'Provenca',                  'op3_zh' => '普罗旺斯',
        'op4_es' => 'Bichota',                   'op4_ca' => 'Bichota',                   'op4_zh' => '比乔塔',
        'dificultad'     => 'dificil',
    ],
    [
        'categoria_id'   => 2,
        'tipo'           => 'emoji',
        'emojis'         => '💔🎸🇪🇸',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Corazon Partido',   'op1_ca' => 'Cor Trencat',       'op1_zh' => '破碎的心',
        'op2_es' => 'Corazon Espinado',  'op2_ca' => 'Cor Espinat',       'op2_zh' => '刺痛的心',
        'op3_es' => 'Despecha',          'op3_ca' => 'Despitada',         'op3_zh' => '报复',
        'op4_es' => 'No Me Compares',    'op4_ca' => 'No Em Comparis',    'op4_zh' => '别拿我比较',
        'dificultad'     => 'medio',
    ],
    [
        'categoria_id'   => 2,
        'tipo'           => 'emoji',
        'emojis'         => '🐺💰🎤',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Las mujeres no lloran', 'op1_ca' => 'Les dones no ploren', 'op1_zh' => '女人不哭泣',
        'op2_es' => 'Waka Waka',             'op2_ca' => 'Waka Waka',           'op2_zh' => '瓦卡瓦卡',
        'op3_es' => 'Las de la intuicion',   'op3_ca' => 'Les de la intuicio',  'op3_zh' => '凭直觉的女人',
        'op4_es' => 'Loca',                  'op4_ca' => 'Boja',                'op4_zh' => '疯狂',
        'dificultad'     => 'facil',
    ],

    // ══════════════════════════════
    // categoria 3 — famosos
    // ══════════════════════════════
    [
        'categoria_id'   => 3,
        'tipo'           => 'emoji',
        'emojis'         => '⚽🐐🇦🇷',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Messi',    'op1_ca' => 'Messi',    'op1_zh' => '梅西',
        'op2_es' => 'Maradona', 'op2_ca' => 'Maradona', 'op2_zh' => '马拉多纳',
        'op3_es' => 'Ronaldo',  'op3_ca' => 'Ronaldo',  'op3_zh' => '罗纳尔多',
        'op4_es' => 'Neymar',   'op4_ca' => 'Neymar',   'op4_zh' => '内马尔',
        'dificultad'     => 'facil',
    ],
    [
        'categoria_id'   => 3,
        'tipo'           => 'emoji',
        'emojis'         => '🍎💻📱',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Steve Jobs',        'op1_ca' => 'Steve Jobs',        'op1_zh' => '史蒂夫·乔布斯',
        'op2_es' => 'Bill Gates',        'op2_ca' => 'Bill Gates',        'op2_zh' => '比尔·盖茨',
        'op3_es' => 'Elon Musk',         'op3_ca' => 'Elon Musk',         'op3_zh' => '埃隆·马斯克',
        'op4_es' => 'Mark Zuckerberg',   'op4_ca' => 'Mark Zuckerberg',   'op4_zh' => '马克·扎克伯格',
        'dificultad'     => 'medio',
    ],
    [
        'categoria_id'   => 3,
        'tipo'           => 'emoji',
        'emojis'         => '🍑👱‍♀️💄',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Kim Kardashian', 'op1_ca' => 'Kim Kardashian', 'op1_zh' => '金·卡戴珊',
        'op2_es' => 'Kylie Jenner',   'op2_ca' => 'Kylie Jenner',   'op2_zh' => '凯莉·詹纳',
        'op3_es' => 'Paris Hilton',   'op3_ca' => 'Paris Hilton',   'op3_zh' => '帕丽斯·希尔顿',
        'op4_es' => 'Cardi B',        'op4_ca' => 'Cardi B',        'op4_zh' => '卡迪·B',
        'dificultad'     => 'dificil',
    ],
    [
        'categoria_id'   => 3,
        'tipo'           => 'emoji',
        'emojis'         => '🪨💪🏻👨🏼‍🦲',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Dwayne Johnson',        'op1_ca' => 'Dwayne Johnson',        'op1_zh' => '道恩·强森',
        'op2_es' => 'Vin Diesel',            'op2_ca' => 'Vin Diesel',            'op2_zh' => '范·迪塞尔',
        'op3_es' => 'Arnold Schwarzenegger', 'op3_ca' => 'Arnold Schwarzenegger', 'op3_zh' => '阿诺德·施瓦辛格',
        'op4_es' => 'Jason Statham',         'op4_ca' => 'Jason Statham',         'op4_zh' => '杰森·斯坦森',
        'dificultad'     => 'medio',
    ],

    // ══════════════════════════════
    // categoria 4 — modo sorpresa
    // ══════════════════════════════
    [
        'categoria_id'   => 4,
        'tipo'           => 'video',
        'emojis'         => null,
        'media_url'      => 'assets/img/videos/messi_penal.mp4',
        'pregunta_es'    => 'Que paso en este momento?',
        'pregunta_ca'    => 'Que va passar en aquest moment?',
        'pregunta_zh'    => '这一刻发生了什么？',
        'opcion_correcta'=> 1,
        'op1_es' => 'Golazo al palo izquierdo',  'op1_ca' => 'Golazo al pal esquerre',   'op1_zh' => '射中左柱进球',
        'op2_es' => 'El portero lo paro',         'op2_ca' => 'El porter ho va aturar',   'op2_zh' => '门将扑出',
        'op3_es' => 'Tiro fuera por arriba',      'op3_ca' => 'Tir fora per dalt',        'op3_zh' => '打高射失',
        'op4_es' => 'Lo repitieron por invasion', 'op4_ca' => 'Es va repetir per invasio','op4_zh' => '因侵入重罚',
        'dificultad'     => 'facil',
    ],
    [
        'categoria_id'   => 4,
        'tipo'           => 'imagen',
        'emojis'         => null,
        'media_url'      => 'assets/img/siluetas/goku.png',
        'pregunta_es'    => 'Quien es este personaje?',
        'pregunta_ca'    => 'Qui es aquest personatge?',
        'pregunta_zh'    => '这个角色是谁？',
        'opcion_correcta'=> 2,
        'op1_es' => 'Gocu',    'op1_ca' => 'Gocu',    'op1_zh' => '悟空（错误拼写）',
        'op2_es' => 'Goku',    'op2_ca' => 'Goku',    'op2_zh' => '孙悟空',
        'op3_es' => 'Vegetta', 'op3_ca' => 'Vegetta', 'op3_zh' => '贝吉塔（错误拼写）',
        'op4_es' => 'Naruto',  'op4_ca' => 'Naruto',  'op4_zh' => '鸣人',
        'dificultad'     => 'medio',
    ],
    [
        'categoria_id'   => 4,
        'tipo'           => 'audio',
        'emojis'         => null,
        'media_url'      => 'assets/audio/despacito.mp3',
        'pregunta_es'    => 'Que cancion es esta?',
        'pregunta_ca'    => 'Quina canco es aquesta?',
        'pregunta_zh'    => '这是哪首歌？',
        'opcion_correcta'=> 1,
        'op1_es' => 'Despacito — Luis Fonsi',    'op1_ca' => 'Despacito — Luis Fonsi',    'op1_zh' => '慢慢来 — 路易斯·冯西',
        'op2_es' => 'Con Calma — Daddy Yankee',  'op2_ca' => 'Con Calma — Daddy Yankee',  'op2_zh' => '保持冷静 — 老爹扬基',
        'op3_es' => 'Danza Kuduro — Don Omar',   'op3_ca' => 'Danza Kuduro — Don Omar',   'op3_zh' => '库杜罗舞 — 堂·奥马尔',
        'op4_es' => 'Bailando — Enrique Iglesias','op4_ca' => 'Ballant — Enrique Iglesias','op4_zh' => '跳舞 — 恩里克·伊格莱西亚斯',
        'dificultad'     => 'facil',
    ],
    [
        'categoria_id'   => 4,
        'tipo'           => 'emoji',
        'emojis'         => '🐰🌴🇵🇷🎤',
        'media_url'      => null,
        'pregunta_es'    => null,
        'pregunta_ca'    => null,
        'pregunta_zh'    => null,
        'opcion_correcta'=> 1,
        'op1_es' => 'Bad Bunny', 'op1_ca' => 'Bad Bunny', 'op1_zh' => '坏痞兔',
        'op2_es' => 'Eminem',    'op2_ca' => 'Eminem',    'op2_zh' => '阿姆',
        'op3_es' => 'Maluma',    'op3_ca' => 'Maluma',    'op3_zh' => '马鲁马',
        'op4_es' => 'Ozuna',     'op4_ca' => 'Ozuna',     'op4_zh' => '奥苏纳',
        'dificultad'     => 'facil',
    ],
    [
        'categoria_id'   => 4,
        'tipo'           => 'codigo',
        'emojis'         => 'String frase = "Hola Mundo";' . "\n" . 'System.out.println(frase.length());',
        'media_url'      => null,
        'pregunta_es'    => 'Cuantos caracteres tiene "Hola Mundo"?',
        'pregunta_ca'    => 'Quants caracters te "Hola Mundo"?',
        'pregunta_zh'    => '"Hola Mundo"有几个字符？',
        'opcion_correcta'=> 1,
        'op1_es' => '10 — cuenta el espacio', 'op1_ca' => '10 — compta lespai',    'op1_zh' => '10 — 包含空格',
        'op2_es' => '9 — olvidan el espacio', 'op2_ca' => '9 — obliden lespai',    'op2_zh' => '9 — 忘了空格',
        'op3_es' => '8',                       'op3_ca' => '8',                     'op3_zh' => '8',
        'op4_es' => 'Error de compilacion',    'op4_ca' => 'Error de compilacio',   'op4_zh' => '编译错误',
        'dificultad'     => 'dificil',
    ],
];

// inserto cada reto usando prepare y bindvalue como indica el profe en d06
foreach ($retos as $reto) {
    $stmt = $db->prepare("
        INSERT INTO retos (
            categoria_id, tipo, emojis, media_url,
            pregunta_es, pregunta_ca, pregunta_zh,
            opcion_correcta,
            op1_es, op1_ca, op1_zh,
            op2_es, op2_ca, op2_zh,
            op3_es, op3_ca, op3_zh,
            op4_es, op4_ca, op4_zh,
            dificultad
        ) VALUES (
            :categoria_id, :tipo, :emojis, :media_url,
            :pregunta_es, :pregunta_ca, :pregunta_zh,
            :opcion_correcta,
            :op1_es, :op1_ca, :op1_zh,
            :op2_es, :op2_ca, :op2_zh,
            :op3_es, :op3_ca, :op3_zh,
            :op4_es, :op4_ca, :op4_zh,
            :dificultad
        )
    ");

    $stmt->bindValue(':categoria_id',    $reto['categoria_id'],    SQLITE3_INTEGER);
    $stmt->bindValue(':tipo',            $reto['tipo'],            SQLITE3_TEXT);
    $stmt->bindValue(':emojis',          $reto['emojis'],          SQLITE3_TEXT);
    $stmt->bindValue(':media_url',       $reto['media_url'],       SQLITE3_TEXT);
    $stmt->bindValue(':pregunta_es',     $reto['pregunta_es'],     SQLITE3_TEXT);
    $stmt->bindValue(':pregunta_ca',     $reto['pregunta_ca'],     SQLITE3_TEXT);
    $stmt->bindValue(':pregunta_zh',     $reto['pregunta_zh'],     SQLITE3_TEXT);
    $stmt->bindValue(':opcion_correcta', $reto['opcion_correcta'], SQLITE3_INTEGER);
    $stmt->bindValue(':op1_es',          $reto['op1_es'],          SQLITE3_TEXT);
    $stmt->bindValue(':op1_ca',          $reto['op1_ca'],          SQLITE3_TEXT);
    $stmt->bindValue(':op1_zh',          $reto['op1_zh'],          SQLITE3_TEXT);
    $stmt->bindValue(':op2_es',          $reto['op2_es'],          SQLITE3_TEXT);
    $stmt->bindValue(':op2_ca',          $reto['op2_ca'],          SQLITE3_TEXT);
    $stmt->bindValue(':op2_zh',          $reto['op2_zh'],          SQLITE3_TEXT);
    $stmt->bindValue(':op3_es',          $reto['op3_es'],          SQLITE3_TEXT);
    $stmt->bindValue(':op3_ca',          $reto['op3_ca'],          SQLITE3_TEXT);
    $stmt->bindValue(':op3_zh',          $reto['op3_zh'],          SQLITE3_TEXT);
    $stmt->bindValue(':op4_es',          $reto['op4_es'],          SQLITE3_TEXT);
    $stmt->bindValue(':op4_ca',          $reto['op4_ca'],          SQLITE3_TEXT);
    $stmt->bindValue(':op4_zh',          $reto['op4_zh'],          SQLITE3_TEXT);
    $stmt->bindValue(':dificultad',      $reto['dificultad'],      SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "reto insertado: " . ($reto['emojis'] ?? $reto['tipo']) . "<br>";
    } else {
        echo "error al insertar reto<br>";
    }
}

// cierro la conexion
$db->close();

echo "<br>proceso completado — todos los retos insertados correctamente";