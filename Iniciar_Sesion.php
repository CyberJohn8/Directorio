<?php
// session_config.php
// Fix para InfinityFree: sesiones
ini_set("session.save_path", __DIR__ . "/tmp");
if (!file_exists(__DIR__ . "/tmp")) {
    mkdir(__DIR__ . "/tmp", 0777, true);
}
session_start();

// ============================================
// LOGIN.PHP - Inicio de Sesión
// ============================================


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexión a la BD
$conn = new mysqli("sql308.infinityfree.com", "if0_39414119", "U7ML7oxb1B", "if0_39414119_geolocalizador");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$message = "";

// ==================== LOGIN =====================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Buscar usuario por email o username
    $sql = "SELECT id, username, email, password, rol 
            FROM usuarios 
            WHERE email = '$email' OR username = '$email'
            LIMIT 1";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $hash = $fila['password'];

        // Verificar hash o texto plano (temporal, por compatibilidad)
        if (password_verify($password, $hash) || $password === $hash) {
            // Normalizar rol
            $rol = strtolower(trim($fila['rol']));
            if ($rol === "admin" || $rol === "usuario") {
                $_SESSION["rol"] = $rol;
            } else {
                $_SESSION["rol"] = "invitado"; // fallback de seguridad
            }

            // Guardar sesión
            $_SESSION["username"] = $fila['username'];
            $_SESSION["user_id"]  = $fila['id'];

            // Redirección segura con PHP
            header("Location: Menu/index.php");
            exit();
        } else {
            $message = "Contraseña incorrecta.";
        }
    } else {
        $message = "Usuario o correo no encontrado.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/png" href="https://cyberjohn.infinityfreeapp.com/Menu/iconos/icon2-8-1.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="Formulario.css">
</head>
<body>
    <div class="wrapper">
        <div class="container_Sesion">
            <h1>Iniciar Sesión</h1>

            <form id="formLogin" method="POST" action="">
                <label for="email">Correo o Usuario:</label>
                <input type="text" id="email" name="email" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="btn-login">Ingresar</button>
            </form>

            <?php if (!empty($message)) : ?>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <?php if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 1) : ?>
                <a href="recuperar_contrasena.php" class="forgot-password-link">¿Olvidaste tu contraseña?</a>
            <?php endif; ?>

            <p>¿No tienes cuenta? <a href="Registrarse.php">Regístrate aquí</a></p>
            <button onclick="location.href='Menu/index.php?guest=true'" class="btn-guest">Ingresar como Invitado</button>
            <button onclick="location.href='index.php'" class="btn-guest">Volver</button>
        </div>
    </div>

    <!-- Offline-first auth -->
    <script src="offline-db.js"></script>
    <script src="auth-offline.js"></script>
    <script>
      // Prepara el formulario para modo online/offline
      prepararFormAuth("#formLogin", "login");
      // Intenta sincronizar pendientes si ya hay conexión
      if (navigator.onLine) sincronizarPendientesConServidor("https://cyberjohn.infinityfreeapp.com");
    </script>
</body>
</html>
