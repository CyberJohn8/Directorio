<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["rol"])) {
    $_SESSION["rol"] = "invitado";
}

// ===== CONEXIÓN A LA BASE DE DATOS MYSQL EN INFINITYFREE =====
$host = "sql204.infinityfree.com";
$user = "if0_39714112";
$pass = "MWgk9nZD6H0RIl";  // la nueva contraseña exacta
$dbname = "if0_39714112_himnario";/** */

/*/ ===== CONEXIÓN A LA BASE DE DATOS MYSQL EN INFINITYFREE =====
$host = "localhost";
$user = "root";
$pass = "";  // la nueva contraseña exacta
$dbname = "himnario";/**/

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión con la base de datos: " . $e->getMessage());
}

// Si se pide un himno específico por ID (para AJAX)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $db->prepare("SELECT * FROM himnos WHERE Numero = ?");
    $stmt->execute([$id]);
    $himno = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($himno) {
        echo json_encode($himno, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error" => "Himno no encontrado"]);
    }
    exit;
}

// Obtener todos los himnos
$stmt = $db->query("SELECT Numero, `Primera_linea`, Letra, Tema FROM himnos ORDER BY Numero ASC");
$himnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>








<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="/Menu/iconos/icon2-8.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Himnario Bíblico</title>
    <link rel="stylesheet" href="Himnario.css">

    <style>
        
        /* === FUENTES === */
        @import url('https://fonts.googleapis.com/css2?family=Rakkas&family=Sansation&display=swap');

        @import url('https://fonts.googleapis.com/css2?family=Oleo+Script&display=swap');

        /* === FUENTES === */
        @import url('https://fonts.googleapis.com/css2?family=Rakkas&family=Sansation&family=Oleo+Script&display=swap');

        /* === FUENTES (igual que en Biblia) === */
        @import url('https://fonts.googleapis.com/css2?family=Sansation&family=Oleo+Script&display=swap');

        *{
        font-family: 'Sansation', cursive;
        }

        /* === ESTILO GENERAL === */
        body {
        font-family: 'Sansation', cursive;
        margin: 0;
        padding: 0;
        color: #192E2F;
        background-image: url('iconos/Fonfo_Mapa_Color.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: fixed;
        }


        /* === ENCABEZADO DEL HIMNARIO === */
        /* === ENCABEZADO === */
        /* === ENCABEZADO === */
        header {
        position: relative;   /* clave para posicionar el botón */
        text-align: center;   /* centra el título */
        padding: 10px 20px;
        }

        header h1 {
        font-family: 'Sansation', sans-serif;
        font-size: 38px;
        margin: 0;
        color: #192E2F;
        }

        /* Botón de retorno fijo a la izquierda */
        header .btn-retorno {
        position: absolute;
        left: 20%;
        top: 50%;
        transform: translateY(-50%); /* lo centra verticalmente */
        background-image: url('iconos/Retorno.png');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        background-color: transparent;
        width: 40px;
        height: 40px;
        border: none;
        cursor: pointer;
        transition: transform 0.3s ease;
        }

        header .btn-retorno:hover {
        transform: translateY(-50%) scale(1.2);
        }


        /* Responsive: ocultar el botón en pantallas pequeñas si quieres */
        @media (max-width: 600px) {
        body {
            background-image: url('iconos/Fondo_Mapa_Tlf.png');
        }
        
        
        header .btn-retorno {
            display: none;
        }

        header h1 {
            font-size: 26px;
            margin-top: 15%;
        }
        }


        /* === ENCABEZADO === */
        h1 {
        font-family: 'Sansation', sans-serif;
        font-size: 42px;
        text-align: center;
        color: #192E2F;
        margin: 20px 0;
        }

        /* === CONTENEDOR GENERAL === */
        .himnario-container {
        max-width: 800px;
        margin: 30px auto;
        background: transparent;
        border-radius: 15px;
        /*/box-shadow: 0 4px 12px rgba(0,0,0,0.15);/**/
        padding: 20px;
        backdrop-filter: blur(4px);
        }

        /* === RESPONSIVE === */
        @media (max-width: 600px) {
        .himnario-container {
        width: 80%;
        margin: 10px auto;
        padding: 10px;
        }
        }

        /* === BUSCADOR === */
        .buscador-container {
        position: relative;
        display: flex;
        justify-content: center;
        margin: 20px auto;
        width: 90%;
        max-width: 500px;
        }

        #buscador {
        width: 100%;
        padding: 12px 45px 12px 15px;
        font-size: 16px;
        border-radius: 20px 10px 10px 10px;
        outline: none;
        transition: all 0.3s ease;
        background-color: #637983;
        color: white; /* Texto en color blanco */
        border: 1px solid #637983; /* Borde que combine con el fondo */
        }

        /* Estilo del placeholder (texto de ejemplo) */
        #buscador::placeholder {
        color: #EAE4D5; /* Placeholder en blanco semi-transparente */
        }

        #buscador:focus {
        border-color: #2a3e42;
        box-shadow: 0 0 6px rgba(42,62,66,0.4);
        }

        .Lupa {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        cursor: pointer;
        filter: invert(80%); /* Color más neutral que el negro puro */
        }

        /* === CONTENEDOR SCROLL === */
        #contenedor-lista {
        max-height: 62vh; /* Altura máxima visible */
        overflow-y: auto;
        padding-right: 8px;
        scrollbar-width: thin;
        scrollbar-color: #637983 transparent;
        }

        /* Scrollbar personalizada */
        #contenedor-lista::-webkit-scrollbar {
        width: 8px;
        }
        #contenedor-lista::-webkit-scrollbar-thumb {
        background-color: transparent;
        border-radius: 6px;
        }
        #contenedor-lista::-webkit-scrollbar-track {
        background-color: transparent;
        }

        /* === LISTA DE HIMNOS === */
        #lista-himnos {
        list-style: none;
        padding: 0;
        margin: 0;
        }

        #lista-himnos li {
        background: #637983;
        margin: 15px 0;
        padding: 12px 18px;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);

        
        color: #EAE4D5;
        font-weight: normal;
        border-radius: 10px 10px 20px 10px;
        }

        #lista-himnos li:hover {
        /*background: #A2B0BE;
        color: #192E2F;/**/
        transform: translateX(10px);
        }

        #lista-himnos li strong {
        color: #EAE4D5;
        font-size: 17px;
        font-weight: normal;
        }

        #lista-himnos li small {
        color: #EAE4D5;
        font-size: 14px;
        font-weight: normal;
        }

        /*#lista-himnos li:hover {
        color: #192E2F;
        font-weight: normal;
        }


        /* === MODAL === */
        #modal {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(162, 176, 190, 0.6); /* Color #A2B0BE con 60% de opacidad */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;

        /* Transición de aparición */
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        #modal.active {
        visibility: visible;
        opacity: 1;
        }

        /* === CONTENIDO DEL MODAL === */
        #contenido-modal {
        position: relative;
        background: #EAE4D5;
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        padding: 30px 25px;
        max-width: 800px;
        width: 80%;
        max-height: 90vh;
        /*overflow-y: auto;/**/
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        animation: slideUp 0.35s ease forwards;


        scrollbar-color: #637983 transparent;
        }

        /* === ENCABEZADO DEL MODAL === */
        #contenido-modal h2 {
        font-family: 'Sansation', sans-serif;
        margin: 0 0 10px 0;
        font-size: 28px;
        color: #2a3e42;
        text-align: left;
        }

        /* Subinformación */
        #contenido-modal p {
        font-size: 16px;
        color: #333;
        margin: 4px 0;

        
        text-align: right;
        }

        /* === LETRA DEL HIMNO === */
        #contenido-modal pre {
        white-space: pre-wrap;
        line-height: 1.7;
        font-size: 18px;
        /*background: rgba(255, 255, 255, 0.6);/**/
        padding: 15px;
        border-radius: 10px;
        /*border: 1px solid rgba(0,0,0,0.1);/***/
        margin-top: 15px;
        color: #1c1c1c;
        font-family: 'Sansation', cursive;
        overflow-y: auto;
        max-height: 240px;

        
        text-align: center;
        }

        /* === BOTÓN DE CIERRE === */
        #contenido-modal button {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 38px;
        height: 38px;
        border: none;
        background: transparent;
        color: rgba(42,62,66,0.9);
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        }

        #contenido-modal button:hover {
        /*background: #1e2e30;/**/
        transform: rotate(90deg);
        }

        /* === ANIMACIONES === */
        @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* === RESPONSIVE === */
        @media (max-width: 600px) {
        #contenido-modal {
            padding: 20px;
        }

        #contenido-modal h2 {
            font-size: 22px;
        }

        #contenido-modal pre {
            font-size: 16px;
            max-height: 250px;
        }

        #contenido-modal button {
            width: 32px;
            height: 32px;
            font-size: 18px;
        }
        }

    </style>
</head>
<body>

    <!-- Íconos visibles solo en escritorio -->
    <!-- CONTENEDOR DEL MENÚ -->
    <nav class="menu-nav">
        <button class="icon-btn btn-menu" onclick="toggleSidebarMenu()" title="Menú">
            <img src="iconos/Menu.png" alt="Menú">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://directorio.wasmer.app//Menu/index.php'" title="Inicio">
            <img src="iconos/Inicio.png" alt="Inicio">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://directorio.wasmer.app//Menu/Submenu.php'" title="Ubicación">
            <img src="iconos/ubicaciones.png" alt="Ubicación">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://directorio.wasmer.app//Menu/Eventos/index.php'" title="Eventos">
            <img src="iconos/eventos.png" alt="Eventos">
        </button>
        <?php if ($_SESSION["rol"] !== "invitado") : ?>
            <button class="icon-btn solo-pc" onclick="location.href='https://directorio.wasmer.app//Menu/Donaciones/index.php'" title="Donaciones">
                <img src="iconos/donation.png" alt="Donaciones">
            </button>
        <?php endif; ?>
        <button class="icon-btn solo-pc" onclick="location.href='https://directorio.wasmer.app//Menu/Material/index.php'" title="Material Literario">
            <img src="iconos/material.png" alt="Material Literario">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://directorio.wasmer.app//Menu/LiteraturaBiblica/index.php'" title="Biblia">
            <img src="iconos/Biblia.png" alt="Estudio Bíblico">
        </button>
        <button class="icon-btn btn-sesion" onclick="location.href='https://directorio.wasmer.app//Menu/logout.php'" title="Cerrar Sesión">
            <img src="iconos/Sesion.png" alt="Cerrar Sesión">
        </button>
    </nav>


    <!-- Menú emergente (sidebar) para celular -->
    <div class="sidebar mobile-only" id="sidebarMenu">
        <h2>Menú</h2>
        <a href="https://directorio.wasmer.app//Menu/index.php">Inicio</a>
        <a href="https://directorio.wasmer.app//Menu/Submenu.php">Ubicación</a>
        <a href="https://directorio.wasmer.app//Menu/Eventos/index.php">Eventos</a>
        <?php if ($_SESSION["rol"] !== "invitado") : ?>
            <a href="https://directorio.wasmer.app//Menu/Donaciones/index.php">Donaciones</a>
        <?php endif; ?>
        <a href="https://directorio.wasmer.app//Menu/Material/index.php">Material Literario</a>
        <a href="https://directorio.wasmer.app//Menu/LiteraturaBiblica/index.php">Estudio Bíblico</a>
        <?php if ($_SESSION["rol"] !== "invitado") : ?>
            <a href="https://directorio.wasmer.app//Cuenta/index.php">Gestionar Sesión</a>
        <?php endif; ?>
        

        <a href="https://directorio.wasmer.app//Menu/Copiryt.php">Acerca de</a>

        <button class="close-btn" onclick="toggleSidebarMenu()">Cerrar</button>
    </div>

    <!-- Fondo oscuro -->
    <div class="overlay" id="overlay" onclick="toggleSidebarMenu()"></div>

    <script>
        function toggleSidebarMenu() {
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
    </script>












    <header>
        <button onclick="window.history.back()" class="btn-retorno"></button>
        <h1 style="font-family: 'Oleo Script', cursive;">Himnario Bíblico</h1>
    </header>

    <div class="himnario-container">
        <div class="buscador-container">
            <input type="text" id="buscador" placeholder="Buscar por número, título o tema..."> 
            <img class="Lupa" src="Lupa.png" alt="Buscar">
        </div>

        <div id="contenedor-lista">
            <ul id="lista-himnos">
            <?php foreach ($himnos as $row): ?>
                <li onclick="mostrarHimno(<?= $row['Numero'] ?>)">
                    <strong><?= $row['Numero'] ?> - <?= htmlspecialchars($row['Primera_linea']) ?></strong><br>
                    <small><?= htmlspecialchars($row['Tema']) ?></small>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        document.getElementById("buscador").addEventListener("input", function() {
        const filtro = this.value.toLowerCase().trim();
        const himnos = document.querySelectorAll("#lista-himnos li");
        let resultados = 0;

        himnos.forEach(li => {
            const texto = li.textContent.toLowerCase();
            if (texto.includes(filtro)) {
            li.style.display = "block";
            resultados++;
            } else {
            li.style.display = "none";
            }
        });

        // Si no hay resultados, mostrar mensaje
        if (!document.getElementById("noResultados")) {
            const msg = document.createElement("p");
            msg.id = "noResultados";
            msg.style.textAlign = "center";
            msg.style.color = "#555";
            msg.style.display = "none";
            msg.textContent = "❌ No se encontraron himnos.";
            document.getElementById("contenedor-lista").appendChild(msg);
        }
        document.getElementById("noResultados").style.display = resultados === 0 ? "block" : "none";
        });
    </script>




    <!-- Modal -->
    <div id="modal">
        <div id="contenido-modal">
            <button onclick="cerrarModal()">✖</button>
            <h2 id="titulo"></h2>
            <p><strong>Número:</strong> <span id="numero"></span></p>
            <p><strong>Tema:</strong> <span id="autor"></span></p>
            <pre id="letra"></pre>


            <!-- CONTROL PERSONALIZADO DE TAMAÑO DE LETRA -->
            <div class="grupo-campo control-tamano-personalizado">
                <label for="tamano-letra" style="color: #EAE4D5; display: none">
                    Tamaño letra: <span id="valor-tamano">16</span>px
                </label><!--  -->
                <div class="control-deslizador">
                    <input type="range" 
                        id="tamano-letra" 
                        min="12" 
                        max="30" 
                        value="16"
                        step="1"
                        class="deslizador-tamano"><!-- value original = 16 -->
                    <div class="marcas-tamano">
                        <span style="color: #EAE4D5">12</span>
                        <span style="color: #EAE4D5">16</span>
                        <span style="color: #EAE4D5">20</span>
                        <span style="color: #EAE4D5">24</span>
                        <span style="color: #EAE4D5">30</span>
                    </div>
                </div>
                <!--<div class="botones-rapidos">
                    <button type="button" class="btn-rapido" onclick="establecerTamanoRapido(14)">A</button>
                    <button type="button" class="btn-rapido" onclick="establecerTamanoRapido(16)">A</button>
                    <button type="button" class="btn-rapido" onclick="establecerTamanoRapido(18)">A</button>
                    <button type="button" class="btn-rapido" onclick="establecerTamanoRapido(20)">A</button>
                    <button type="button" class="btn-rapido" onclick="establecerTamanoRapido(22)">A</button>
                </div>-->
            </div>
        </div>


        

        <script>
            // === CONTROL PERSONALIZADO DE TAMAÑO DE LETRA ===
            let tamanoLetraActual = 16; // Tamaño por defecto

            // Inicializar el control deslizante
            document.addEventListener('DOMContentLoaded', function() {
                const deslizador = document.getElementById('tamano-letra');
                const valorDisplay = document.getElementById('valor-tamano');
                
                // Cargar preferencia guardada
                const tamanoGuardado = localStorage.getItem('tamanoLetraPersonalizado');
                if (tamanoGuardado) {
                    tamanoLetraActual = parseInt(tamanoGuardado);
                    deslizador.value = tamanoLetraActual;
                    valorDisplay.textContent = tamanoLetraActual;
                    aplicarTamanoLetra(tamanoLetraActual);
                }
                
                // Evento para el deslizador
                deslizador.addEventListener('input', function() {
                    const nuevoTamano = parseInt(this.value);
                    valorDisplay.textContent = nuevoTamano;
                    aplicarTamanoLetra(nuevoTamano);
                });
                
                // Eventos para las marcas
                const marcas = document.querySelectorAll('.marcas-tamano span');
                marcas.forEach(marca => {
                    marca.addEventListener('click', function() {
                        const tamano = parseInt(this.textContent);
                        deslizador.value = tamano;
                        valorDisplay.textContent = tamano;
                        aplicarTamanoLetra(tamano);
                    });
                });
                
                // Aplicar tamaño inicial
                aplicarTamanoLetra(tamanoLetraActual);
            });

            // Función para aplicar el tamaño de letra - CORREGIDA
            function aplicarTamanoLetra(tamano) {
                const letra = document.getElementById('letra');
                if (letra) {
                    // Aplicar directamente al elemento pre (CORRECCIÓN PRINCIPAL)
                    letra.style.fontSize = tamano + 'px';
                    
                    // Actualizar estado visual de botones rápidos
                    actualizarBotonesRapidos(tamano);
                    
                    // Guardar preferencia
                    tamanoLetraActual = tamano;
                    localStorage.setItem('tamanoLetraPersonalizado', tamano);
                }
            }

            // Función para botones rápidos
            function establecerTamanoRapido(tamano) {
                const deslizador = document.getElementById('tamano-letra');
                const valorDisplay = document.getElementById('valor-tamano');
                
                deslizador.value = tamano;
                valorDisplay.textContent = tamano;
                aplicarTamanoLetra(tamano);
            }

            // Actualizar estado visual de botones rápidos
            function actualizarBotonesRapidos(tamanoActual) {
                const botones = document.querySelectorAll('.btn-rapido');
                botones.forEach(boton => {
                    // Extraer el tamaño del onclick
                    const match = boton.getAttribute('onclick').match(/\((\d+)\)/);
                    if (match) {
                        const tamanoBoton = parseInt(match[1]);
                        if (tamanoBoton === tamanoActual) {
                            boton.classList.add('activo');
                        } else {
                            boton.classList.remove('activo');
                        }
                    }
                });
            }

            // Función para aplicar el tamaño cuando se muestran nuevos himnos
            function aplicarTamanoAContenidoNuevo() {
                aplicarTamanoLetra(tamanoLetraActual);
            }
        </script>

        <style>
            /* === CONTROL PERSONALIZADO DE TAMAÑO DE LETRA === */
            .control-tamano-personalizado {
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid #EAE4D5;

                background: #637983;
                border-radius: 15px;
                color: #EAE4D5;
                padding-right: 20px;
                padding-left: 20px;
            }

            .control-deslizador {
                position: relative;
                margin: 15px 0 10px 0;
            }

            .deslizador-tamano {
                width: 100%;
                height: 6px;
                border-radius: 3px;
                background: #EAE4D5;
                outline: none;
                -webkit-appearance: none;
            }

            .deslizador-tamano::-webkit-slider-thumb {
                -webkit-appearance: none;
                width: 20px;
                height: 10px;
                border-radius: 50%;
                background: #637983;
                cursor: pointer;
                border: 2px solid white;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }

            .deslizador-tamano::-moz-range-thumb {
                width: 20px;
                height: 10px;
                border-radius: 50%;
                background: #637983;
                cursor: pointer;
                border: 2px solid white;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }

            .marcas-tamano {
                display: flex;
                justify-content: space-between;
                margin-top: 8px;
                font-size: 11px;
                color: #637983;
            }

            .marcas-tamano span {
                cursor: pointer;
                padding: 2px 4px;
                border-radius: 3px;
                transition: background-color 0.2s;
            }

            .marcas-tamano span:hover {
                background-color: #EAE4D5;
            }/* * */

            .botones-rapidos {
                display: flex;
                justify-content: space-between;
                gap: 5px;
                margin-top: 10px;
            }

            .btn-rapido {
                flex: 1;
                padding: 6px 2px;
                border: 1px solid #637983;
                background: white;
                color: #384A49;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
                transition: all 0.3s ease;
                min-height: 30px;
                font-size: 12px;
            }

            .btn-rapido:hover {
                background: #637983;
                color: white;
                transform: translateY(-1px);
            }

            .btn-rapido.activo {
                background: #384A49;
                color: white;
                border-color: #384A49;
            }

            /* Tamaños específicos para los botones rápidos */
            .botones-rapidos button:nth-child(1) { font-size: 12px; }
            .botones-rapidos button:nth-child(2) { font-size: 14px; }
            .botones-rapidos button:nth-child(3) { font-size: 16px; }
            .botones-rapidos button:nth-child(4) { font-size: 18px; }
            .botones-rapidos button:nth-child(5) { font-size: 20px; }

            #valor-tamano {
                font-weight: bold;
                color: #EAE4D5;
                /*background: #f0f0f0;/** */
                padding: 2px 6px;
                border-radius: 4px;
                min-width: 30px;
                display: inline-block;
                text-align: center;
            }

            /* === RESPONSIVE PARA EL CONTROL DE TAMAÑO === */
            @media screen and (max-width: 680px) {
                .control-tamano-personalizado {
                    margin-top: 2px;
                    padding-top: 2px;
                }
                
                .botones-rapidos {
                    gap: 3px;
                }
                
                .btn-rapido {
                    padding: 4px 1px;
                    font-size: 10px;
                    min-height: 26px;
                }
                
                .marcas-tamano {
                    font-size: 10px;
                }
                
                .deslizador-tamano {
                    height: 4px;
                }
                
                .deslizador-tamano::-webkit-slider-thumb {
                    width: 16px;
                    height: 16px;
                }
            }
        </style>
    </div>



    <script>
        function mostrarHimno(id) {
            fetch("?id=" + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById("titulo").textContent = data.Primera_linea;
                    document.getElementById("numero").textContent = data.Numero;
                    document.getElementById("autor").textContent = data.Tema || "Sin tema";
                    document.getElementById("letra").textContent = data.Letra;
                    document.getElementById("modal").classList.add("active");
                });
        }



        function cerrarModal() {
            document.getElementById("modal").classList.remove("active");
        }
    </script>
</body>
</html>
