// ── Diccionario JS de Clash ───────────────────────────────────────────────────
// textos que se generan dinámicamente con JavaScript:
// barra animada superior (topbar) — usada en main.js
//  slider "Cómo se juega" (4 pasos) — usado en landing.js
//
// El idioma activo se lee de localStorage('idioma_clash'),
// que header.php guarda cada vez que el usuario cambia de idioma.

var CLASH_TEXTOS_JS = {
    es: {
        topbar_msgs: [
            'Adivina emojis y gana el premio',
            'Disponible en español, catalán y chino',
            'Responde en menos de 30 segundos',
            'El más rápido gana más puntos',
            'Películas, canciones, famosos y modo sorpresa',
            'Haz click aquí para unirte a la partida',
        ],
        pasos: [
            { num: '01', titulo: 'Escanea y regístrate',  texto: 'Escanea el QR con tu móvil, escribe tu nombre, elige tu avatar y escribe el PIN que el presentador mostrará en pantalla.',  color: '#FFAAEA' },
            { num: '02', titulo: 'Espera en sala',         texto: 'Verás a los demás jugadores conectarse en tiempo real. Cuando el presentador lo decida, la partida arranca sola.',            color: '#acadff' },
            { num: '03', titulo: '¡Responde rápido!',      texto: 'Tienes menos de 30 segundos para adivinar la respuesta correcta. Cuanto antes respondas, más puntos consigues.',             color: '#fff4a3' },
            { num: '04', titulo: 'Sube al podio',          texto: 'Al terminar todas las rondas verás el ranking final en tu móvil. ¿Serás el más rápido y certero?',                          color: '#d2ffeb' },
        ],

        hints: {
            1: 'Adivina la peli sin una sola letra.',
            2: 'Adivina la canción sin escucharla.',
            3: 'Adivina quién es el famoso sin que te digan su nombre.',
            4: '¿Eres capaz de adivinar lo que ocultan estas pistas?'
        },

        resultados: {
            tiempo_agotado: 'Tiempo agotado',
            correcto: '¡Correcto!',
            incorrecto: 'Incorrecto',
            siguiente: 'Siguiente pregunta en {s}s...',
            cargando: 'Cargando...',
            total: 'Total',
            ranking: 'Ranking',
            fin_partida: '¡Todas respondidas!',
            esperando_jugadores: 'Esperando a los demás...'
        },

        
    },
    ca: {
        topbar_msgs: [
            'Endevina emojis i guanya el premi',
            'Disponible en espanyol, català i xinès',
            'Respon en menys de 30 segons',
            'El més ràpid guanya més punts',
            'Pel·lícules, cançons, famosos i mode sorpresa',
            'Fes clic aquí per unir-te a la partida',
        ],
        pasos: [
            { num: '01', titulo: "Escaneja i registra't",  texto: 'Escaneja el QR amb el teu mòbil, escriu el teu nom, tria el teu avatar i escriu el PIN que el presentador mostrarà en pantalla.',  color: '#FFAAEA' },
            { num: '02', titulo: 'Espera a la sala',        texto: "Veuràs els altres jugadors connectar-se en temps real. Quan el presentador ho decideixi, la partida arrencarà sola.",            color: '#acadff' },
            { num: '03', titulo: 'Respon ràpid!',           texto: 'Tens menys de 30 segons per endevinar la resposta correcta. Com abans responguis, més punts aconsegueixes.',                     color: '#fff4a3' },
            { num: '04', titulo: 'Puja al podi',            texto: 'En acabar totes les rondes veuràs el rànquing final al teu mòbil. Seràs el més ràpid i encertat?',                              color: '#d2ffeb' },
        ],

        hints: {
            1: 'Endevina la pel·lícula sense cap lletra.',
            2: 'Endevina la cançó sense escoltar-la.',
            3: 'Endevina quin famós és sense dir-te el nom.',
            4: 'Ets capaç d’endevinar què amaguen aquestes pistes?'
        },

        resultados: {
            tiempo_agotado: 'Temps esgotat',
            correcto: 'Correcte!',
            incorrecto: 'Incorrecte',
            siguiente: 'Següent pregunta en {s}s...',
            cargando: 'Carregant...',
            total: 'Total',
            ranking: 'Rànquing',
            fin_partida: 'Totes respostes!',
            esperando_jugadores: 'Esperant els altres jugadors...'
        },

        
    },
    zh: {
        topbar_msgs: [
            '猜表情包赢取奖品',
            '支持西班牙语、加泰罗尼亚语和中文',
            '30秒内作答',
            '回答越快得分越多',
            '电影、歌曲、名人和惊喜模式',
            '点击此处加入比赛',
        ],
        pasos: [
            { num: '01', titulo: '扫描并注册',  texto: '用手机扫描二维码，输入你的名字，选择你的头像并输入主持人显示的PIN码。',          color: '#FFAAEA' },
            { num: '02', titulo: '在大厅等待',  texto: '你将看到其他玩家实时加入。当主持人决定开始时，比赛将自动启动。',                  color: '#acadff' },
            { num: '03', titulo: '快速作答！',  texto: '你有不到30秒的时间猜出正确答案。回答得越快，获得的分数越多。',                   color: '#fff4a3' },
            { num: '04', titulo: '登上领奖台',  texto: '所有轮次结束后，你将在手机上看到最终排名。你会是最快、最准确的吗？',              color: '#d2ffeb' },
        ],

        hints: {
            1: '不用文字猜电影',
            2: '不用听声音猜歌曲',
            3: '不说名字猜名人',
            4: '你能猜出这些线索隐藏的内容吗？'
        },

        resultados: {
            tiempo_agotado: '时间到',
            correcto: '回答正确！',
            incorrecto: '回答错误',
            siguiente: '下一题将在 {s} 秒后开始...',
            cargando: '加载中...',
            total: '总分',
            ranking: '排名',
            fin_partida: '所有题目已回答！',
            esperando_jugadores: '正在等待其他玩家...'
        }
    }
};

// idioma activo — lee lo que header.php guarda en localStorage
var _idioma = localStorage.getItem('idioma_clash') || 'es';
var CLASH_T_JS = CLASH_TEXTOS_JS[_idioma] || CLASH_TEXTOS_JS['es'];