<?php
// Fix para InfinityFree
ini_set("session.save_path", __DIR__ . "/../tmp");
if (!file_exists(__DIR__ . "/../tmp")) {
    mkdir(__DIR__ . "/../tmp", 0777, true);
}

session_start();
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$rol = $_SESSION['rol'];

// Conexión a MySQL con soporte utf8mb4
$conn = new mysqli("sql308.infinityfree.com", "if0_39414119", "U7ML7oxb1B", "if0_39414119_chat_biblico");

// Verifica conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// IMPORTANTE: establecer codificación utf8mb4 para soportar caracteres especiales y emojis
$conn->set_charset("utf8mb4");

if (!$conn->set_charset("utf8mb4")) {
    echo "Error al establecer utf8mb4: " . $conn->error;
    exit();
}

// Crear sala con mensaje inicial
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nueva_sala'])) {
    $nueva_sala = trim($_POST['nueva_sala']);
    $mensaje_inicial = trim($_POST['mensaje_inicial']);
    if (!empty($nueva_sala) && !empty($mensaje_inicial)) {
        $stmt = $conn->prepare("INSERT INTO mensajes (sala, nombre, mensaje) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nueva_sala, $username, $mensaje_inicial);
        $stmt->execute();
        $stmt->close();
        header("Location: conversacion.php");
        exit();
    }
}

// Eliminar sala si el usuario la creó o es admin
if (isset($_GET['eliminar'])) {
    $salaEliminar = $_GET['eliminar'];

    $stmt = $conn->prepare("SELECT nombre FROM mensajes WHERE sala = ? ORDER BY id ASC LIMIT 1");
    $stmt->bind_param("s", $salaEliminar);
    $stmt->execute();
    $result_creador = $stmt->get_result();
    $creadorSala = null;

    if ($row_creador = $result_creador->fetch_assoc()) {
        $creadorSala = $row_creador['nombre'];
    }
    $stmt->close();

    if ($creadorSala && (strcasecmp(trim($creadorSala), trim($username)) === 0 || $rol === 'admin')) {
        $stmt = $conn->prepare("DELETE FROM mensajes WHERE sala = ?");
        $stmt->bind_param("s", $salaEliminar);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: conversacion/conversacion.php");
    exit();
}

// Obtener salas únicas con su creador y cantidad de mensajes
$sql = "
    SELECT m1.sala, m1.nombre AS creador, COUNT(m2.id) as total
    FROM mensajes m1
    INNER JOIN (
        SELECT sala, MIN(id) AS primer_id
        FROM mensajes
        GROUP BY sala
    ) m_idx ON m1.sala = m_idx.sala AND m1.id = m_idx.primer_id
    LEFT JOIN mensajes m2 ON m1.sala = m2.sala
    GROUP BY m1.sala
    ORDER BY m1.sala ASC
";
$result = $conn->query($sql);












// Solo si $_SESSION['user_id'] está definida pero $_SESSION['username'] no
if (!isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
    $conn_users = new mysqli("sql308.infinityfree.com", "if0_39414119", "U7ML7oxb1B", "if0_39414119_geolocalizador");
    $conn_users->set_charset("utf8mb4");
    $stmt = $conn_users->prepare("SELECT username, rol FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($username, $rol);
    if ($stmt->fetch()) {
        $_SESSION['username'] = $username;
        $_SESSION['rol'] = $rol;
    }
    $stmt->close();
    $conn_users->close();
}/**/
// SOLO PARA DEPURAR:
//echo "Usuario actual: " . htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
?>





<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8 1.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salas del chat</title>
    <link rel="stylesheet" href="DiseñoConversador.css">
    
    <style>
        /* === FUENTES === */
        @import url('https://fonts.googleapis.com/css2?family=Rakkas&family=Sansation&display=swap');

        @import url('https://fonts.googleapis.com/css2?family=Oleo+Script&display=swap');

        /* === FUENTES === */
        @import url('https://fonts.googleapis.com/css2?family=Rakkas&family=Sansation&family=Oleo+Script&display=swap');

        /* Menú por defecto: vertical (computadora) */
        .menu-nav {
        position: fixed;
        top: 0;
        left: 0;
        width: 100px;
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: start;
        padding-top: 0px;
        z-index: 1001;
        background-color: #637983;
        }

        /* Botones del menú *
        .icon-btn {
        background: none;
        border: none;
        margin: 10px 0;
        padding: 5px;
        cursor: pointer;
        }

        .icon-btn img {
        width: 28px;
        height: 28px;
        }/**/



        /* === SIDEBAR === */
        /* Botón hamburguesa (puedes ocultarlo si ya hay barra de íconos) */
        .menu-toggle {
        display: none;
        }

        /* Barra lateral con íconos */
        .icon-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100px;
        height: 100vh;
        background-color: #637983;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 5px;
        z-index: 1003;
        }

        .icon-btn {
        background: none;
        border: none;
        margin: 5px 0;
        cursor: pointer;
        padding: 5px;
        width: 100%;
        transition: background 0.3s;
        }

        .icon-btn:hover {
        background-color: #2a3e42;
        }

        .icon-btn img {
        width: auto;
        height: 70%;
        /*filter: invert(1);/**/
        }

        /* Panel emergente */
        .sidebar {
        position: fixed;
        top: 0;
        left: -300px;
        width: 250px;
        height: 100%;
        background-color: transparent/*#263C3EA6/**/;
        color: #EAE4D5;
        padding: 20px;
        z-index: 1004;
        transition: left 0.3s ease;
        }

        .sidebar.active {
        left: 100px; /* aparece justo al lado del menú de íconos */
        }

        .sidebar h2 {
        font-family: 'Oleo Script', cursive;
        color: #EAE4D5;
        
        font-weight: normal;
        font-size: 38px;
        }

        .sidebar a {
        color: #EAE4D5;
        text-decoration: none;
        display: block;
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;

        
        font-weight: normal;
        font-size: 24px;
        }

        .sidebar a:hover {
        background-color: #2a3e42;
        }

        .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1002;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        }

        .overlay.active {
        opacity: 1;
        pointer-events: auto;
        }

        .sidebar .close-btn {
        padding: 15px 25px;
        border: none;
        /*border-radius: 10px;/**/
        font-size: 16px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        cursor: pointer;
        transition: background 0.3s;

        border-radius: 15px 15px 40px 15px;
        margin: 10px;
        }

        .sidebar .close-btn:hover {
        transform: scale(1.25);
        }



        /*==================/DISEÑO PARA CELULARES 768PX/==================*/
        /* RESPONSIVE */
        @media (max-width: 768px) {
        /*==================/MENÚ LATERAL/==================*/
        .menu-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 55px;
            display: flex;
            flex-direction: row;
            justify-content: space-between; /* ← Cambiado para mover extremos */    
            align-items: center;
            padding: 0 0;
            z-index: 1001;

            border-radius: 0 0 20px 20px;
        }

        .icon-btn {
            width: auto;
            height: 80%;
        }

        .icon-btn img {
            width: 32px;
            height: 32px;
            /*filter: invert(1);/**/
        }

        .solo-pc {
            display: none !important;
        }





        /*==================/MENÚ EMERGENTE OCULTO/==================*/
        /* Panel emergente */
        .sidebar {
            left: -300px;
            width: 250px;
            height: 100%;
            background-color: #637983/*transparent/*#263C3EA6*/;
            color: #EAE4D5;
            padding: 20px;
        }

        .sidebar.active {
            left: 0px; /* aparece justo al lado del menú de íconos */
        }

        .sidebar h2 {
            font-family: 'Oleo Script', cursive;
            color: #EAE4D5;
            
            font-weight: normal;
            font-size: 26px;
        }

        .sidebar a {
            color: #EAE4D5;
            border-radius: 0px;
            color: #EAE4D5;        
            font-weight: normal;
            font-size: 16px;

            border-bottom: 1px solid #263C3E;
        }

        .sidebar a:hover {
            background-color: #2a3e42;
        }



        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1002;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .sidebar .close-btn {    
            border-radius: 45px 15px 45px 15px;
            background-color: #A2B0BE;
            color: #192E2F;
        }

        .sidebar .close-btn:hover {
            transform: scale(1.25);
            background-color: #192E2F;
            color: #A2B0BE;
        }
        }







        /*==================/DISEÑO PARA CELULARES 280PX/==================*/
        /* RESPONSIVE */
        @media (max-width: 280px) {
        /*==================/MENÚ LATERAL/==================*/
        /*==================/MENÚ LATERAL/==================*/
        .menu-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 55px;
            display: flex;
            flex-direction: row;
            justify-content: space-between; /* ← Cambiado para mover extremos */    
            align-items: center;
            padding: 0 0;
            z-index: 1001;

            border-radius: 0 10px 0 10px;
        }

        .icon-btn {
            width: auto;
            height: 80%;
        }

        .icon-btn img {
            width: 32px;
            height: 32px;
            /*filter: invert(1);/**/
        }

        .solo-pc {
            display: none !important;
        }





        /*==================/MENÚ EMERGENTE OCULTO/==================*/
        /* Panel emergente */
        .sidebar {
            left: -300px;
            width: 150px;
            height: 100%;
            background-color: #637983/*transparent/*#263C3EA6*/;
            color: #EAE4D5;
            padding: 20px;
        }

        .sidebar.active {
            left: 0px; /* aparece justo al lado del menú de íconos */
        }

        .sidebar h2 {
            font-family: 'Oleo Script', cursive;
            color: #EAE4D5;
            
            font-weight: normal;
            font-size: 26px;
        }

        .sidebar a {
            color: #EAE4D5;
            border-radius: 0px;
            color: #EAE4D5;        
            font-weight: normal;
            font-size: 16px;

            border-bottom: 1px solid #263C3E;
        }

        .sidebar a:hover {
            background-color: #2a3e42;
        }



        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1002;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .sidebar .close-btn {    
            border-radius: 45px 15px 45px 15px;
            background-color: #A2B0BE;
            color: #192E2F;
        }

        .sidebar .close-btn:hover {
            transform: scale(1.25);
            background-color: #192E2F;
            color: #A2B0BE;
        }
        }


















        /* Eliminar scroll visible del body y html */
        html, /* Oculta la barra de scroll vertical, pero permite hacer scroll */
        body {
            overflow-y: scroll; /* asegura que el scroll siga funcionando */
            scrollbar-width: none; /* para Firefox */
        }

        /* Para navegadores WebKit como Chrome, Edge, Safari */
        body::-webkit-scrollbar {
            width: 0px;
            background: transparent; /* opcional, por si quieres un fondo invisible */
        }


        .contenido {
            overflow-y: scroll;
            scrollbar-width: none;
        }

        .contenido::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }




        /* === FONDO CON CAPAS === */
        /* === FONDO CON CAPAS === */
        /* Asegura que todo ocupe el alto completo */
        html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: 'Sansation', sans-serif;
        }

        /* Fondo de pantalla completo */
        body::before {
        content: "";
        position: fixed; /* para que no se repita al hacer scroll */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('iconos/Fonfo_Mapa_Color.png');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top center;
        opacity: 0.7;
        z-index: -1;
        }

        /* Asegura que se muestre scroll si el contenido es más largo */
        body {
        overflow-x: hidden;
        overflow-y: auto;
        position: relative;
        background-color: #fff; /* fallback si la imagen no carga */
        }



        html, body {
            overflow-x: hidden;
            position: relative;
        }

        /* === CONTENEDOR GENERAL === */
        .container {
        display: flex;
        flex-direction: column;
        align-items: center; /* CENTRA horizontalmente */
        justify-content: center;
        width: 100%;
        }

        
        /* === ENCABEZADO SUPERIOR === */
        .top-header {
            width: 100%;
            max-width: 900px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            gap: 10px;
            margin: 10px auto; /* Centra horizontalmente el layout */
        }
        
        .top-header h1 {
            font-family: 'Oleo Script', cursive;
            font-weight: normal;
            font-size: 38px;
            color: #2a3e42;
            margin: 0;
            text-align: center;
            flex-grow: 1;
        }
        
        .back-btn {
            background-image: url('iconos/Retorno.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            background-color: transparent;
            border: none;
            width: 40px;
            height: 40px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .back-btn:hover {
            transform: scale(1.2);
        }
        
        /* === DISEÑO FLEX DE SALAS Y FORMULARIO === */
        .salas-layout {
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* CENTRA horizontalmente los hijos */
            align-items: flex-start;
            gap: 40px;
            max-width: 80%;
            margin: 0 auto;  /* CENTRA el layout en la página */
        }
        
        

        .container {
            justify-content: center;
            padding: 30px 20px;
        }
        
        
        /* === BLOQUE DE SALAS CON SCROLL === */
        .salas-scroll {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            max-height: 450px;
            overflow-y: auto;
            text-align: center;
        }
        
        .salas-scroll h3 {
            font-family: 'Oleo Script', cursive;
            font-size: 26px;
            margin-bottom: 20px;
            color: #2a3e42;
            font-weight: normal;
        }
        
        .sala-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .sala-btn {
            background-color: #637983;
            color: #ffffff;
            padding: 12px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 16px;
            flex-grow: 1;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .sala-btn:hover {
            background-color: #4b5f66;
        }
        
        .delete-btn {
            margin-left: 10px;
            background-color: #B87856;
            color: #EAE4D5;
            border-radius: 6px;
            padding: 6px 10px;
            text-decoration: none;
            font-size: 14px;
        }
        
        .delete-btn:hover {
            background-color: #E6CDB7;
            color: #192E2F;
        }
        
        /* === FORMULARIO CREAR SALA === */
        .crear-sala-form {
            background: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        
        .crear-sala-form h3 {
            font-family: 'Oleo Script', cursive;
            font-size: 24px;
            color: #2a3e42;
            margin-bottom: 20px;
        }
        
        .crear-sala-form label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
            color: #2a3e42;
        }
        
        .crear-sala-form input[type="text"],
        .crear-sala-form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 2px dashed #a2b0be;
            margin-bottom: 15px;
            background: #fff;
            box-sizing: border-box;
        }
        
        .crear-sala-form textarea {
            resize: vertical;
            height: 80px;
        }
        
        .crear-sala-form button {
            background-color: #637983;
            color: #EAE4D5;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }
        
        .crear-sala-form button:hover {
            background-color: #4b5f66;
        }
        










        /* === FORMATO RESPONSIVO PARA CELULARES === */
        @media screen and (max-width: 768px) {
        body {
            background-image: url('iconos/Fondo_Mapa_Tlf.png'); /* tu imagen */
        } 

        html, body {
            font-size: 14px;
            padding: 0;
        }

        .container {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;  /* Centrado horizontal */
            justify-content: center;

            margin-left: -40px;
        }
        
        .top-header {
            flex-direction: column;
            align-items: center;
            padding: 10px;
            gap: 10px;


            
            margin-bottom: 0px;
            margin: 10px auto; /* Centra horizontalmente el layout */
        }

        .top-header h1 {
            font-size: 26px;
            text-align: center;
            
            margin-top: 10px;
        }

        .top-header .back-btn {
            display: none !important;
        }

        .salas-layout {
            flex-direction: column;
            gap: 20px;
            max-width: 90%;
            margin: 0 auto;  /* CENTRAR */
            align-items: center;
        }
        
        .salas-scroll h3 {
            margin-bottom: 10px;
        }

        .salas-scroll,
        .crear-sala-form {
            max-width: 100%;
            padding: 20px;
            box-shadow: none;
            border-radius: 12px;
        }

        .sala-item {
            flex-direction: column;
            align-items: stretch;
        }

        .sala-btn {
            font-size: 14px;
            padding: 10px;
        }

        .delete-btn {
            margin-left: 0;
            margin-top: 2px;
            padding: 6px;
            font-size: 13px;
        }

        .crear-sala-form h3 {
            font-size: 20px;
        }

        .crear-sala-form input[type="text"],
        .crear-sala-form textarea {
            font-size: 14px;
            padding: 8px;
        }

        .crear-sala-form button {
            padding: 10px;
            font-size: 15px;
        }
        }




        @media screen and (max-width: 300px) {
        .top-header {
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 10px;
        }

        .top-header h1 {
            font-size: 20px;
            text-align: center;
        }

        .salas-layout {
            flex-direction: column;
            gap: 15px;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 5px;
            align-items: center;
        }

        .salas-scroll,
        .crear-sala-form {
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            box-shadow: none;
            background: rgba(255, 255, 255, 0.95);
        }

        .salas-scroll h3,
        .crear-sala-form h3 {
            font-size: 18px;
        }

        .sala-btn {
            font-size: 14px;
            padding: 10px;
        }

        .delete-btn {
            font-size: 12px;
            margin-top: 6px;
            padding: 5px 8px;
        }

        .crear-sala-form input,
        .crear-sala-form textarea {
            font-size: 14px;
            padding: 8px;
        }

        .crear-sala-form button {
            font-size: 14px;
            padding: 10px;
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
        <button class="icon-btn solo-pc" onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/index.php'" title="Inicio">
            <img src="iconos/Inicio.png" alt="Inicio">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/Submenu.php'" title="Ubicación">
            <img src="iconos/ubicaciones.png" alt="Ubicación">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/Eventos/index.php'" title="Eventos">
            <img src="iconos/eventos.png" alt="Eventos">
        </button>
        <?php if ($_SESSION["rol"] !== "invitado") : ?>
            <button class="icon-btn solo-pc" onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/Donaciones/index.php'" title="Donaciones">
                <img src="iconos/donation.png" alt="Donaciones">
            </button>
        <?php endif; ?>
        <button class="icon-btn solo-pc" onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/Material/index.php'" title="Material Literario">
            <img src="iconos/material.png" alt="Material Literario">
        </button>
        <button class="icon-btn solo-pc" onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/LiteraturaBiblica/index.php'" title="Biblia">
            <img src="iconos/Biblia.png" alt="Estudio Bíblico">
        </button>
        <button class="icon-btn btn-sesion" onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/logout.php'" title="Cerrar Sesión">
            <img src="iconos/Sesion.png" alt="Cerrar Sesión">
        </button>
    </nav>


    <!-- Menú emergente (sidebar) para celular -->
    <div class="sidebar mobile-only" id="sidebarMenu">
        <h2>Menú</h2>
        <a href="https://cyberjohn.infinityfreeapp.com/Menu/index.php">Inicio</a>
        <a href="https://cyberjohn.infinityfreeapp.com/Menu/Submenu.php">Ubicación</a>
        <a href="https://cyberjohn.infinityfreeapp.com/Menu/Eventos/index.php">Eventos</a>
        <?php if ($_SESSION["rol"] !== "invitado") : ?>
            <a href="https://cyberjohn.infinityfreeapp.com/Menu/Donaciones/index.php">Donaciones</a>
        <?php endif; ?>
        <a href="https://cyberjohn.infinityfreeapp.com/Menu/Material/index.php">Material Literario</a>
        <a href="https://cyberjohn.infinityfreeapp.com/Menu/LiteraturaBiblica/index.php">Estudio Bíblico</a>
        <?php if ($_SESSION["rol"] !== "invitado") : ?>
            <a href="https://cyberjohn.infinityfreeapp.com/Cuenta/index.php">Administrar Cuenta</a>
        <?php endif; ?>

        <a href="https://cyberjohn.infinityfreeapp.com/Menu/Copiryt.php">Acerca de</a>

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

<div class="container">
    <header class="top-header">
        <button onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/LiteraturaBiblica/index.php'" class="back-btn" title="Volver"></button>
        <h1>Sistema de Chat Bíblico</h1>
    </header>

    <div class="salas-layout">
        <!-- Contenedor de salas -->
        <div class="salas-scroll">
            <h3>Salas disponibles</h3>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="sala-item">
                        <a class="sala-btn" href="sala_conversacion.php?sala=<?= urlencode($row['sala']) ?>">
                            <?= htmlspecialchars($row['sala']) ?>
                            <?= strcasecmp(trim($row['creador']), trim($username)) === 0 ? ' (tú)' : '' ?>
                        </a>

                        <?php if (strcasecmp(trim($row['creador']), trim($username)) === 0 || $rol === 'admin'): ?>
                            <a class="delete-btn" href="?eliminar=<?= urlencode($row['sala']) ?>"
                            onclick="return confirm('¿Eliminar la sala &quot;<?= htmlspecialchars($row['sala']) ?>&quot; y todos sus mensajes?');">
                                Eliminar
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-salas">Aún no hay salas disponibles.</p>
            <?php endif; ?>
        </div>


        <!-- Formulario para crear nueva sala -->
        <form class="crear-sala-form" method="post">
            <h3>Crear nueva sala</h3>
            <label>Nombre de la sala:</label>
            <input type="text" name="nueva_sala" placeholder="Nombre de la nueva sala" required>

            <label>Mensaje inicial (tema de conversación):</label>
            <textarea name="mensaje_inicial" placeholder="Escribe el tema del chat o una bienvenida" required></textarea>

            <button type="submit">Crear sala</button>
        </form>
    </div>
</div>

</body>
</html>