<?php 
$servidor = "sql308.infinityfree.com"; 
$usuario = "if0_39414119";
$contrasena = "U7ML7oxb1B";
$base_datos = "if0_39414119_geolocalizador";

$conn = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer conjunto de caracteres a UTF-8
$conn->set_charset("utf8mb4");
?>

