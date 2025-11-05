<?php
// Iniciar la sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Datos de conexión
$host = "localhost";          // Usualmente localhost
$usuario = "root";            // Usuario de tu base de datos
$contrasena = "";             // Contraseña del usuario (en blanco por defecto en XAMPP)
$basedatos = "if0_39714112_geolocalizador"; // Nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($host, $usuario, $contrasena, $basedatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}

// Establecer conjunto de caracteres para evitar problemas con tildes y caracteres especiales
$conn->set_charset("utf8");
?>
