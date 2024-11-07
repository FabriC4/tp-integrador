<?php
// Datos de conexión a la base de datos
$host = "localhost";   // Dirección del servidor
$user = "root";        // Nombre de usuario de MySQL
$pass = "Tom50744149249"; // Contraseña de MySQL
$dbname = "registerlog";   // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar si hay errores en la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
