<?php
include '../php/db_connection.php';
session_start();

if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    $tema_id = $_POST['tema_id'];

    $sql = "DELETE FROM temas_foro WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tema_id);
    $stmt->execute();

    header("Location: ../pages/foro.php");
    exit();
} else {
    echo "No tienes permisos para eliminar temas.";
}
?>
