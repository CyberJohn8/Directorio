<?php
session_start();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="/Menu/iconos/icon2-8.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Bíblica con IA</title>
    
    <link rel="stylesheet" href="IA_Biblica.css">
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

<main>
  <div>
    <header>
        <button onclick="location.href='https://directorio.wasmer.app//Menu/LiteraturaBiblica/index.php'"></button>
        <h1>Consulta Bíblica con Inteligencia Artificial</h1>
    </header>

    <div class="container">
        <p>Formula tus preguntas sobre la Biblia y deja que la IA bíblica te responda:</p>
        <iframe src="https://www.yeschat.ai/es/gpts-ZxX36KFX-BibleGPT"></iframe>
    </div>
  </div>
</main>


</body>
</html>
