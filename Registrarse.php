<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$message = "";
$message_type = ""; // 'success' o 'error'


// Conexión a la base de datos
//$conn = new mysqli("localhost", "root", "", "directorio");/** */
$conn = new mysqli("sql204.infinityfree.com", "if0_39714112", "MWgk9nZD6H0RIl", "if0_39714112_directorio_asambleas");/** */
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);

    if ($password !== $confirmPassword) {
        $message = "❌ Las contraseñas no coinciden.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "❌ El correo ya está registrado.";
            $message_type = "error";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $rol = "usuario";

            $stmt = $conn->prepare("INSERT INTO usuarios (username, email, password, rol) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $rol);

            if ($stmt->execute()) {
                $message = "✅ Registro exitoso. Redirigiendo al inicio de sesión...";
                $message_type = "success";
                header("refresh:2; url=Iniciar_Sesion.php");
                // No hacer exit aquí para que muestre el mensaje y el toast
            } else {
                $message = "❌ Error al registrar el usuario.";
                $message_type = "error";
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/x-icon" href="/Menu/iconos/icon2-8.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="Formulario.css">


    <style>
        #toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 4px;
            padding: 16px;
            position: fixed;
            z-index: 1000;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
            opacity: 0;
            transition: opacity 0.5s ease-in-out, visibility 0.5s;
        }
        #toast.show {
            visibility: visible;
            opacity: 1;
        }
        #toast.success {
            background-color: #4CAF50; /* verde */
        }
        #toast.error {
            background-color: #f44336; /* rojo */
        }
    </style>

</head>
<body>
    <div class="wrapperRegistro">
        <div class="container_Registro">
            <h1>Registro</h1>
            <form method="POST" action="">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirmPassword">Confirmar Contraseña:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>

                <button type="submit" class="btn-register" style="background-color: #4D6164;">Registrarse</button>
            </form>

            <?php if (!empty($message)) : ?>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <p>¿Ya tienes cuenta? <a href="Iniciar_Sesion.php">Inicia sesión aquí</a></p>
            <button onclick="location.href='index.php'" class="btn-back">Volver</button>
        </div>
        
        <div id="toast"></div>

    </div>


    <script>
        window.onload = function() {
            const message = <?php echo json_encode($message); ?>;
            const messageType = <?php echo json_encode($message_type); ?>;
            if(message) {
                const toast = document.getElementById("toast");
                toast.textContent = message;
                toast.classList.add("show");
                toast.classList.add(messageType); // success o error

                // Ocultar toast después de 3.5 segundos
                setTimeout(() => {
                    toast.classList.remove("show");
                }, 3500);
            }
        };
    </script>

</body>

</html>
