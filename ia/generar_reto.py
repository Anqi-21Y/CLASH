#!/usr/bin/env python3
"""
generar_retos.py — genera preguntas de emojis para el juego Clash
usa la API de Groq y las guarda en la base de datos
"""

#!/usr/bin/env python3
"""
generar_reto.py — genera retos emoji con IA (Groq)

uso:
    python generar_reto.py
    python generar_reto.py --categoria 1
    python generar_reto.py --cantidad 8
"""

# librerías
import sqlite3
import json
import urllib.request
import urllib.error
import os
from dotenv import load_dotenv
load_dotenv()

import argparse
import time


# Configuración

# Reemplaza el valor por defecto con tu API Key real de Groq
API_KEY = os.environ.get('GROQ_API_KEY')

# Ruta a la base de datos
DB_PATH = os.path.join(os.path.dirname(__file__), '..', 'database', 'clash.db')

# Categorías del juego
CATEGORIAS = {
    1: 'peliculas',
    2: 'canciones',
    3: 'famosos',
    4: 'modo sorpresa'
}

CANTIDAD_DEFAULT = 4


# Base de datos

def conectar_db():
    # conectar clash.db
    conn = sqlite3.connect(DB_PATH)
    conn.row_factory = sqlite3.Row
    return conn


def obtener_emojis_existentes(conn, categoria_id):
    # emojis ya usados
    cursor = conn.cursor()
    cursor.execute("SELECT emojis FROM retos WHERE categoria_id = ?", (categoria_id,))
    return {row['emojis'] for row in cursor.fetchall()}


def obtener_opciones_existentes(conn, categoria_id):
    # respuestas correctas ya usadas
    cursor = conn.cursor()
    cursor.execute(
        "SELECT op1_es, op2_es, op3_es, op4_es, opcion_correcta FROM retos WHERE categoria_id = ?",
        (categoria_id,)
    )
    respuestas = set()
    for row in cursor.fetchall():
        idx = row['opcion_correcta']
        opciones = [row['op1_es'], row['op2_es'], row['op3_es'], row['op4_es']]
        correcta = opciones[idx - 1]
        if correcta:
            respuestas.add(correcta.lower().strip())
    return respuestas


def insertar_reto(conn, categoria_id, reto):
    # Inserta la pregunta generada por la IA en la tabla 'retos'
    cursor = conn.cursor()
    cursor.execute("""
        INSERT INTO retos (
            categoria_id, tipo, emojis, opcion_correcta,
            op1_es, op1_ca, op1_zh,
            op2_es, op2_ca, op2_zh,
            op3_es, op3_ca, op3_zh,
            op4_es, op4_ca, op4_zh,
            dificultad
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    """, (
        categoria_id, 'emoji',
        reto['emojis'], reto['opcion_correcta'],
        reto['op1_es'], reto['op1_ca'], reto['op1_zh'],
        reto['op2_es'], reto['op2_ca'], reto['op2_zh'],
        reto['op3_es'], reto['op3_ca'], reto['op3_zh'],
        reto['op4_es'], reto['op4_ca'], reto['op4_zh'],
        'medio'
    ))
    conn.commit()  # Guarda los cambios en el archivo .db


# Generación con IA─

def generar_reto_ia(categoria_nombre, emojis_existentes, opciones_existentes):
    # Prepara la lista de respuestas existentes para incluirla en el prompt
    existentes_texto = ''
    if opciones_existentes:
        lista = ', '.join(sorted(opciones_existentes)[:30])
        existentes_texto = f"\nIMPORTANTE: Estas respuestas YA EXISTEN, NO las repitas: {lista}\n"

    # Mensaje que se envía a la IA — le pedimos solo JSON, sin texto extra
    prompt = f"""Genera un reto para un juego de emojis sobre la categoría: {categoria_nombre}.
{existentes_texto}
debe incluir:
- emojis (2 a 4)
- 1 correcta + 3 falsas
- traducciones (es, ca, zh)

responde SOLO json:
{{
    "emojis": "🎬🦁👑",
    "opcion_correcta": 1,
    "op1_es": "El rey leon",
    "op1_ca": "El rei lleó",
    "op1_zh": "狮子王",
    "op2_es": "Bambi",
    "op2_ca": "Bambi",
    "op2_zh": "小鹿斑比",
    "op3_es": "Dumbo",
    "op3_ca": "Dumbo",
    "op3_zh": "小飞象",
    "op4_es": "Pinocho",
    "op4_ca": "Pinotxo",
    "op4_zh": "木偶奇遇记"
}}"""

    datos = {
        "model": "llama-3.3-70b-versatile",  # Modelo gratuito disponible en Groq
        "max_tokens": 1000,
        "temperature": 0.9,  # Valor alto = respuestas más variadas y creativas
        "messages": [
            {
                "role": "system",
                "content": "Eres un generador de preguntas para un juego. Respondes SOLO con JSON válido, sin texto adicional ni bloques de código."
            },
            {
                "role": "user",
                "content": prompt
            }
        ]
    }

    # Cabeceras de autenticación — Groq usa Bearer token
    cabeceras = {
        'Content-Type': 'application/json',
        'Authorization': f'Bearer {API_KEY}',
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept': 'application/json'
    }

    try:
        peticion = urllib.request.Request(
            'https://api.groq.com/openai/v1/chat/completions',
            data=json.dumps(datos).encode('utf-8'),
            headers=cabeceras,
            method='POST'
        )
        with urllib.request.urlopen(peticion, timeout=30) as respuesta:
            resultado = json.loads(respuesta.read().decode('utf-8'))
            texto = resultado['choices'][0]['message']['content'].strip()

            # A veces la IA envuelve el JSON en ```json ... ``` — lo limpiamos
            if texto.startswith('```'):
                texto = texto.split('```')[1]
                if texto.startswith('json'):
                    texto = texto[4:]
                texto = texto.strip()

            return json.loads(texto)

    except urllib.error.HTTPError as e:
        print(f"  ✗ Error HTTP {e.code}: {e.read().decode()}")
        return None
    except urllib.error.URLError as e:
        print(f"  ✗ Error de conexión: {e}")
        return None
    except json.JSONDecodeError as e:
        print(f"  ✗ La IA no devolvió un JSON válido: {e}")
        return None


# Comprobación de duplicados

def es_duplicado(reto, emojis_existentes, opciones_existentes):
    # Comprobación 1: ¿los emojis ya existen en la BD?
    if reto['emojis'] in emojis_existentes:
        return True, f"emojis '{reto['emojis']}' ya existen"

    # Comprobación 2: ¿la respuesta correcta ya existe en la BD?
    idx = reto['opcion_correcta']
    respuesta = reto.get(f"op{idx}_es", '').lower().strip()
    if respuesta in opciones_existentes:
        return True, f"respuesta '{respuesta}' ya existe"

    return False, None


# Generación por categoría

def generar_para_categoria(conn, categoria_id, categoria_nombre, cantidad):
    print(f"\n{'─'*50}")
    print(f"Categoría: {categoria_nombre} (id={categoria_id})")
    print(f"{'─'*50}")

    emojis_existentes   = obtener_emojis_existentes(conn, categoria_id)
    opciones_existentes = obtener_opciones_existentes(conn, categoria_id)
    print(f"  Preguntas existentes en BD: {len(emojis_existentes)}")

    generados    = 0
    intentos     = 0
    max_intentos = cantidad * 3  # Máximo de reintentos = 3 veces el objetivo

    while generados < cantidad and intentos < max_intentos:
        intentos += 1
        print(f"\n  [{generados+1}/{cantidad}] Generando... (intento {intentos})")

        reto = generar_reto_ia(categoria_nombre, emojis_existentes, opciones_existentes)

        if not reto:
            print("  ✗ La IA no devolvió datos válidos, reintentando...")
            time.sleep(2)
            continue

        duplicado, motivo = es_duplicado(reto, emojis_existentes, opciones_existentes)
        if duplicado:
            print(f"  ⚠ Duplicado detectado ({motivo}), reintentando...")
            time.sleep(1)
            continue

        # Todo correcto — insertamos en la base de datos
        insertar_reto(conn, categoria_id, reto)

        # Actualizamos los sets locales para evitar duplicados en el siguiente intento
        emojis_existentes.add(reto['emojis'])
        idx = reto['opcion_correcta']
        opciones_existentes.add(reto.get(f"op{idx}_es", '').lower().strip())

        generados += 1
        print(f"  ✓ Insertado: {reto['emojis']} → {reto.get(f'op{idx}_es', '?')}")

        if generados < cantidad:
            time.sleep(1)  # Pausa breve para no superar el límite de la API

    if generados < cantidad:
        print(f"\n  ⚠ Solo se generaron {generados}/{cantidad} preguntas")
    else:
        print(f"\n  ✓ {generados} preguntas generadas correctamente")

    return generados


# Función principal─

def main():
    # Configuramos los argumentos que acepta el script desde el terminal
    parser = argparse.ArgumentParser(
        description='Genera preguntas emoji para Clash usando Groq AI'
    )
    parser.add_argument(
        '--categoria', type=int, choices=[1, 2, 3, 4],
        help='ID de la categoría. Sin este argumento, genera para todas.'
    )
    parser.add_argument(
        '--cantidad', type=int, default=CANTIDAD_DEFAULT,
        help=f'Número de preguntas por categoría (default: {CANTIDAD_DEFAULT}, máximo: 20)'
    )
    args = parser.parse_args()

    # Verificamos que la API Key esté configurada antes de continuar
    if not API_KEY or API_KEY == 'QQQQQQQQQ':
        print("✗ ERROR: No se encontró la API Key de Groq.")
        print("  Edita la línea 'API_KEY = ...' y pon tu key real (empieza por gsk_...)")
        return

    if args.cantidad < 1 or args.cantidad > 20:
        print("✗ ERROR: --cantidad debe estar entre 1 y 20")
        return

    print("=" * 50)
    print("  CLASH — Generador de preguntas emoji (Groq)")
    print("=" * 50)

    conn  = conectar_db()
    total = 0

    if args.categoria:
        # Genera solo para la categoría indicada
        nombre = CATEGORIAS[args.categoria]
        total  = generar_para_categoria(conn, args.categoria, nombre, args.cantidad)
    else:
        # Sin --categoria, genera para las 4 categorías
        for cat_id, cat_nombre in CATEGORIAS.items():
            total += generar_para_categoria(conn, cat_id, cat_nombre, args.cantidad)

    conn.close()

    print(f"\n{'='*50}")
    print(f"  Proceso completado — {total} preguntas insertadas en total")
    print(f"{'='*50}\n")


# Punto de entrada — solo se ejecuta si llamamos al archivo directamente
if __name__ == '__main__':
    main()