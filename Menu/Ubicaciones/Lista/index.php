<?php
$conn = new mysqli("sql308.infinityfree.com", "if0_39414119", "U7ML7oxb1B", "if0_39414119_geolocalizador");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

<?php
// Fix para InfinityFree
ini_set("session.save_path", __DIR__ . "/../tmp");
if (!file_exists(__DIR__ . "/../tmp")) {
    mkdir(__DIR__ . "/../tmp", 0777, true);
}

// Conexión a la base de datos
$servername = "sql308.infinityfree.com";
$username = "if0_39414119";
$password = "U7ML7oxb1B";
$database = "if0_39414119_geolocalizador";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$sql = "SELECT id, asamblea, numero, ciudad, estado, direccion, domingo, lunes, martes, miercoles, jueves, viernes, sabado, obras, GoogleMaps, Fehca_Fundacion FROM iglesias";
$result = $conn->query($sql);
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8 1.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Iglesias</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="Lista.css">
    <script>
        function filtrarTabla() {
            let input = document.getElementById("buscador").value.toLowerCase();
            let columna = document.getElementById("columna").value;
            let filas = document.querySelectorAll("tbody tr");

            filas.forEach(fila => {
                let celdas = fila.getElementsByTagName("td");
                if (columna === "all") {
                    let textoFila = fila.innerText.toLowerCase();
                    fila.style.display = textoFila.includes(input) ? "" : "none";
                } else {
                    let textoCelda = celdas[columna].innerText.toLowerCase();
                    fila.style.display = textoCelda.includes(input) ? "" : "none";
                }
            });
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
            document.getElementById("modal").style.display = "block";
        }

        function cerrarModal() {
            document.getElementById("modal").style.display = "none";
        }
    </script>






    
    <style>
        
        /* Por defecto en pantallas grandes, muestra el nombre completo */
        .estado-abrev {
            display: none !important; 
        }
        .estado-nombre {
            display: inline !important;
        }

        /* En móviles, muestra el acrónimo y oculta el nombre largo */
        @media only screen and (max-width: 600px) {
            .estado-abrev {
                display: inline !important;
            }
            .estado-nombre {
                display: none !important;
            }
        }
    </style>
    <style>
        .espacio .tlf-no {
            display: none;
        }
        .espacio {
            display: none;
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



<div class="contento">
    <header>
        <button onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/Submenu.php'" class="return-button" title="Volver"></button>
        <h1>Asambleas Congregadas al Nombre de Señor en Venezuela</h1>
    </header>

    <div class="filtros-container">
        <div class="fila-buscador">
            <select id="columna" onchange="filtrarTabla()">
                <option value="all">Buscar todo</option>
                <option value="0">Asamblea</option>
                <option value="1">Número</option>
                <option value="2">Ciudad</option>
                <option value="3">Estado</option>
            </select>

            <input class="BarradeBuscador" type="text" id="buscador" onkeyup="filtrarTabla()" placeholder="Buscar...">
        </div>


        <select id="selector-estado" onchange="filtrarTabla()">
            <option value="">Selecciona un estado</option>
            <option value="Amazonas ama">Amazonas</option>
            <option value="Anzoátegui anz">Anzoátegui</option>
            <option value="Apure apu">Apure</option>
            <option value="Aragua ara">Aragua</option>
            <option value="Barinas bar">Barinas</option>
            <option value="Bolívar bol">Bolívar</option>
            <option value="Carabobo cara">Carabobo</option>
            <option value="Cojedes coj">Cojedes</option>
            <option value="Delta Amacuro del">Delta Amacuro</option>
            <option value="Distrito Capital dis CAPI">Distrito Capital</option>
            <option value="Falcon fal">Falcón</option>
            <option value="Guarico gua">Guárico</option>
            <option value="Lara lar">Lara</option>
            <option value="Mérida mer">Mérida</option>
            <option value="Miranda mir">Miranda</option>
            <option value="Monagas mon">Monagas</option>
            <option value="Nueva Esparta nue">Nueva Esparta</option>
            <option value="Portuguesa por">Portuguesa</option>
            <option value="Sucre suc">Sucre</option>
            <option value="Tachira tac">Táchira</option>
            <option value="Trujillo tru">Trujillo</option>
            <option value="Yaracuy yar">Yaracuy</option>
            <option value="Zulia zul">Zulia</option>
            <option value="Frontera Colombia FRON colo col">--Colombia--</option>
        </select>



        <script>
            function filtrarTabla() {
                const columna = document.getElementById("columna").value;
                const buscador = document.getElementById("buscador");
                const selectorEstado = document.getElementById("selector-estado");

                let input = "";

                if (columna === "3") {
                    input = selectorEstado.value.toLowerCase();
                } else {
                    input = buscador.value.toLowerCase();
                }

                const palabrasClave = input.split(" ").filter(Boolean);
                const filas = document.querySelectorAll(".tabla-cuerpo tbody tr");

                filas.forEach(fila => {
                    const celdas = fila.getElementsByTagName("td");

                    if (columna === "all") {
                        // Si input está vacío, mostrar todo
                        if (input.trim() === "") {
                            fila.style.display = "";
                            return;
                        }

                        const textoFila = fila.innerText.toLowerCase();
                        fila.style.display = textoFila.includes(input) ? "" : "none";
                    } else {
                        let indiceReal;
                        switch (columna) {
                            case "0": indiceReal = 0; break; // Asamblea
                            case "1": indiceReal = 2; break; // Número
                            case "2": indiceReal = 4; break; // Ciudad
                            case "3": indiceReal = 6; break; // Estado
                            default: indiceReal = 0;
                        }

                        const textoCelda = celdas[indiceReal]?.innerText.toLowerCase() || "";
                        const abrev = celdas[indiceReal]?.getAttribute("data-abrev")?.toLowerCase() || "";
                        const contenido = textoCelda + " " + abrev;

                        // Si no hay palabras clave, mostrar todo
                        if (palabrasClave.length === 0) {
                            fila.style.display = "";
                        } else {
                            const coincide = palabrasClave.some(p => contenido.includes(p));
                            fila.style.display = coincide ? "" : "none";
                        }
                    }
                });
            }








            document.getElementById("columna").addEventListener("change", () => {
                const columna = document.getElementById("columna").value;
                const buscador = document.getElementById("buscador");
                const selectorEstado = document.getElementById("selector-estado");

                if (columna === "3") {
                    buscador.style.display = "none";
                    selectorEstado.style.display = "inline-block";
                } else {
                    buscador.style.display = "inline-block";
                    selectorEstado.style.display = "none";
                }

                filtrarTabla();
            });
        </script>

        <!-- Ordenar tabla por -->
        <div >
            <label for="ordenar"></label>
            <select id="ordenar" onchange="ordenarTabla()" >
                <option value="">Ordenar por: </option>
                <option value="asamblea-az">Asamblea A-Z</option>
                <option value="asamblea-za">Asamblea Z-A</option>
                <option value="numero-asc">Número Ascendente</option>
                <option value="numero-desc">Número Descendente</option>
                <option value="estado-az">Estado A-Z</option>
                <option value="estado-za">Estado Z-A</option>
            </select>

            <script>
                function ordenarTabla() {
                    const select = document.getElementById("ordenar").value;
                    const tbody = document.querySelector(".tabla-cuerpo tbody");
                    const filas = Array.from(tbody.querySelectorAll("tr"));

                    let columna = 0;
                    let tipo = "texto";
                    let asc = true;

                    switch (select) {
                        case "asamblea-az": columna = 0; tipo = "texto"; asc = true; break;
                        case "asamblea-za": columna = 0; tipo = "texto"; asc = false; break;
                        case "numero-asc": columna = 2; tipo = "numero"; asc = true; break;
                        case "numero-desc": columna = 2; tipo = "numero"; asc = false; break;
                        case "estado-az": columna = 6; tipo = "texto"; asc = true; break;
                        case "estado-za": columna = 6; tipo = "texto"; asc = false; break;
                        default: return;
                    }

                    filas.sort((a, b) => {
                        let valA = a.cells[columna]?.innerText.trim() || "";
                        let valB = b.cells[columna]?.innerText.trim() || "";

                        if (tipo === "numero") {
                            valA = parseFloat(valA) || 0;
                            valB = parseFloat(valB) || 0;
                        } else {
                            valA = valA.toLowerCase();
                            valB = valB.toLowerCase();
                        }

                        if (valA < valB) return asc ? -1 : 1;
                        if (valA > valB) return asc ? 1 : -1;
                        return 0;
                    });

                    filas.forEach(fila => tbody.appendChild(fila));
                }
            </script>


        </div>
    </div>

    <script>
        // Configura el filtro correctamente al cargar la página
        window.addEventListener("DOMContentLoaded", () => {
            const columna = document.getElementById("columna").value;
            const buscador = document.getElementById("buscador");
            const selectorEstado = document.getElementById("selector-estado");

            if (columna === "3") {
                buscador.style.display = "none";
                selectorEstado.style.display = "inline-block";
            } else {
                buscador.style.display = "inline-block";
                selectorEstado.style.display = "none";
            }
        });
    </script>

    

    <div class="tabla-contenedor">
        <!-- Encabezado fijo -->
        <table class="tabla-cabecera">
            <thead>
                <tr>
                    <th class="col1">Asamblea</th>
                    <th class="espacio"></th>
                    <th class="col2">Número</th>
                    <th class="espacio"></th>
                    <th class="col3">Ciudad</th>
                    <th class="espacio"></th>
                    <th class="col4">Estado</th>
                    <th class="espacio"></th>
                    <th class="col5">Detalles<br>Ubicación</th>
                </tr>
            </thead>
        </table>

        <!-- Cuerpo con scroll -->
        <div class="tabla-scroll">
            <table class="tabla-cuerpo">
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $estado = $row['estado'];
                            
                            if ($estado === "Frontera Colombia") {
                                $abrev = "COLO";
                            } elseif ($estado === "Distrito Capital") {
                                $abrev = "CAPI";
                            } else {
                                $abrev = strtoupper(substr($estado, 0, 4));
                            }
                            
                        ?>
                        <tr>
                            <td class="col1"><?= htmlspecialchars($row['asamblea']) ?></td>
                            <td class="espacio"></td>

                            <td class="col2"><?= htmlspecialchars($row['numero']) ?></td>
                            <td class="espacio tlf-no"></td>

                            <td class="col3"><?= htmlspecialchars($row['ciudad']) ?></td>
                            <td class="espacio tlf-no"></td>

                            <td class="col4" data-abrev="<?= $abrev ?>">
                                <span class="estado-nombre"><?= htmlspecialchars($estado) ?></span>
                                <span class="estado-abrev"><?= $abrev ?></span>
                            </td>
                            <td class="espacio"></td>

                            <td class="col5">
                                <button class="btn-detalles" onclick='mostrarDetalles(<?= json_encode($row, JSON_UNESCAPED_UNICODE) ?>)'>Ver</button>
                                <a class="btn-mapa" 
                                    href="<?= htmlspecialchars($row['GoogleMaps']) ?>" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    title="Ver en Google Maps">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>

                            </td>
                        </tr>

                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr><td colspan="9">No hay datos disponibles</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function actualizarEstadosParaMovil() {
            const isMobile = window.innerWidth <= 600;
            const celdasEstado = document.querySelectorAll('.col4');

            celdasEstado.forEach(td => {
                const nombreCompleto = td.textContent.trim();
                const abreviatura = td.dataset.abrev;

                if (isMobile && abreviatura) {
                    td.textContent = abreviatura;
                } else {
                    td.textContent = nombreCompleto.length === 3 ? td.getAttribute('data-original') || abreviatura : nombreCompleto;
                }
            });
        }

        // Guarda el nombre completo al cargar
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.col4').forEach(td => {
                td.setAttribute('data-original', td.textContent.trim());
            });
            actualizarEstadosParaMovil();
        });

        // También actualiza si cambias el tamaño del navegador
        window.addEventListener('resize', actualizarEstadosParaMovil);


        
    </script>


</div>

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
            <a id="det-mapa" href="#" target="_blank" class="btn-mapa">
                <i class="fas fa-map-marker-alt"></i> Ver ubicación
            </a>
        </p>
    </div>
</div>


<script>
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
<?php $conn->close(); ?>
