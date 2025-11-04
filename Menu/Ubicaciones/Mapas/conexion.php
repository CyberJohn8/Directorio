<?php
// Iniciar sesión si no se ha iniciado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
$servername = "sql204.infinityfree.com";
$username = "if0_39714112";
$password = "MWgk9nZD6H0RIl";
$database = "if0_39714112_directorio_asambleas";/*/ 

$servername = "localhost";
$username = "root";
$password = "";
$database = "directorio";/**/

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// No ejecutes consultas aquí si este archivo solo se encarga de conectar.
// Puedes quitar esta parte si no quieres obtener los datos automáticamente aquí:
// $sql = "SELECT id, asamblea, numero, ciudad, estado, direccion, domingo, lunes, martes, miercoles, jueves, viernes, sabado, obras, GoogleMaps FROM iglesias";
// $result = $conn->query($sql);
?>
