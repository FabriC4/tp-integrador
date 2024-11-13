<?php
session_start();
include 'db_connection.php'; // Archivo con la conexión a la base de datos

if (isset($_POST['comentario']) && isset($_SESSION['usuario'])) {
    $comentario = $_POST['comentario'];
    $tema = $_POST['tema']; // El nombre del partido/tema

    // Obtener el ID del usuario logueado
    $usuario = $_SESSION['usuario'];
    $sql = "SELECT id FROM usuarios WHERE nombre_usuario = '$usuario'";
    $resultado = $conn->query($sql);
    $usuario_id = $resultado->fetch_assoc()['id'];

    // Insertar comentario
    $sql_insertar_comentario = "INSERT INTO comentarios (usuario_id, tema, comentario) VALUES ('$usuario_id', '$tema', '$comentario')";
    if ($conn->query($sql_insertar_comentario) === TRUE) {
        header("Location: ../pages/Foro.php"); // Redirige al usuario al inicio después de registrarse
        exit(); 
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
