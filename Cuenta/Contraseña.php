<?php
// Fix para InfinityFree
ini_set("session.save_path", __DIR__ . "/../tmp");
if (!file_exists(__DIR__ . "/../tmp")) {
    mkdir(__DIR__ . "/../tmp", 0777, true);
}

// --- Parámetros de sesión segura para InfinityFree ---
ini_set('session.cookie_domain', '.if0_39414119.infinityfreeapp.com');
session_set_cookie_params([
    'path' => '/',
    'secure' => false,       // Cambiar a true si usas HTTPS real
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Activar errores solo en ambiente de desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- Redirección obligatoria a HTTPS ---
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

// --- Depuración temporal: muestra sesión actual ---
if (!isset($_GET['no_dump'])) {
    echo '<pre>$_SESSION = ' . htmlspecialchars(print_r($_SESSION, true)) . '</pre>';
}

// Conexión database
require_once "conexion.php";
$conn->set_charset("utf8mb4");

// Autenticación de sesión
if (!isset($_SESSION["username"]) || !in_array($_SESSION["rol"] ?? '', ["admin", "usuario"])) {
    header("Location: ../Iniciar_Sesion.php");
    exit();
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$mensaje = "";

// Control de intentos fallidos
$_SESSION['intentos'] = $_SESSION['intentos'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("❌ Petición inválida (CSRF detectado).");
    }

    $usuario = trim($_SESSION["username"]);
    $actual = trim($_POST["actual"]);
    $nueva  = trim($_POST["nueva"]);
    $confirmar = trim($_POST["confirmar"]);

    if (empty($actual) || empty($nueva) || empty($confirmar)) {
        $mensaje = "❌ Todos los campos son obligatorios.";
    } elseif (strlen($nueva) < 8) {
        $mensaje = "⚠️ La nueva contraseña debe tener al menos 8 caracteres.";
    } elseif ($nueva !== $confirmar) {
        $mensaje = "⚠️ Las nuevas contraseñas no coinciden.";
    } elseif ($_SESSION['intentos'] >= 3) {
        $mensaje = "❌ Se han excedido los intentos permitidos. Inténtalo más tarde.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM usuarios WHERE username = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($hash_actual);
        $stmt->fetch();

        if ($stmt->num_rows === 0) {
            $mensaje = "❌ Usuario no encontrado.";
        } else {
            $validacion_correcta = false;

            if (password_verify($actual, $hash_actual)) {
                $validacion_correcta = true;
            } elseif (hash_equals($actual, $hash_actual)) {
                // contraseña antigua sin hash
                $nuevo_hash = password_hash($actual, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE usuarios SET password = ? WHERE username = ?");
                $upd->bind_param("ss", $nuevo_hash, $usuario);
                $upd->execute();
                $upd->close();
                $validacion_correcta = true;
            }

            if (!$validacion_correcta) {
                $_SESSION['intentos']++;
                $mensaje = "❌ La contraseña actual no es correcta.";
            } else {
                // Reset intento en caso de éxito
                $_SESSION['intentos'] = 0;

                $hash_nuevo = password_hash($nueva, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE usuarios SET password = ? WHERE username = ?");
                $upd->bind_param("ss", $hash_nuevo, $usuario);

                if ($upd->execute()) {
                    $mensaje = "✅ Contraseña actualizada correctamente.";
                } else {
                    $mensaje = "❌ Error al actualizar la contraseña.";
                }
                $upd->close();
            }
        }
        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8 1.png">
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Cambiar Contraseña</title>
<link rel="stylesheet" href="cuenta.css">
</head>
<body>
<div class="cuenta-container">
  <h1>Administrar Cuenta</h1>
  <p>Usuario: <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></p>
  <?php if ($mensaje !== ""): ?>
    <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
  <?php endif; ?>

  <form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <label>Contraseña actual:</label>
    <input type="password" name="actual" required>
    <label>Nueva contraseña:</label>
    <input type="password" name="nueva" required>
    <label>Confirmar nueva contraseña:</label>
    <input type="password" name="confirmar" required>
    <button type="submit">Cambiar contraseña</button>
  </form>

  <a href="index.php" class="volver">← Volver al menú</a>
</div>
</body>
</html>
