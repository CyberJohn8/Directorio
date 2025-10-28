<?php
// Fix para InfinityFree
ini_set("session.save_path", __DIR__ . "/../tmp");
if (!file_exists(__DIR__ . "/../tmp")) {
    mkdir(__DIR__ . "/../tmp", 0777, true);
}

session_start();
require_once "conexion.php";

if (!isset($_SESSION["username"])) {
    header("Location: ../Iniciar_Sesion.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevo_nombre = trim($_POST["nuevo_nombre"]);
    $usuario_actual = $_SESSION["username"];

    // Validación básica
    if (!empty($nuevo_nombre)) {
        $stmt = $conn->prepare("UPDATE usuarios SET username = ? WHERE username = ?");
        $stmt->bind_param("ss", $nuevo_nombre, $usuario_actual);

        if ($stmt->execute()) {
            $_SESSION["username"] = $nuevo_nombre;
            $mensaje = "✅ Nombre de usuario actualizado correctamente.";
        } else {
            $mensaje = "❌ Error al actualizar el nombre.";
        }

        $stmt->close();
    } else {
        $mensaje = "⚠️ El nuevo nombre no puede estar vacío.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8 1.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar nombre de usuario</title>
    <link rel="stylesheet" href="cuenta.css">
</head>
<body>
<div class="cuenta-container">
    <h1>Cambiar nombre de usuario</h1>

    <form method="post">
        <label>Nuevo nombre de usuario:</label>
        <input type="text" name="nuevo_nombre" required>
        <button type="submit">Guardar</button>
    </form>

    <p><?= $mensaje ?></p>
    <a href="index.php" class="volver">← Volver a mi cuenta</a>
</div>
</body>
</html>
