<?php
$host = "sql204.infinityfree.com";
$user = "if0_39714112"; // Reemplaza con tu usuario
$password = "MWgk9nZD6H0RIl"; // Reemplaza con tu contraseña
$database = "if0_39714112_directorio_asambleas"; // Reemplaza con tu nombre de base

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");


?>
