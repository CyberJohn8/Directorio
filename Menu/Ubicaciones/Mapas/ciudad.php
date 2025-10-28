<?php
// Fix para InfinityFree
ini_set("session.save_path", __DIR__ . "/../tmp");
if (!file_exists(__DIR__ . "/../tmp")) {
    mkdir(__DIR__ . "/../tmp", 0777, true);
}

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'conexion.php';

// Validar parámetros
if (!isset($_GET['ciudad']) || !isset($_GET['estado'])) {
    die("Parámetros inválidos. No se recibió ciudad o estado.");
}

// Obtener y procesar parámetros
$estado = $_GET['estado'];
$ciudadesParam = $_GET['ciudad'];
$ciudades = array_map('trim', explode(',', $ciudadesParam));

// Crear placeholders
$placeholders = implode(',', array_fill(0, count($ciudades), '?'));

// Consulta SQL
$sql = "SELECT * FROM iglesias WHERE estado = ? AND ciudad IN ($placeholders)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en la consulta: " . $conn->error);
}

// Construir parámetros dinámicamente
$params = array_merge([$estado], $ciudades);
$types = str_repeat('s', count($params));
$stmt->bind_param($types, ...$params);

$stmt->execute();
$result = $stmt->get_result();
$datos_iglesias = $result->fetch_all(MYSQLI_ASSOC);

$conn->set_charset("utf8");

// Mapas por ciudad
$mapa_ciudades = [
    'Morón' => [
        'img' => 'IMG/ciudades/Morón.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Barrio Las Colinas', 'asamblea' => 'MORÓN - Barrio Las Colinas', 'top' => '40%', 'left' => '40%'],
            ['texto' => '2', 'leyenda' => '2 - Colinas De Mara', 'asamblea' => 'MORÓN - Colinas De Mara', 'top' => '60%', 'left' => '55%'],
            ['texto' => '3', 'leyenda' => '3 - Las Parcelas', 'asamblea' => 'MORÓN - Las Parcelas', 'top' => '75%', 'left' => '30%'],
            ['texto' => '4', 'leyenda' => '4 - Palma Sola', 'asamblea' => 'MORÓN - Palma Sola', 'top' => '20%', 'left' => '30%'],
        ]
    ],
    'Valencia' => [
        'img' => 'IMG/ciudades/valencia.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - Anzoátegui', 'asamblea' => 'VALENCIA - AV. ANZOATEGUI', 'top' => '40%', 'left' => '40%'],
            ['texto' => '2', 'leyenda' => '2 - Bárbula', 'asamblea' => 'VALENCIA - Bárbula', 'top' => '60%', 'left' => '55%'],
            ['texto' => '3', 'leyenda' => '3 - Bella Vista I', 'asamblea' => 'VALENCIA - Bella Vista I', 'top' => '75%', 'left' => '30%'],
            ['texto' => '4', 'leyenda' => '4 - Bello Monte II', 'asamblea' => 'VALENCIA - Bello Monte II', 'top' => '6%', 'left' => '50%'],
            ['texto' => '5', 'leyenda' => '5 - Simón Bolívar', 'asamblea' => 'VALENCIA - COMUNIDAD SIMÓN BOLÍVAR', 'top' => '12%', 'left' => '50%'],
            ['texto' => '6', 'leyenda' => '6 - Flor Amarillo', 'asamblea' => 'VALENCIA - Flor Amarillo', 'top' => '18%', 'left' => '60%'],
            ['texto' => '7', 'leyenda' => '7 - Fundación CAP', 'asamblea' => 'VALENCIA - Fundación CAP', 'top' => '20%', 'left' => '40%'],
            ['texto' => '8', 'leyenda' => '8 - González Plaza', 'asamblea' => 'VALENCIA - González Plaza', 'top' => '22%', 'left' => '56%'],
            ['texto' => '9', 'leyenda' => '9 - La Bocaina', 'asamblea' => 'VALENCIA - La Bocaina', 'top' => '50%', 'left' => '30%'],
            ['texto' => '10', 'leyenda' => '10 - Florida', 'asamblea' => 'VALENCIA - LA FLORIDA', 'top' => '40%', 'left' => '20%'],
            ['texto' => '11', 'leyenda' => '11 - Socorro II', 'asamblea' => 'VALENCIA - PARCELAS II DEL SOCORRO', 'top' => '66%', 'left' => '44%'],
            ['texto' => '12', 'leyenda' => '12 - Guayos', 'asamblea' => 'VALENCIA - LOS GUAYOS', 'top' => '35%', 'left' => '40%'],
            ['texto' => '13', 'leyenda' => '13 - Manguitos', 'asamblea' => 'VALENCIA - LOS MANGUITOS', 'top' => '30%', 'left' => '20%'],
            ['texto' => '14', 'leyenda' => '14 - Tarapío', 'asamblea' => 'VALENCIA - Tarapío', 'top' => '60%', 'left' => '30%'],
            ['texto' => '15', 'leyenda' => '15 - Primero de Mayo', 'asamblea' => 'VALENCIA - Primero de Mayo', 'top' => '26%', 'left' => '32%'],
            ['texto' => '16', 'leyenda' => '16 - San Diego', 'asamblea' => 'VALENCIA - San Diego', 'top' => '66%', 'left' => '28%'],
        ]
    ],
    'Puerto Cabello' => [
        'img' => 'IMG/ciudades/puerto_cabello.png',
        'botones' => [
            ['texto' => '1', 'leyenda' => '1 - El Cambur', 'asamblea' => 'El Cambur', 'top' => '54%', 'left' => '14%'],
            ['texto' => '2', 'leyenda' => '2 - Bartolomé Salom', 'asamblea' => 'PUERTO CABELLO - BARTOLOMÉ SALOM', 'top' => '15%', 'left' => '32.5%'],
            ['texto' => '3', 'leyenda' => '3 - Borburata', 'asamblea' => 'PUERTO CABELLO - BORBURATA', 'top' => '42%', 'left' => '48%'],
            ['texto' => '4', 'leyenda' => '4 - Colinas de Santa Cruz', 'asamblea' => 'PUERTO CABELLO - Colinas de Santa Cruz', 'top' => '27%', 'left' => '31%'],
            ['texto' => '5', 'leyenda' => '5 - El Fortín', 'asamblea' => 'PUERTO CABELLO - El Fortín', 'top' => '22%', 'left' => '36%'],
            ['texto' => '6', 'leyenda' => '6 - El Milagro', 'asamblea' => 'PUERTO CABELLO - El Milagro', 'top' => '17%', 'left' => '29%'],
            ['texto' => '7', 'leyenda' => '7 - El Palito', 'asamblea' => 'PUERTO CABELLO - El Palito', 'top' => '14%', 'left' => '12%'],
            ['texto' => '8', 'leyenda' => '8 - La Libertad', 'asamblea' => 'PUERTO CABELLO - La Libertad', 'top' => '20%', 'left' => '24%'],
            ['texto' => '9', 'leyenda' => '9 - La Sorpresa', 'asamblea' => 'PUERTO CABELLO - La Sorpresa', 'top' => '12%', 'left' => '26%'],
            ['texto' => '10', 'leyenda' => '10 - Morillo', 'asamblea' => 'PUERTO CABELLO - Morillo', 'top' => '14%', 'left' => '20%'],
            ['texto' => '11', 'leyenda' => '11 - Valle Seco', 'asamblea' => 'PUERTO CABELLO - Valle Seco', 'top' => '32%', 'left' => '38%'],
            ['texto' => '12', 'leyenda' => '12 - Calle Sucre', 'asamblea' => 'PUERTO CABELLO - Calle Sucre', 'top' => '14%', 'left' => '37%'],
            ['texto' => '13', 'leyenda' => '13 - San Esteban', 'asamblea' => 'SAN ESTEBAN PUEBLO ', 'top' => '50%', 'left' => '38%'],/**/
        ]
    ]
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8 1.png">
    <meta charset="UTF-8">
    <title><?= htmlspecialchars(implode(', ', $ciudades)) ?> - Iglesias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="ciudades.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <style>
        /* === UBICACIONES DE CADA BOTON === */
        /*Morón*/
        .btn-morón-1 { top: 31%; left: 50%; }
        .btn-morón-2 { top: 31%; left: 53%; }
        .btn-morón-3 { top: 28%; left: 55%; }
        .btn-morón-4 { top: 26%; left: 58%; }

        /*Celular*/
        @media only screen and (max-width: 768px) {
        .btn-morón-1 { top: 32%; left: 70%; }
        .btn-morón-2 { top: 32%; left: 74%; }
        .btn-morón-3 { top: 29%; left: 77%; }
        .btn-morón-4 { top: 26%; left: 82%; }
        }

        /*Valencia*/
        .btn-valencia-1 { top: 34%; left: 29%; }
        .btn-valencia-2 { top: 14%; left: 28%; }
        .btn-valencia-3 { top: 44%; left: 28%; }
        .btn-valencia-4 { top: 36%; left: 34%; }
        .btn-valencia-5 { top: 16%; left: 29%; }
        .btn-valencia-6 { top: 42%; left: 42%; }
        .btn-valencia-7 { top: 45%; left: 19%; }
        .btn-valencia-8 { top: 15%; left: 32%; }
        .btn-valencia-9 { top: 40%; left: 27%; }
        .btn-valencia-10 { top: 39.5%; left: 22%; }
        .btn-valencia-11 { top: 44%; left: 24%; }
        .btn-valencia-12 { top: 34%; left: 42%; }
        .btn-valencia-13 { top: 38%; left: 25.5%; }
        .btn-valencia-14 { top: 21%; left: 26%; }
        .btn-valencia-15 { top: 36%; left: 24%; }
        .btn-valencia-16 { top: 21%; left: 40%; }

        /*Celular*/
        @media only screen and (max-width: 768px) {
        .btn-valencia-1 { top: 34%; left: 39%; }
        .btn-valencia-2 { top: 14%; left: 38%; }
        .btn-valencia-3 { top: 44%; left: 42%; }
        .btn-valencia-4 { top: 36%; left: 50%; }
        .btn-valencia-5 { top: 16%; left: 39%; }
        .btn-valencia-6 { top: 42%; left: 60%; }
        .btn-valencia-7 { top: 45%; left: 28%; }
        .btn-valencia-8 { top: 15%; left: 45%; }
        .btn-valencia-9 { top: 40%; left: 41%; }
        .btn-valencia-10 { top: 39.5%; left: 32%; }
        .btn-valencia-11 { top: 44%; left: 34%; }
        .btn-valencia-12 { top: 34%; left: 60%; }
        .btn-valencia-13 { top: 38%; left: 38%; }
        .btn-valencia-14 { top: 21%; left: 38%; }
        .btn-valencia-15 { top: 36%; left: 34%; }
        .btn-valencia-16 { top: 21%; left: 56%; }
        }

        /*Puerto Cabello*/
        .btn-puerto_cabello-1 { top: 54%; left: 14%; }
        .btn-puerto_cabello-2 { top: 15%; left: 32.5%; }
        .btn-puerto_cabello-3 { top: 42%; left: 48%; }
        .btn-puerto_cabello-4 { top: 27%; left: 31%; }
        .btn-puerto_cabello-5 { top: 22%; left: 36%; }
        .btn-puerto_cabello-6 { top: 17%; left: 29%; }
        .btn-puerto_cabello-7 { top: 14%; left: 12%; }
        .btn-puerto_cabello-8 { top: 20%; left: 24%; }
        .btn-puerto_cabello-9 { top: 12%; left: 26%; }
        .btn-puerto_cabello-10 { top: 14%; left: 20%; }
        .btn-puerto_cabello-11 { top: 32%; left: 38%; }
        .btn-puerto_cabello-12 { top: 14%; left: 37%; }
        .btn-puerto_cabello-13 { top: 50%; left: 38%; }/**/

        @media only screen and (max-width: 768px) {
        .btn-puerto_cabello-1 { top: 54%; left: 20%; }
        .btn-puerto_cabello-2 { top: 15%; left: 47%; }
        .btn-puerto_cabello-3 { top: 42%; left: 70%; }
        .btn-puerto_cabello-4 { top: 27%; left: 46%; }
        .btn-puerto_cabello-5 { top: 22%; left: 52%; }
        .btn-puerto_cabello-6 { top: 17%; left: 39%; }
        .btn-puerto_cabello-7 { top: 14%; left: 18%; }
        .btn-puerto_cabello-8 { top: 20%; left: 32%; }
        .btn-puerto_cabello-9 { top: 12%; left: 34%; }
        .btn-puerto_cabello-10 { top: 14%; left: 28%; }
        .btn-puerto_cabello-11 { top: 32%; left: 56%; }
        .btn-puerto_cabello-12 { top: 14%; left: 54%; }
        .btn-puerto_cabello-13 { top: 50%; left: 56%; }/**/
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
            <a href="https://cyberjohn.infinityfreeapp.com/Cuenta/index.php">Gestionar Sesión</a>
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




    <div class="content">
        <header>
            <a href="javascript:history.back()" class="btn-volver"></a>
            <h1>Asambleas en <?= htmlspecialchars($ciudades[0]) ?>, <?= htmlspecialchars($estado) ?></h1>
        </header>


        <?php
        // Mostrar cada mapa una sola vez, incluso si hay varias ciudades relacionadas
        $ciudades_mostradas = [];

        foreach ($ciudades as $ciudadItem) {
            foreach ($mapa_ciudades as $ciudadMapa => $datosMapa) {
                if (stripos($ciudadItem, $ciudadMapa) !== false && !in_array($ciudadMapa, $ciudades_mostradas)) {
                    $ciudades_mostradas[] = $ciudadMapa;
        ?>
                    <div class="mapa-contenedor-con-leyenda">
                        <div class="mapa-container">
                            <img src="<?= $mapa_ciudades[$ciudadMapa]['img'] ?>" alt="Mapa de <?= htmlspecialchars($ciudadMapa) ?>" class="mapa-ciudad">
                            <?php foreach ($mapa_ciudades[$ciudadMapa]['botones'] as $btn): ?>
                                <button class="btn-mapa btn-<?= strtolower(str_replace(' ', '_', $ciudadMapa)) . '-' . $btn['texto'] ?>"
                                        onclick="filtrarPorZona('<?= htmlspecialchars($btn['asamblea']) ?>')">
                                    <?= $btn['texto'] ?>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <!-- Leyenda -->
                        <div class="leyenda-interactiva">
                            <h3>Leyenda de <?= htmlspecialchars($ciudadMapa) ?>, <?= htmlspecialchars($estado) ?></h3>
                            <ul>
                                <?php foreach ($mapa_ciudades[$ciudadMapa]['botones'] as $btn): ?>
                                    <li>
                                        <button onclick="filtrarPorZona('<?= htmlspecialchars($btn['asamblea']) ?>')">
                                            <?= isset($btn['leyenda']) ? $btn['leyenda'] : $btn['texto'] ?>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
        <?php
                }
            }
        }
        ?>

    </div>

    <!-- MODAL DETALLES -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h3>Detalles de la Asamblea</h3>
            <p><strong>Asamblea:</strong> <span id="det-asamblea"></span></p>
            <p><strong>Número:</strong> <span id="det-numero"></span></p>
            <p><strong>Fecha de Fundación:</strong> <span id="det-Fehca_Fundacion"></span></p>
            <p><strong>Ciudad:</strong> <span id="det-ciudad"></span></p>
            <p><strong>Estado:</strong> <span id="det-estado"></span></p>
            <p><strong>Dirección:</strong> <span id="det-direccion"></span></p>
            <ul>
                <li><strong>Domingo:</strong> <span id="det-domingo"></span></li>
                <li><strong>Lunes:</strong> <span id="det-lunes"></span></li>
                <li><strong>Martes:</strong> <span id="det-martes"></span></li>
                <li><strong>Miércoles:</strong> <span id="det-miercoles"></span></li>
                <li><strong>Jueves:</strong> <span id="det-jueves"></span></li>
                <li><strong>Viernes:</strong> <span id="det-viernes"></span></li>
                <li><strong>Sábado:</strong> <span id="det-sabado"></span></li>
            </ul>
            <p><strong>Obras:</strong> <span id="det-obras"></span></p>
            <p><strong>Google Maps:</strong> 
                <a id="det-mapa" href="#" target="_blank" class="btn-mapa-enlace">
                    <i class="fas fa-map-marker-alt"></i> Ver ubicación
                </a>
            </p>
        </div>
    </div>

    <script>
        const datosIglesias = <?= json_encode($datos_iglesias, JSON_UNESCAPED_UNICODE) ?>;

        function normalizar(texto) {
            return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim().toLowerCase();
        }

        function filtrarPorZona(zona) {
            const zonaNormalizada = normalizar(zona);

            const iglesia = datosIglesias.find(ig =>
                normalizar(ig.asamblea) === zonaNormalizada
            );

            if (iglesia) {
                mostrarDetalles(iglesia);
            } else {
                alert("No se encontraron datos para la asamblea: " + zona);
            }
        }

        function mostrarDetalles(data) {
            document.getElementById("det-asamblea").textContent = data.asamblea;
            document.getElementById("det-numero").textContent = data.numero;
            document.getElementById("det-Fehca_Fundacion").textContent = data.Fehca_Fundacion;
            document.getElementById("det-ciudad").textContent = data.ciudad;
            document.getElementById("det-estado").textContent = data.estado;
            document.getElementById("det-direccion").textContent = data.direccion;
            document.getElementById("det-domingo").textContent = data.domingo;
            document.getElementById("det-lunes").textContent = data.lunes;
            document.getElementById("det-martes").textContent = data.martes;
            document.getElementById("det-miercoles").textContent = data.miercoles;
            document.getElementById("det-jueves").textContent = data.jueves;
            document.getElementById("det-viernes").textContent = data.viernes;
            document.getElementById("det-sabado").textContent = data.sabado;
            document.getElementById("det-obras").textContent = data.obras;
            document.getElementById("det-mapa").href = data.GoogleMaps;
            document.getElementById("modal").style.display = "block";
        }

        function cerrarModal() {
            document.getElementById("modal").style.display = "none";
        }



        /*==========Solo en caso de no tener Enlace GoogleMaps==========*/
        document.addEventListener("DOMContentLoaded", function () {
            const mapaLink = document.getElementById("det-mapa");

            mapaLink.addEventListener("click", function (e) {
                const url = mapaLink.getAttribute("href");

                if (!url || url === "#" || url.trim() === "") {
                    e.preventDefault(); // Previene que se abra el enlace vacío
                    alert("Enlace no disponible por ahora.\n\nSi lo tiene, favor hacerlo llegar al correo:\n\ndirectorioasambleas@gmail.com\n\nIndicando claramente el nombre de la asamblea y el estado en el que se encuentra.\n\nGracias de antemano.");
                }
            });
        });
    </script>
</body>
</html>