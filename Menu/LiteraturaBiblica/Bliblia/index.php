<?php
// Fix para InfinityFree
ini_set("session.save_path", __DIR__ . "/../tmp");
if (!file_exists(__DIR__ . "/../tmp")) {
    mkdir(__DIR__ . "/../tmp", 0777, true);
}

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la base de datos
$conn = new mysqli("sql308.infinityfree.com", "if0_39414119", "U7ML7oxb1B", "if0_39414119_db_biblia");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Obtener todos los libros con su cantidad de capítulos
$libros = [];
$resultado = $conn->query("
    SELECT b.id, b.modern_name, MAX(v.chapter) AS max_chapter
    FROM books b
    JOIN verses v ON b.id = v.book_id
    GROUP BY b.id, b.modern_name
    ORDER BY b.id
");
while ($fila = $resultado->fetch_assoc()) {
    $libros[] = $fila;
}

// === Mostrar un versículo aleatorio destacado ===
$versiculo_destacado = null;
$rand_query = $conn->query("
    SELECT b.modern_name, v.chapter, v.verse, v.text
    FROM verses v
    JOIN books b ON v.book_id = b.id
    ORDER BY RAND() LIMIT 1
");
if ($rand_query && $rand_query->num_rows > 0) {
    $versiculo_destacado = $rand_query->fetch_assoc();
}

// === Lógica de lectura del capítulo ===
$versiculos = [];
$capitulos_disponibles = [];
$libro_seleccionado = $_POST['libro'] ?? $libros[0]['modern_name'];
$capitulo_seleccionado = intval($_POST['capitulo'] ?? 1);

// Obtener ID del libro seleccionado
$stmt = $conn->prepare("SELECT id FROM books WHERE modern_name = ?");
$stmt->bind_param("s", $libro_seleccionado);
$stmt->execute();
$res = $stmt->get_result();
$book = $res->fetch_assoc();

if ($book) {
    $book_id = $book['id'];

    // Capítulos disponibles para el libro
    $capitulos_res = $conn->query("SELECT DISTINCT chapter FROM verses WHERE book_id = $book_id ORDER BY chapter ASC");
    while ($row = $capitulos_res->fetch_assoc()) {
        $capitulos_disponibles[] = $row['chapter'];
    }

    // Obtener versículos del capítulo seleccionado
    $stmt = $conn->prepare("SELECT verse, text FROM verses WHERE book_id = ? AND chapter = ? ORDER BY verse ASC");
    $stmt->bind_param("ii", $book_id, $capitulo_seleccionado);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        $versiculos[] = $fila;
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8 1.png">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lector Bíblico</title>
  <link rel="stylesheet" href="Biblica.css">
  
  
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


<main>
  <div>
    <header>
      <button onclick="location.href='https://cyberjohn.infinityfreeapp.com/Menu/LiteraturaBiblica/index.php'" style="margin-bottom: 15px;"></button>
      <h1>Lector de la Biblia</h1>
    </header>

    <!-- Versículo aleatorio destacado -->
    <?php if ($versiculo_destacado): ?>
      <div style="background: rgba(255,255,255,0.9); padding: 20px; margin-bottom: 25px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <p style="font-size: 18px; font-style: italic; color: #2a3e42;">
          <strong><?= $versiculo_destacado['modern_name'] . " " . $versiculo_destacado['chapter'] . ":" . $versiculo_destacado['verse'] ?>:</strong>
          <?= $versiculo_destacado['text'] ?>
        </p>
      </div>
    <?php endif; ?>

    <!-- Formulario de selección -->
    <form method="POST" style="text-align: center; margin-top: 20px;">
      <label for="libro">Libro:</label>
      <select name="libro" id="libro" onchange="this.form.submit()">
        <?php foreach ($libros as $libro): ?>
          <option value="<?= htmlspecialchars($libro['modern_name']) ?>" <?= $libro_seleccionado === $libro['modern_name'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($libro['modern_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="capitulo">Capítulo:</label>
      <select name="capitulo" id="capitulo">
        <?php foreach ($capitulos_disponibles as $cap): ?>
          <option value="<?= $cap ?>" <?= $capitulo_seleccionado === intval($cap) ? 'selected' : '' ?>><?= $cap ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit">Leer</button>
    </form>

    <!-- Mostrar capítulo completo -->
    <?php if (!empty($versiculos)): ?>
      <div id="bible-text" style="margin-top: 30px;">
        <?php foreach ($versiculos as $verso): ?>
          <p><strong><?= $verso['verse'] ?>:</strong> <?= $verso['text'] ?></p>
        <?php endforeach; ?>
      </div>
    <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
      <div id="bible-text"><p>No se encontraron versículos para esta selección.</p></div>
    <?php endif; ?>
  </div>
</main>




</body>
</html>
