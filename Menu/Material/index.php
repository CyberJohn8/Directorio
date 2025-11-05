<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
//$conn = new mysqli("localhost", "root", "", "directorio");/** */
$conn = new mysqli("sql204.infinityfree.com", "if0_39714112", "MWgk9nZD6H0RIl", "if0_39714112_directorio_asambleas");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

$conn->set_charset("utf8");

// Verificar si el usuario es administrador
$es_admin = ($_SESSION['rol'] ?? '') === 'admin';

// CRUD solo para administradores
if ($_SERVER["REQUEST_METHOD"] == "POST" && $es_admin) {
    $tabla = $_POST["tabla"];
    $titular = $_POST["titular"];
    $descripcion = $_POST["descripcion"];
    $enlace = $_POST["enlace"];

    if (isset($_POST["agregar"])) {
        $conn->query("INSERT INTO $tabla (Titular, Descripcion, Enlace) VALUES ('$titular', '$descripcion', '$enlace')");
    }

    if (isset($_POST["editar"])) {
        $id = $_POST["id"];
        $conn->query("UPDATE $tabla SET Titular='$titular', Descripcion='$descripcion', Enlace='$enlace' WHERE id=$id");
    }
}

// Eliminar también solo si es admin
if (isset($_GET["eliminar"], $_GET["tabla"]) && $es_admin) {
    $id = $_GET["eliminar"];
    $tabla = $_GET["tabla"];
    $conn->query("DELETE FROM $tabla WHERE id=$id");
}

// Obtener datos para mostrar
$result_es = $conn->query("SELECT * FROM sitios_web");
$result_en = $conn->query("SELECT * FROM sitios_web_otros");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="/Menu/iconos/icon2-8.png">
    <meta charset="UTF-8">
    <title>Sitios Web Recomendados</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Material.css">
    <script>
        function filtrarTabla() {
            let input = document.getElementById("buscador").value.toLowerCase();
            document.querySelectorAll("tbody tr").forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
            });
        }
    </script>
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




    <div class="content">
        <header>
            <button onclick="history.back()" class="return-button" title="Volver"></button>
            <h1>Material Literario<!-- en Español e Inglés--></h1>
        </header>

        <input type="text" id="buscador" onkeyup="filtrarTabla()" placeholder="Buscar...">

        <!-- Español -->
        <h2>Publicaciones en Español</h2>
        <div class="card-container">
            <?php while ($row = $result_es->fetch_assoc()): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($row['Titular']) ?></h3>
                    <p><?= htmlspecialchars($row['Descripcion']) ?></p>
                    <a href="<?= htmlspecialchars($row['Enlace']) ?>" target="_blank" class="btn-enlace largo">Ir al sitio</a>
                    
                </div>
            <?php endwhile; ?>
        </div>



        <!-- Inglés -->
        <h2>Publications in English</h2>
        <div class="card-container">
            <?php while ($row = $result_en->fetch_assoc()): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($row['Titular']) ?></h3>
                    <p><?= htmlspecialchars($row['Descripcion']) ?></p>
                    <a href="<?= htmlspecialchars($row['Enlace']) ?>" target="_blank" class="btn-enlace largo">Visit</a>
                    
                </div>
            <?php endwhile; ?>
        </div>



    </div>

</body>
</html>

<?php
$conn->close();
?>
