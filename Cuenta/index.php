<?php
// Fix para InfinityFree
ini_set("session.save_path", __DIR__ . "/../tmp");
if (!file_exists(__DIR__ . "/../tmp")) {
    mkdir(__DIR__ . "/../tmp", 0777, true);
}

session_start();

if (!isset($_SESSION["username"]) || $_SESSION["rol"] === "invitado") {
    header("Location: ../Iniciar_Sesion.php");
    exit();
}

require_once "conexion.php";

// Obtener datos del usuario desde la base de datos
$usuario = $_SESSION["username"];
$stmt = $conn->prepare("SELECT username, email, rol FROM usuarios WHERE username = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8 1.png">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Cuenta</title>
  <link rel="stylesheet" href="cuenta.css"> <!-- Reutiliza tu CSS existente -->
</head>
<body>
  <div class="cuenta-container">
    <h1>Mi Cuenta</h1>
    <p><strong>Usuario:</strong> <?= htmlspecialchars($datos["username"]) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($datos["email"]) ?></p>
    <p><strong>Rol:</strong> <?= htmlspecialchars($datos["rol"]) ?></p>

    <!--<hr>-->

    <h2>Administrar</h2>
    <ul>
      <li><a href="cambiar_nombre.php">Cambiar nombre de usuario</a></li>
      <li><a href="cambiar_correo.php">Cambiar correo electrónico</a></li>
      <li><a href="contraseña.php">Cambiar contraseña</a></li>
    </ul>

    <a href="https://cyberjohn.infinityfreeapp.com/Menu/index.php" class="volver">← Volver al menú principal</a>
  </div>
</body>
</html>
