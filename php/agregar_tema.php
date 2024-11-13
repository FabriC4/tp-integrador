<?php
include '../php/db_connection.php';
session_start();

if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $logo_url = '';

    // Manejar la subida del archivo de logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "../uploads/"; // Asegúrate de que esta carpeta exista y tenga permisos de escritura
        $target_file = $target_dir . basename($_FILES["logo"]["name"]);
        move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);
        $logo_url = $target_file;
    }

    // Insertar el tema en la base de datos
    $sql = "INSERT INTO temas_foro (titulo, logo_url, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $titulo, $logo_url, $descripcion);
    $stmt->execute();

    header("Location: ../pages/foro.php");
    exit();
} else {
    echo "No tienes permisos para añadir temas.";
}
?>
