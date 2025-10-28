<?php
$host = "sql308.infinityfree.com";
$user = "if0_39414119"; // Reemplaza con tu usuario
$password = "U7ML7oxb1B"; // Reemplaza con tu contraseña
$database = "if0_39414119_chat_biblico"; // Reemplaza con tu nombre de base

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");


?>
