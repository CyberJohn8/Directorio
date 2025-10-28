<?php
// ============================
// FIX PARA INFINITYFREE
// ============================
ini_set("session.save_path", __DIR__ . "/../tmp");
if (!file_exists(__DIR__ . "/../tmp")) {
    mkdir(__DIR__ . "/../tmp", 0777, true);
}
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================
// CONTROL DE SESIÓN
// ============================

// --- Si viene como invitado, siempre forzamos la sesión de invitado ---
if (isset($_GET['guest']) && $_GET['guest'] === 'true') {
    $_SESSION["username"] = "Invitado";
    $_SESSION["rol"] = "invitado";
} elseif (!isset($_SESSION["username"]) || !isset($_SESSION["rol"])) {
    // Si no hay sesión ni modo invitado, se redirige al login
    header("Location: https://cyberjohn.infinityfreeapp.com/index.php");
    exit();
}

// ============================
// NORMALIZAR ROL
// ============================
$roles_validos = ["admin", "usuario", "invitado"];
$rol_sesion = strtolower(trim($_SESSION['rol']));

if (!in_array($rol_sesion, $roles_validos)) {
    $_SESSION["username"] = "Invitado";
    $_SESSION["rol"] = "invitado";
}
?>





<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    
    <link rel="stylesheet" href="Menu.css"> <!-- Enlace al archivo de estilos CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Oleo+Script&display=swap" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="iconos/icon2-8 1.png">

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

    <!-- Contenido principal -->
    <div class="contenido">
        <header>
            <!-- Dentro del <head>
            <link rel="icon" type="image/x-icon" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8 1.png"> -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

            <img src="Titulo.png" alt="Bienvenida" class="hero-img">

            
        </header>


        <div class="Versiculo">
            <!--<img src="IMG/mapa venezuela.jpg" alt="Bienvenida" class="hero-img">-->
            <p class="texto-bienvenida">
                Porque donde están dos o tres congregados en mi nombre, </p>
            <p class="texto-bienvenida">    allí estoy yo en medio de ellos.
            </p>
            <p class="texto-cita">Mateo 18:20</p>
        </div>

        <div class="opciones">
            <button onclick="location.href='Submenu.php'" class="btn-opcion">Ubicaciones</button>
            <button onclick="location.href='Eventos/index.php'" class="btn-opcion">Eventos</button>
            <?php if ($_SESSION["rol"] !== "invitado") : ?>
                <button onclick="location.href='Donaciones/index.php'" class="btn-opcion">Hacer Donación</button>
            <?php endif; ?>
            <button onclick="location.href='Material/index.php'" class="btn-opcion">Material Literario</button>
            <button onclick="location.href='LiteraturaBiblica/index.php'" class="btn-opcion">Estudio Bíblico</button>
            <button onclick="location.href='logout.php'" class="btn-opcion logout">Cerrar Sesión</button>
        </div>

        <!--<footer>
            <p>&copy; 2025 John Malavé. Todos los derechos reservados.</p>
            <p>Prohibida su reproducción total o parcial sin autorización previa.</p>
            <p>Versión 1.0 | Última actualización: agosto de 2025</p>
        </footer>-->
    </div>

    <script>
        function toggleMenu() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");
            sidebar.classList.toggle("active");
            overlay.classList.toggle("active");
        }
    </script>


    <!--https://cyberjohn.infinityfreeapp.com/Menu/-->
    

</body>
</html>
